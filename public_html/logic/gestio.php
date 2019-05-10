<?
mb_internal_encoding("UTF-8"); 

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	header('Location: /');
}

$str = $_POST['str'];
?>
<html>
<head>
<title>BackOffice</title>
</head>
<body>
	<form name="sql" id="sql" action="#" method="post">
		<input type="text" id="str" name="str" value="<?=$str?>">
		<input type="submit" value="Executar">
	</form>
<?
	if( strlen($_POST['str']) > 0) {
		// we have a query
		$arr = array();
		include 'thecon.php';
		$q=mysql_query($str) or die("Unable to execute query");
		while($e=mysql_fetch_assoc($q)){
			/*
			$columns = array_keys($e);
			for(a=0;a<count($columns);a++)
				echo($columns[a]);
			*/
			//$arr[] = $e;
			echo json_encode($e);
			echo '<br/>';
		}	
		// Closing connection
		mysql_free_result($q);
		mysql_close($con);
	}else{
		// we don't have a query
		echo('<p>Enter a query</p>');
	}
?>
</body>
</html>