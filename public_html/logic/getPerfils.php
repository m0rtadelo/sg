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
$s="select * from perfils";
$q=mysql_query($s) or die("Unable to execute query");

// method 1
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// method 2
//for($rows = array(); $row = mysql_fetch_assoc($q); $arr[] = $row);
 
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results
//echo '{"members":'.json_encode($arr).'}';
echo '{"status":"success","members":'.json_encode($arr).'}';
}
?>