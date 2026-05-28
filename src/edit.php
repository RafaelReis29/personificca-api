<?php
require_once __DIR__ . "/config.php";

$id = $_GET["id"] ?? 0;

$stmt = $pdo->prepare("
    SELECT
        p.id,
        p.user_id,
        p.name,
        p.story,
        p.category_id,
        p.share,
        c.name AS category
    FROM personas p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.id = :id
");
$stmt->execute(["id" => $id]);
$persona = $stmt->fetch();

if (!$persona) {
    send_json(null);
}

$attributes = $pdo->prepare("
    SELECT a.id, a.name, pa.level
    FROM persona_attributes pa
    JOIN attributes a ON a.id = pa.attribute_id
    WHERE pa.persona_id = :id
    ORDER BY a.id
");
$attributes->execute(["id" => $id]);
$persona["attributes"] = $attributes->fetchAll();

send_json($persona);
?>
