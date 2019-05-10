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

	// Checking if the logged user has perms to do things like this
	if(checkPerms( $_GET['id'])){

		if($_GET['a']=='repass')// regenerate password
			reToken( $_GET['id'] );

		if($_GET['a']=='del')// delete user & rel. data
			delUser( $_GET['id']);

		// Returning results
		echo '{"status":"success"}';
	}else{
		echo '{"status":"untrusted"}';
	}

	// Closing connection
	mysql_close($con);

}
?>