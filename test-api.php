<?php
// Pulling credentials from the environment variables in docker-compose.yml
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT');

$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Test Query
    $stmt = $pdo->query("SELECT current_database(), now()");
    $result = $stmt->fetch();
    
    echo "Successfully connected to: " . $result['current_database'];
} catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Connection Error: " . $e->getMessage();
}
