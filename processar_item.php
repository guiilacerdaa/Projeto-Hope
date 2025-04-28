<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = limparEntrada($_POST['nome']);
    $descricao = limparEntrada($_POST['descricao']);
    $quantidade = (int)$_POST['quantidade'];
    $categoria = limparEntrada($_POST['categoria']);
    $instituicao_id = $_SESSION['user_id'];
    
    // Validar dados
    if ($quantidade <= 0) {
        $_SESSION['mensagem'] = "A quantidade deve ser maior que zero.";
        $_SESSION['tipo_mensagem'] = "danger";
        header("Location: cadastrar_item.php");
        exit();
    }
    
    // Inserir item no banco de dados
    $stmt = $conn->prepare("INSERT INTO itens_necessarios (instituicao_id, nome, descricao, quantidade, categoria, status) VALUES (?, ?, ?, ?, ?, 'ativo')");
    $stmt->bind_param("issis", $instituicao_id, $nome, $descricao, $quantidade, $categoria);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Item cadastrado com sucesso!";
        $_SESSION['tipo_mensagem'] = "success";
        header("Location: listar_itens.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar item: " . $conn->error;
        $_SESSION['tipo_mensagem'] = "danger";
        header("Location: cadastrar_item.php");
        exit();
    }
} else {
    // Se não foi POST, redirecionar para a página de cadastro
    header("Location: cadastrar_item.php");
    exit();
}
?>