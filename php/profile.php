<?php
require_once 'config.php';
$conn = new mysqli($host, $db_user, $db_pass, $db_name);
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379); 

$mongo = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongo->guvi_db;
$collection = $db->profiles;

$token = $_REQUEST['token'] ?? '';
$userId = $redis->get("session:$token");

if (!$userId) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// IF METHOD IS POST: Update the data (You already have this)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->updateOne(
        ['user_id' => (int)$userId],
        ['$set' => [
            'age' => (int)$_POST['age'],
            'dob' => $_POST['dob'],
            'contact' => $_POST['contact']
        ]],
        ['upsert' => true]
    );
    echo json_encode(["success" => true, "message" => "Profile updated"]);
} 
// IF METHOD IS GET: Fetch and display the data
else {
    $profile = $collection->findOne(['user_id' => (int)$userId]);
    if ($profile) {
        echo json_encode(["success" => true, "data" => $profile]);
    } else {
        echo json_encode(["success" => false, "message" => "No profile found yet"]);
    }
}
?>