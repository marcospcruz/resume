<?php
	require "../dao/pessoaDao.php";
	require "../model/pessoa.php";
	require "../model/maritalStatus.php";

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$pessoa = new PessoaTO();
	$pessoaDao=new PessoaDAO();
	$maritalStatus=new MaritalStatusTO();

	$pessoa->__set('name',$request->name);
	$maritalStatus->__set('idMaritalStatus',$request->maritalStatus);
	$pessoa->__set('maritalStatus',$maritalStatus);
	$pessoa->__set('nationality',$request->nationality);
	$pessoa->__set('birthDate',$request->birthDate);
	$pessoa->__set('summary',$request->summary);
	$retorno=$pessoaDao->update($pessoa);

	if($retorno==1)
		echo "Registro inserido com sucesso!";

	//echo $postdata;
?>
