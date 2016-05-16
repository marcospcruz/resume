<?php
	require_once 'imports.php';

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
	$pessoa->__set('formacao',$montador->montaFormacao($pessoa,$request->educationList));
	
	//CRIANDO FLUENCIA
	$pessoa->__set('idiomas',$montador->montaFluenciaIdiomas($pessoa,$request->languages));
	//CRIANDO VIVENCIA INTERNACIONAL
	$pessoa->__set('vivenciaInternacional',$montador->montaVivenciaInternacional($pessoa,$request->internationalExperience));
	//SKILLS
	$pessoa->__set('skills',$montador->montaSkills($pessoa,$request->skills));
	die('Sucesso!');
	//echo $postdata;

class Montador{
	public function montaSkills($pessoa,$skills){
		$skills_pessoa=array();
		foreach($skills as $conhecimento){
			$skill=new SkillTO();
			$skill->__set('nomeSkill',$conhecimento);
			$dao=new SkillDAO();
			$s=$dao->read($skill);
			if(!isset($s)){
				$skill=$dao->create($skill);
			}else
				$skill=$s;
			$dao->updateRelationship($pessoa,$skill); 
			$skills_pessoa[sizeof($skills_pessoa)]=$skill;
				
		}	
		return $skills_pessoa;
	}
	public function montaVivenciaInternacional($pessoa,$internationalExperience){
		$vivencias=array();
		foreach($internationalExperience as $experience){
			$pais=new PaisTO();
			$pais->__set('nomePais',$experience->country);
			$paisDao=new PaisDAO();
			$p=$paisDao->read($pais);
			if(!isset($p)){
				$pais=$paisDao->create($pais);
			}else
				$pais=$p;

			$tipoVivencia=new TipoVivenciaInternacionalTO();
			$tipoVivencia->__set('descricao',$experience->experienceLiving->label);
			$tipoVivencia->__set('idTipoVivenciaInternacional',$experience->experienceLiving->id);
//
			$vivencia=new VivenciaInternacionalTO();
			$vivencia->__set('duracao',$experience->duration);
			$vivencia->__set('pais',$pais);
			$vivencia->__set('tipoVivenciaInternacional',$tipoVivencia);
			$vivenciaDao=new VivenciaInternacionalDAO();
			$v=$vivenciaDao->read($vivencia,$pessoa);
			if(!isset($v)){
				$vivencia=$vivenciaDao->create($vivencia);
			}else
				$vivencia=$v;
			$vivenciaDao->updateRelacionamento($pessoa,$vivencia);
			$vivencias[sizeof($vivencias)]=$vivencia;

		}

		return $vivencias;
	}
	public function montaFluenciaIdiomas($pessoa,$languages){
		$idiomas=array();
		foreach($languages as $language){
			$idioma=new IdiomaTO();
			$dao=new IdiomaDAO();
			$idioma->__set('nomeIdioma',$language->language);
			$fluencia=new FluenciaIdiomaTO();
			$fluencia->__set('descricao',$language->languageLevel->label);
			$fluencia->__set('idFluenciaIdioma',$language->languageLevel->id);
			$i=$dao->read($idioma);
			if(!isset($i)){
				$idioma=$dao->create($idioma);
			}else{
				$idioma=$i;
			}
			$dao->updateRelationship($pessoa,$fluencia,$idioma);
			
		}
	}
	public function montaFormacao($pessoa,$education){
		$formacoes=array();
		foreach($education as $dado){
			$inicio=convertDate($dado->dataInicio);
			$fim=convertDate($dado->dataFim);
			$dao=new FormacaoDAO();
			$formacao=new FormacaoTO();
			$formacao->__set('curso',$this->montaCurso($dado->education,$dado->institution));
			$formacao->__set('dataInicio',$inicio);
			$formacao->__set('dataFim',$fim);
			$formacao->__set('pessoa',$pessoa);
			$tipoFormacao=new TipoFormacaoTO();
			//$tipoFormacao->__set('descricao',$dado->educationDegree->label);
			$tipoFormacao->__set('idTipoFormacao',$dado->educationType->id);
			$tipoFormacao->__set('descricao',$dado->educationType->label);
			$formacao->__set('tipoFormacao',$tipoFormacao);
			
			if($dado->educationDegree->id!=null){
				$valorGrau=$dado->educationDegree->id;
				$grau=new GrauFormacaoTO();
				$grau->__set('descricao',$dado->educationDegree->label);	
				$grau->__set('idGrauFormacao',$valorGrau);
				$formacao->__set('grauFormacao',$grau);
			}


			
	
			if($grau->__get('idGrauFormacao')==1)
				$formacao->__set('conclusao',explode('-',$fim)[0]);
			$formacao->__set('duracaoHoras',$dado->duration);

			$f=$dao->read($formacao);

			if(!isset($f)){

				$formacao=$dao->create($formacao);

			}else
				$formacao=$f;
			
			$formacoes[sizeof($formacoes)]=$formacao;

		}
		return $formacoes;
	}

	private function montaCurso($nomeCurso,$nomeEmpresa){
		$dao=new CursoDAO();
		$curso=new CursoTO();
		$empresa=new EmpresaTO();
		$empresa->__set('nomeEmpresa',$nomeEmpresa);
		$instituicao=$this->montaEmpresa($empresa);
		$curso->__set('instituicao',$instituicao);
		$curso->__set('nomeCurso',$nomeCurso);
		$c=$dao->read($curso);
		if(!isset($c)){
			$curso=$dao->create($curso);
			return $curso;
		}
		return $c;

	}
	private function setDisplayOnView($show){
		if($show==1)
			return $show;
		return 0;
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
			$contato->__set('displayOnView',$this->setDisplayOnView($dado->displayOnView));
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

	private function montaEmpresa($dadosEmpresa){
		$empresaDao=new EmpresaDAO();
		$empresa=$empresaDao->read($dadosEmpresa->nomeEmpresa);
		if(!isset($empresa)){
			$empresa=new EmpresaTO();
			$empresa->__set('nomeEmpresa',$dadosEmpresa->nomeEmpresa);
			$empresa->__set('descricaoEmpresa',$dadosEmpresa->descricaoEmpresa);
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
		$endereco->__set('displayOnView',$this->setDisplayOnView($dados->displayOnView));
		$e=$dao->read($endereco);
		if(!isset($e)){
			$e=$dao->create($endereco);
		}
		return $e;
	}
}

?>
