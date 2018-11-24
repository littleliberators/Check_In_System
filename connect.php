<?php

//This is to check the connection 

$host = getenv("https://ide.c9.io/austinn14/senior_project");
$username = "austinn14";
$password = "";
$database = "c9";
$dbport = 3306;

// Create connection
$db = mysqli_connect($host, $username, $password, $database, $dbport);

// Check connection
if ($db->connect_error) 
{
    die("Connection failed: " . $db->connect_error);
} 

echo "Connected Successfully(".$db->host_info.")";
?>