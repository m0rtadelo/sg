<?php

/**
	RFM Software 2014
	Ricard Figuls Mateu
	rfmsoftware@gmail.com
*/

	// connecting db
	include 'thecon.php';

	// getting email
	$e = $_POST['user'];
	reTokenM($e);

	mysql_close($con);

	//header('Location: /login.html');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<title>Recuperar contrasenya</title>
</head>
<body> 
S'ha enviat un correu electrònic a l'adreça indicada amb un enllaç per a re-generar la contrasenya. Reviseu el correu no dessitjat (spam) del vostre compte de correu.
<br>
<a href="/">Click aqui per a tornar a la pàgina principal</a>
</body>
</html>