<?php
require "../util/bdConexao.php";

class PessoaDAO{
	private $COLUNAS=array(
		'idPessoa',
		'birthDate',

		'name',
		'nationality',
		'idEndereco',
		'idMaritalStatus'
	);

	public function read($name){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].",";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5];
		$SQL.=" from pessoa where ".$this->COLUNAS[2]." = '".$name."'";
		$query=mysql_query($SQL);
		$pessoa=null;
		while($result=mysql_fetch_array($query)){
			$pessoa=new PessoaTO();
			$pessoa->__set($this->COLUNAS[0],$result[0]);
			$pessoa->__set($this->COLUNAS[1],$result[1]);
			$pessoa->__set($this->COLUNAS[2],$result[2]);
			$pessoa->__set($this->COLUNAS[3],$result[3]);
			$pessoa->__set($this->COLUNAS[4],$result[4]);
			$pessoa->__set($this->COLUNAS[5],$result[5]);
		}

		return $pessoa;
	}
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir pessoa!');
		return $retVal;
	}

	private function insert($pessoa){
		
		$SQL="insert into pessoa(";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].") values('";;
		$SQL=$SQL.$pessoa->__get($this->COLUNAS[1])."','";
		$SQL=$SQL.$pessoa->__get($this->COLUNAS[2])."','";
		$SQL.=$pessoa->__get($this->COLUNAS[3])."',";
		$endereco=$pessoa->__get('endereco');
		$SQL.=$endereco->__get($this->COLUNAS[4]).",";
		$maritalStatus=$pessoa->__get('maritalStatus');
		$SQL.=$maritalStatus->__get($this->COLUNAS[5]).")";
		$this->runSql($SQL);
		return $this->read($pessoa->__get($this->COLUNAS[2]));

	}

	public function update($pessoa){

		if($pessoa->__get('idPessoa')==null){
			return $this->insert($pessoa);
		}		

	}


	
}


?>
