<?php
session_start();

// Tempo de expiração da senha: 30 minutos 
$expiracao = 30 * 60; 

function gerarSenha($tamanho = 6) {
    $numeros = '0123456789';
    $senha = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= $numeros[rand(0, strlen($numeros) - 1)];
    }
    return $senha;
}

// Verifica se já existe uma senha válida na sessão
if (isset($_SESSION['senha']) && isset($_SESSION['hora_gerada'])) {
    $tempo_passado = time() - $_SESSION['hora_gerada'];

    if ($tempo_passado < $expiracao) {
        // Senha ainda é válida
        echo $_SESSION['senha'];
        exit;
    }
}

// Senha expirada ou inexistente: gerar nova
$novaSenha = gerarSenha();
$_SESSION['senha'] = $novaSenha;
$_SESSION['hora_gerada'] = time();

header('Content-Type: text/plain');
echo $novaSenha;

