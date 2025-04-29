<?php
include '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';

if (!$email) {
    echo json_encode(["success" => false, "message" => "E-mail não fornecido"]);
    exit;
}

// Verifica se o e-mail existe
$stmt = $pdo->prepare("SELECT id FROM instituicoes WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => true, "message" => "Se o e-mail estiver cadastrado, enviaremos instruções."]);
    exit;
}

// Gera token de redefinição (válido por 1h)
$token = bin2hex(random_bytes(16));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Armazena token no banco (crie uma tabela `tokens_recuperacao` para isso)
$stmt = $pdo->prepare("INSERT INTO tokens_recuperacao (user_id, token, expira_em) VALUES (?, ?, ?)");
$stmt->execute([$user['id'], $token, $expira]);

// Envia e-mail (substitua com o e-mail real se for usar SMTP)
$link = "http://localhost/frontend/nova_senha.html?token=$token";

// Simulando envio
// Aqui você usaria `mail()` ou PHPMailer de verdade
file_put_contents('../temp_email_simulado.txt', "Redefina sua senha: $link");

echo json_encode(["success" => true, "message" => "O e-mail foi enviado para sua conta com sucesso."]);
