<?php
// 1. Permite que o Angular (na porta 4200) acesse este arquivo
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/config.php');

try {
    // 3. Pega os dados que o Angular enviou via GET
    $nome  = $_POST['nome']  ?? null;
    $email = $_POST['email'] ?? null;

    if ($nome && $email) {
        // 4. Insere no banco
        $sql = "INSERT INTO usuarios (nome, email) VALUES (:n, :e)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['n' => $nome, 'e' => $email]);

        echo json_encode(["mensagem" => "Sucesso! $nome foi salvo."]);
    } else {
        echo json_encode(["mensagem" => "Erro: Preencha todos os campos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["mensagem" => "Erro no banco: " . $e->getMessage()]);
}
?>
