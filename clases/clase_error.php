<?php
/*
 * Error Class
 *
 * @version 1.0
 * @author Sergio Dobkevicius
 * @link http://www.fuxionweb.com
*/
 
class Errores
{
	private $todos = array(); 	// Guarda los datos en una Matriz
	private $texto = '';      	// Listado de errores
	private $errnro = '';     	// Número de error
	public  $existe = 0;

	public function __construct() {
		//$this->errnro = $errnro;
	}

	// Destructor: Borra todo
	
	public function __destruct() {
		$this->Borrar();
	}
	
	// Borra todos los errores

	public function Borrar() {
		$this->errnro = 0;
		unset($this->todos);
		$this->texto = '';
	}

	public function Guardar($errnro){
		$this->errnro = $errnro;
		$this->existe = 1;
		switch($this->errnro){
			case 1:
				$this->todos[] = 'Usuario o password incorrectos';
				break;
		}
	}

	public function Mostrar($titular=0){// titular define si muestra o no el título "errores:"
		if(count($this->todos) > 0){
			$this->texto = '<div class="errores">';
			if($titular == 1){$this->texto.= 'Errores:<ul>';}
			foreach ($this->todos as $i => $value) {
				if($titular == 1){$this->texto.= '<li>';}
				$this->texto.= $value;
				if($titular == 1){$this->texto.= '</li>';}
			}
			if($titular == 1){$this->texto .= '</ul>';}
			$this->texto.= '</div>';
			return $this->utf($this->texto);
		}else{
			return '';
		}
	}
	
	public function utf($origen){
		return utf8_decode($origen);
	}
}
?>