<?php
require "../util/bdConexao.php";


class CurriculumDAO{
	
	private $COLUNAS=array(
		'idCurriculum',
		'objetivo',
		'summary'
	);

	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir cargo!');
		return $retVal;
	}

	public function read($dados){
		$SQL="select ".$this->COLUNAS[0].",".$this->COLUNAS[1].",".$this->COLUNAS[2]." from curriculum where objetivo='";
		$SQL.=$dados."'";
		$query=mysql_query($SQL);
		$curriculum=null;
		while($result=mysql_fetch_array($query)){
			$curriculum=new CurriculumTO();
			$curriculum->__set('idCurriculum',$result[1]);
			$curriculum->__set('objetivo',$result[2]);
			$curriculum->__set('idCurriculum',$result[0]);
		}

		return $curriculum;
	}

	public function create($cv){
		$SQL="insert into curriculum(".$this->COLUNAS[1].",".$this->COLUNAS[2].") values('";

		$SQL.=$cv->__get($this->COLUNAS[1])."','".$cv->__get($this->COLUNAS[2])."')";
		$this->runSql($SQL);
		return $this->read($cv->__get($this->COLUNAS[1]));
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	public function createJoin($pessoa,$curriculum){

		$SQL='insert into pessoaCurriculuns(idPessoa,idCurriculum) values(';
		$SQL.=$pessoa->__get('idPessoa').',';
		$SQL.=$curriculum->__get('idCurriculum');
		$SQL.=')';
		$this->runSql($SQL);

	}	
}


?>