<?php
require "../util/bdConexao.php";

class ContatoDAO{
	private $COLUNAS=array(
		'idContato',
		'valor',
		'idPessoa',
		'idTipoContato',
		'displayOnView'
	
	);
	const TABLE_NAME='contato';
	private function populateEntity($query){
		$contato=null;
		while($result=mysql_fetch_array($query)){
			$contato=new PessoaTO();
			$contato->__set($this->COLUNAS[0],$result[0]);
			$contato->__set($this->COLUNAS[1],$result[1]);
			//$contato->__set('pessoa',$pessoa);
			$tipoContato=new TipoContatoTO();
			$tipoContato->__set($this->COLUNAS[3],$result[3]);
			$contato->__set('tipoContato',$tipoContato);
			$contato->__set('displayOnView',$result[4]);

		}

		return $contato;
	}

	public function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='c.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=',tc.descricao ';
		$sql.=' from '.self::TABLE_NAME.' c ';
		$sql.=' inner join tipoContato tc on c.idTipoContato=tc.idTipoContato ';
		return $sql;

	}
	public function readPersonContact($pessoa){
		$sql=$this->selectBuilder();
		$sql.=' where idPessoa='.$pessoa->__get('idPessoa');

		$query=mysql_query($sql);
		
		$contatos=array();
		while($result=mysql_fetch_array($query)){
			$contato=new ContatoTO();
			$contato->__set($this->COLUNAS[0],$result[0]);
			$contato->__set($this->COLUNAS[1],$result[1]);
			//$contato->__set('pessoa',$pessoa);
			$tipoContato=new TipoContatoTO();
			$tipoContato->__set($this->COLUNAS[3],$result[3]);
			$tipoContato->__set('descricao',$result[5]);
			$contato->__set('tipoContato',$tipoContato);
			$contato->__set('displayOnView',boolval($result[4]));
			$contatos[sizeof($contatos)]=$contato;
		}

		return $contatos;
	}
	public function read($contato){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].",";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4];
		$SQL.=" from contato where ".$this->COLUNAS[1]." = '".$contato->__get($this->COLUNAS[1])."' and ";
		$pessoa=$contato->__get('pessoa');
		$SQL.=$this->COLUNAS[2].'='.$pessoa->__get($this->COLUNAS[2]);
		$query=mysql_query($SQL);
		return $this->populateEntity($query);
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
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4];
		$SQL.=") values('";;
		$SQL.=$contato->__get($this->COLUNAS[1])."',";
		$pessoa=$contato->__get('pessoa');
		$SQL.=$pessoa->__get($this->COLUNAS[2]).",";
		$tipoContato=$contato->__get('tipoContato');
		$SQL.=$tipoContato->__get($this->COLUNAS[3]).",";
		$SQL.=$contato->__get($this->COLUNAS[4]).')';
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
