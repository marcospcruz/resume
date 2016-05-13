<?php
require "../util/bdConexao.php";

class CargoDAO{

	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir cargo!');
		return $retVal;
	}

	public function read($dados){
		$SQL="select idCargo,descricaoCargo,nivelCargo from cargo where descricaoCargo='";
		$SQL.=$dados."'";
		$query=mysql_query($SQL);
		$cargo=null;
		while($result=mysql_fetch_array($query)){
			$cargo=new CargoTO();
			$cargo->__set('descricaoCargo',$result[1]);
			$cargo->__set('nivelCargo',$result[2]);
			$cargo->__set('idCargo',$result[0]);
		}
		return $cargo;
	}

	public function create($cargo){
		$SQL="insert into cargo(descricaoCargo,nivelCargo) values('";
		$SQL.=$cargo->__get('descricaoCargo')."','".$cargo->__get('nivelCargo')."')";
		$this->runSql($SQL);
		return $this->read($cargo->__get('descricaoCargo'));
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
