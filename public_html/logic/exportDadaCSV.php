<?php

/*
	RFM Software 2013

	export all data from current enterprise

*/

function f($val){
	return str_replace('.', ',', $val);
};

mb_internal_encoding("UTF-8"); 
header("Content-Type: application/force-download");
header("Content-Disposition: attachment;filename=export.csv");

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	echo '';
}
else{

// connecting db
include 'thecon.php';

// Getting data
$uid = $_SESSION['userid'];

// Setting data
$del = $_GET['del']; //'"';
$sep = $_GET['sep']; // '";"';

if($del=='')
	$del = '"';
if($sep=='')
	$sep = '";"';

// Executing query
$arr = array();
$s="select d.*, a.nom anom, o.nom onom, e.nom enom from dada d, actuacions a, objectes o, estats e where d.idactuacio = a.idactuacio and d.idobjecte = o.idobjecte and d.idestat = e.idestat ";
$s=$s."and d.idusuariassig in (select idusuari from usuaris where identitat in (select identitat from usuaris where idusuari = ".$uid."))";
$q=mysql_query($s) or die("Unable to execute query");
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results CSV

// Header
echo $del.'iddada'.$sep.'latitude'.$sep.'longitude'.$sep.'idestat'.$sep.'idactuacio'.$sep.'idobjecte'.$sep.'idusuariassig'.$del;
echo chr(13).chr(10);
// data
$values = array_values($arr);
//echo count($values);

for($a=0;$a<count($values);$a++){
	echo $del.
	$values[$a]['iddada'].$sep.
	f($values[$a]['latitude']).$sep.
	f($values[$a]['longitude']).$sep.
	$values[$a]['idestat'].$sep.
	$values[$a]['idactuacio'].$sep.
	$values[$a]['idobjecte'].$sep.
	$values[$a]['idusuariassig']
	.$del;
	echo chr(13).chr(10);
}

//echo '{"status":"success","members":'.json_encode($arr).'}';
//echo '{"members":'.json_encode($arr).'}';
}

?>