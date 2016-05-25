<?php
require "../util/bdConexao.php";

class AcessoDAO{
	
	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir acesso!');
		return $retVal;
	}

	public function create($acesso){
		$SQL="insert into statistics(ipRemoto,momentoAcesso) values('";
		$SQL.=$acesso->__get('ip')."','".$acesso->__get('hora')."')";
		$this->runSql($SQL);
		//return $this->read($cargo->__get('descricaoCargo'));
	}
	
}


?>
