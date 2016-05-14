<?php
require "../util/bdConexao.php";

class EmpresaDAO{

	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir pessoa!');
		return $retVal;
	}

	public function read($dados){
		$SQL="select * from empresa where nomeEmpresa='";
		$SQL.=$dados."'";
		$query=mysql_query($SQL);
		$empresa=null;
		while($result=mysql_fetch_array($query)){
			$empresa=new EmpresaTO();
			$empresa->__set('nomeEmpresa',$result[1]);
			$empresa->__set('idEmpresa',$result[0]);
		}
		return $empresa;
	}

	public function create($empresa){
		$SQL="insert into empresa(nomeEmpresa) values('";
		$SQL.=$empresa->__get('nomeEmpresa')."')";
		$this->runSql($SQL);
		return $this->read($empresa->__get('nomeEmpresa'));
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
