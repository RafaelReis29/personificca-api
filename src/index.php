<?php
require_once __DIR__ . "/config.php";

$rows = $pdo->query("
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
    ORDER BY p.id DESC
")->fetchAll();

foreach ($rows as &$row) {
    $stmt = $pdo->prepare("
        SELECT a.id, a.name, pa.level
        FROM persona_attributes pa
        JOIN attributes a ON a.id = pa.attribute_id
        WHERE pa.persona_id = :id
        ORDER BY a.id
    ");
    $stmt->execute(["id" => $row["id"]]);
    $row["attributes"] = $stmt->fetchAll();
}

send_json($rows);
?>
