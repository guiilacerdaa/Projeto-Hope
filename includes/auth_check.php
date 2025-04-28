<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensagem'] = "Você precisa estar logado para acessar esta página.";
    $_SESSION['tipo_mensagem'] = "warning";
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_tipo'] !== 'instituicao') {
    $_SESSION['mensagem'] = "Acesso restrito para instituições.";
    $_SESSION['tipo_mensagem'] = "warning";
    header("Location: index.php");
    exit();
}
?>