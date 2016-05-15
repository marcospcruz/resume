<?php
require "../util/bdConexao.php";

class ContatoDAO{
	private $COLUNAS=array(
		'idContato',
		'valor',
		'idPessoa',
		'idTipoContato'
	);

	public function read($contato){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].",";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3];
		$SQL.=" from contato where ".$this->COLUNAS[1]." = '".$contato->__get($this->COLUNAS[1])."' and ";
		$pessoa=$contato->__get('pessoa');
		$SQL.=$this->COLUNAS[2].'='.$pessoa->__get($this->COLUNAS[2]);
		$query=mysql_query($SQL);
		$contato=null;
		while($result=mysql_fetch_array($query)){
			$contato=new PessoaTO();
			$contato->__set($this->COLUNAS[0],$result[0]);
			$pessoa->__set($this->COLUNAS[1],$result[1]);
			$pessoa->__set('pessoa',$pessoa);
			$tipoContato=new TipoContatoTO();
			$tipoContato->__set($this->COLUNAS[3],$result[3]);
			$pessoa->__set('tipoContato',$tipoContato);

		}

		return $contato;
	}
	private function runSql($sql){
		$retVal=mysql_query($sql);
		if(!$retVal)
			die('Falha ao inserir contato!');
		return $retVal;
	}

	private function insert($contato){
		
		$SQL="insert into contato(";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3];
		$SQL.=") values('";;
		$SQL.=$contato->__get($this->COLUNAS[1])."',";
		$pessoa=$contato->__get('pessoa');
		$SQL.=$pessoa->__get($this->COLUNAS[2]).",";
		$tipoContato=$contato->__get('tipoContato');
		$SQL.=$tipoContato->__get($this->COLUNAS[3]).')';
		$this->runSql($SQL);
		return $this->read($contato);

	}

	public function update($contato){

		if($contato->__get('idContato')==null){
			return $this->insert($contato);
		}		

	}


	
}


?>
