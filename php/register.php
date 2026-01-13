<?php
require_once 'db.php'; 
require_once 'config.php';
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Password Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepared Statements
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
       
        echo json_encode(["status" => "success", "message" => "Registration successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }


    $stmt->close();
    $conn->close();
}
?>