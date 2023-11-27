<?php

class AjaxPoll {
	
	private $fileContents = '';
	
	/**
	 * Contructor function
	 *
	 * @param string $fileName
	 * 
	 * @return AjaxPoll
	 */
	
	public function AjaxPoll($fileName, $id_enc) {
		
		if (trim($fileName) == '' || !is_file($fileName) || !is_readable($fileName)) {
			trigger_error("Error al intentar acceder al template: ".$fileName, E_USER_ERROR);
		}
		else {
			$this->fileContents = file_get_contents($fileName);
			$this->id = $id_enc;
		}
	}
	
	/**
	 * This function replaces the tag by actual value
	 *
	 * @param string $tagname
	 * @param mixed $value
	 */
	public function tag($tagname, $value) {
		if (strtolower(trim($tagname)) == 'options') {
			if (!is_array($value)) {
				trigger_error("Error en encuesta.", E_USER_ERROR);				
			}
			else {
				$id_enc = $this->id;
				$query_en = "SELECT * FROM intranet_encuestas_opc WHERE encuesta = ".$id_enc;
				$resul_en = fullQuery($query_en);
				$cantidad = mysqli_num_rows($resul_en);

				$str = '';
				while($row_en = mysqli_fetch_array($resul_en)) {
					$id_opc = $row_en['id'];
					$nom_op = $row_en['valor'];
					$str .= '<INPUT type="radio" id="'.$id_opc.'" name="poll" value="'.$id_opc.'" onclick="selectOption(this)" />'.$nom_op.'<SPAN class="answer"> &nbsp;</SPAN><BR />';
				}
				$this->fileContents = str_replace("{options}", $str, $this->fileContents);
			}			
		}
		else {
			$this->fileContents = str_replace("{".strtolower($tagname)."}", $value, $this->fileContents);
		}
	}
	
	
	/**
	 * This function returns the file contents 
	 *
	 * @return string
	 */
	public function write() {
		return $this->fileContents;
	}
}
?>