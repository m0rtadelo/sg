<?php
/*
	RFM Software 2013
*/
mb_internal_encoding("UTF-8"); 
header('Content-Type: application/json; charset=utf-8');

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	echo '{"status":"untrusted"}';
}
else{

// connecting db
include 'thecon.php';

// Cleaning values
$action = $_POST['action'];
$id = i_str($_POST['id'],9);
$nom = s_str($_POST['nom']);
$email = i_str($_POST['email'],150);
$perfil = i_str($_POST['perfil'],2);

// Checking values
$data = getUsuariM($_POST['email']);
if($data['idusuari'] > 0 && $data['idusuari'] != $id){
	echo '{"status":"error","msg":"Adreça de correu ja existent"}';
}else{

	// Checking action
	if($action=='add'){
		addUser($nom, $email, $perfil);
		echo '{"status":"success"}';	
	}
	elseif($action=='edit'){
		editUser($nom, $email, $id, $perfil);
		echo '{"status":"success"}';	
	}
	elseif($action=='del'){

	}
	else{
	// Returning results
	echo '{"status":"untrusted"}';	
	}
}

// Closing connection
mysql_free_result($q);
mysql_close($con);
}
?>