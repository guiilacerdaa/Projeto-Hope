<?php
// Função para limpar entradas de formulários
function limparEntrada($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Função para verificar se o usuário está logado
function verificarLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Função para verificar se é uma instituição
function verificarInstituicao() {
    if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'instituicao') {
        header("Location: index.php");
        exit();
    }
}
?>