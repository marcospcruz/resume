var app=angular.module('formApp',['ui.tinymce','ngSanitize']);

app.controller('formController',['$scope','$http','$timeout',function($scope,$http,$timeout){
		$scope.ageCalculator=function(d1,d2){
			var dataAtual=new Date(d1);
			var dataNascimento=new Date(d2);
			var diff=Math.abs(dataAtual-dataNascimento);
			var dias=(1000*60*60*24)
			return Math.floor((diff/dias)/365);
		};
		$scope.formData={};

		$http.get("backend/action/cvGet.php")
		.then(function(response){
			console.log('response.data is null: '+JSON.stringify(response.data.curriculum));
			console.log(new Date());
			if(response.data!="null"){

				$scope.formData=response.data;
				$timeout(function(){
					formataData('birthDateId');
					formataData("dataInicioCurso");
					formataData('dataFimCurso');
					console.log(new Date())
				},150);
				if(response.data.contatos){				
					for(var i=0;i<$scope.formData.contatos.length;i++)
						$scope.add();
				}
				if(response.data.curriculum.hasOwnProperty('professionalExperience')){	
					for(var i=0;i<$scope.formData.curriculum.professionalExperience.length;i++)
						$scope.addExperience();
				}
				if(response.data.skills){
					for(var i=0;i<$scope.formData.skills.length;i++)
						$scope.addSkill();
				}
				if(response.data.languages){
					for(var i=0;i<$scope.formData.languages.length;i++)
						$scope.addLanguage();
				}
				if(response.data.internationalExperience){
					for(var i=0;i<$scope.formData.internationalExperience.length;i++)
						$scope.addInternationalExperience();
				}
				if(response.data.educationList){
					for(var i=0;i<$scope.formData.educationList.length;i++)
						$scope.addEducation();
				}
				
			}else console.log('response sem dados');

		},function myError(response){
			alert(JSON.stringify(response));
		});

		var formataData=function(idCampoData){
			var formattedDate=millisToDate(document.getElementById(idCampoData).value);
			console.log("Formatando data em "+idCampoData+" - "+formattedDate);	
			document.getElementById(idCampoData).value=formattedDate;
		};

		/**function to convert milliseconds to date**/
		var millisToDate=function(valor){

			if(!valor)
				return "";
			var data=new Date();
			data.setTime(valor);

			return data.toString("dd/MM/yyyy");
		};

/***********************************************************************/
		//$scope.formData.contatos=[];
		$scope.formData.curriculum={};
		$scope.formData.curriculum.professionalExperience=[];
		$scope.submitForm=function(){
			$http({
				method: 'POST',
				url:	'backend/action/cvAction.php',
				data: $scope.formData
				//headers: {'Content-Type:':'application/x-www-form-urlencoded'}
			}).success(function(data){
				alert(JSON.stringify(data));
				//$scope.errorName = data.errors.name;
				//$scope.errorUserName = data.errors.username;
				//$scope.errorEmail = data.errors.email;
			});
		};

		$scope.tinymceOptions = {
		    plugins: 'link image code',
		    menubar: false,
		    toolbar: 'fontselect |  fontsizeselect | undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | code'
		  };
/**********************************************************************/

/**************************Contact*************************************/

		//inicializando valores formulario
		$scope.items = [{
		  id: 1,
		  label: 'Telefone'
		}, {
		  id: 2,
		  label: 'E-mail'
		}, {
		  id: 3,
		  label: 'Skype'
		}];

		$scope.componentes=[];

		$scope.add=function(){
			$scope.componentes.push({
				textPlaceHolder: "Informe um contato"
			});
		}
/***************************Education**********************************/
		$scope.educationComponents=[];
		$scope.addEducation=function(){

			$scope.educationComponents.push({
				textPlaceHolder: "Informe a formação",
				institutionPlaceHolder: "Informe a instituição de ensino"
			});
		}

		$scope.educationTypeItems=[{
				  id: 1,
				  label: 'Acadêmica'
				}, {
				  id: 2,
				  label: 'Complementar'
				}]; 

		$scope.educationItems = [{
				  id: 1,
				  label: 'Superior'
				}, {
				  id: 2,
				  label: 'Mestrado'
				},{
				  id: 3,
				  label: 'Doutorado'
				}];
/**********************************************************************/
/***************************Languages**********************************/
		$scope.languageComponents=[];
		$scope.addLanguage=function(){

			$scope.languageComponents.push({
				textPlaceHolder: "Informe o Idioma",
				institutionPlaceHolder: "Informe a instituição de ensino"
			});
		}

		$scope.languageItems = [{
				  id: 1,
				  label: 'Fluente'
				}, {
				  id: 2,
				  label: 'Avançado'
				},{
				  id: 3,
				  label: 'Básico'
				},{
				  id: 4,
				  label: 'Nativo'					
				}];
/**********************************************************************/
/***************************Experience*********************************/
		$scope.experienceComponents=[];
		var indice=$scope.experienceComponents.length;
		$scope.addExperience=function(){

			$scope.experienceComponents.push({
				positionPlaceHolder: "Informe a função exercida",
				companyPlaceHolder: "Informe a empresa que trabalhou",
				fromPlaceHolder: "Informe a data que entrou na empresa",
				toPlaceHolder: "Informe a data que saiu",
			});
			//indice=$scope.experienceComponents.length;
			//console.log(indice);
			//$scope.experienceComponents[indice].taskComponents=[];
			//console.log($scope.experienceComponents[indice].taskComponents);
			
			
		}
		//$scope.addTask=function(){
		//	$scope.experienceComponents.taskComponents.push({
		//		taskPlaceHolder: "Informe a atividade que realizou"
		//	});
		//};

/**********************************************************************/
/***********************International Experience***********************/
		$scope.internationalComponents=[];

		$scope.addInternationalExperience=function(){
			$scope.internationalComponents.push({
				countryPlaceHolder: "Informe o país visitado.",
				durationPlaceHolder: "Informe quanto tempo durou."
			});
			
		}

		$scope.internationalItems=[{
				  id: 1,
				  label: 'Vivência Profissional'					
				},{
				  id: 2,
				  label: 'Turismo'					
				},{
				  id: 3,
				  label: 'Trânsito'					
				}];
/**********************************************************************/
/***********************complementary education************************/
		$scope.complementaryComponents=[];

		$scope.addComplementaryEducation=function(){
			$scope.complementaryComponents.push({
				companyPlaceHolder: "Informe a Empresa ou Instituição de Ensino.",
				durationPlaceHolder: "Informe a duração do curso.",
				trainingPlaceHolder: "Informe o treinamento cursado."
			});
			
		}

		/*$scope.internationalItems=[{
				  id: 1,
				  label: 'Vivência Profissional'					
				},{
				  id: 2,
				  label: 'Turismo'					
				},{
				  id: 3,
				  label: 'Trânsito'					
				}];*/
/**********************************************************************/
/***********************Skills*****************************************/
		$scope.skillsComponents=[];

		$scope.addSkill=function(){
			$scope.skillsComponents.push({
				skillPlaceHolder: "Informe o conhecimento adquirido.",
			});
			
		}

/**********************************************************************/

	}
]);


