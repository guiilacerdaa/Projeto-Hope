<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $email = limparEntrada($_POST['email']);
    $senha = $_POST['senha'];
    
    // Buscar usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar a senha
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta, iniciar sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            
            // Redirecionar para o painel
            header("Location: painel.php");
            exit();
        } else {
            // Senha incorreta
            $_SESSION['mensagem'] = "Email ou senha incorretos.";
            $_SESSION['tipo_mensagem'] = "danger";
            header("Location: login.php");
            exit();
        }
    } else {
        // Usuário não encontrado
        $_SESSION['mensagem'] = "Email ou senha incorretos.";
        $_SESSION['tipo_mensagem'] = "danger";
        header("Location: login.php");
        exit();
    }
} else {
    // Se não foi POST, redirecionar para a página de login
    header("Location: login.php");
    exit();
}
?>