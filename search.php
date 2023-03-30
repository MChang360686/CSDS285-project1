<!DOCTYPE html>
<!-- TODO USE BOOTSTRAP OR SOMETHING FOR STYLING -->
<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> <!-- JQuery -->
    <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
	
	<div id="header">
		
		<div id="search">
			<h1>Search all US Craigslist Postings</h1>
			<h4 style="margin-bottom: 0;">Enter item to scrape for</h4>
			 <div style="margin-bottom: 10px;">
				<input id="searchInput" type="text" placeholder="Enter Item">
				<button id="searchSubmitButton">Search</button>
			</div>
			<!--Help Popup code-->
		  <br>
		 </div>
		 
	</div>
	
	<div id="map-container">
		<div id="map-key">
			<h3>Key by Price</h3>
			<span class="key"> Not Listed <img src="http://labs.google.com/ridefinder/images/mm_20_white.png"></img></span>
			<span class="key"> Low <img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png"></img></span>
			<span class="key"> Mid <img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"></img></span>
			<span class="key"> High <img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png"></img></span>
			<span class="key"> High High <img src="http://maps.google.com/mapfiles/ms/icons/dollar.png"></img></span>
		</div>
		<div id="map"></div>
	</div>
	

	 
        <!--JS goes here-->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC35EMzaJEToK-ZyOZ1SkBa1yKegKAfb8o&callback=initMap"></script>
		<script>
		
		let global_map;
		let markersArray = [];
		
		function clearPins() {
			for (var i = 0; i < markersArray.length; i++ ) {
				markersArray[i].setMap(null);
			}
			markersArray.length = 0;
		}
		
		$("#searchInput").keyup(function(e) {
			if(e.key === "Enter"){
				submitSearch();
			}
		});
		$("#searchSubmitButton").click(submitSearch);
			
		function submitSearch(e) {
			clearPins();
			const searchText = $("#searchInput").val();
			console.log("Submitted search:", searchText);
			$.post("backend/search.php?q=" + searchText, function(data, status) {
				console.log("Result:", data, status);
				data.sort(function(a, b) { return a.price - b.price;});
				for (let i = 0; i <= data.length; i ++) {
					if(data[i]){
						if(data[i].price <= 0) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://labs.google.com/ridefinder/images/mm_20_white.png', i);
							else if(i < data.length/3) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/green-dot.png', i);
							else if((i > data.length/3) && (i < (data.length/3) * 2)) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png', i);
							else if((i > (data.length/3) * 2) && (i < (data.length - 50))) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/red-dot.png', i);
							else addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/dollar.png', i);
					}
				}
			});
		}
		function hePop() {
			var popup = document.getElementById("helpPopup");
			popup.classList.toggle("show");
		}
		
		function initMap() {
		  global_map = new google.maps.Map(
			document.getElementById('map'), {zoom: 4, center: {lat: 41.36444, lng: -98.31665}}
		  );
		  
		  addMarker({lat: 41.36444, lng: -98.31665}, 100, "placeholder", "placeholder", 'http://labs.google.com/ridefinder/images/mm_20_white.png');
		}	
		
		let infoWindow;
		function showPins(){
			let content = `<div id="pin-options-container">
			  <img class="pin-option" src="http://maps.google.com/mapfiles/ms/icons/red-pushpin.png"></img>
			  <img class="pin-option" src="http://maps.google.com/mapfiles/ms/icons/blue-pushpin.png"></img>              
			  <img class="pin-option" src="http://maps.google.com/mapfiles/ms/icons/ltblu-pushpin.png"></img>
			  <img class="pin-option" src="http://maps.google.com/mapfiles/ms/icons/pink-pushpin.png"></img>
			</div>`
			infoWindow.setContent(content);
		}
		
		function addMarker(coords, price, title, url, color){
		  let marker = new google.maps.Marker({position: coords, map: global_map, icon: {url: color} });
		  markersArray.push(marker);
		  
		  let content = `<h3>${title}: ${price}$</h3> \n <a href=${url}>view listing<a> <img onClick="showPins()" id="init-pin" class="pin-option" src="http://maps.google.com/mapfiles/ms/icons/red-pushpin.png">`;
		  
		 infoWindow = new google.maps.InfoWindow({ content: content });

		  marker.addListener('click', function(){
			infoWindow.setContent(content);
			infoWindow.open(map, marker);
		  });

		}
		
		</script>
		
    </body>
</html>