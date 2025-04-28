<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar se os parâmetros foram passados
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    $_SESSION['mensagem'] = "Parâmetros inválidos.";
    $_SESSION['tipo_mensagem'] = "danger";
    header("Location: listar_itens.php");
    exit();
}

$item_id = (int)$_GET['id'];
$novo_status = limparEntrada($_GET['status']);

// Verificar se o status é válido
if ($novo_status !== 'ativo' && $novo_status !== 'inativo') {
    $_SESSION['mensagem'] = "Status inválido.";
    $_SESSION['tipo_mensagem'] = "danger";
    header("Location: listar_itens.php");
    exit();
}

// Verificar se o item pertence à instituição logada
$stmt = $conn->prepare("SELECT id FROM itens_necessarios WHERE id = ? AND instituicao_id = ?");
$stmt->bind_param("ii", $item_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['mensagem'] = "Item não encontrado ou você não tem permissão para alterá-lo.";
    $_SESSION['tipo_mensagem'] = "danger";
    header("Location: listar_itens.php");
    exit();
}

// Atualizar o status do item
$stmt = $conn->prepare("UPDATE itens_necessarios SET status = ? WHERE id = ?");
$stmt->bind_param("si", $novo_status, $item_id);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Status do item atualizado com sucesso.";
    $_SESSION['tipo_mensagem'] = "success";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar status do item: " . $conn->error;
    $_SESSION['tipo_mensagem'] = "danger";
}

header("Location: listar_itens.php");
exit();
?>