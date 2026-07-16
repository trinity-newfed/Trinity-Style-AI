<?php 
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("error" . $conn->connect_error);
}



?>