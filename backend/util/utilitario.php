<?php
	/**
	  * Conversão de datas do formato dd/mm/yyyy em yyyy-mm-dd
	  */
       	function convertDate($data){

		if(strlen($data)<10){
			return null;
		}

		$data=explode('/',$data);
		return $data[2].'-'.$data[1].'-'.$data[0];
	}


	function convertFromSqlToDate($date){
		$data=explode('-',$date);
		
		return $data[2].'/'.$data[1].'/'.$data[0];	
	}

?>
