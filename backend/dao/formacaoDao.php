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
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir formacao!');
		return $retVal;
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
			$tipoFormacao=new TipoFormatacaoTO();
			$tipoFormacao->__set('idTipoFormacao',$result[1]);
			$curso=new CursoTO();
			$curso->__set('idCurso',$result[6]);
			$grauFormacao=new GrauFormacaoTO();
			$grauFormacao->__set('idGrauFormacao',$result[7]);

			$formacao=new FormacaoAcademicaTO();
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
		$SQL="insert into formacaoAcademica(";
		$SQL.=$this->COLUNAS[1].',';
		$SQL.=$this->COLUNAS[2].',';
		$SQL.=$this->COLUNAS[3].',';
		$SQL.=$this->COLUNAS[4].',';
		$SQL.=$this->COLUNAS[5].',';
		$SQL.=$this->COLUNAS[6].',';
		$SQL.=$this->COLUNAS[7].',';
		$SQL.=$this->COLUNAS[8];
		$SQL.=") values('";
		$SQL.=$formacao->__get($this->COLUNAS[1])."',";

		$i=$formacao->__get('curso');
		$SQL.=$i->__get($this->COLUNAS[2]).",";				
		$tipoFormacao=$formacao->__get('tipoFormacao');
		$SQL.=$tipoFormacao->__get($this->COLUNAS[3]).",";
		$pessoa=$formacao->__get('pessoa');
		$SQL.=$pessoa->__get($this->COLUNAS[4]).")";
		$this->runSql($SQL);
		return $this->read($formacao);
	}

	
}


?>
