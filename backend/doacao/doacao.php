<?php
// Conexão com banco
include '../config/db.php';

// Lê os dados JSON enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Validação básica
$instituicao_id = $data['instituicao_id'] ?? null;  // ID da instituição que receberá
$tipo = $data['tipo'] ?? null;                      // Ex: alimentos, roupas, etc
$nome = $data['nome'] ?? null;                      // Ex: alimentos, roupas, etc
$quantidade = $data['quantidade'] ?? null;

if (!$instituicao_id || !$tipo || !$quantidade) {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."]);
    exit();
}

// Prepara e executa a inserção
try {
    $stmt = $pdo->prepare("INSERT INTO doacoes (nome, instituicao_id, tipo, quantidade, data_doacao) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$instituicao_id, $tipo, $quantidade, $nome]);

    echo json_encode(["success" => true, "message" => "Doação registrada com sucesso."]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao registrar doação: " . $e->getMessage()
    ]);
}
