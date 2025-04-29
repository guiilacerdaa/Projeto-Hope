<?php
// Conexão com banco
include '../config/db.php';

// Lê os dados JSON enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Validação básica
$instituicao_id = $data['instituicao_id'] ?? null;  // Nome da instituição
$tipo = $data['tipo'] ?? null;
$nome = $data['nome'] ?? null;
$quantidade = $data['quantidade'] ?? null;
$locais = $data['locais'] ?? null;
$descricao = $data['descricao'] ?? null;




if (!$instituicao_id || !$tipo || !$quantidade || !$nome || !$descricao || !$locais) {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."]);
    exit();
}


// Prepara e executa a inserção
try {
    $stmt = $pdo->prepare("INSERT INTO doacoes (nome, instituicao_id, tipo, quantidade, descricao, locais, data_doacao) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$nome, $instituicao_id, $tipo, $quantidade, $descricao, $locais]);


    echo json_encode(["success" => true, "message" => "Doação registrada com sucesso."]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao registrar doação: " . $e->getMessage()
    ]);
}
