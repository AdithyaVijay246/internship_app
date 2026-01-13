<?php
header('Content-Type: application/json');
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$token = $_POST['token'] ?? '';
// Delete the specific session key from Redis
$redis->del("session:$token");

echo json_encode(["status" => "success"]);
?>