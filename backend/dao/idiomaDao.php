<?php
require "../util/bdConexao.php";

class IdiomaDAO{
	private $COLUNAS=array(
		'idIdioma',
		'nomeIdioma'
	);
	const TABLE_NAME='idioma';
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir idioma!');
		return $retVal;
	}
	public function readLanguangesFromPerson($pessoa){

		$sql=$this->selectBuilder();
		$query=mysql_query($sql);

		$idiomas=null;
		while($result=mysql_fetch_array($query)){
			$indice=sizeof($idiomas);
			$idioma=new IdiomaTO();
			$idioma->__set($this->COLUNAS[0],$result[0]);
			$idioma->__set($this->COLUNAS[1],$result[1]);
			
			$fluenciaIdioma=new FluenciaIdiomaTO();
			$fluenciaIdioma->__set('idFluenciaIdioma',$result[2]);
			$idiomas[$idioma->__get('nomeIdioma')]=$fluenciaIdioma;
		}

		return $idiomas;

	}
	public function read($idioma){
	
		$SQL="select ";
		
		$SQL.=$this->COLUNAS[0]. ',';
		$SQL.=$this->COLUNAS[1];
		$SQL.=" from idioma where ";
		$SQL.=$this->COLUNAS[1]."='";
		$SQL.=$idioma->__get($this->COLUNAS[1])."'";

		$query=mysql_query($SQL);

		$idioma=null;
		while($result=mysql_fetch_array($query)){
			$idioma=new IdiomaTO();
			$idioma->__set($this->COLUNAS[0],$result[0]);
			$idioma->__set($this->COLUNAS[1],$result[1]);
		}
		return $idioma;
	}

	public function create($idioma){
		$SQL="insert into idioma(";
		$SQL.=$this->COLUNAS[1];
		$SQL.=") values('";
		$SQL.=$idioma->__get($this->COLUNAS[1]);
		$SQL.="')";

		$this->runSql($SQL);
		return $this->read($idioma);
	}

	public function updateRelationship($pessoa,$fluencia,$idioma){
		$SQL="insert into pessoaFluenciaIdioma values(";
		$SQL.=$pessoa->__get('idPessoa').',';
		$SQL.=$fluencia->__get('idFluenciaIdioma').',';
		$SQL.=$idioma->__get('idIdioma').')';
		mysql_query($SQL);

	}

	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='i.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=',pfi.idFluenciaIdioma ';
		$sql.=' from '.self::TABLE_NAME.' i ';
		$sql.=' inner join pessoaFluenciaIdioma pfi on pfi.idIdioma=i.idIdioma ';
		return $sql;
	}

}


?>
