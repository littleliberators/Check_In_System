<?php
/*-------------------------------------------------------------------------
* Name: connect-db.php                                                      *
* Description:  Connect to the database.                                    *
*               $dbc is the variable that will be used in all other php     *
*               pages to make the database connection.                      *
---------------------------------------------------------------------------*/

    // Database credentials
    // $host = "localhost";
    // $user = "admin";      //non root mysql user
    // $pass = "@BolivarR1";       //non root user password 
    // $db = "little_liberators";  //database name1
    // $port = 3306;
    
    $url = parse_url(getenv("CLEARDB_BRONZE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);
    
    $dbc = new mysqli($server, $username, $password, $db);
    
    // Connect to the database
    // $dbc = mysqli_connect($host, $user, $pass, $db, $port);
    
    // Check connection
    if ($dbc->connect_error) {
      die("Connection failed: " . $dbc->connect_error);
    }

?>