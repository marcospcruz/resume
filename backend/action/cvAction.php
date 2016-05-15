<?php
	require "../util/utilitario.php";
	require "../dao/pessoaDao.php";
	require "../dao/enderecoDao.php";
	require "../dao/empresaDao.php";
	require "../dao/cargoDao.php";
	require "../dao/cursoDao.php";
	require "../dao/contatoDao.php";
	require "../dao/experienciaProfissionalDao.php";
	require "../dao/curriculumDao.php";
	require "../model/pessoa.php";
	require "../model/maritalStatus.php";
	require "../model/curriculum.php";
	require "../model/empresa.php";
	require "../model/cargo.php";
	require "../model/experienciaProfissional.php";
	require "../model/endereco.php";
	require "../model/contato.php";
	require "../model/tipoContato.php";
	require "../model/formacaoAcademica.php";
	require "../model/curso.php";

	$teste='{"curriculum":{"professionalExperience":[{"empresa":"compsis","position":"Analista de Suporte e Implantação Pleno","periodFrom":"01/09/2013","periodTo":"04/05/2016","tasksDescription":"<p>Atividades Executadas</p>"}],"summary":"<p>Resumo</p>","objetivo":"Analista Desenvolvedor de Sistemas"},"skills":{"0":"JAVA","1":"Javascript"},"complementaryEducation":{"0":{"company":"Globalcode","course":"AA2","duration":"40horas","dataInicio":"01/11/2015","dataFim":"10/11/2015"}},"internationalExperience":{"0":{"country":"Nigéria","duration":"2 anos","experienceLiving":{"id":1,"label":"Vivência Profissional"}}},"languages":{"0":{"languageLevel":{"id":1,"label":"Fluente"},"language":"Inglês"}},"educationAcademic":{"0":{"educationDegree":{"id":1,"label":"Superior"},"education":"Tecnologia em Análise e Desenvolvimento de Sistemas","institution":"ETEP Faculdades","conclusion":"2013"}},"name":"Marcos Pereira da Cruz","endereco":{"logradouro":"Rua Alexandrino José de Souza","numero":"493","bairro":"Santana","cidade":"São José dos Campos","uf":"SP"},"contatos":{"0":{"tipoContato":{"id":1,"label":"Telefone"},"contato":"12 981110829"}},"nationality":"Brasileira","maritalStatus":{"status":"1"},"birthDate":"16/07/1982"}';

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);

	$montador=new Montador();

	//$pessoa = new PessoaTO();
	$pessoaDao=new PessoaDAO();
	$pessoa=$pessoaDao->read($request->name);
	if(!isset($pessoa)){

		$maritalStatus=new MaritalStatusTO();
		$idMaritalStatus=$request->maritalStatus->status;
		$maritalStatus->__set('idMaritalStatus',$idMaritalStatus);
		$pessoa=new PessoaTO();
		$pessoa->__set('name',$request->name);
		$pessoa->__set('maritalStatus',$maritalStatus);
		$pessoa->__set('nationality',$request->nationality);
		$pessoa->__set('birthDate',convertDate($request->birthDate));
		//CRIANDO ENDERECO
		$pessoa->__set('endereco',$montador->montaEndereco($request->endereco));

		$pessoa=$pessoaDao->update($pessoa);
	
	//if($retorno!=1)
	//	echo "Registro inserido com sucesso!";
	}
	//CRIANDO CURRICULUM
	$pessoa->__set('curriculuns',array());
	$pessoa->addCurriculum($montador->montaCurriculum($pessoa,$request->curriculum));
	//CRIANDO CONTATOS
	$pessoa->__set('contatos',$montador->montaContatos($pessoa,$request->contatos));
	//CRIANDO FORMACAO CURRICULAR
	$pessoa->__set('formacaoAcademica',$montador->montaFormacaoAcademica($pessoa,$request->educationAcademic));


	//echo $postdata;

class Montador{
	public function montaFormacaoAcademica($pessoa,$education){
		foreach($education as $dado){
			$formacaoAcademica=new FormacaoAcademicaTO();
			$formacaoAcademica->__set('curso',$this->montaCurso($dado->education,$dado->institution));
//			$curso->__set('nomeEmpresa',);
			die('montaFormacao');

		}
	}

