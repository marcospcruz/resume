<?php
class CargoTO{
	private $idContato;
	private $valor;
	private $tipoContato;
	private $pessoa;


	public function __get($property) {
	    if (property_exists($this, $property)) {
	      return $this->$property;
	    }
	}

	public function __set($property, $value) {
	    if (property_exists($this, $property)) {
	      $this->$property = $value;
	    }
	    //echo "teste setter:".$this->$property;
	    return $this;
	}

}
?>
