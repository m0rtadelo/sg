<?php
//header('Content-Type: application/json; charset=utf-8');

// session ok?
session_start(); 
if($_SESSION['auth']!="yes"){
	echo '{"status":"untrusted"}';
}
else{

// connecting db
include 'thecon.php';
include 'markers.php';

$value = json_decode(file_get_contents('php://input'));

$action = $value->action;
$members = $value->members;
$in=0;
$up=0;
$de=0;

if($action=="update")
{
	// recorrem array (excepte el darrer que és buit)
	for($i=0;$i<count($members)-1;$i++){
		
		if($members[$i]->todelete>0){
				if($members[$i]->iddada>0){
					// delete
					$de++;
					$q = "DELETE FROM `dada` WHERE `iddada` = '".$members[$i]->iddada."'";
					$res = mysql_query($q);
				}
		}else{
			if($members[$i]->iddada > 0){
				// update
				$up++;
				$q = "UPDATE `dada` SET `latitude`= '".$members[$i]->latitude."', `longitude`= '".$members[$i]->longitude."', `idestat` = '".$members[$i]->idestat."', `idactuacio` = '".$members[$i]->idactuacio."', `idobjecte` = '".$members[$i]->idobjecte."', `timestamp` = SYSDATE()+0 WHERE `iddada` = ".$members[$i]->iddada;
				$res = mysql_query($q);

			}else{
			// insert
			$in++;
			$res = insert($members[$i]->latitude, $members[$i]->longitude,$members[$i]->idestat,$members[$i]->idactuacio,$members[$i]->idobjecte,$members[$i]->idusuariassig );
			}
		}
	}

	$res = "inserts:".$in." updates:".$up." deletes:".$de;
	echo json_encode(array("status"=>"ok","action"=> $action,"query"=>$q,"result"=>$res));
}
else if($action=="updatedada")
{
	
	
}
else
{
	echo json_encode(array("status"=>"ko","message"=>"undefined action"));
}

// Closing connection
//mysql_free_result($q);
mysql_close($con);

}
?>

