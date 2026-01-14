<?php
// PHP Configuration Template
// For AWS/Heroku, set these in the Environment Variables console.
// For Local Docker, these will default to your service names.

$host = getenv('DB_HOST') ?: "db";
$db_user = getenv('DB_USER') ?: "root";
$db_pass = getenv('DB_PASS') ?: "rootpassword";
$db_name = getenv('DB_NAME') ?: "guvi_db";

$redis_host = getenv('REDIS_HOST') ?: "redis";
$redis_port = getenv('REDIS_PORT') ?: 6379;

$mongo_uri = getenv('MONGO_URI') ?: "mongodb://mongodb:27017";
$mongo_db = getenv('MONGO_DB') ?: "guvi_db";
?>