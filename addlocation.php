<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die("Connection failed: " . $conn->connect_error);
}

$ip = str_replace(array(":","."), array("",""), $_POST["ip"]);
$ip = mysqli_real_escape_string($conn, $ip);
$lat = $_POST["lat"];
$lat = mysqli_real_escape_string($conn, $lat);
$lng = $_POST["lng"];
$lng = mysqli_real_escape_string($conn, $lng);

$sql = "INSERT INTO locationpicker (ipaddress, lat, lng) VALUES('".$ip."', ".$lat.", ".$lng.") ON DUPLICATE KEY UPDATE lat=".$lat.", lng=".$lng;


if ($conn->query($sql) === TRUE) {
    http_response_code(200);
    echo "Success";
} else {
    http_response_code(400);
    echo "SQL request given: " . $sql . "\n";
    echo "SQL Error recieved: " . $conn->error;
}

$conn->close();
?>