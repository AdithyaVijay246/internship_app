<?php
header('Content-Type: application/json');
require_once 'config.php'; // Load $redis_host and $redis_port

$redis = new Redis();

try {
    // Connect using the service name 'redis' from your config
    $redis->connect($redis_host, $redis_port);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Could not connect to Redis"]);
    exit;
}

$token = $_POST['token'] ?? '';

if ($token) {
    // Delete the specific session key from Redis
    $redis->del("session:$token");
    echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "No token provided"]);
}
?>