<?php

global $db_conn;
    
$servername = "dbserver.students.cs.ubc.ca";
// $username = "mabrosim";
// $password = "a12275756";

$username = "atsai12";
$password = "a99740268";

 $db_conn= new mysqli($servername, $username, $password, $username);

if ($db_conn->connect_error) {
    echo "not successful connection";
}

?>