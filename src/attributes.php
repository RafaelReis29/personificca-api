<?php
require_once __DIR__ . "/config.php";

send_json($pdo->query("SELECT id, name FROM attributes ORDER BY id")->fetchAll());
?>
