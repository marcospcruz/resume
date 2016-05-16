<?php
require "../util/bdConexao.php";

class EnderecoDAO{
	private $COLUNAS=array(
		'idEndereco',
		'bairro',
		'cep',
		'cidade',
		'logradouro',
		'numero',
		'uf',
		'displayOnView'
	);
	const TABLE_NAME="endereco";
	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir endereco!');
		return $retVal;
	}
	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.=$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=' from '.self::TABLE_NAME.' e ';
		return $sql;
	}
	public function readPersonAddress($endereco){
		$sql=$this->selectBuilder();
		$sql.=' where e.idEndereco='.$endereco->__get('idEndereco');

		$query=mysql_query($sql);
		return $this->populateEntity($query);
	}
	private function populateEntity($query){
		$endereco=null;
		while($result=mysql_fetch_array($query)){
			$endereco=new EnderecoTO();
			$endereco->__set($this->COLUNAS[0],$result[0]);
			$endereco->__set($this->COLUNAS[1],$result[1]);
			$endereco->__set($this->COLUNAS[2],$result[2]);
			$endereco->__set($this->COLUNAS[3],$result[3]);
			$endereco->__set($this->COLUNAS[4],$result[4]);
			$endereco->__set($this->COLUNAS[5],$result[5]);
			$endereco->__set($this->COLUNAS[6],$result[6]);
			$endereco->__set($this->COLUNAS[7],boolval($result[7]));
		}
		return $endereco;
	}
	public function read($endereco){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].","; 
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].",";
		$SQL.=$this->COLUNAS[6].",";
		$SQL.=$this->COLUNAS[7];
		$SQL.=" from endereco where ";
		$SQL.=$this->COLUNAS[1]."='".$endereco->__get($this->COLUNAS[1])."' AND ";
		$SQL.=$this->COLUNAS[2]."='".$endereco->__get($this->COLUNAS[2])."' AND ";
		$SQL.=$this->COLUNAS[3]."='".$endereco->__get($this->COLUNAS[3])."' AND ";
		$SQL.=$this->COLUNAS[4]."='".$endereco->__get($this->COLUNAS[4])."' AND ";
		$SQL.=$this->COLUNAS[5]."='".$endereco->__get($this->COLUNAS[5])."' AND ";
		$SQL.=$this->COLUNAS[6]."='".$endereco->__get($this->COLUNAS[6])."'";
		$query=mysql_query($SQL);
		return $this->populateEntity($query);
	}

	public function create($endereco){
		$SQL="insert into endereco(";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].",";
		$SQL.=$this->COLUNAS[6].",";
		$SQL.=$this->COLUNAS[7].") values('";
		$SQL.=$endereco->__get($this->COLUNAS[1])."','";
		$SQL.=$endereco->__get($this->COLUNAS[2])."','";
		$SQL.=$endereco->__get($this->COLUNAS[3])."','";
		$SQL.=$endereco->__get($this->COLUNAS[4])."','";
		$SQL.=$endereco->__get($this->COLUNAS[5])."','";
		$SQL.=$endereco->__get($this->COLUNAS[6])."',";
		$SQL.=$endereco->__get($this->COLUNAS[7]).")";
		$this->runSql($SQL);
		return $this->read($endereco);
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
