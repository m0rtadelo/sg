// RFM Software 2013


var map;// map object
var userid;// userid
var usertype; // usertype
var selectMode = false; // selectMode editor

markers = Array();// markers Array
infoWindows = Array();// infoWindows Array

var dmarker;// dummie marker
//var mypos;// current pos marker

// longpress events
  function LongClick(map, length) {
    this.length_ = length;
    var me = this;
    me.map_ = map;
    google.maps.event.addListener(map, 'mousedown', function(e) { me.onMouseDown_(e) });
    google.maps.event.addListener(map, 'mouseup', function(e) { me.onMouseUp_(e) });
  }
  
  LongClick.prototype.onMouseUp_ = function(e) {
    var now = +new Date;
    if (now - this.down_ > this.length_) {
      google.maps.event.trigger(this.map_, 'longpress', e); 
    }
  }
  
  LongClick.prototype.onMouseDown_ = function() {
    this.down_ = +new Date;
  }

 function loadKml(){
	 var ctaLayer = new google.maps.KmlLayer({
		    //url: 'http://sg.travisnet.es/kml/cta.kml' 
		 url: 'http://sg.travisnet.es/kml/doc.kml'
		  });
		  ctaLayer.setMap(map);
 } 
// init map
function initialize() {
	toLog(MSG_JMAP_INIT);
	var mapOptions = {// map options
		center: new google.maps.LatLng(41.382991, 2.1697998046),// Barcelona
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	// setting map object
	map = new google.maps.Map(document.getElementById("pos-map"), mapOptions);
	
	// setting dummie marker
	// Dummie marker
	dmarker = new google.maps.Marker({map: null});
	// Dummie event
	google.maps.event.addListener(dmarker, 'click', DMarkerClick);
	// setting mypos marker
	//mypos = new google.maps.Marker({map: null});

	// adding map click listener
	google.maps.event.addListener(map, 'click',function(event) {
		MapClick1(event.latLng);
		MapClick4(event.latLng);
//    	placeMarker(event.latLng);
	}); 
	
	// TEST EVENTS START
	// adding map dblclick listener
	google.maps.event.addListener(map, 'dblclick',function(event) {
		MapClick2(event.latLng);
    });
	
	new LongClick(map, 500);

	// adding long press click listener
	google.maps.event.addListener(map, 'longpress', function (event){
		MapClick3(event.latLng);
    });
	// TEST EVENTS END
	
	// showing UI
	$(".accordion h3:first").addClass("active");
	$(".accordion p:not(:first)").hide();

	$(".accordion h3").click(function(){
		$(this).next("p").slideToggle("slow");
//		.siblings("p:visible").slideUp("slow");
		$(this).toggleClass("active");
		$(this).siblings("h3").removeClass("active");
	});

	// getting user data
	getUserData();
	
}

function DMarkerClick(){
	dmarker.setMap(null);
	common.placeMarker(dmarker.getPosition());
}

// Map click events (moved from negoci.js to here)
function MapClick1(location){// Map click event
	if(selectMode)return;// no afegim tasques en selectMode
	if($('#insMode').val()==1)
		common.placeMarker(location);// Place location only if event = insmode
}
function MapClick2(location){// Map dblclick events
	if(selectMode)return;// no afegim tasques en selectMode
	if($('#insMode').val()==2){
		common.placeMarker(location);// Place location only if event = insmode
		$['casque'].val(1);// trying to stop zoom by dblclick by casquing (stopping )!
	}
}
function MapClick3(location){// Map long click event
	if(selectMode)return;// no afegim tasques en selectMode
	if($('#insMode').val()==3)
		common.placeMarker(location);// Place location only if event = insmode
}
function MapClick4(location){// Map dummie marker event
	if(selectMode)return;
	if($('#insMode').val()==4)
		common.placeBMarker(location);
}
function updateObjecte(){
	if(selectMode)return;
	
	$.each(markers, function (index, marker){
							  if(marker.id==$('#idmarker').val()){

	marker.idobjecte = $('#objectes').val();
	marker.touched=true;
							  }
							  });
}

function updateActuacio(){
//	toLog("update actucio");
	if(selectMode)return;
		
	$.each(markers, function (index, marker){
							  if(marker.id==$('#idmarker').val()){
//		toLog("marker.id" + marker.id);
		marker.idactuacio = $('#actuacions').val();
		marker.setIcon(util.getMarkerIcon(marker.idactuacio));
		marker.touched=true;
							  }
	});	
		
		
}
function updateEstat(){
	if(selectMode)return;
	
	$.each(markers, function (index, marker){
							  if(marker.id==$('#idmarker').val()){

	marker.idestat = $('#estats').val();
	marker.touched=true;
							  }
							  });
}

function isTouched(){
	var res = false;
	$.each(markers, function (index, marker){
//							  toLog("markerid: " + marker.id + " = " + marker.touched);
							  if(marker.touched==true)
								  res =true;
  });
	return res;
}

function setSaved(){
	$.each(markers, function (index, marker){
							  marker.touched=false;
  });
}

function redrawMarkers(){
	$.each(markers, function (index, marker){
							  marker.setIcon(getMarkerIcon(marker.idactuacio));
});	
}

function populateInfoSelects(){
	toLog("Populating data...");
//	$('#objectes').find('option').clone().appendTo('#infoObjecte');
	$('#infoObjecte').append($('<option>').text('myText').attr('value',123));
//	$('#infoObjecte').val($('#objectes').val());
}
/*
	Afegeig als tècnics al select
*/
function popTecnics(ts){
	$(ts).find('option').remove();

	$.getJSON("logic/getTecnics.php",function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
			 $(ts).append($('<option>').text(dat.nom).attr('value', dat.idusuari));
			});				
		}else{
			alert("Untrusted!");
			closeSession();
		}
	});	
}


