<?php

/*
	RFM Software 2013
*/

// connecting db
include 'thecon.php';

// Executing query
$arr = array();
$user = $_POST['user'];
$pass = $_POST['pass'];
$s="SELECT * FROM usuaris WHERE usuari like '".$user."' and pass like '".$pass."'";
$q=mysql_query($s) or die("Unable to execute query");
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results
echo '{"members":'.json_encode($arr).'}';

?>