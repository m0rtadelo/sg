<?php

/**
	RFM Software 2014
	Ricard Figuls Mateu
	rfmsoftware@gmail.com
*/
	$t = $_GET['t'];
	if($t==''){
		header('Location: index.html');
	}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accés Sistemes Geogràfics</title>
    <script type="text/javascript" src="js/md5.pack.js"></script> 
<script type="text/javascript">
function login(){
	document.form1.style.display='none';
	document.form1.pass.value = md5(document.form1.pass.value);
	document.form1.pass2.value = document.form1.pass.value;
//	document.form1.submit();
}
function valida(){
	p1 = document.form1.pass.value;
	p2 = document.form1.pass2.value;

	if(p1.length<8){
		alert('La contrasenya ha de tenir com a mínim 8 caràcters!');
		return false;
	}
	if(p1!=p2){
		alert('Les contrasenyes introduïdes no coincideixen!');
		return false;
	}
	login();
	return true;
}
</script>
<style type="text/css">
<!--
body{
	font-family:Verdana, Geneva, sans-serif;
	background-color:BBBBBB;
	background-image: url('/img/fons.png');
}
#lform {
	width:300px;
	background-color:#FFFFFF;
	border: 10px solid #999999;
}
a {
  font-size: 10px;
}
.text{
  font-size: 12px;
  text-align: justify;
  padding: 10px;
}
-->
</style>
</head>

<body onload="javascript:document.getElementById('user').focus();" bgcolor="#BBBBBB">
<table width="100%" height="100%" border="0">
  <tr height="33%">
    <td height="33%">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td id="lform" align="center"><img src="img/logosg3.png" alt="Sistemes Geogràfics" width="224" height="264" style="align:center" />
    	<p class="text">S'ha sol·licitat re-generar la contrasenya per a l'usuari. Indiqueu la nova contrasenya a continuació:</p>
    <form id="form1" name="form1" method="post" action="logic/chPass.php" onsubmit="return valida();">
  <p align="right">
    <label>Contrasenya:
      <input type="password" name="pass" id="pass" />
    </label>
  </p>
  <p align="right">
    <label>Repetir:
      <input type="password" name="pass2" id="pass2" />
    </label>
  </p>
  <p align="right">
  <input type="hidden" name="t" id="t" value="<?php echo $t ?>">
  <input type="submit" name="submit" id="submit" value="Definir nova contrasenya" />
  </p>
</form></td>
    <td>&nbsp;</td>
  </tr>
  
</table>


</body>
</html>
<?php		
	}
?>