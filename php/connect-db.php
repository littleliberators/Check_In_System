<?php
// Database credentials
$host = "127.0.0.1";
$user = "emmatsipan";
$pass = "";
$db = "little_liberators";
$port = 3306;

// Connect to the database
$dbc = mysqli_connect($host, $user, $pass, $db, $port);

// Check connection
if ($dbc->connect_error) {
   die("Connection failed: " . $dbc->connect_error);
} 
?>