/*
	RFM Software 2013
	
	common file for bussines functions (user actions)

	- closeSession
	- fitMarkers
	- placeMarker
	- deleteAllMarkers
	- getMarkers
	- synchMarkers ***
	- populateUI ***
	- uploadKML ***
	- placeBMarker ***
*/

var common = {
	
	// Close session function
	/*
	 * We don't need to check if the user has unsaved changes
	 * The beforeunload function check's work progress and work lost
	 * The user can cancel the close.php redirection
	 */
	closeSession: function(){
		toLog(MSG_COMMON_CLOSE);// showing log action
		window.open('close.php','_self');// redirecting to close.php page
	},
	
	// Fit markers function
	/*
	 * Fit all the actual markers on the map
	 * Only markers on the markers collection will be fitted
	 */
	fitMarkers: function(){
		toLog(MSG_COMMON_FITMARKERS);
		if(markers.length==0)// has data?
			return;// no data = exit
		var bounds = new google.maps.LatLngBounds();//  Create a new viewpoint bound
		$.each(markers, function (index, marker) {
			bounds.extend(marker.position);
		});
		map.fitBounds(bounds);//  Fit these bounds to the map
	},

	// Place dummie marker
	placeBMarker: function(location){
		dmarker.setPosition(location);
		dmarker.setMap(map);
		dmarker.setIcon('/img/addmark.png');
		dmarker.setTitle(LBL_ADD_MARKER);
		//dmarker.icon = '/img/addmark.png';
	},

	// Place marker function
	/*
	 * This function creates the marker on the memory & map
	 * It's called with new & existing (db) markers
	 * Will be called for each existing marker.
	 */
	placeMarker: function(location, iddada, idestat, idactuacio, idobjecte, idusuariassig, idperfilassig, anom, onom, enom){

		var touched=false;// no need to save
		
		// Dades al Inserir nova tasca
		if(iddada==undefined||iddada=='undefined')iddada=0;// nova
		if(idestat==undefined||idestat=='undefined')idestat=$('#estatsIns').val();// desconegut
		if(idactuacio==undefined||idactuacio=='undefined')idactuacio=$('#actuacionsIns').val();// indefinida
		if(idobjecte==undefined||idobjecte=='undefined')idobjecte=$('#objectesIns').val();// desconegut
		if(idusuariassig==undefined||idusuariassig=='undefined')idusuariassig=$('#tecnicsIns').val();//cap
		if(idperfilassig==undefined||idperfilassig=='undefined')idperfilassig=0;//cap
		if(anom==undefined||anom=='undefined')anom=$("#actuacionsIns :selected").text();// nova
		if(onom==undefined||onom=='undefined')onom=$("#objectesIns :selected").text();// nova
		if(enom==undefined||enom=='undefined')enom=$("#estatsIns :selected").text();// nova
		
		if(iddada==0)
			touched=true;// new item to save
		
		// setting icon
		var image = util.getMarkerIcon(idactuacio);

	// text
		var mtitle = anom + " " + onom;

	// creating marker
		var marker = new google.maps.Marker({
			todelete:0,
			selected:false,
			touched:touched,
			id: markers.length,
			position: location,
	    	map: map,
			icon: image,
			title: mtitle,
			iddada: iddada,
			idestat: idestat,
			idactuacio: idactuacio,
			idobjecte: idobjecte,
			idusuariassig: idusuariassig,
			idperfilassig: idperfilassig
		});
		
		var infoWindow = new google.maps.InfoWindow({
			id: infoWindows.length,
			markerid: marker.id,
			content: util.getInfoText(location, iddada, idestat, idactuacio, idobjecte, idusuariassig, idperfilassig, anom, onom, enom)
		});
		
		// setting listener to marker
		google.maps.event.addListener(marker, 'click', function() {
			
			// amaguem les infoWindows actuals
			util.closeInfo();
		
			if(selectMode==false){
				// mostrem les dades i obtenim ids (iddada/idmarker)
				util.showMarkerData(marker);

				// definim el text (obtenint dades del propi marker)
				infoWindow.setContent(util.getInfoText(marker.position, marker.iddada, marker.idestat, marker.idactuacio, marker.idobjecte, marker.idusuariassig, marker.idperfilassig, $("#actuacions :selected").text(), $("#objectes :selected").text(), $("#estats :selected").text()));
			
				// mostrem la infoWindow del marker
				infoWindow.open(map, marker);
			
				// actualitzem text del marker (per si s'ha modificat)
				marker.setTitle(anom + ' ' + onom);
			}
			else{
				// switch
				if(marker.selected){
					marker.selected=false;
					marker.setIcon(util.getMarkerIcon(marker.idactuacio));
				}else{
					marker.selected=true;
					marker.setIcon('img/select.png');
				}
				
			}
			
		});
		// adding marker to list
		markers.push(marker);
		// adding infoWindow to list
		infoWindows.push(infoWindow);	
	},

	// Delete All markers function
	/*
	 * Set flag on all markers to be deleted on the next synch
	 * Util to clean/remove/delete all the work from a tech
	 */
	deleteAllMarkers: function(){
		toLog(MSG_COMMON_DELETEALLMARKERS);
		$.each(markers, function (index, marker){
				  if(marker.iddada>0)
					  marker.touched=true;
				 else
					marker.touched=false;
				  marker.todelete=1;
				  marker.setMap(null);
			  });		
	},
	
	getMarkers: function(idtecnic){
		toLog(MSG_COMMON_GETMARKERS);
		
		if(isTouched()){
			if(confirm(CONFIRM_MSG_UNSAVEDWORK)==false)
				toLog(MSG_COMMON_GETMARKERS_CANCEL);
				return; // Cancel·lem la càrrega
		}
		
		// esborrem les marques actuals
		util.clearMarkers();
		
		// Carreguem dades útils
		//util.feedSelects();
		//util.addTecnics();
		
		// Obtenim feina
			var total=0;
		
		$.getJSON("logic/getDadaById.php?id="+idtecnic,function(json){

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
				alert(MSG_UNTRUSTED);
				common.closeSession();
			}
		});

		// Igualem (per a insert mode)
		$('#tecnicsIns').val($('#tecnics').val());

	}
	
}