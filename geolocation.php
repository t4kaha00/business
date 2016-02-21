<!DOCTYPE html>
<html>
  <head>
    <title>GeoLocation</title>
    <style type="text/css"> 
        html { height: 100% }
        body { height: 100%; margin: 0px; padding: 0px }
        #map { height: 250px; width: 500px }
    </style> 

    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script type="text/javascript" charset="utf-8">

    //declare namespace
    var submit = {};

    //declare map
    var map;

    //set the geocoder
    var newgeocoder = new google.maps.Geocoder();

    function trace(message){
        if (typeof console != 'undefined') {
            console.log(message);
        }
    }

    //function that gets run when the document loads
    submit.initialize =  function(){
        var latlong = new google.maps.LatLng(34,-118);
        var Options = {
            zoom: 13,
            center: latlong,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map"), Options);

    }

    //geocode function
    submit.geocode = function(){
        var address = $('#address').val();
        newgeocoder.geocode({'address': address}, function(results, status){
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location        
                });
            }else{
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }
    
    function getLocation(){
    	console.log("Entering getLocation()");
    	if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition(
			displayCurrentLocation,
			displayError,
			{ 
				maximumAge: 3000, 
				timeout: 5000, 
				enableHighAccuracy: true 
			})
		}else{
			console.log("Oops, no geolocation support");
		} 
    	console.log("Exiting getLocation()");
    };

    function displayCurrentLocation(position){
    	console.log("Entering displayCurrentLocation");
    	var latitude = position.coords.latitude;
		var longitude = position.coords.longitude;
		console.log("Latitude " + latitude +" Longitude " + longitude);

        var latlon = new google.maps.LatLng(latitude, longitude)
        mapholder = document.getElementById('map')
        mapholder.style.height = '250px';
        mapholder.style.width = '500px';

        var myOptions = {
            center:latlon,
            zoom:14,
            mapTypeId:google.maps.MapTypeId.ROADMAP,
            mapTypeControl:false,
            navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
        }

        var maps = new google.maps.Map(document.getElementById('map'),myOptions);
        var marker = new google.maps.Marker({position:latlon,map:maps,title:"You are here!"});

        getAddressFromLatLang(latitude, longitude);

    	console.log("Exiting displayCurrentLocation");
    }

   function  displayError(error){
		console.log("Entering ConsultantLocator.displayError()");
		var errorType = {
			0: "Unknown error",
			1: "Permission denied by user",
			2: "Position is not available",
			3: "Request time out"
		};
		var errorMessage = errorType[error.code];
		if(error.code == 0  || error.code == 2){
			errorMessage = errorMessage + "  " + error.message;
		}
		alert("Error Message " + errorMessage);
		console.log("Exiting ConsultantLocator.displayError()");
	}

    function getAddressFromLatLang(lat,lng){
    	console.log("Entering getAddressFromLatLang()");
    	var geocoder = new google.maps.Geocoder();

        if (lat != '' && lng != '') {
            var latLng = new google.maps.LatLng(lat, lng);              //turn coordinates to an object    
            return getCurrentAddress(latLng);
        };                 //create geocoder object
        

        geocoder.geocode( { 'latLng': latLng}, function(results, status) {
    		console.log("After getting address");
    		console.log(results);
    		if (status == google.maps.GeocoderStatus.OK) {
    			if (results[1]) {
                    console.log(results[1]);
                    alert(results[1].formatted_address);
                }
    		}else{
    			alert("Geocode was not successful for the following reason: " + status);
    		}
        });

    	console.log("Exiting getAddressFromLatLang()");
    }

    function getCurrentAddress(location) {
        console.log("Entering getCurrentAddress()");
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({
            'location': location
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results[0]);
                $("#address").val(results[0].formatted_address);
            }else{
                alert("Geocode was not successful for the following reason: " + status);
            }
        });

        console.log("Exiting getCurrentAddress()");
    }

    /*var input = document.getElementById('address');
    var searchbox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getbounds());
    });

    var markers = [];
    searchBox.addListener('places_changed', function(){
        var places = searchBox.getPlaces();

        if(places.length == 0){
            return;
        }

        markers.forEach(function(marker){
            marker.setMap(null);
        });

        markers = [];

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place){
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            markers.push(new google.maps.Marker({
            map: map,
            icon: icon,
            title: place.name,
            position: place.geometry.location
      }));
            if (place.geometry.viewport) {
            // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }     
        });
        map.fitBounds(bounds);
    });*/

    

    </script>
  </head>
  <body onload="submit.initialize()">
    <div style="position:absolute; width:380px; height: 100%; overflow:auto; float:left; padding-left:10px; padding-right:10px;"> 
  	<h1>Display the map here</h1>
    <input type="text" id="address" />
  	<input type="button" onclick="submit.geocode()" value="find"/>
    <input type="button" id="getLocation" onclick="getLocation()" value="Get Location"/>
    <div id="map"></div>
    <p id="adress"></p>
    </div>
    <div id="map_canvas" style="height:100%; margin-left: 400px;"></div>
    
  </body>
</html> 