<?php
require "../util/bdConexao.php";

class PessoaDAO{

	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir pessoa!');
		return $retVal;
	}

	private function insert($pessoa){
		
		$SQL="insert into pessoa(name,birthdate,idMaritalStatus,summary,nationality) values('";
		$SQL=$SQL.$pessoa->__get('name')."','";
		$SQL=$SQL.$this->convertDate($pessoa->__get('birthDate'))."',";
		$maritalStatus=$pessoa->__get('maritalStatus');
		$SQL=$SQL.$maritalStatus->__get('idMaritalStatus').",'";
		$SQL=$SQL.$pessoa->__get('summary')."','";
		$SQL=$SQL.$pessoa->__get('nationality')."')";

		return $this->runSql($SQL);

	}

	public function update($pessoa){
		if($pessoa->__get('idPessoa')==null){
			return $this->insert($pessoa);
		}		

	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
