<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit;
}

$host = getenv("DB_HOST");
$db = getenv("DB_DATABASE");
$user = getenv("DB_USERNAME");
$pass = getenv("DB_PASSWORD");
$port = getenv("DB_PORT");

$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

function body()
{
    return json_decode(file_get_contents("php://input"), true) ?: $_POST;
}

function send_json($value)
{
    echo json_encode($value);
    exit;
}
?>
