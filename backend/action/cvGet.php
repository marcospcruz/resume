<?php
	require_once 'imports.php';
	$pessoaDao=new PessoaDAO();
	$pessoa=$pessoaDao->readEntity('Marcos Pereira da Cruz');

	//echo serialize($pessoa);
	$pessoaJson=new StdClass();
	$pessoaJson->name=$pessoa->name;
	$pessoaJson->nationality=$pessoa->nationality;
	$maritalStatus=$pessoa->maritalStatus;
	$pessoaJson->maritalStatus->status=$pessoa->maritalStatus->idMaritalStatus;
	$pessoaJson->birthDate=convertFromSqlToDate($pessoa->birthDate);
	$endereco=$pessoa->__get('endereco');
	//populando endereco
	$pessoaJson->endereco->uf=$endereco->uf;
	$pessoaJson->endereco->cidade=$endereco->cidade;
	$pessoaJson->endereco->bairro=$endereco->bairro;
	$pessoaJson->endereco->numero=$endereco->numero;
	$pessoaJson->endereco->cep=$endereco->cep;
	$pessoaJson->endereco->logradouro=$endereco->logradouro;
	$pessoaJson->endereco->displayOnView=$endereco->displayOnView;
	//populando contatos
	$contatoDao=new ContatoDAO();
	$contatos=$pessoa->__get('contatos');

	for($i=0;$i<sizeof($contatos);$i++){
		$contato=$contatos[$i];
		$tipoContato=$contato->__get('tipoContato');
		$pessoaJson->contatos[$i]->tipoContato->id=$tipoContato->idTipoContato;		
		$pessoaJson->contatos[$i]->contato=$contato->valor;
		$pessoaJson->contatos[$i]->displayOnView=$contato->displayOnView;

	}
	
	//populando curriculuns
	$curriculuns=$pessoa->__get('curriculuns');
	$pessoaJson->curriculum->summary=$curriculuns[0]->summary;
	$pessoaJson->curriculum->objetivo=$curriculuns[0]->objetivo;
	$experiencias=$curriculuns[0]->__get('experienciaProfissional');

	for($i=0;$i<sizeof($experiencias);$i++){
		$experiencia=$experiencias[$i];
		//die();
		$pessoaJson->curriculum->professionalExperience[$i]->periodFrom=convertFromSqlToDate($experiencia->dataInicio);
		$pessoaJson->curriculum->professionalExperience[$i]->periodTo=convertFromSqlToDate($experiencia->dataFim);
		$pessoaJson->curriculum->professionalExperience[$i]->tasksDescription=$experiencia->summary;
		$cargo=$experiencia->__get('cargo');
		$pessoaJson->curriculum->professionalExperience[$i]->position=$cargo->__get('descricaoCargo');
		$empresa=$experiencia->__get('empresa');
		$pessoaJson->curriculum->professionalExperience[$i]->empresa->nomeEmpresa=$empresa->nomeEmpresa;
		$pessoaJson->curriculum->professionalExperience[$i]->empresa->descricaoEmpresa=$empresa->descricaoEmpresa;

		
	}
	
	$skills=$pessoa->__get('conhecimentosAdquiridos');
	for($i=0;$i<sizeof($skills);$i++){
		$pessoaJson->skills[$i]=$skills[$i]->nomeSkill;
	}

	$idiomas=$pessoa->__get('idiomas');
	$i=0;
	foreach($idiomas as $key=>$value){
		$pessoaJson->languages[$i]->language=$key;
		$pessoaJson->languages[$i]->languageLevel->id=$value->idFluenciaIdioma;
		$i++;
	}
	//vivencia internacional
	$vivenciasInternacionais=$pessoa->__get('vivenciaInternacional');


	for($i=0;$i<sizeof($vivenciasInternacionais);$i++){
		$vivencia=$vivenciasInternacionais[$i];
		$pais=$vivencia->__get('pais');
		$tipoVivencia=$vivencia->__get('tipoVivenciaInternacional');
		$pessoaJson->internationalExperience[$i]->country=$pais->__get('nomePais');
		$pessoaJson->internationalExperience[$i]->duration=$vivencia->__get('duracao');
		$pessoaJson->internationalExperience[$i]->experienceLiving->id=$tipoVivencia->__get('idTipoVivenciaInternacional');
	}

	//formacao
	$formacao=$pessoa->__get('formacao');
	for($i=0;$i<sizeof($formacao);$i++){
		$f=$formacao[$i];
		$curso=$f->__get('curso');
		$tipo=$f->__get('tipoFormacao');
		$instituicao=$curso->__get('instituicao');
		$grauFormacao=$f->__get('grauFormacao');

		$pessoaJson->educationList[$i]->education=$curso->nomeCurso;
		$pessoaJson->educationList[$i]->institution=$instituicao->__get('nomeEmpresa');
		$pessoaJson->educationList[$i]->educationType->id=$tipo->__get('idTipoFormacao');
		$pessoaJson->educationList[$i]->duration=$f->__get('duracaoHoras');
		$pessoaJson->educationList[$i]->dataInicio=$f->__get('dataInicio');
		$pessoaJson->educationList[$i]->dataFim=$f->__get('dataFim');
		$pessoaJson->educationList[$i]->educationDegree->id=$grauFormacao->__get('idGrauFormacao');
	}

	echo json_encode($pessoaJson);

?>
