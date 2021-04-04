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

/*$ip = $_POST["ip"];
$lat = $_POST["lat"];
$lng = $_POST["lng"];*/

$sql = "SELECT lat,lng FROM locationpicker";
$result = $conn->query($sql);

if ($result !== FALSE) {
  http_response_code(200);
  $rows = array();
  while($row = $result->fetch_assoc()) {
      $data = array('lat' => (float)$row["lat"], 'lng' => (float)$row["lng"]);
      $rows[] = $data;
  }
  print json_encode($rows);
} else {
  http_response_code(400);
  echo "SQL request given: " . $sql . "\n";
  echo "SQL Error recieved: " . $conn->error;
}

$conn->close();
?>