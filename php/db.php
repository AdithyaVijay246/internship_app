<?php
// 1. Include the config file where the Docker settings are
require_once 'config.php'; 

// 2. Use the variables from config.php instead of hardcoded strings
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// 3. Error handling
if ($conn->connect_error) {
    die("MySQL Connection failed: " . $conn->connect_error);
}
?>