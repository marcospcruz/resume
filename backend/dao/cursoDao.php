<?php
require "../util/bdConexao.php";

class CursoDAO{
	private $COLUNAS=array(
		'idCurso',
		'nomeCurso',
		'idInstituicao'
	);
	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir curso!');
		return $retVal;
	}

	public function read($curso){

		$SQL="select ";
		$SQL.=$this->COLUNAS[0]. ',';
		$SQL.=$this->COLUNAS[1]. ',';
		$SQL.=$this->COLUNAS[2];
		$SQL.=" from curso where ";
		$SQL.=$this->COLUNAS[1]."='".$curso->__get($this->COLUNAS[1])."' and ";
		$empresa=$curso->__get('empresa');

		$SQL.=$this->COLUNAS[2]."='".$empresa->__get($this->COLUNAS[1]);
		die($SQL);
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
