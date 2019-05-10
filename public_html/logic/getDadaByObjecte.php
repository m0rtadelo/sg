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
$id = $_GET['id'];
$s="select d.*, a.nom anom, o.nom onom, e.nom enom from dada d, actuacions a, objectes o, estats e where d.idactuacio = a.idactuacio and d.idobjecte = o.idobjecte and d.idestat = e.idestat and d.idobjecte = ".$id;
$q=mysql_query($s) or die("Unable to execute query");
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results
echo '{"status":"success","members":'.json_encode($arr).'}';
//echo '{"members":'.json_encode($arr).'}';
}
?>