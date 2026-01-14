<?php
// Use the EC2 IP if available, otherwise default to 'db' (for local docker)
$host = getenv('DB_HOST') ?: "db"; 
$db_user = "root";
$db_pass = "rootpassword";
$db_name = "guvi_db";

// Redis
$redis_host = getenv('REDIS_HOST') ?: "redis";
$redis_port = 6379;

// MongoDB
$mongo_host = getenv('MONGO_HOST') ?: "mongodb";
$mongo_uri = "mongodb://" . $mongo_host . ":27017";
$mongo_db = "guvi_db";
?>