<?php
require "../util/bdConexao.php";


class ExperienciaProfissionalDAO{
	
	private $COLUNAS=array(
		'idExperienciaProfissional',//0
		'dataInicio',//1
		'dataFim',//2
		'summary',//3
		'idCargo',//4
		'idEmpresa',//5
		'idCurriculum'//6
	);

	private function runSql($sql){
		//die($sql);
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir experiencia profissional!');
		return $retVal;
	}

	public function read($inicio,$fim,$curriculum){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].",";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].",";
		$SQL.=$this->COLUNAS[6];
		$SQL.=" from experienciaProfissional where ";
		$SQL.=$this->COLUNAS[1]."='".$inicio."' and ";
		$SQL.=$this->COLUNAS[2]."='".$fim."' and ";
		$SQL.=$this->COLUNAS[6]."=".$curriculum->__get($this->COLUNAS[6]);
		$query=mysql_query($SQL);
		$experiencia=null;
		while($result=mysql_fetch_array($query)){

			$experiencia=new ExperienciaProfissionalTO();
			$experiencia->__set($this->COLUNAS[0],$result[0]);
			$experiencia->__set($this->COLUNAS[1],$result[1]);
			$experiencia->__set($this->COLUNAS[2],$result[2]);
			$experiencia->__set($this->COLUNAS[3],$result[3]);
			$experiencia->__set($this->COLUNAS[4],$result[4]);
			$experiencia->__set($this->COLUNAS[5],$result[5]);
			$experiencia->__set($this->COLUNAS[6],$result[6]);
	
		}	
		return $experiencia;
	}

	public function create($experiencia){
		$SQL="insert into experienciaProfissional(";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].",";
		$SQL.=$this->COLUNAS[6];
		$SQL.=") values('";
		$SQL.=$experiencia->__get($this->COLUNAS[1])."','";
		$SQL.=$experiencia->__get($this->COLUNAS[2])."','";
		$SQL.=$experiencia->__get($this->COLUNAS[3])."',";
		$cargo=$experiencia->__get('cargo');
		$SQL.=$cargo->__get($this->COLUNAS[4]).",";
		$empresa=$experiencia->__get('empresa');
		$SQL.=$empresa->__get($this->COLUNAS[5]).",";
		$cv=$experiencia->__get('curriculum');
		$SQL.=$cv->__get($this->COLUNAS[6]).")";
		$this->runSql($SQL);
		return $this->read($experiencia->__get($this->COLUNAS[2]),$experiencia->__get($this->COLUNAS[2]),$cv);
	}

	
}


?>
