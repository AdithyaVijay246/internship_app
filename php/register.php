<?php
require_once 'config.php'; // contains your $host, $db_user, etc.
require_once 'db.php';     // contains your $conn connection logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if fields are actually sent to avoid "Undefined Index" warnings
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(["status" => "error", "message" => "Missing username or password"]);
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Password Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query (email is omitted entirely)
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            // Check for duplicate usernames
            if ($conn->errno === 1062) {
                echo json_encode(["status" => "error", "message" => "Username already taken."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
            }
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement."]);
    }
    
    $conn->close();
}
?>