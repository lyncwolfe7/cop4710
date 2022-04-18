<?php

$serverName = "localhost";
$dbusername = "root";
$dbpassword = "root";
$db = "project";

$conn = mysqli_connect($serverName, $dbusername, $dbpassword, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>