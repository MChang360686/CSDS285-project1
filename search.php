<!DOCTYPE html>
<!-- TODO USE BOOTSTRAP OR SOMETHING FOR STYLING -->
<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> <!-- JQuery -->
    <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
        <h1>Search all US Craigslist Postings</h1>
        <h4>Enter item to scrape for</h4>
        <!--Help Popup code-->
        <div class="popup" style="margin-left: 15px font-family: Arial" onclick="hePop()">Click here for Help
            <span class="popuptext" id="helpPopup">
                <p style="text-align:left margin-left: 15px">How to use this application</p>
                <p style="text-align:left margin-left: 15px">1. Enter name of item you wish to purchase from Ebay into input</p>
                <p style="text-align:left margin-left: 15px">2. Wait</p>
            </span>
        </div>
        <div>
            <input id="searchInput" type="text" placeholder="Enter Item">
            <button id="searchSubmitButton">Search</button>
        </div>
		
	  <br>
	  
	  <div id="map"></div>
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
					if(data[i].price <= 0) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://labs.google.com/ridefinder/images/mm_20_white.png');
					else if(i < data.length/3) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/green-dot.png');
					else if((i > data.length/3) && (i < (data.length/3) * 2)) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
					else if((i > (data.length/3) * 2) && (i < (data.length - 50))) addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/red-dot.png');
					else addMarker({lat: parseFloat(data[i].lat), lng: parseFloat(data[i].lon)}, data[i].price, data[i].title, data[i].url, 'http://maps.google.com/mapfiles/ms/icons/dollar.png');
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
		}	
		
		function addMarker(coords, price, title, url, color){
			let marker = new google.maps.Marker({position: coords, map: global_map, icon: {url: color} });
			markersArray.push(marker);
			let infoWindow = new google.maps.InfoWindow({
			  content: `<h3>${title}: ${price}$</h3> \n <a href=${url}>view listing<a>`
			});

			marker.addListener('click', function(){
			 infoWindow.open(map, marker); 
			});

		}
		</script>
		
    </body>
</html>