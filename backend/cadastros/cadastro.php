<?php
include '../config/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $nome = $data['nome'];
    $email = $data['email'];
    $senha = password_hash($data['senha'], PASSWORD_DEFAULT);
    $telefone = $data['telefone'];

    $stmt = $pdo->prepare("INSERT INTO instituicoes (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $email, $senha, $telefone]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        // Erro de e-mail duplicado
        echo json_encode([
            "success" => false,
            "message" => "Este e-mail já está cadastrado. Por favor, utilize outro e-mail."
        ]);
    } else {
        // Outros erros: enviar a mensagem real (de forma controlada)
        echo json_encode([
            "success" => false,
            "message" => "Erro no banco de dados: " . $e->getMessage()
        ]);
    }
}
?>
