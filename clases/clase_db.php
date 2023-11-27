<?php
/*
 * DB Class
 *
 * @version 1.0
 * @original class modified by Sergio Dobkevicius
 * @link http://www.fuxionweb.com
 
 BASADO EN
 
 /**
 * Ultimate MySQL Wrapper Class
 *
 * @version 2.5.1
 * @author Jeff L. Williams
 * @link http://www.phpclasses.org/ultimatemysql
 *
 * Contributions from
 *   Frank P. Walentynowicz
 *   Larry Wakeman
 *   Nicola Abbiuso
 *   Douglas Gintz
 *   Emre Erkan
 *   Vincent van Daal
 */

class MySQL
{
	// Por defecto localhost
	private $db_host    = "localhost";  // Nombre del host
	private $db_user    = "root";      	// Usuario
	private $db_pass    = "";           // Password
	private $db_dbname  = "db";			// Base de datos
	private $db_charset = "";           // Charset (opcional)
	private $db_pcon    = false;        // Conexión persistente
	
	// constants for SQLValue function
	const SQLVALUE_BIT      = "bit";
	const SQLVALUE_BOOLEAN  = "boolean";
	const SQLVALUE_DATE     = "date";
	const SQLVALUE_DATETIME = "datetime";
	const SQLVALUE_NUMBER   = "number";
	const SQLVALUE_T_F      = "t-f";
	const SQLVALUE_TEXT     = "text";
	const SQLVALUE_TIME     = "time";
	const SQLVALUE_Y_N      = "y-n";

	public $ThrowExceptions = false;
	private $error_desc     = "";      	  // Descripción del error
	private $error_num      = 0;          // Numero de error
	private $mysqli_link     = 0;          // mysql link resource
	private $last_result;                  // Ultimo resultado de una query
	private $last_sql       = "";         // Ultima Query
	private $last_insert_id;              // Ultimo ID de valor insertado
	private $active_row     = -1;         // Fila actual
	private $in_transaction = false;      // Usado para transacciones

	public function __construct($connect = true, $database = null, $server = null,
								$username = null, $password = null, $charset = null) {

		if ($database !== null) $this->db_dbname  = $database;
		if ($server   !== null) $this->db_host    = $server;
		if ($username !== null) $this->db_user    = $username;
		if ($password !== null) $this->db_pass    = $password;
		if ($charset  !== null) $this->db_charset = $charset;

		if (strlen($this->db_host) > 0 &&
			strlen($this->db_user) > 0) {
			if ($connect) $this->Open();
		}
	}

	/**
	 * Destructor: Closes the connection to the database
	 *
	 */
	public function __destruct() {
		$this->Close();
	}
	
	/**
	 * Frees memory used by the query results and returns the function result
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure
	 */
	public function Release() {
		$this->ResetError();
		if (! $this->last_result) {
			$success = true;
		} else {
			$success = @mysqli_free_result($this->last_result);
			if (! $success) $this->SetError();
		}
		return $success;
	}

	/**
	 * Close current MySQL connection
	 *
	 * @return object Returns TRUE on success or FALSE on error
	 */
	public function Close() {
		$this->ResetError();
		$this->active_row = -1;
		$success = $this->Release();
		if ($success) {
			$success = @mysqli_close($this->mysqli_link);
			if (! $success) {
				$this->SetError();
			} else {
				unset($this->last_sql);
				unset($this->last_result);
				unset($this->mysqli_link);
			}
		}
		return $success;
	}

	// Devuelve el último error de MySQL como texto

	public function Error() {
		$error = $this->error_desc;
		if (empty($error)) {
			if ($this->error_num <> 0) {
				$error = "Error desconocido (#" . $this->error_num . ")";
			} else {
				$error = false;
			}
		} else {
			if ($this->error_num > 0) {
				$error .= " (#" . $this->error_num . ")";
			}
		}
		return $error;
	}

	// Devuelve el último error de MySQL como número

	public function ErrorNumber() {
		if (strlen($this->error_desc) > 0)
		{
			if ($this->error_num <> 0)
			{
				return $this->error_num;
			} else {
				return -1;
			}
		} else {
			return $this->error_num;
		}
	}

