<?php
require_once __DIR__ . "/config.php";

$id = $_GET["id"] ?? 0;
$data = body();

$stmt = $pdo->prepare("
    UPDATE personas
    SET name = :name, story = :story, category_id = :category_id, share = :share
    WHERE id = :id
");
$stmt->execute([
    "id" => $id,
    "name" => $data["name"],
    "story" => $data["story"],
    "category_id" => $data["category_id"],
    "share" => $data["share"]
]);

$pdo->prepare("DELETE FROM persona_attributes WHERE persona_id = :id")->execute(["id" => $id]);

$attributeStmt = $pdo->prepare("
    INSERT INTO persona_attributes (persona_id, attribute_id, level)
    VALUES (:persona_id, :attribute_id, :level)
");

foreach ($data["attributes"] as $attribute) {
    $attributeStmt->execute([
        "persona_id" => $id,
        "attribute_id" => $attribute["id"],
        "level" => $attribute["level"]
    ]);
}

send_json(["message" => "Persona updated"]);
?>
