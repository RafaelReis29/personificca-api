<?php
// 1. Permite que o Angular (na porta 4200) acesse este arquivo
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 2. Dados da conexão
$host = getenv('DB_HOST');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);

    // 3. Pega os dados que o Angular enviou via GET
    $nome  = $_GET['nome']  ?? null;
    $email = $_GET['email'] ?? null;

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
