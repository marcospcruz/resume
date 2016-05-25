<?php

	function convertFromSqlToDate($date){
		//$data=explode('-',$date);
		//$separador='';
		//return $data[0].$separador.$data[1].$separador.$data[2];	
		return strtotime($date)*1000;
	}
	/**
	  * Conversão de datas do formato dd/mm/yyyy em yyyy-mm-dd
	  */
       	function convertDate($data){
		if(strlen($data)<10){

			return null;
		}
		
		if(strpos($data,'/')==0){
			$seconds= $data/1000;			
			$data=date('d/m/Y',$seconds);
		}

		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}

	function convertTime($data){
		return date('d-m-Y h:i:s',($data/1000));
	}

	function salvaInfoAcesso($ip){
		$acesso=new AcessoTO();
		$dao=new AcessoDAO();
		$acesso->__set('ip',$ip);
		$acesso->__set('hora',date('Y-m-d h:i:s'));
		$dao->create($acesso);
	}
	
?>
