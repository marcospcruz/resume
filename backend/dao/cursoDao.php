<?php
require "../util/bdConexao.php";

class CursoDAO{
	private $COLUNAS=array(
		'idCurso',
		'nomeCurso',
		'idEmpresa'
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
		$empresa=$curso->__get('instituicao');
		$SQL.=$this->COLUNAS[2]."=".$empresa->__get($this->COLUNAS[2]);
		if($curso->__get('nomeCurso')!=""){
			$SQL.=" AND ".$this->COLUNAS[1]."='".$curso->__get($this->COLUNAS[1])."'";
			
		}else{
			$SQL.=" AND idCurso=".$curso->__get('idCurso');
		}

		$query=mysql_query($SQL);
		$curso=null;
		while($result=mysql_fetch_array($query)){
			$curso=new CursoTO();
			$curso->__set($this->COLUNAS[0],$result[0]);
			$curso->__set($this->COLUNAS[1],$result[1]);
			$empresaDao=new EmpresaDAO();
			$empresa=$empresaDao->readEmpresaById($empresa->__get('idEmpresa'));
			$curso->__set('instituicao',$empresa);
		}
		return $curso;
	}

	public function create($curso){
		$SQL="insert into curso(";
		$SQL.=$this->COLUNAS[1].',';
		$SQL.=$this->COLUNAS[2];
		$SQL.=") values('";
		$SQL.=$curso->__get($this->COLUNAS[1])."',";
		$i=$curso->__get('instituicao');
		$SQL.=$i->__get($this->COLUNAS[2]).")";				
		$this->runSql($SQL);
		return $this->read($curso);
	}

	private function convertDate($data){
		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}
	
}


?>
