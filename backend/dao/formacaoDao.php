<?php
require "../util/bdConexao.php";

class FormacaoDAO{
	private $COLUNAS=array(
		'idFormacao',//0
		'idTipoFormacao',//1
		'dataFim',//2
		'dataInicio',//3
		'conclusao',//4
		'duracaoHoras',//5
		'idCurso',//6
		'idGrauFormacao',//7
		'idPessoa'//8
	);
	const TABLE_NAME='formacao';
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir formacao!');
		return $retVal;
	}

	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='f.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=',c.idEmpresa ';
		$sql.=' from '.self::TABLE_NAME.' f ';
		$sql.=' inner join curso c on c.idCurso=f.idcurso ';

		return $sql;
	}
	public function readFormacaoFromPerson($pessoa){
		$sql=$this->selectBuilder();
		$sql.=' where f.idPessoa='.$pessoa->__get('idPessoa');
		$sql.=' order by f.dataFim desc';
		$query=mysql_query($sql);
		$formacoes=null;
		while($result=mysql_fetch_array($query)){
			$tipoFormacao=new TipoFormacaoTO();
			$tipoFormacao->__set('idTipoFormacao',$result[1]);

			$cursoDao=new CursoDAO();
			$curso=new CursoTO();
			$empresa=new EmpresaTO();
			$empresa->__set('idEmpresa',$result[9]);			
			$curso->__set('instituicao',$empresa);
			$curso->__set('idCurso',$result[6]);
			$curso=$cursoDao->read($curso);

			$grauFormacao=new GrauFormacaoTO();
			$grauFormacao->__set('idGrauFormacao',$result[7]);

			$formacao=new FormacaoTO();
			$formacao->__set($this->COLUNAS[0],$result[0]);

			$formacao->__set('tipoFormacao',$tipoFormacao);
			$formacao->__set($this->COLUNAS[2],convertFromSqlToDate($result[2]));
			$formacao->__set($this->COLUNAS[3],convertFromSqlToDate($result[3]));
			$formacao->__set($this->COLUNAS[4],$result[4]);
			$formacao->__set($this->COLUNAS[5],$result[5]);

			$formacao->__set('curso',$curso);
			$formacao->__set('grauFormacao',$grauFormacao);
			$formacao->__set('pessoa',$pessoa);
			$formacoes[sizeof($formacoes)]=$formacao;
		}

		return $formacoes;
	}
	public function read($formacao){
	


		$SQL="select ";
		
		$SQL.=$this->COLUNAS[0]. ',';
		$SQL.=$this->COLUNAS[1]. ',';
		$SQL.=$this->COLUNAS[2]. ',';
		$SQL.=$this->COLUNAS[4]. ',';
		$SQL.=$this->COLUNAS[5]. ',';
		$SQL.=$this->COLUNAS[6]. ',';
		$SQL.=$this->COLUNAS[7]. ',';
		$SQL.=$this->COLUNAS[8];
		$SQL.=" from formacao where ";
		$curso=$formacao->__get('curso');
		$SQL.=$this->COLUNAS[6]."='".$curso->__get($this->COLUNAS[6])."' and ";
		$SQL.=$this->COLUNAS[2]."='".$formacao->__get($this->COLUNAS[2])."' and ";
		$SQL.=$this->COLUNAS[3]."='".$formacao->__get($this->COLUNAS[3])."' and ";
		$pessoa=$formacao->__get('pessoa');

		$SQL.=$this->COLUNAS[8]."=".$pessoa->__get($this->COLUNAS[8]);

		$query=mysql_query($SQL);
		$formacao=null;
		while($result=mysql_fetch_array($query)){
			$tipoFormacao=new TipoFormacaoTO();
			$tipoFormacao->__set('idTipoFormacao',$result[1]);
			$curso=new CursoTO();
			$curso->__set('idCurso',$result[6]);
			$grauFormacao=new GrauFormacaoTO();
			$grauFormacao->__set('idGrauFormacao',$result[7]);

			$formacao=new FormacaoTO();
			$formacao->__set($this->COLUNAS[0],$result[0]);

			$formacao->__set('tipoFormacao',$tipoFormacao);
			$formacao->__set($this->COLUNAS[2],$result[2]);
			$formacao->__set($this->COLUNAS[3],$result[3]);
			$formacao->__set($this->COLUNAS[4],$result[4]);
			$formacao->__set($this->COLUNAS[5],$result[5]);

			$formacao->__set('curso',$curso);
			$formacao->__set('grauFormacao',$grauFormacao);
			$formacao->__set('pessoa',$pessoa);
		}
		return $formacao;
	}

	public function create($formacao){
		$SQL="insert into formacao(";
		$SQL.=$this->COLUNAS[1].',';
		$SQL.=$this->COLUNAS[2].',';
		$SQL.=$this->COLUNAS[3].',';
		$SQL.=$this->COLUNAS[4].',';
		$SQL.=$this->COLUNAS[5].',';
		$SQL.=$this->COLUNAS[6].',';
		$SQL.=$this->COLUNAS[7].',';
		$SQL.=$this->COLUNAS[8];
		$SQL.=") values(";
		$tipoFormacao=$formacao->__get('tipoFormacao');
		$SQL.=$tipoFormacao->__get($this->COLUNAS[1]).",'";
		$SQL.=$formacao->__get($this->COLUNAS[2])."','";
		$SQL.=$formacao->__get($this->COLUNAS[3])."','";
		$SQL.=$formacao->__get($this->COLUNAS[4])."','";
		$SQL.=$formacao->__get($this->COLUNAS[5])."',";
		$curso=$formacao->__get('curso');
		$SQL.=$curso->__get($this->COLUNAS[6]).",";
		$grauFormacao=$formacao->__get('grauFormacao');
		if(isset($grauFormacao))
			$SQL.=$grauFormacao->__get($this->COLUNAS[7]).",";
		else 
			$SQL.="NULL,";
		$pessoa=$formacao->__get('pessoa');
		$SQL.=$pessoa->__get($this->COLUNAS[8]).")";

		$this->runSql($SQL);
		return $this->read($formacao);
	}

	
}


?>
