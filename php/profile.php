<?php
require_once 'config.php';
require_once 'db.php'; 
header('Content-Type: application/json');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

$redis = new Redis();
try {
    $redis->connect($redis_host, $redis_port); 
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Redis connection failed"]);
    exit;
}

try {
    /** * FIX: Use 'typeMap' to force MongoDB to return arrays.
     * This prevents the "BSONArray compatibility" fatal error on PHP 8.2+
     */
    $mongo = new MongoDB\Client($mongo_uri, [], [
        'typeMap' => [
            'root' => 'array', 
            'document' => 'array', 
            'array' => 'array'
        ]
    ]);
    $db = $mongo->selectDatabase($mongo_db);
    $collection = $db->profiles;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "MongoDB connection failed"]);
    exit;
}

$token = $_REQUEST['token'] ?? '';
$userId = $redis->get("session:$token");

if (!$userId) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// IF METHOD IS POST: Update the data 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection->updateOne(
        ['user_id' => (int)$userId],
        ['$set' => [
            'age' => (int)($_POST['age'] ?? 0),
            'dob' => $_POST['dob'] ?? '',
            'contact' => $_POST['contact'] ?? ''
        ]],
        ['upsert' => true]
    );
    echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
} 
// IF METHOD IS GET: Fetch and display the data
else {
    $profile = $collection->findOne(['user_id' => (int)$userId]);
    
    if ($profile) {
        /**
         * Because of the typeMap above, $profile is already a plain array.
         * We just need to handle the internal _id field.
         */
        if (isset($profile['_id'])) {
            unset($profile['_id']);
        }

        echo json_encode(["success" => true, "data" => $profile]);
    } else {
        echo json_encode(["success" => false, "message" => "No profile found yet"]);
    }
}
?>