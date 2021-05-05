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
$lat = $_POST["lat"];
$lng = $_POST["lng"];

$sql = "INSERT INTO locationpicker (ipaddress, lat, lng) VALUES(".$ip.", ".$lat.", ".$lng.") ON DUPLICATE KEY UPDATE lat=".$lat.", lng=".$lng;

$sql = mysqli_real_escape_string($conn, $sql);

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