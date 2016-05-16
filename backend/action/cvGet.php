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
	


	echo json_encode($pessoaJson);

?>
