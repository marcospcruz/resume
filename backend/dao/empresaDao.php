<?php
require "../util/bdConexao.php";

class EmpresaDAO{

	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir empresa!');
		return $retVal;
	}
	public function readEmpresaById($idEmpresa){
		$SQL="select idEmpresa,nomeEmpresa,descricao from empresa where idEmpresa=";
		$SQL.=$idEmpresa;
		$query=mysql_query($SQL);
		return $this->populateEntity($query);
	}
	public function read($dados){
		$SQL="select idEmpresa,nomeEmpresa,descricao from empresa where nomeEmpresa='";
		$SQL.=$dados."'";
		$query=mysql_query($SQL);
		return $this->populateEntity($query);
	}
	private function populateEntity($query){
		$empresa=null;
		while($result=mysql_fetch_array($query)){
			$empresa=new EmpresaTO();
			$empresa->__set('nomeEmpresa',$result[1]);
			$empresa->__set('idEmpresa',$result[0]);
			$empresa->__set('descricaoEmpresa',$result[2]);
		}
		return $empresa;
	
	}
	public function create($empresa){

		$SQL="insert into empresa(nomeEmpresa,descricao) values('";
		$SQL.=$empresa->__get('nomeEmpresa')."','";
		$SQL.=$empresa->__get('descricaoEmpresa')."')";
		$this->runSql($SQL);
		return $this->read($empresa->__get('nomeEmpresa'));
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
