// RFM Software 2013
/*
	Funcions de la capa de negoci
*/


function selectModo(modo){
	if(modo==true){
		// multiple activated
		selectMode=true;
		// tanquem tots els infoWindows
		closeInfo();
	}
	else{
		// multiple deactivated
		selectMode=false;
		redrawMarkers();
	}
}
/*
	Carreguem la feina d'un tècnic definit per idtecnic
*/

/*
	Carreguem la feina des d'un objecte
*/
/*
function getObjecteWork(idobjecte){
	
	// TODO: Revisar que no perdem feina feta
	if(isTouched()){
		if(confirm(CONFIRM_MSG_UNSAVEDWORK)==false)
			return; // Cancel·lem la càrrega
	}
	
	// esborrem les marques actuals
	clearMarkers();
	
	// Carreguem dades útils
	feedSelects();
	// Igualem
	//$('#tecnicsIns').val($('#tecnics').val());
	
	// Obtenim feina
		var total=0;
	toLog("Obtenint dades ...");
	
	$.getJSON("logic/getDadaByObjecte.php?id="+idobjecte,function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
				total++;
				common.placeMarker(
							new google.maps.LatLng(dat.latitude,dat.longitude), 
							dat.iddada, 
							dat.idestat, 
							dat.idactuacio, 
							dat.idobjecte,
							dat.idusuariassig,
							dat.idperfilassig,
							dat.anom,
							dat.onom,
							dat.enom
							);
			});				
			toLog("Dades obtingudes (total: "+total+")");
		}else{
			alert("Untrusted!");
			closeSession();
		}
	});

}
*/
function deleteMarker(){
	var mrkid = $('#idmarker').val();
	var idda = $('#iddada').val();
	toLog("deleteMarker mrkid="+mrkid+" / iddada="+idda);
	
	// Esborrem el marker (literal)
	$.each(markers, function (index, marker){
		  if(marker.id==mrkid){
			  if(idda>0)
				  marker.touched=true;
			 else
				marker.touched=false;
			  marker.todelete=1;
			  marker.setMap(null);
		  }
		  });
	// Si te id, cal esborrar-lo de la base de dades
	if(idda>0){
		// afegim a col·lecció d'esborrar
	}
	
	// stop propagation
	stopp();
}


var isSynch = false;
function Synch(){
	toLog("NEGOCI: Sincronitzant tasques...");
	if(isTouched()){
		// Primer desem les marques (si n'hi han)
		isSynch = true;
		saveMarks();
	}
	else{
		util.clearMarkers();
		common.getMarkers($('#tecnics').val());
	}
}

// test db update
function saveMarks(){
	
	toLog("Desant les dades...");
	/*
	$.ajax({
	 url: "logic/action.php",
	 type: "POST",
	 dataType: "application/json; charset=utf-8",
	 data: json_markers()
	},function(data){
		alert(data);
		toLog("Fet!");
	});

*/

	$.post("logic/action.php", json_markers(), 
											function(data){
												var data = $.parseJSON(data);
												if(data.status == "ok"){
													toLog(data.result);
													if(isSynch==true){
														setSaved();
														util.clearMarkers();
														common.getMarkers($('#tecnics').val());
													}
													else{
														alert(data.result);
														setSaved();
													}
												}
												else{
													alert(data.status);
												}
												isSynch=false;
											});
	
//	var data = {};
//	$.each(markers, function (index, marker){
		
//	});
} 


