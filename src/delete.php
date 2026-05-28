<?php
require_once __DIR__ . "/config.php";

$id = $_GET["id"] ?? 0;

$pdo->prepare("DELETE FROM favorites WHERE persona_id = :id")->execute(["id" => $id]);
$pdo->prepare("DELETE FROM persona_attributes WHERE persona_id = :id")->execute(["id" => $id]);
$pdo->prepare("DELETE FROM personas WHERE id = :id")->execute(["id" => $id]);

send_json(["message" => "Persona deleted"]);
?>
