<?php
$host = "<<HOST_NOT_SET>>";
$username = "<<USER_NOT_SET>>";
$password = "<<PW_NOT_SET>>";
$dbconnection = mysql_pconnect($host, $username, $password); 
if (!$dbconnection){
    print "Failed to connect to the database.";
    exit;
}

$databaseName = "avatar";
mysql_select_db($databaseName);
if (!$databaseName){
    print "Database does not exist!";
    exit;
}
?>
