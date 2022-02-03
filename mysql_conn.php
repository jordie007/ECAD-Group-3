<?php
//Connection Parameters
$servername = $_ENV["SQLHOST"] ?? 'localhost';
$username = 'root';
$userpwd = '';
$dbname = 'donut';

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>
