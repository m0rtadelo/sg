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

// Executing query
$arr = array();
//$id = $_GET['id'];
$s="select u.idusuari, u.usuari, u.nom, u.idperfil, p.nom perfil from usuaris u, perfils p where u.identitat = ".$_SESSION['ide']." and u.idperfil = p.idperfil order by u.nom asc";
$q=mysql_query($s) or die("Unable to execute query");

// method 1
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results
echo '{"status":"success","members":'.json_encode($arr).'}';
}
?>