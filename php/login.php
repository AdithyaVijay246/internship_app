<?php
header('Content-Type: application/json');
require_once 'db.php'; // MySQL connection
require_once 'config.php';
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Initialize Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 1. Prepared Statement to find user
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            // 2. Login successful: Generate a Token
            $token = bin2hex(random_bytes(16));
            
            // 3. Store Token in Redis (maps token to user ID)
            $redis->setex("session:$token", 3600, $row['id']); // Expires in 1 hour

            echo json_encode(["status" => "success", "token" => $token]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
    $stmt->close();
}
?>