	// Detiene la ejecución (die/exit) y muestra el último error

	public function Kill($mensaje = "") {
		if (strlen($mensaje) > 0) {
			exit($mensaje);
		} else {
			exit($this->Error());
		}
	}
	
	// Determina si existe una conexión
	
	public function IsConnected() {
		if (gettype($this->mysqli_link) == "resource") {
			return true;
		} else {
			return false;
		}
	}

	// Conexión al servidor MySQL

	public function Open($database = null, $server = null, $username = null,
						 $password = null, $charset = null, $pcon = false) {
		$this->ResetError();

		// Default
		if ($database !== null) $this->db_dbname  = $database;
		if ($server   !== null) $this->db_host    = $server;
		if ($username !== null) $this->db_user    = $username;
		if ($password !== null) $this->db_pass    = $password;
		if ($charset  !== null) $this->db_charset = $charset;
		if (is_bool($pcon))     $this->db_pcon    = $pcon;

		$this->active_row = -1;

		// Abre una conexión común o persistente
		if ($pcon) {
			$this->mysqli_link = @mysqli_pconnect(
				$this->db_host, $this->db_user, $this->db_pass);
		} else {
			$this->mysqli_link = @mysqli_connect (
				$this->db_host, $this->db_user, $this->db_pass);
		}
		// Hubo un error?
		if (! $this->IsConnected()) {
			$this->SetError();
			return false;
		} else {
			// Selecciona una base de datos (si se especificó)
			if (strlen($this->db_dbname) > 0) {
				if (strlen($this->db_charset) == 0) {
					if (! $this->SelectDatabase($this->db_dbname)) {
						return false;
					} else {
						return true;
					}
				} else {
					if (! $this->SelectDatabase(
						$this->db_dbname, $this->db_charset)) {
						return false;
					} else {
						return true;
					}
				}
			} else {
				return true;
			}
		}
	}

	// Ejecuta la query
	
	public function Query($sql) {
		$this->ResetError();
		$this->last_sql = $sql;
		$this->last_result = @fullQuery($sql, $this->mysqli_link);
		if(! $this->last_result) {
			$this->active_row = -1;
			$this->SetError();
			return false;
		} else {
			if (strpos(strtolower($sql), "insert") === 0) {
				$this->last_insert_id = mysqli_insert_id();
				if ($this->last_insert_id === false) {
					$this->SetError();
					return false;
				} else {
					$numrows = 0;
					$this->active_row = -1;
					return $this->last_result;
				}
			} else if(strpos(strtolower($sql), "select") === 0) {
				$numrows = mysqli_num_rows($this->last_result);
				if ($numrows > 0) {
					$this->active_row = 0;
				} else {
					$this->active_row = -1;
				}
				$this->last_insert_id = 0;
				return $this->last_result;
			} else {
				return $this->last_result;
			}
		}
	}

	// Borra las variables internas de errores

	private function ResetError() {
		$this->error_desc = '';
		$this->error_num = 0;
	}

	// Cuenta los resultados

