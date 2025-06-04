<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "sklep_kasyno";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Coś poszło nie tak");
}

?>