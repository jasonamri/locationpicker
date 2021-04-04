<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

//geocode coords to address
//why do this in the backend instead of frontend? to hide the geocoding api key!
$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$_GET["lat"].",".$_GET["lng"]."&key=".$api_key;
$ch = curl_init($url); // such as http://example.com/example.xml
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch);
curl_close($ch);

echo $data;
?>