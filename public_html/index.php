<?php

session_start(); 
if($_SESSION['auth']!="yes"){
	header('Location: login.html');
}
else{
?>
<!DOCTYPE html>
<html>
  <head>
   <meta charset="utf-8" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
	  input { width:100%}
	  .infowindow h1 { font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px }
	  .infowindow h2 { font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; text-align:right; color:#0000FF; }
	  .infowindow p { font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; text-align:left; color:#333333;}
      #pos-map { height: 100%; width: 80%; position:absolute; top:0px; left:0 }
	  #pos-ctr { height: 100%; width: 20%; position:absolute; top:0px; right:0}
	  #menu { visibility:hidden; border: 1px solid #000000;}
	  .noborder { border:none; font-size:9px; width:70%; background-color:#f7f7f7;}
	  .reg {width:100%; height:300px; font-size:10px; background-color:#FFFFFF;}
	  .accordion {
	width: 100%;
	border-bottom: solid 1px #c4c4c4;
	font: 65%/120% Arial, Helvetica, sans-serif;
}
.accordion h3 {
	background: #e9e7e7 url(img/arrow-square.gif) no-repeat right -51px;
	padding: 7px 15px;
	margin: 0;
	font: bold 120%/100% Arial, Helvetica, sans-serif;
	border: solid 1px #c4c4c4;
	border-bottom: none;
	cursor: pointer;
}
.accordion h3:hover {
	background-color: #e3e2e2;
}
.accordion h3.active {
	background-position: right 5px;
}
.accordion p {
	background: #f7f7f7;
	margin: 0;
	padding: 10px 15px 20px;
	border-left: solid 1px #c4c4c4;
	border-right: solid 1px #c4c4c4;
}
    </style>
    <style>
   	#wrapper { position: relative; }
    #msg{ position: absolute; 
    top: 10px; 
    left: 10px; 
    z-index: 99; 
    width: 500px; 
    height: 280px; 
    background-color: white; 
    border: 3px solid #bbbbbb;
    box-shadow: 2px 2px 5px #999;
    display: none;
	padding: 0px;
    margin: 0px;
    }
    #us{ position: absolute; 
    top: 10px; 
    left: 10px; 
    z-index: 99; 
    width: 500px; 
    height: 280px; 
    background-color: white; 
    border: 3px solid #bbbbbb;
    box-shadow: 2px 2px 5px #999;
    display: none;
    padding: 0px;
    margin: 0px;
    }
	</style>
    <script type="text/javascript" src="js/jquery.js"></script><!-- jquery util  --> 
    <script type="text/javascript" src="js/constants.js"></script><!-- string constants -->
    <script type="text/javascript" src="js/negoci.js"></script>
    <script type="text/javascript" src="js/util.js"></script>
    <script type="text/javascript" src="js/common.js"></script><!-- common bussines -->
    <script type="text/javascript" src="js/jmap.js"></script><!-- google map objects, functions & events --> 
    <!-- map key key=AIzaSyCtn9-Ipeb-IaBWUGQp7_jubMep_-Y9Cm4 -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>
    <!-- JSON EXPLORER 7/8 WORKAROUND -->
    <script type="text/javascript" src="js/json2.js"></script> 																																																																																																																	
    
    <script type="text/javascript">
	  userid = <?php echo $_SESSION['userid']; ?> ;// setting userid (PHP->JS)
	  // load listener
      google.maps.event.addDomListener(window, 'load', initialize);
	  // unload 
	  $(window).bind('beforeunload', function(){
		if(isTouched()){											  
			return '>>>>>Atenció<<<<<<<< \n Hi han dades sense desar, si abandona la pàgina perdrà els canvis.';
		}
	
	});
     </script>
  </head>
  <body>
    <div id="pos-ctr" >
<div class="accordion">
	<h3>Sessió actual</h3>
	<p>
    <b>Usuari:</b> <input type="text" id="user" value="" class="noborder"><br>
    <b>Nom:</b> <input type="text" id="nom" value="" class="noborder"><br>
	<b>Descripció:</b> <input type="text" id="desc" value="" class="noborder">
<input type="button" onClick="javascript:common.closeSession();" value="Sortir">

    </p>
	<h3>Mapa</h3>
	<p>
    	<input type="button" id="center" onClick="javascript:common.fitMarkers();" value="Enquadrar tasques">
    <input type="button" id="showMyPos" onClick="javascript:getCurrentPos();" value="Mostra posició">  
    <script>util.getLocString('#showMyPos',LBL_SHOWMYPOS);</script>        
    </p>
    <!--
	<h3>Base de dades</h3>
	<p>
k    <input type="button" id="ajax" onClick="javascript:ajaxQuery();" value="Carregar totes les tasques">
    <input type="button" id="savemarks" onClick="javascript:saveMarks();" value="Desar els canvis">
    </p>
    -->
	<h3 id="operador">Dades</h3>
	<p id="operador">
    <select name="tecnics" id="tecnics" onChange="javascript:common.getMarkers(getElementById('tecnics').value);" style="width:59%"></select>
	<input type="button" id="getTecnic" onClick="javascript:common.getMarkers(getElementById('tecnics').value);" value="Carregar" style="width:39%">
    <!--
    <select name="lobjectes" id="lobjectes" onChange="javascript:getObjecteWork(getElementById('lobjectes').value);" style="width:59%"></select>
	<input type="button" id="getTecnic" onClick="javascript:getObjecteWork(getElementById('lobjectes').value);" value="Carregar" style="width:39%">
    -->
<input type="button" id="Sync" onClick="javascript: Synch();" value="Sincronitzar dades">
    </p>
    <!--
	<h3 id="tecnic">Tècnic</h3>
	<p id="tecnic">
    <input type="hidden" id="tecnics" name="tecnics" value="<?php echo $_SESSION['userid']; ?>">
    <input type="button" id="Sync" onClick="javascript: Synch();" value="Sincronitzar dades">      
    <input type="button" id="showMyPos" onClick="javascript:getCurrentPos();" value="Mostra posició">  
    <script>util.getLocString('#showMyPos',LBL_SHOWMYPOS);</script>
    </p>
-->
    
	<h3>Editor múltiple de tasques</h3>
    <p>
    Seleccioneu múltiples tasques, i utilitzeu aquests controls per a modificar-les totes a l'hora.<br>
    <input type="hidden" name="iddada" id="iddada">
    <input type="hidden" name="idmarker" id="idmarker">
      <input type="checkbox" name="selectMode" id="selectMode" onClick="javascript:selectModo(this.checked);">Activar selector múltiple<br>
      <LABEL FOR="objectes">Objecte:</LABEL><select name="objectes" id="objectes" onChange="javascript:updateObjecte();"></select>
      <LABEL FOR="actuacions">Actuació:</LABEL><select name="actuacions" id="actuacions" onChange="javascript:updateActuacio();"></select>
      <LABEL FOR="estats">Estat:</LABEL><select name="estats" id="estats"  onChange="javascript:updateEstat();"></select>
    </p>
    

    
<h3>Mode d'inserció</h3>
    <p>
    Definiu aquí la configuració al inserir una nova tasca (click a una zona buida del mapa). Totes les tasques seran creades amb la configuració aquí definida.<br>
    Mode d'inserció: 
    <select name="insMode" id="insMode">
    	<option value="3" id="3">Click llarg</option>
    	<option value="1" id="1">Click sencill</option>
        <option value="2" id="2">Doble click</option>
        <option value="4" id="4" selected>Marker confirmació</option>
    </select>
    <br>
    Al fer click a una zona buida del mapa, s'afegirà un <select name="objectesIns" id="objectesIns"></select> en estat <select name="estatsIns" id="estatsIns"></select> i, el tècnic  <select name="tecnicsIns" id="tecnicsIns"></select> ha de <select name="actuacionsIns" id="actuacionsIns" ></select> l'objecte.
</p>

<h3>Taules mestre</h3>
<p>
    <input type="button" id="mgUsuaris" onClick="javascript:util.showUsers();" value="Gestionar usuaris">
</p>
	<h3 id="reg">Registre</h3>
	<p>
      <textarea class="reg" id="log"></textarea>
    </p>
    <h3 id="eines">Eines</h3>
    <p>
    <input type="button" id="reTecnics" onClick="javascript:util.feedSelects();" value="Recarregar dades">
    <!--<input type="button" id="loadKML" onClick="javascript:loadKml();" value="Mostrar KML">-->
    <input type="button" id="uploadKML" onClick="javascript:util.showUpload();" value="Carregar arxiu KML">
    <input type="button" id="deleteAllMarkers" onClick="javascript:common.deleteAllMarkers();" value="Esborrar totes les marques">
    <input type="button" id="exportCSV" name="exportCSV" onClick="javascript:location.href='/logic/exportDadaCSV.php';" value="exportar CSV">
    <input type="button" id="exportKML" name="exportKML" onClick="javascript:location.href='/logic/exportDadaKML.php';" value="exportar KML">
<!--
<input type="button" id="alert" onClick="javascript:alert(json_markers());" value="json_markers">
        <input type="button" id="aler2t" onClick="javascript:alert(isTouched());" value="isTouched">
-->
    </p>
    
</div>

    </div>
	<div>
    <div id="us">
    <iframe id="usf" name="usf" src="usuaris.php" style="width:100%; height:280px; border:0;" >
    </iframe>
    </div>
	<div id="msg">
    <iframe id="msgf" name="msgf" src="uploadKML.html" style="width:100%; height:350px; border:0;" >
    </iframe>
	</div>
	 <div id="pos-map"/>
		
	</div>
  </body>
</html>
<?php
}
?>
