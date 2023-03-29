
let global_map;

$("#searchInput").keyup(function(e) {
	if(e.key === "Enter"){
		submitSearch();
	}
});
$("#searchSubmitButton").click(submitSearch);
function submitSearch(e) {
	const searchText = $("#searchInput").val();
	console.log("Submitted search:", searchText);
	$.post("backend/search.php?q=" + searchText, function(data, status) {
		console.log("Result:", data, status);
		for (let item in data) {
			addMarker({lat: parseFloat(data[item].lat), lng: parseFloat(data[item].lon)}, data[item].price, data[item].title);
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

function addMarker(coords, price, title ){
  let marker = new google.maps.Marker({position: coords, map: global_map});
  let infoWindow = new google.maps.InfoWindow({
	  content: `<h3>${title}: ${price}$</h3>`
  });
  
  marker.addListener('click', function(){
	 infoWindow.open(map, marker); 
  });
  
}