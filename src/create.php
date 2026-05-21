<?php
require_once __DIR__ . "/config.php";

$data = body();

$stmt = $pdo->prepare("
    INSERT INTO personas (name, story, category_id, share)
    VALUES (:name, :story, :category_id, :share)
    RETURNING id
");
$stmt->execute([
    "name" => $data["name"],
    "story" => $data["story"],
    "category_id" => $data["category_id"],
    "share" => $data["share"]
]);

$personaId = $stmt->fetch()["id"];
$attributeStmt = $pdo->prepare("
    INSERT INTO persona_attributes (persona_id, attribute_id, level)
    VALUES (:persona_id, :attribute_id, :level)
");

foreach ($data["attributes"] as $attribute) {
    $attributeStmt->execute([
        "persona_id" => $personaId,
        "attribute_id" => $attribute["id"],
        "level" => $attribute["level"]
    ]);
}

send_json(["message" => "Persona created", "id" => $personaId]);
?>
