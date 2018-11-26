<?php

//This is to check the connection 

$host = getenv("https://ide.c9.io/austinn14/senior_project");
$username = "austinn14";
$password = "";
$database = "c9";
$dbport = 3306;

// Create connection
$db = mysqli_connect($host, $username, $password, $database, $dbport);

function Login()
{
    if(!$this->CheckLog($PIN))
    {
        return false;
    }
    session_start();
    
    $_SESSION[$this->GetLoginSessionVar()] = $PIN;
    
    return true;
}

function CheckLog($PIN)
{
    
    $PIN = $this->SanitizeForSQL($PIN);
    $qry = "Select PIN from $this->Family".
        "where PIN ='$PIN'";
     
    $result = mysql_query($qry,$this->Info.html);
     
    if(!$result)
    {
        $this->HandleError("Error logging in. ".
            "The username or password does not match");
        return false;
    }
    return true;
}
?>

/* Check connection
if ($db->connect_error) 
{
    die("Connection failed: " . $db->connect_error);
} 

echo "Connected Successfully(".$db->host_info.")";
?>
*/