	private function montaCurso($nomeCurso,$nomeEmpresa){
		$dao=new CursoDAO();
		$curso=new CursoTO();
		$empresa=$this->montaEmpresa($nomeEmpresa);
		$curso->__set('empresa',$empresa);
		$curso->__set('nomeCurso',$nomeCurso);
		die($empresa->__get());
		$c=$dao->read($curso);
		die('montaCurso');
		return $curso;

	}
	public function montaContatos($pessoa,$contatos){
		foreach($contatos as $dado){
			$dao=new ContatoDAO();
			$contato=new ContatoTO();
			$contato->__set('valor',$dado->contato);
			$tipoContato=new TipoContatoTO();
			$tipoContato->__set('idTipoContato',$dado->tipoContato->id);
			$contato->__set('tipoContato',$tipoContato);
			$contato->__set('pessoa',$pessoa);
			$co=$dao->read($contato);
			if(!isset($co)){
				$contato=$dao->update($contato);
			}
		}
	}
	public function montaCurriculum($pessoa,$dados){
		$dao=new CurriculumDAO();
		$curriculum=$dao->read($dados->objetivo);
		if(!isset($curriculum)){
			$curriculum=new CurriculumTO();
			$curriculum->__set('summary',$dados->summary);
			$curriculum->__set('objetivo',$dados->objetivo);	
			$curriculum=$dao->create($curriculum);
			$dao->createJoin($pessoa,$curriculum);
		}
		$curriculum->__set('experienciaProfissional',$this->montaExperienciaProfissional($dados->professionalExperience,$curriculum));
		return $curriculum;
	}

	private function montaExperienciaProfissional($dados,$curriculum){
		$experienciaArray=array();
		for($i=0;$i<sizeof($dados);$i++){
			$dado=$dados[$i];
			$inicio=convertDate($dado->periodFrom);
			$fim=convertDate($dado->periodTo);

			if(!isset($inicio)||!isset($fim)){
				//die('data invalida');			
				continue;
			}

			$dao=new ExperienciaProfissionalDAO();
			$experienciaProfissional=$dao->read($inicio,$fim,$curriculum);
			if(!isset($experienciaProfissional)){
				$experienciaProfissional=new ExperienciaProfissionalTO();
				$experienciaProfissional->__set('summary',$dado->tasksDescription); 
				$experienciaProfissional->__set('dataInicio',$inicio); 
				$experienciaProfissional->__set('dataFim',$fim); 
				$experienciaProfissional->__set('empresa',$this->montaEmpresa($dado->empresa));
				$experienciaProfissional->__set('cargo',$this->montaCargo($dado->position));
				$experienciaProfissional->__set('curriculum',$curriculum);
				$experienciaProfissional=$dao->create($experienciaProfissional);
			}
			$experienciaArray[$i]=$experienciaProfissional;
		
		}

		return $experienciaArray;
	}

	private function montaEmpresa($dados){
		$empresaDao=new EmpresaDAO();
		$empresa=$empresaDao->read($dados);
		if(!isset($empresa)){
			$empresa=new EmpresaTO();
			$empresa->__set('nomeEmpresa',$dados);
			$empresa=$empresaDao->create($empresa);
		}
	
		return $empresa;
	}
	
	private function montaCargo($dados){
		$cargoDao=new CargoDAO();
		$cargo=$cargoDao->read($dados);
		if(!isset($cargo)){		
			$cargo=new CargoTO();
			$cargo->__set('descricaoCargo',$dados);
			$cargo=$cargoDao->create($cargo);

		}
		return $cargo;
	}

	public function montaEndereco($dados){

		$dao=new EnderecoDAO();

		$endereco=new EnderecoTO();
		$endereco->__set('logradouro',$dados->logradouro);
		$endereco->__set('bairro',$dados->bairro);
		$endereco->__set('cidade',$dados->cidade);
		$endereco->__set('numero',$dados->numero);
		$endereco->__set('uf',$dados->uf);
		$endereco->__set('cep',$dados->cep);
		$e=$dao->read($endereco);
		if(!isset($e)){
			$e=$dao->create($endereco);
		}
		return $e;
	}
}
?>
