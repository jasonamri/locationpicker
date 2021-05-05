// Get element references
var confirmBtn = document.getElementById('confirmPosition');
var addressTxt = document.getElementById('addressTxt');

//test message
console.log("Welcome to Location Picker!")

// Initialize locationPicker plugin
var lp = new locationPicker('map', {
  setCurrentPosition: true // You can omit this, defaults to true
}, {
  zoom: 8, // You can set any google map options here
  clickableIcons: false,
  fullscreenControl: false,
  mapTypeControl: false,
  streetViewControl: false,
  maxZoom: 16,
  gestureHandling: "greedy"
});

var mc = null;
update();


// place markers on the map
async function update() {
  if (mc) {
    mc.clearMarkers();
  }

  //get coordinates
  const rawRes = await fetch("https://jasonamri.com/locationpicker/getlocations.php");
  locations = await rawRes.json()

  //turn coordinates into markers
  var markers = locations.map(function(location) {
    return new google.maps.Marker({ position: location });
  });

  // Add marker clusterer to manage the markers
  mc = new MarkerClusterer(lp.map, markers, 
    {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
}

// Listen to map idle event, listening to idle event more accurate than listening to ondrag event
google.maps.event.addListener(lp.map, 'idle', async function (event) {
  // Get current location and show it in HTML
  var location = lp.getMarkerPosition();
  lat = location.lat;
  lng = ((location.lng+180) % 360) - 180; //convert from too big or too small lng
  url = "getaddress.php?lat="+lat+"&lng="+lng;
  const rawRes = await fetch(url);
  response = await rawRes.json();

  if (response.status != "OK") {
    addressTxt.innerHTML = 'Unable to get location...';
    console.log(response.status);
    console.log(response.error_message);
    console.log(lat,lng)
    console.log(response);
  } else {
    usedIndex = 1;
    address = response.results[usedIndex].formatted_address;
    lat = response.results[usedIndex].geometry.location.lat;
    lng = response.results[usedIndex].geometry.location.lng;
    addressTxt.innerHTML = "Selected location: " + address;
  }
});

// Listen to button onclick event
confirmBtn.onclick = async function () {
  // Get current location and show it in HTML
  var location = lp.getMarkerPosition();
  //addressTxt.innerHTML = 'The chosen location is ' + location.lat + ',' + location.lng;
  const rawRes = await fetch("https://www.cloudflare.com/cdn-cgi/trace");
  response = await rawRes.text()
  ipAddress = response.split('\n')[2].substring(3)

  //randomize lat and lng to within 500 meters
  lat = location.lat;
  lng = ((location.lng+180) % 360) - 180; //convert from too big or too small lng
  lat = parseFloat(lat.toFixed(2)) + (Math.floor(Math.random()*100)/10000);
  lng = parseFloat(lng.toFixed(2)) + (Math.floor(Math.random()*100)/10000);

  //send coordinates
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      update();
      addressTxt.innerHTML = "Successfully added or updated location!";
    } else if (this.readyState == 4) {
      addressTxt.innerHTML = "Error adding or updating location :(";
      console.log(this.responseText);
    }
  };
  xhttp.open("POST", "https://jasonamri.com/locationpicker/addlocation.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.setRequestHeader("Access-Control-Allow-Origin", "*");
  xhttp.send("ip="+ipAddress+"&lat="+lat+"&lng="+lng);
};