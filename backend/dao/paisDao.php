<?php
require "../util/bdConexao.php";

class PaisDAO{
	private $COLUNAS=array(
		'idPais',
		'nomePais'
	);
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir pais!');
		return $retVal;
	}

	public function read($pais){
	
		$SQL="select ";
		
		$SQL.=$this->COLUNAS[0]. ',';
		$SQL.=$this->COLUNAS[1];
		$SQL.=" from pais where ";
		if($pais->__get($this->COLUNAS[1])!=""){				
			$SQL.=$this->COLUNAS[1]."='";
			$SQL.=$pais->__get($this->COLUNAS[1])."'";
		}
		else{
			$SQL.="idPais=".$pais->__get('idPais');
		}
		$query=mysql_query($SQL);

		$pais=null;
		while($result=mysql_fetch_array($query)){
			$pais=new PaisTO();
			$pais->__set($this->COLUNAS[0],$result[0]);
			$pais->__set($this->COLUNAS[1],$result[1]);
		}
		return $pais;
	}

	public function create($pais){
		$SQL="insert into pais(";
		$SQL.=$this->COLUNAS[1];
		$SQL.=") values('";
		$SQL.=$pais->__get($this->COLUNAS[1]);
		$SQL.="')";
		$this->runSql($SQL);
		return $this->read($pais);
	}

	public function updateRelationship($pessoa,$fluencia,$idioma){
		$SQL="insert into pessoaFluenciaIdioma values(";
		$SQL.=$pessoa->__get('idPessoa').',';
		$SQL.=$fluencia->__get('idFluenciaIdioma').',';
		$SQL.=$idioma->__get('idIdioma').')';
		mysql_query($SQL);

	}
}


?>
