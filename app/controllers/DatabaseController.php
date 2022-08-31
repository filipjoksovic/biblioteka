<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "biblioteka";

$connection = mysqli_connect($server, $username, $password, $database);

if (!$connection) {
    die("Neuspesno povezivanje na bazu podataka");
}