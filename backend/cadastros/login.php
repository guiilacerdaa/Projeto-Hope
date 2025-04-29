<?php
include '../config/db.php';

// Recebe os dados JSON
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

// Verifica se os campos foram preenchidos
if (empty($email) || empty($senha)) {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos."]);
    exit();
}

try {
    // Busca a instituição pelo e-mail
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM instituicoes WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou e a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Aqui você pode iniciar uma sessão se quiser:
        // session_start();
        // $_SESSION['usuario_id'] = $usuario['id'];

        echo json_encode(["success" => true, "nome" => $usuario['nome']]);
    } else {
        echo json_encode(["success" => false, "message" => "E-mail ou senha inválidos."]);
    }

} catch (PDOException $e) {
    error_log("Erro de login: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Erro ao acessar o banco de dados."]);
}
?>