function updObjectes(slt){
//	toLog(" value = " +slt.options[slt.selectedIndex].value);
	$('#objectes').val(slt.options[slt.selectedIndex].id);
	updateObjecte();
}

function updActuacions(slt){
	$('#actuacions').val(slt.options[slt.selectedIndex].id);
	updateActuacio();
}
function updEstats(slt){
	$('#estats').val(slt.options[slt.selectedIndex].id);
	updateEstat();
}

function popObjectes(ts){
	$(ts).find('option').remove();

	$.getJSON("logic/getObjectes.php",function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
			 $(ts).append($('<option>').text(dat.nom).attr('value', dat.idobjecte));
			});				
		}else{
			alert("Untrusted!");
			closeSession();
		}
	});	
}


function popEstats(ts){
	$(ts).find('option').remove();

	$.getJSON("logic/getEstats.php",function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
			 $(ts).append($('<option>').text(dat.nom).attr('value', dat.idestat));
			});				
		}else{
			alert("Untrusted!");
			closeSession();
		}
	});	
}


function popActuacions(ts){
	$(ts).find('option').remove();

	$.getJSON("logic/getActuacions.php",function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
			 $(ts).append($('<option>').text(dat.nom).attr('value', dat.idactuacio));
			});				
		}else{
			alert("Untrusted!");
			closeSession();
		}
	});	
}


/*
	Obté els marcadors que s'han tocat (afegit, modificat)
*/
function json_markers(){
	var j = '{"action":"update","members":[';
	$.each(markers, function (index, marker){
	if(marker.touched==true){							  
		var ll = marker.getPosition();
		j=j+"{";
    	  j=j+'"todelete":"'+marker.todelete+'",' ;
		  j=j+'"iddada":"'+marker.iddada+'",' ;
		  j=j+'"idestat":"'+marker.idestat+'",' ;
		  j=j+'"idactuacio":"'+marker.idactuacio+'",' ;
		  j=j+'"idobjecte":"'+marker.idobjecte+'",' ;
		  j=j+'"idusuariassig":"'+marker.idusuariassig+'",' ;
		  j=j+'"idperfilassig":"'+marker.idperfilassig+'",' ;
		  j=j+'"latitude":"'+ll.lat()+'",' ;
		  j=j+'"longitude":"'+ll.lng()+'"' ;
		j=j+"},";
	}
});	
	return j + "{}]}";
}
// test db query database (jquery+ajax+json)
function ajaxQuery(){
	var total=0;
//	toLog("Obtenint dades ...");
	
	$.getJSON("logic/getDada.php",function(json){

		// status?
		if (json.status == "success"){
			$.each(json.members,function(i,dat){
				total++;
				placeMarker(
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

function stopp(){
	if (!e) var e = window.event;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}
function getUserData(){

	//addTecnics();
	util.feedSelects();
	
	toLog("Consultant credencials de l'usuari...");
	
	$.getJSON("logic/getUserById.php?id="+userid, function(json){
		
		// status
		if(json.status == "success"){
		$.each(json.members, function(i,dat){
					$("#user").val(dat.usuari);
					$("#nom").val(dat.nom);
					$("#desc").val(dat.descripcio);
					usertype = dat.idperfil;
		});
		
		// Definint vistes
		if(usertype==1){// Operador
			UI_ope();
		}
		else if(usertype==2){// Tècnic
			UI_tec();
		}else { // Indefinit
			alert("Tipus d'usuari no definit!");
			closeSession();
		}
		}else{
			// sessió no autoritzada
			alert("Untrusted!");
			closeSession();
		}
	});
}




function encode_utf8(s) {
  return unescape(encodeURIComponent(s));
}

function n(n){
    return n > 9 ? "" + n: "0" + n;
}
// Afegeig el texte rebut a la finestre de registre
function toLog(txt){
var fecha = new Date();
var hora = fecha.getHours();
var minuto = fecha.getMinutes();

//var segundo = fecha.getSeconds();
	$("#log").val($("#log").val() + "\n" + n(hora) + ":" + n(minuto) + " " + txt);
	$("#log").scrollTop(99999);
}

// Indica que cal mostrar l'interfície per a l'operador, desactivant la del tècnic
function UI_ope(){
	toLog("Definint interfície per a l'operador...");
	//$("#tecnic").hide();
}
// Indica que cal mostrar l'interfície per al tècnic, desactivant la de l'operador
function UI_tec(){
	toLog("Definint interfície per al tècnic...");
	//$("#operador").hide();
	$("#eines").hide();
	//$("#reg").hide();
	$("#tecnicsIns").attr('disabled','disabled');
	$("#tecnics").val(userid);
	$("#tecnics").attr('disabled','disabled');
	Synch();
	//common.fitMarkers();
}

// get current location 
function getCurrentPos(){
	if(navigator.geolocation){
		// geolocation support
		 navigator.geolocation.getCurrentPosition(function(position) {
		      var gpsLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
		      common.placeBMarker(gpsLocation);
		      toLog("GPS: OK");
		 }, function() {
      		// error obtaining geolocation
      		toLog(MSG_GPS_ERROR);
    });		
	}else{
		// no method to obtain currrent geolocation
		toLog(MSG_GPS_NULL);
	}
}
