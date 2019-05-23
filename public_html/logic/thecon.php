<?php

// Wrapper for deprecated mysql API
function mysql_connect($host, $u, $p) {
	$conn = mysqli_connect($host,$u,$p,"rfmsoftn_sg");
	$conn->set_charset("utf8");
	return $conn;
}

function mysql_query($q, $conn = null) {
	if(!$conn)
		$conn=mysql_connect("mysql","rfmsoftn_user","Primera.2");
	return $conn->query($q);
}

function mysql_fetch_assoc($p) {
	return mysqli_fetch_assoc($p);
}

function mysql_free_result($result)
{
	mysqli_free_result($result);
	return true;
}

function mysql_close($c) {
	return mysqli_close($c);
}
// Connecting database
mb_internal_encoding( 'UTF-8' );
$con = mysql_connect("mysql","rfmsoftn_user","Primera.2");
if(!$con)
	die("Unable to connect: ".mysql_error());

// Setting output to UTF8
mysql_query("SET NAMES 'utf8'", $con);
// Setting database
// mysql_select_db("rfmsoftn_sg") or die("Unable to select database");


/*
	RFM Software 2013 (rfmsoftware@gmail.com)
*/

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

// BASIC SQL EXEC
function hasQ($str){
	$counter=0;
	$q=mysql_query($str) or die("500 Internal server error");
	while($e=mysql_fetch_assoc($q)){
		$counter++;
	}	
	return $counter;
}

/**
return > 0 if hasUsuari
*/
function hasUsuari($str){
	return hasQ("SELECT * FROM `usuaris` WHERE `usuari` like '".$str."'");
}

/**
return > 0 if hasEntitat
*/
function hasEntitat($str){
	return hasQ("SELECT * FROM `entitats` WHERE `nom` like '".s_str($str)."'");
}

/**
delete user and rel. data
*/
function delUser($id){

	// removing rel. data
	hasQ("delete from `dada` where `idusuariassig` = ".$id);
	
	// removing user
	hasQ("delete from `usuaris` where `idusuari` = ".$id);

	//echo($id);
}

/**
getting usuari info
*/
function getUsuari($id){
	$q=mysql_query("select * from `usuaris` where `idusuari` = ".$id) or die("500 Internal server error");
	while($e=mysql_fetch_assoc($q)){
		$arr[] = $e;
		return $e;
	}	
}
function getUsuariM($mail){
	$q=mysql_query("select * from `usuaris` where `usuari` = '".s_str($mail)."'") or die("500 Internal server error");
	while($e=mysql_fetch_assoc($q)){
		$arr[] = $e;
		return $e;
	}	
}
function getUsuariT($token){
	$q=mysql_query("select * from `usuaris` where `token` = '".s_str($token)."'") or die("500 Internal server error");
	while($e=mysql_fetch_assoc($q)){
		$arr[] = $e;
		return $e;
	}	
}

/**
*/
function reTokenM($mail){
	$d = getUsuariM($mail);
	reToken($d['idusuari']);
}
/**
reset pass (1 step)
*/
function reToken($id){
	$tkn = createToken();
	hasQ("update `usuaris`set `token` = '".$tkn."' where `idusuari` = ".$id);
	$d = getUsuari($id);

	$para = $d['usuari'];
	$titulo = 'Re-generar contrasenya SG';
	$mensaje = 
	'<html><head><title>Re-generar contrasenya SG</title></head><body>' .
	'<p>Hola</p>' .
	'<p>S\'ha rebut una petició per a re-generar la vostra contrasenya en el sistema SG.</p>' .
	'<p>Si no voleu canviar la contrasenya ignoreu aquest correu.</p>' .
	'<p>Per a re-generar la vostra contrasenya feu click en el següent enllaç:</p>' .
	'<p><a href="http://sg.rfmsoft.net/reToken.php?t=' . $tkn .'">http://sg.rfmsoft.net/reToken.php?t=' . $tkn .'</a></p></body>';
	$cabeceras = 
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
	'From: no-reply@sg.com' . "\r\n" .
    'Reply-To: no-reply@sg.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($para, $titulo, $mensaje, $cabeceras);	
}
/**
reset user password (2 step)
*/
function rePass($token, $md5pass){

	// reset password - creating password
	//$pass = createPassword();

	// reset password - crypting password
	//$md5pass = md5($pass);

	// getting mail
	$d = getUsuariT($token);

	// update password
	hasQ("update `usuaris` set `hash` = '".$md5pass."', `token` = null where `token` = '".$token."'");
	//echo "update `usuaris` set `hash` = '".$md5pass."', `token` = null where `token` = '".$token."'";
	// sending email
	$para = $d['usuari'];

//$para      = 'ricard.figuls@gmail.com';
$titulo = 'Contrasenya SG canviada';
$mensaje = 'Contrasenya correctament canviada!';
$cabeceras = 'From: no-reply@sg.com' . "\r\n" .
    'Reply-To: no-reply@sg.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);	
return $para;
}
/**
creating password
*/
function createPassword(){
	return createStr(8);
}

function createToken(){
	return createStr(rand(25,30));
}

function createStr($l){
	$c = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
	$r = '';
	for($a=1;$a<$l;$a++){
		$r = $r . substr($c, rand(0, strlen($c)-1), 1);
	}
	return $r;
}

/**
check if signed user has permission to edit users
*/
function checkPerms($id){
	// Check if auth user is admin
	if(hasQ("select * from `usuaris` where `idusuari` = ".$_SESSION['userid']." and idperfil = 1")==1){

		// Check if dest user is from the same entitie
		if(hasQ("select * from `usuaris` where `idusuari` = ".$id." and `identitat` = ".$_SESSION['ide'])==1){
			return true;
		}else{
			return false; // dest user is from other entitat
		}

	}else{
		return false; // auth user is not admin
	}
}

/**
	add user
*/
function addUser($nom, $email, $perfil){
	hasQ("insert into `usuaris` (`nom`, `usuari`, `idperfil`,`identitat`) values ('".$nom."', '".$email."', '".$perfil."', '".$_SESSION['ide']."')");
}

/**
	edit user
*/
function editUser($nom, $email, $id, $perfil){
	$q="update `usuaris` set `nom`='".$nom."',`usuari`='".$email."',`idperfil`='".$perfil."' where `idusuari`=".$id;
	//echo $q;
	hasQ($q);
}
?>