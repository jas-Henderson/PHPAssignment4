<?php
$servername = "localhost"; 
$username = "root";        
$password = "2Ey7u9pk!995304"; 
$dbname = "sportsprotech"; 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>