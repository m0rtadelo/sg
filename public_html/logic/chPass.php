<?php

/**
	RFM Software 2014
	Ricard Figuls Mateu
	rfmsoftware@gmail.com
*/

	// connecting db
	include 'thecon.php';

	// gettingdata
	$t = $_POST['t'];
	$p = $_POST['pass'];
	$u = rePass($t, $p)

	//mysql_close($con);
	//header('Location: /login.html');
?>

<html>
<head>
</head>
<body>
	<form name="login" method="post" action="/login2.php">
		<input type="hidden" name="user" id="user" value="<?php echo $u ?>">
		<input type="hidden" name="pass" id="pass" value="<?php echo $p ?>">
	</form>
<script language="javascript">
document.login.submit();
</script>
</body>
</html>
