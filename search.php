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
		<script src="./script.js"></script>
		
    </body>
</html>