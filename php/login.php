<?php
header('Content-Type: application/json');
require_once 'config.php'; // Load variables like $redis_host
require_once 'db.php';     // Provides the $conn object

// Initialize Redis
$redis = new Redis();
try {
    // Use the variable from config.php (which should be 'redis')
    $redis->connect($redis_host, $redis_port); 
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Redis Connection Failed"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if keys exist to avoid warnings
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Prepared Statement to find user
    // We use the $conn already created in db.php
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            // Login successful: Generate a Token
            $token = bin2hex(random_bytes(16));
            
            // Store Token in Redis (maps token to user ID)
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
$conn->close();
?>