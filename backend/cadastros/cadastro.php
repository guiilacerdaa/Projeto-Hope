<?php
include '../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$nome = $data['nome'];
$email = $data['email'];
$senha = password_hash($data['senha'], PASSWORD_DEFAULT);
$telefone = $data['telefone'];

$stmt = $pdo->prepare("INSERT INTO instituicoes (nome, email, senha, telefone) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$nome, $email, $senha, $telefone])) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
