<?php

class PessoaTO{
	private $idPessoa;
	private $name;
	private $nationality;
	private $maritalStatus;
	private $birthDate;
	private $contacts;
	private $summary;
	private $experienceList;
	private $projects;
	private $independentCourseWorkList;
	private $skillsList;
	private $languages;
	private $educationList;

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
