<?php

/*
	RFM Software 2013 (rfmsoftware@gmail.com)
*/

// connecting db
include 'logic/thecon.php';

// Executing query
$arr = array();
$user = $_POST['user'];
$pass = $_POST['pass'];
$counter = 0;
$idu = 0;
$hash = '';
$ide = 0;

// protecting from MySQL injection
$user = i_str($user,150);
$pass = i_str($pass,32);

// Executing safe query
$s="SELECT * FROM usuaris WHERE `usuari` like '".$user."' and `hash` like '".$pass."'";
$q=mysql_query($s) or die("500 Internal server error");
while($e=mysql_fetch_assoc($q)){
	$idu=$e["idusuari"];
	$ide=$e["identitat"];
	$hash=$e["hash"];
	$counter++;
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// analyzing results
if($counter==1 && strlen($hash)==strlen($pass) && $hash == $pass){
	session_start(); 
	// setting session
	include 'logic/session.php';
	$_SESSION['auth']='yes';
	$_SESSION['user']=$user;
	$_SESSION['userid']=$idu;	
	$_SESSION['ide']=$ide;
	// redirect
	header('Location: index.php');
}else{
	header('Location: login.html');
}
?>