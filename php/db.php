<?php
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "guvi_internship";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("MySQL Connection failed: " . $conn->connect_error);
}
?>