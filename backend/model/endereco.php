<?php
class EnderecoTO{
	
	private $idEndereco;
	private $displayOnView;
	private $logradouro;
	private $numero;
	private $cep;
	private $bairro;
	private $cidade;
	private $uf;

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