	public function RowCount() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("Sin conexión", -1);
			return false;
		}else {
			$result = @mysqli_num_rows($this->last_result);
			if (! $result) {
				$this->SetError();
				return false;
			} else {
				return $result;
			}
		}
	}

	// Guarda las variables con la info del último error

	private function SetError($errorMessage = "", $errorNumber = 0) {
		try {
			if (strlen($errorMessage) > 0) {
				$this->error_desc = $errorMessage;
			} else {
				if ($this->IsConnected()) {
					$this->error_desc = mysqli_error($this->mysqli_link);
				} else {
					$this->error_desc = mysqli_error();
				}
			}
			if ($errorNumber <> 0) {
				$this->error_num = $errorNumber;
			} else {
				if ($this->IsConnected()) {
					$this->error_num = @mysqli_errno($this->mysqli_link);
				} else {
					$this->error_num = @mysqli_errno();
				}
			}
		} catch(Exception $e) {
			$this->error_desc = $e->getMessage();
			$this->error_num = -999;
		}
		if ($this->ThrowExceptions) {
			if (isset($this->error_desc) && $this->error_desc != NULL) {
				throw new Exception($this->error_desc . ' (' . __LINE__ . ')');
			}
		}
	}

	// Arregla las string para que se puedan utilizar en MySQL

	static public function SQLFix($value) {
		return @addslashes($value);
	}

	// Devuelve la cadena MySQL a modo normal
	
	static public function SQLUnfix($value) {
		return @stripslashes($value);
	}

	/* Convierte cualquier valor al valor correcto
	 * [STATIC] Formats any value into a string suitable for SQL statements
	 * (NOTE: Also supports data types returned from the gettype function)
	 *
	 * @param mixed $value Any value of any type to be formatted to SQL
	 * @param string $datatype Use SQLVALUE constants or the strings:
	 *                          string, text, varchar, char, boolean, bool,
	 *                          Y-N, T-F, bit, date, datetime, time, integer,
	 *                          int, number, double, float
	 * @return string
	 */
	static public function SQLValue($value, $datatype = self::SQLVALUE_TEXT) {
		$return_value = "";

		switch (strtolower(trim($datatype))) {
			case "text":
			case "string":
			case "varchar":
			case "char":
				if (strlen($value) == 0) {
					$return_value = "NULL";
				} else {
					if (get_magic_quotes_gpc()) {
						$value = stripslashes($value);
					}
					$return_value = "'" . str_replace("'", "''", $value) . "'";
				}
				break;
			case "number":
			case "integer":
			case "int":
			case "double":
			case "float":
				if (is_numeric($value)) {
					$return_value = $value;
				} else {
					$return_value = "NULL";
				}
				break;
			case "boolean":  //boolean to use this with a bit field
			case "bool":
			case "bit":
				if (self::GetBooleanValue($value)) {
				   $return_value = "1";
				} else {
				   $return_value = "0";
				}
				break;
			case "y-n":  //boolean to use this with a char(1) field
				if (self::GetBooleanValue($value)) {
					$return_value = "'Y'";
				} else {
					$return_value = "'N'";
				}
				break;
			case "t-f":  //boolean to use this with a char(1) field
				if (self::GetBooleanValue($value)) {
					$return_value = "'T'";
				} else {
					$return_value = "'F'";
				}
				break;
			case "date":
				if (self::IsDate($value)) {
					$return_value = "'" . date('Y-m-d', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			case "datetime":
				if (self::IsDate($value)) {
					$return_value = "'" . date('Y-m-d H:i:s', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			case "time":
				if (self::IsDate($value)) {
					$return_value = "'" . date('H:i:s', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			default:
				exit("ERROR: Invalid data type specified in SQLValue method");
		}
		return $return_value;
	}
	
	public function SelectDatabase($database, $charset = "") {
		$return_value = true;
		if (! $charset) $charset = $this->db_charset;
		$this->ResetError();
		if (! (mysqli_select_db($database))) {
			$this->SetError();
			$return_value = false;
		} else {
			if ((strlen($charset) > 0)) {
				if (! (fullQuery("SET CHARACTER SET '{$charset}'", $this->mysqli_link))) {
					$this->SetError();
					$return_value = false;
				}
			}
		}
		return $return_value;
	}

	/**
	 * Comienza la transacción
	 *
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function TransactionBegin() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if (! $this->in_transaction) {
				if (! fullQuery("START TRANSACTION", $this->mysqli_link)) {
					$this->SetError();
					return false;
				} else {
					$this->in_transaction = true;
					return true;
				}
			} else {
				$this->SetError("Already in transaction", -1);
				return false;
			}
		}
	}

	/**
	 * Ends a transaction and commits the queries
	 *
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function TransactionEnd() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if ($this->in_transaction) {
				if (! fullQuery("COMMIT", $this->mysqli_link)) {
					// $this->TransactionRollback();
					$this->SetError();
					return false;
				} else {
					$this->in_transaction = false;
					return true;
				}
			} else {
				$this->SetError("Not in a transaction", -1);
				return false;
			}
		}
	}

	/**
	 * Rolls the transaction back
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure
	 */
	public function TransactionRollback() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if(! fullQuery("ROLLBACK", $this->mysqli_link)) {
				$this->SetError("Could not rollback transaction");
				return false;
			} else {
				$this->in_transaction = false;
				return true;
			}
		}
	}
}
?>