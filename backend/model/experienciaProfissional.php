<?php
class ExperienciaProfissionalTO{
	
	
	private $idExperienciaProfissional;
	private $empresa;
	private $cargo;
	private $dataInicio;
	private $dataFim;
	private $summary;

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
