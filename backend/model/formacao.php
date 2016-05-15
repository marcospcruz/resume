<?php
class FormacaoTO{
	private $idFormacao;
	private $curso;
	private $tipoFormacao;
	private $grauFormacao;
	private $conclusao;
	private $pessoa;
	private $dataInicio;
	private $dataFim;
	private $duracaoHoras;


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
