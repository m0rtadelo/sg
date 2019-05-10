<?php

/*
	RFM Software 2013

	export all data from current enterprise

*/

function f($val){
	return str_replace('.', ',', $val);
};

mb_internal_encoding("UTF-8"); 
header("Content-Type: application/force-download");
header("Content-Disposition: attachment;filename=export.kml");

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	echo 'out';
}
else{

// connecting db
include 'thecon.php';

// Getting data
$uid = $_SESSION['userid'];

// Executing query
$arr = array();
$s="select d.*, a.nom anom, o.nom onom, e.nom enom from dada d, actuacions a, objectes o, estats e where d.idactuacio = a.idactuacio and d.idobjecte = o.idobjecte and d.idestat = e.idestat ";
$s=$s."and d.idusuariassig in (select idusuari from usuaris where identitat in (select identitat from usuaris where idusuari = ".$uid."))";
$q=mysql_query($s) or die("Unable to execute query");
while($e=mysql_fetch_assoc($q)){
	$arr[] = $e;
}
// Closing connection
mysql_free_result($q);
mysql_close($con);

// Returning results KML

// header

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<kml xmlns="http://www.opengis.net/kml/2.2">
<Document>
  <Folder>
<?php

// data
$values = array_values($arr);
//echo count($values);

for($a=0;$a<count($values);$a++){
	echo '<Placemark>';
	echo '	<Point>';
	echo '		<coordinates>'.f($values[$a]['longitude']).','.f($values[$a]['latitude']).',0';
	echo '</coordinates>';
	echo '	</Point>';
	echo '</Placemark>';
	echo chr(13).chr(10);
}

	// footer
?>
  </Folder>
</Document>
</kml>
<?php
}
?>