<?php

class PessoaTO{
	private $idPessoa;
	private $name;
	private $nationality;
	private $endereco;
	private $nacionalidade;
	private $maritalStatus;
	private $contatos;
	private $birthDate;
	private $curriculuns;
	private $formacao;
	private $idiomas;
	private $vivenciaInternacional;
//	private $formacaoExtracurricular;
	private $conhecimentosAdquiridos;
	
	public function addFormacaoExtracurricular($formacao){
		if($idPessoa==null || sizeof($curriculuns)==0)
			$this->curriculuns[sizeof($curriculuns)]=$curriculum;

	}


	public function addCurriculum($curriculum){
		if($idPessoa==null || sizeof($curriculuns)==0)
			$this->curriculuns[sizeof($curriculuns)]=$curriculum;

	}

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
