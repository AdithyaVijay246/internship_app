<?php
// PHP code must be in a separate file (php/register.php)
require_once 'db.php'; // Include your database connection file
require_once 'config.php';
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Password Hashing (Security best practice)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. STRICT RULE: Always use Prepared Statements in MySQL
    // No usage of simple SQL statements like $conn->query() is allowed.
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // 3. Bind parameters and execute
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // Return a response for the jQuery AJAX call
        echo json_encode(["status" => "success", "message" => "Registration successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    // 4. Close connections
    $stmt->close();
    $conn->close();
}
?>