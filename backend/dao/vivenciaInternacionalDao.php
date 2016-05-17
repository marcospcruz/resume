<?php
require "../util/bdConexao.php";

class VivenciaInternacionalDAO{
	private $COLUNAS=array(
		'idVivenciaInternacional',
		'duracao',
		'idTipoVivenciaInternacional',
		'idPais'
	);
	const TABLE_NAME='vivenciaInternacional';
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir vivencia!');
		return $retVal;
	}

	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='v.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=' from '.self::TABLE_NAME.' v ';
		$sql.=' inner join pessoaVivenciaInternacional pv on v.idVivenciaInternacional=pv.idVivenciaInternacional ';
		return $sql;
	}

	public function readVivenciaFromPerson($pessoa){
		$sql=$this->selectBuilder();
		$sql.=" where pv.idPessoa=".$pessoa->__get('idPessoa');
		$query=mysql_query($sql);
		$vivencias=null;
		while($result=mysql_fetch_array($query)){

			$vivencia=new VivenciaInternacionalTO();
			$vivencia->__set($this->COLUNAS[0],$result[0]);
			$vivencia->__set($this->COLUNAS[1],$result[1]);
			$tipoVivencia=new TipoVivenciaInternacionalTO();
			$tipoVivencia->__set($this->COLUNAS[2],$result[2]);
			$vivencia->__set('tipoVivenciaInternacional',$tipoVivencia);
			$paisDao=new PaisDAO();
			$pais=new PaisTO();
			$pais->__set('idPais',$result[3]);
			$pais=$paisDao->read($pais);
			$vivencia->__set('pais',$pais);
			$vivencias[sizeof($vivencias)]=$vivencia;

		}

		return $vivencias;
	}
	public function read($vivenciaInternacional,$pessoa){
		$JOIN="left";
		if(isset($pessoa)){
			$JOIN="inner";
			$filtroPessoa=" and pv.idPessoa=".$pessoa->__get('idPessoa');
		}
		$SQL="select ";
		
		$SQL.='v.'.$this->COLUNAS[0]. ',v.';
		$SQL.=$this->COLUNAS[1]. ',v.';
		$SQL.=$this->COLUNAS[2]. ',v.';
		$SQL.=$this->COLUNAS[3];
		$SQL.=" from vivenciaInternacional v ";
		$SQL.=$JOIN." join pessoaVivenciaInternacional pv on pv.idVivenciaInternacional=v.idVivenciaInternacional";
		$SQL.=" where ";
		$SQL.="v.idPais=";
		$pais=$vivenciaInternacional->__get('pais');
		$SQL.=$pais->__get($this->COLUNAS[3])." and v.idTipoVivenciaInternacional=";
		$tipo=$vivenciaInternacional->__get('tipoVivenciaInternacional');
		$SQL.=$tipo->__get($this->COLUNAS[2]);		
		$SQL.=$filtroPessoa;
		$query=mysql_query($SQL);
		$vivencia=null;
		while($result=mysql_fetch_array($query)){

			
			$vivencia=new VivenciaInternacionalTO();
			$vivencia->__set($this->COLUNAS[0],$result[0]);
			$vivencia->__set($this->COLUNAS[1],$result[1]);
			$tipoVivencia=new TipoVivenciaInternacionalTO();
			$tipoVivencia->__set($this->COLUNAS[2],$result[2]);
			$vivencia->__set('tipoVivenciaInternacional',$tipoVivencia);

		}
		return $vivencia;
	}

	public function create($vivencia){
		$SQL="insert into vivenciaInternacional(";
		$SQL.=$this->COLUNAS[1].',';
		$SQL.=$this->COLUNAS[2].',';
		$SQL.=$this->COLUNAS[3];
		$SQL.=") values('";
		$SQL.=$vivencia->__get('duracao')."',";
		$tipoVivencia=$vivencia->__get('tipoVivenciaInternacional');
		$SQL.=$tipoVivencia->__get('idTipoVivenciaInternacional').",";
		$pais=$vivencia->__get('pais');
		$SQL.=$pais->__get('idPais');
		$SQL.=")";
		$this->runSql($SQL);
		return $this->read($vivencia,null);
	}

	public function updateRelacionamento($pessoa,$vivencia){
		$SQL="insert into pessoaVivenciaInternacional values(";
		$SQL.=$pessoa->__get('idPessoa').',';
		$SQL.=$vivencia->__get('idVivenciaInternacional').')';
		mysql_query($SQL);

	}
}


?>
