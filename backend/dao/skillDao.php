<?php
require "../util/bdConexao.php";

class SkillDAO{
	private $COLUNAS=array(
		'idSkill',
		'nomeSkill'
	);
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir skill!');
		return $retVal;
	}

	public function read($skill){
	
		$SQL="select ";
		
		$SQL.=$this->COLUNAS[0]. ',';
		$SQL.=$this->COLUNAS[1];
		$SQL.=" from skill where ";
		$SQL.=$this->COLUNAS[1]."='";
		$SQL.=$skill->__get($this->COLUNAS[1])."'";
		$query=mysql_query($SQL);

		$skill=null;
		while($result=mysql_fetch_array($query)){
			$skill=new SkillTO();
			$skill->__set($this->COLUNAS[0],$result[0]);
			$skill->__set($this->COLUNAS[1],$result[1]);
		}
		return $skill;
	}

	public function create($skill){
		$SQL="insert into skill(";
		$SQL.=$this->COLUNAS[1];
		$SQL.=") values('";
		$SQL.=$skill->__get($this->COLUNAS[1]);
		$SQL.="')";
		$this->runSql($SQL);
		return $this->read($skill);
	}

	public function updateRelationship($pessoa,$skill){
		$SQL="insert into pessoaSkills values(";
		$SQL.=$pessoa->__get('idPessoa').',';
		$SQL.=$skill->__get('idSkill').')';
		mysql_query($SQL);

	}
}


?>
