<?php 

	
	function insert($latitude, $longitude, $idestat, $idactuacio, $idobjecte, $idusuariassig){
		$q = "INSERT INTO `dada` (`latitude`, `longitude`, `idestat`, `idactuacio`, `idobjecte`,`timestamp`,`idusuariassig`) VALUES ('".$latitude."','".$longitude."','".$idestat."','".$idactuacio."','".$idobjecte."', SYSDATE()+0, '".$idusuariassig."')";
		$res = mysql_query($q);
		return $res;		
	}

?>