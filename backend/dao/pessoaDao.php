<?php
require "../util/bdConexao.php";

class PessoaDAO{
	private $COLUNAS=array(
		'idPessoa',
		'birthDate',
		'name',
		'nationality',
		'idEndereco',
		'idMaritalStatus'
	);
	const TABLE_NAME='pessoa';
	const ALIAS='p.';
	private function selectBuilder(){
		$sql='select ';
		for($i=0;$i<sizeof($this->COLUNAS);$i++){

			$sql.='p.'.$this->COLUNAS[$i];

			if($i<(sizeof($this->COLUNAS)-1))
				$sql.=',';
		}
		$sql.=',m.description ';
		$sql.=' from '.self::TABLE_NAME.' p ';
		$sql.=' inner join maritalStatus m on m.idMaritalStatus=p.idMaritalStatus ';
		return $sql;
	}

	public function readEntity($param){

		$sql=$this->selectBuilder();

		$sql.=" where name='".$param."'";
		$query=mysql_query($sql);
		return $this->populateEntity($query);
	}
	public function read($name){
		$SQL="select ";
		$SQL.=$this->COLUNAS[0].",";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5];
		$SQL.=" from pessoa where ".$this->COLUNAS[2]." = '".$name."'";
		$query=mysql_query($SQL);
		return $this->populateEntity($query);
	}
	private function populateEntity($query){
		$pessoa=null;
		while($result=mysql_fetch_array($query)){
			$pessoa=new PessoaTO();
			$pessoa->__set($this->COLUNAS[0],$result[0]);
			$pessoa->__set($this->COLUNAS[1],$result[1]);
			$pessoa->__set($this->COLUNAS[2],$result[2]);
			$pessoa->__set($this->COLUNAS[3],$result[3]);
			$enderecoDao=new EnderecoDAO();
			$endereco=new EnderecoTO();
			$endereco->__set('idEndereco',$result[4]);
			$endereco=$enderecoDao->readPersonAddress($endereco);
			$pessoa->__set('endereco',$endereco);
			$maritalStatus=new MaritalStatusTO();
			$maritalStatus->__set($this->COLUNAS[5],$result[5]);
			$pessoa->__set('maritalStatus',$maritalStatus);
			//$pessoa->__set($this->COLUNAS[5],$result[5]);

			$contatoDao=new ContatoDAO();
			$contatos=$contatoDao->readPersonContact($pessoa);
			$pessoa->__set('contatos',$contatos);

			$curriculumDao=new CurriculumDAO();
			$curriculuns=$curriculumDao->readCurriculumPessoa($pessoa);
			$pessoa->__set('curriculuns',$curriculuns);
			
			$skillDao=new SkillDAO();
			$skills=$skillDao->readSkillsFromPerson($pessoa);
			$pessoa->__set('conhecimentosAdquiridos',$skills);

			$idiomaDao=new IdiomaDAO();
			$idiomas=$idiomaDao->readLanguangesFromPerson($pessoa);
			$pessoa->__set('idiomas',$idiomas);

			$vivenciaDao=new VivenciaInternacionalDAO();
			$vivencias=$vivenciaDao->readVivenciaFromPerson($pessoa);
			$pessoa->__set('vivenciaInternacional',$vivencias);

			$formacaoDao=new FormacaoDAO();
			$formacao=$formacaoDao->readFormacaoFromPerson($pessoa);
			$pessoa->__set('formacao',$formacao);
	
		}
		return $pessoa;
	}
	private function runSql($sql){
		$retVal=mysql_query($sql);

		if(!$retVal)
			die('Falha ao inserir pessoa!');
		return $retVal;
	}

	private function insert($pessoa){
		
		$SQL="insert into pessoa(";
		$SQL.=$this->COLUNAS[1].",";
		$SQL.=$this->COLUNAS[2].",";
		$SQL.=$this->COLUNAS[3].",";
		$SQL.=$this->COLUNAS[4].",";
		$SQL.=$this->COLUNAS[5].") values('";;
		$SQL=$SQL.$pessoa->__get($this->COLUNAS[1])."','";
		$SQL=$SQL.$pessoa->__get($this->COLUNAS[2])."','";
		$SQL.=$pessoa->__get($this->COLUNAS[3])."',";
		$endereco=$pessoa->__get('endereco');
		$SQL.=$endereco->__get($this->COLUNAS[4]).",";
		$maritalStatus=$pessoa->__get('maritalStatus');
		$SQL.=$maritalStatus->__get($this->COLUNAS[5]).")";
		$this->runSql($SQL);
		return $this->read($pessoa->__get($this->COLUNAS[2]));

	}

	public function update($pessoa){

		if($pessoa->__get('idPessoa')==null){
			return $this->insert($pessoa);
		}		

	}


	
}


?>
