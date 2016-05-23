<?php
require "../util/bdConexao.php";


class CurriculumDAO{
	
	private $COLUNAS=array(
		'idCurriculum',
		'objetivo',
		'summary'
	);
	const TABLE_NAME="curriculum";
	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='c.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=' from '.self::TABLE_NAME.' c ' ;
		$sql.=' inner join pessoaCurriculuns pc on pc.idCurriculum=c.idCurriculum ';
		return $sql;
	}
	public function readCurriculumPessoa($pessoa){
		$sql=$this->selectBuilder();
		$sql.=" where pc.idPessoa=".$pessoa->__get('idPessoa');
		$query=mysql_query($sql);
		$curriculuns=array();
		while($result=mysql_fetch_array($query)){
			$curriculum=new CurriculumTO();
			$curriculum->__set('summary',$result[2]);
			$curriculum->__set('objetivo',$result[1]);
			$curriculum->__set('idCurriculum',$result[0]);

			$expDao=new ExperienciaProfissionalDAO();
			$experiencias=$expDao->readExperienciaCurriculum($curriculum);

			$curriculum->__set('experienciaProfissional',$experiencias);

			$curriculuns[sizeof($curriculuns)]=$curriculum;
			
		}

		return $curriculuns;

	}
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
	public function update($cv){
		$SQL="update curriculum set ".$this->COLUNAS[1]."='".$cv->__get($this->COLUNAS[1])."',".$this->COLUNAS[2]."='".$cv->__get($this->COLUNAS[2])."' where idCurriculum=".$cv->__get('idCurriculum');
		$this->runSql($SQL);
		return $this->read($cv->__get($this->COLUNAS[1]));
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
