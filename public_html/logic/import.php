<?php

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	echo '{"status":"untrusted"}';
}
else{

// connecting db
include 'thecon.php';
include 'markers.php';
	
// checking file upload
if (isset($_FILES['file'])){
	
	// reading file content
	$data = file_get_contents($_FILES['file']['tmp_name']); // reading data from temporal file
	
	// parsing xml file into php array indexed structure
	$p = xml_parser_create();
	xml_parse_into_struct($p, $data, $vals, $index);
	xml_parser_free($p);
	
	// showing coordinates (method A)
	foreach ($index as $key=>$val) {
			 if ($key == "COORDINATES") { // filter for COORDINATES key index array
				 foreach($val as $key=>$val){
					 foreach($vals[$val] as $key=>$val){ // getting COORDINATE value from vals indexed array
						 if($key == "value"){ // filter for value key
							 $fet = $fet + pVal($val);// <-- coordinates value data (lat,lng,z)
							 $enviat++;
						}
					 }
				 }
			 }
	}
	
	// process ending (redirecting to start page)

	//header('Location: /uploadKML.php'); // method 1 (header)
?>
<html><head><title>redirect</title></head><body>
<script language="javascript">
parent.document.getElementById('msg').style.display='none';
alert("S'han importat "+<?php echo $fet ?>+" de "+<?php echo $enviat ?>+" marques!\nCal sincronitzar per a mostrar les dades en el visor.");
window.location.href = '/uploadKML.html';
</script>
</body></html>
<?php
}
else 

	// no file upload 
	echo "<font color = 'red'>There was an error uploading the file.</font><br><a href='javascript:history.back();'>Go Back</a>";
}

function pVal($val){
	
		// splitting string into array
		$vals = split(',', $val);
		
		// function insert($latitude, $longitude, $idestat, $idactuacio, $idobjecte, $idusuariassig)
		return insert($vals[1], $vals[0], $_POST['estatsUps'], $_POST['actuacionsUps'], $_POST['objectesUps'], $_POST['tecnicsUps']);
		//echo '<br>';
		
		// printing valuesw
		//echo 'lng: '.$vals[0];
		//echo '<br>lat: '.$vals[1];
		//echo '<br><br>';
}

// Closing connection
mysql_close($con);

?>