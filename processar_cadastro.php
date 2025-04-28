<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = limparEntrada($_POST['nome']);
    $email = limparEntrada($_POST['email']);
    $cnpj = limparEntrada($_POST['cnpj']);
    $telefone = limparEntrada($_POST['telefone']);
    $endereco = limparEntrada($_POST['endereco']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validar dados
    $erro = false;
    
    // Verificar se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        $_SESSION['mensagem'] = "As senhas não coincidem.";
        $_SESSION['tipo_mensagem'] = "danger";
        $erro = true;
    }
    
    // Verificar se o email já está em uso
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['mensagem'] = "Este email já está cadastrado.";
        $_SESSION['tipo_mensagem'] = "danger";
        $erro = true;
    }
    
    // Se houver erro, redirecionar de volta para o formulário
    if ($erro) {
        header("Location: cadastro.php");
        exit();
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, cnpj, endereco, telefone, tipo) VALUES (?, ?, ?, ?, ?, ?, 'instituicao')");
    $stmt->bind_param("ssssss", $nome, $email, $senha_hash, $cnpj, $endereco, $telefone);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça login para continuar.";
        $_SESSION['tipo_mensagem'] = "success";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar: " . $conn->error;
        $_SESSION['tipo_mensagem'] = "danger";
        header("Location: cadastro.php");
        exit();
    }
} else {
    // Se não foi POST, redirecionar para a página de cadastro
    header("Location: cadastro.php");
    exit();
}
?>