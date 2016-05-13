<?php
	require "../dao/pessoaDao.php";
	require "../model/pessoa.php";
	require "../model/maritalStatus.php";
	require "../model/curriculum.php";
	require "../model/empresa.php";
	require "../model/cargo.php";
	require "../model/experienciaProfissional.php";

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);

	$montador=new Montador();
	
	$pessoa = new PessoaTO();
	$pessoaDao=new PessoaDAO();
	$maritalStatus=new MaritalStatusTO();

	$pessoa->__set('name',$request->name);
	$maritalStatus->__set('idMaritalStatus',$request->maritalStatus);
	$pessoa->__set('maritalStatus',$maritalStatus);
	$pessoa->__set('nationality',$request->nationality);
	$pessoa->__set('birthDate',$request->birthDate);

	
	$pessoa->__set('curriculuns',array());

	$pessoa->addCurriculum($montador->montaCurriculum($request->curriculum));

	die();
	$retorno=$pessoaDao->update($pessoa);

	if($retorno==1)
		echo "Registro inserido com sucesso!";

	//echo $postdata;

class Montador{
	public function montaCurriculum($dados){
		$curriculum=new CurriculumTO();
		$curriculum->__set('summary',$dados->summary);
		$curriculum->__set('objetivo',$dados->objetivo);	
		$curriculum->__set('experienciaProfissional',$this->montaExperienciaProfissional($dados->professionalExperience));
		return $curriculum;
	}

	private function montaExperienciaProfissional($dados){
		$experienciaArray=array();
		for($i=0;$i<sizeof($dados);$i++){
			$dado=$dados[$i];
			$experienciaProfissional=new ExperienciaProfissionalTO();
			$experienciaProfissional->__set('summary',$dado->tasksDescription); 
			$experienciaProfissional->__set('dataInicio',$dado->periodFrom); 
			$experienciaProfissional->__set('dataFim',$dado->periodTo); 
			$experienciaProfissional->__set('empresa',$this->montaEmpresa($dado->empresa));
			$experienciaProfissional->__set('cargo',$this->montaCargo($dado->position));

			$experienciaArray[$i]=$experienciaProfissional;
		
		}

		return $experienciaArray;
	}

	private function montaEmpresa($dados){
		$empresa=new EmpresaTO();
		$empresa->__set('nomeEmpresa',$dados);
		return $empresa;
	}
	
	private function montaCargo($dados){
		$cargo=new CargoTO();
		$cargo->__set('descricaoCargo',$dados);

		return $cargo;
	}
}
?>
