<?php

/*
	RFM Software 2013 (rfmsoftware@gmail.com)
*/
/*
// parsing function
function i_str($str, $len){
	$chde = array('(','[','{','-','%','*','_',' ');// chars to remove from user & pass
	$str = substr($str,0,$len);
	$str = str_replace("'","\'",$str);
	$str = str_replace($chde,'',$str);
	return $str;
}

function s_str($str){
	$str = str_replace("'","\'",$str);
	return $str;
}

// DAO functions
function hasQ($str){
	$counter=0;
	$q=mysql_query($str) or die("500 Internal server error");
	while($e=mysql_fetch_assoc($q)){
		$counter++;
	}	
	return $counter;
}

function hasUsuari($str){
	return hasQ("SELECT * FROM `usuaris` WHERE `usuari` like '".$str."'");
}
function hasEntitat($str){
	return hasQ("SELECT * FROM `entitats` WHERE `nom` like '".s_str($str)."'");
}
*/
// connecting db
include 'logic/thecon.php';

// Executing query
$arr = array();
$user = $_POST['usuari'];
$pass = $_POST['pass'];
$entitat = $_POST['entitat'];
$nom = $_POST['nom'];
$counter = 0;
$idu = 0;
$hash = '';
$ide = 0;

// protecting from MySQL injection
$user = i_str($user,150);
$pass = i_str($pass,32);


// Checking new entitat
if(hasEntitat($entitat)>0){ // ERROR: this user exists
echo("Error: Ja existeix aquesta entitat. <a href='signin.html'>Tornar</a>"); 
return;
}

// Checking new user
if(hasUsuari($user)>0){ // ERROR: this user exists
echo("Error: Ja existeix aquest usuari. <a href='signin.html'>Tornar</a>"); 
return;
}

// Inserting new entitat
$s = "INSERT INTO `entitats` (`nom`) VALUES ('".s_str($entitat)."')";
$q=mysql_query($s) or die("500 Internal server error");


// getting identitat
$s="SELECT * FROM `entitats` where `nom` like '".s_str($entitat)."'";
$q=mysql_query($s) or die("500 Internal server error");
while($e=mysql_fetch_assoc($q)){
	$ide=$e["identitat"];
}

// Inserting new user
$s="INSERT INTO `usuaris` (`usuari`,`hash`,`idperfil`,`nom`,`identitat`) VALUES ('".$user."','".$pass."','1','".s_str($nom)."','".$ide."')";
$q=mysql_query($s) or die("500 Internal server error");

/*
// Executing safe query
$s="SELECT * FROM usuaris WHERE `usuari` like '".$user."' and `hash` like '".$pass."'";
$q=mysql_query($s) or die("500 Internal server error");
while($e=mysql_fetch_assoc($q)){
	$idu=$e["idusuari"];
	$hash=$e["hash"];
	$counter++;
	$arr[] = $e;
}

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
*/

// Closing connection
mysql_free_result($q);
mysql_close($con);

?>
<html><head></head>
<body onload="javascript:document.getElementById('f').submit();">
	<form method="POST" action="login2.php" name="f" id="f">
		<input type="hidden" id="user" name="user" value="<?php echo $user ?>">
		<input type="hidden" id="pass" name="pass" value="<?php echo $pass ?>">
	</form>
</body>
</html>