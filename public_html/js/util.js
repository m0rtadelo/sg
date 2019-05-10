/*
	utils functions
*/

var util = {
	

	// localize string
	getLocString: function(id_value, value){
		$(id_value).val(value);
	},

	showUsers: function(){
		$('#msg').hide();
		$('#us').toggle(400);
		$('#usf').attr('src','usuaris.php');
	},

	showUpload: function(){
		$('#us').hide();
		$('#msg').toggle(400);
		$('#msgf').attr('src','uploadKML.html');
	},

	// get Marker icon
	/*
	*	Return the path for the correct icon
	*/
	getMarkerIcon: function(idactuacio){
		var image = 'img/undefined.png';	
		if(idactuacio==3)
			image = 'img/revisar.png';
		if(idactuacio==2)
			image = 'img/retirar.png';
		if(idactuacio==4)
			image = 'img/colocar.png';
		if(idactuacio==6)
			image = 'img/moure.png';
		return image;
	},

	// get Info content
	/*
	*	return the div content for the infoWindow element
	*/
	getInfoText: function(location, iddada, idestat, idactuacio, idobjecte, idusuariassig, idperfilassig, anom, onom, enom){
		// creating infoWindow
		var defcolor = "#FF0000";
		if(idactuacio==1)// Indefinida
			defcolor = "#5B0101";
		if(idactuacio==3)// Revisar
			defcolor = "#FF6600";
		if(idactuacio==4)//colocar
			defcolor = "#019F14";
			
		var cstring =  "<div class='infowindow'><h1>"+onom+"</h1><h2 style='color:"+defcolor+"'>"+anom+"</h2><p> Lat.:"+ location.lat() + "<br> Lng.:"+ location.lng()+"<br> Estat: "+enom+"</p>"
		+
		"<input type='button' value='Esborrar element' onClick='javascript:deleteMarker();'>";
		
		// Objecte
		cstring = cstring +
		"Objecte: <select name='infoObjecte' id='infoObjecte' onChange='javascript:updObjectes(this)'>";
		$('#objectes').find('option').each(function() {
			if($(this).val()==idobjecte)
		   		cstring = cstring + "<option id='"+$(this).val()+"' selected>" + $(this).text() + "</option>";
			else
		   		cstring = cstring + "<option id='"+$(this).val()+"'>" + $(this).text() + "</option>";
	   	});
		cstring = cstring +	"</select></br>";

		// Actuació
		cstring = cstring +
		"Actuació: <select name='infoActuacio' id='infoActuacio' onChange='javascript:updActuacions(this)'>";
		$('#actuacions').find('option').each(function() {
			if($(this).val()==idactuacio)
		   		cstring = cstring + "<option id='"+$(this).val()+"' selected>" + $(this).text() + "</option>";
			else
		   		cstring = cstring + "<option id='"+$(this).val()+"'>" + $(this).text() + "</option>";
	   	});
		cstring = cstring +	"</select></br>";

		// Estat
		cstring = cstring +
		"Estat: <select name='infoEstat' id='infoEstat' onChange='javascript:updEstats(this)'>";
		$('#estats').find('option').each(function() {
			if($(this).val()==idestat)
		   		cstring = cstring + "<option id='"+$(this).val()+"' selected>" + $(this).text() + "</option>";
			else
		   		cstring = cstring + "<option id='"+$(this).val()+"'>" + $(this).text() + "</option>";
	   	});
		cstring = cstring +	"</select></br>";

	cstring = cstring +	"</div>";
	return cstring;
	},

	// set marker data
	/*
	*	pupulates the hidden vars with data of the marker
	*/
	showMarkerData: function(marker){
	//	toLog("Marker iddada clicked: " + marker.iddada + " / idmarker: " + marker.id);
		$('#objectes').val(marker.idobjecte);
		$('#estats').val(marker.idestat);
		$('#actuacions').val(marker.idactuacio);
		$('#iddada').val(marker.iddada);
		$('#idmarker').val(marker.id);
	//	populateInfoSelects();
	},

	// Close all infoWindows
	/*
	*	Close all the infoWindows that are open in the map
	*/
	closeInfo: function(){
			$.each(infoWindows, function(index, infoWindow){
				infoWindow.setMap(null);
			});
	},

	// remove markers/work from memory
	/*
	*	Remove all the markers/infoWindows (current work) from memory
	*/
	clearMarkers: function(){
		$.each(markers, function (index, marker){
								  marker.setMap(null);
		});
		markers.length = 0;
		$.each(infoWindows, function(index, infoWindow){
									infoWindow.setMap(null);
		});
		infoWindows.length = 0;
		
	},

	// populate ui with database data
	/*
	*	Refresh the data on the UI (selects)
	*/
	feedSelects: function(){
		this.addObjectes();
		this.addEstats();
		this.addActuacions();
		this.addTecnics();
	},

	// populate objectes objects
	/*
	*	Refresh the data (objectes) on the UI (selects)
	*/
	addObjectes: function(){

		var ins = $('#objectesIns').val();
		var ins2 = $('#lobjectes').val();
		$('#objectes').find('option').remove();
		$('#objectesIns').find('option').remove();
		$('#objectesUps').find('option').remove();
		$('#lobjectes').find('option').remove();
		
		$.getJSON("logic/getObjectes.php",function(json){

			// status?
			if (json.status == "success"){
				$.each(json.members,function(i,dat){
				 $('#objectes').append($('<option>').text(dat.nom).attr('value', dat.idobjecte));
				 $('#objectesIns').append($('<option>').text(dat.nom).attr('value', dat.idobjecte));
				 $('#objectesUps').append($('<option>').text(dat.nom).attr('value', dat.idobjecte));
				 $('#lobjectes').append($('<option>').text(dat.nom).attr('value', dat.idobjecte));
				});				
			}else{
				alert(MSG_UNTRUSTED);
				common.closeSession();
			}
			$('#objectesIns').val(ins);
			$('#lobjectes').val(ins2);
		});		
	},

	// populate estats objects
	/*
	*	Refresh the data (estats) on the UI (selects)
	*/
	addEstats: function(){
		var ins = $('#estatsIns').val();
		$('#estats').find('option').remove();
		$('#estatsIns').find('option').remove();
		$('#estatsUps').find('option').remove();
		$.getJSON("logic/getEstats.php",function(json){

			// status?
			if (json.status == "success"){
				$.each(json.members,function(i,dat){
				 $('#estats').append($('<option>').text(dat.nom).attr('value', dat.idestat));
				 $('#estatsIns').append($('<option>').text(dat.nom).attr('value', dat.idestat));
				 $('#estatsUps').append($('<option>').text(dat.nom).attr('value', dat.idestat));
				});				
			}else{
				alert(MSG_UNTRUSTED);
				common.closeSession();
			}
			$('#estatsIns').val(ins);
		});		
	},

	// populate actuacions objects
	/*
	*	Refresh the data (actuacions) on the UI (selects)
	*/
	addActuacions: function(){
		var ins = $('#actuacionsIns').val();
		$('#actuacions').find('option').remove();
		$('#actuacionsIns').find('option').remove();
		$('#actuacionsIUps').find('option').remove();
		$.getJSON("logic/getActuacions.php",function(json){

			// status?
			if (json.status == "success"){
				$.each(json.members,function(i,dat){
				 $('#actuacions').append($('<option>').text(dat.nom).attr('value', dat.idactuacio));
				 $('#actuacionsIns').append($('<option>').text(dat.nom).attr('value', dat.idactuacio));
				 $('#actuacionsUps').append($('<option>').text(dat.nom).attr('value', dat.idactuacio));
				});				
			}else{
				alert(MSG_UNTRUSTED);
				common.closeSession();
			}
			$('#actuacionsIns').val(ins);
		});	
		
	},	

	// populate tecnics objects
	/*
	*	Refresh the data (tecnics) on the UI (selects)
	*/
	addTecnics: function(){
	var total=0;
		var ins = $('#tecnics').val();
		$('#tecnics').find('option').remove();
		$('#tecnicsIns').find('option').remove();
		$('#tecnicsUps').find('option').remove();
		$.getJSON("logic/getTecnics.php",function(json){

			// status?
			if (json.status == "success"){
				$.each(json.members,function(i,dat){
					total++;
				 $('#tecnics').append($('<option>').text(dat.nom).attr('value', dat.idusuari));
				 $('#tecnicsIns').append($('<option>').text(dat.nom).attr('value', dat.idusuari));
				 $('#tecnicsUps').append($('<option>').text(dat.nom).attr('value', dat.idusuari));
				});				
			}else{
				alert(MSG_UNTRUSTED);
				common.closeSession();
			}
			$('#tecnics').val(ins);		
		});	
		//util.feedSelects();
	}

}