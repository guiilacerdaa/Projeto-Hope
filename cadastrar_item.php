<?php 
include('includes/header.php');
include('includes/auth_check.php');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu lateral (mesmo do painel.php) -->
            <div class="list-group">
                <a href="painel.php" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="cadastrar_item.php" class="list-group-item list-group-item-action active">Cadastrar Item Necessário</a>
                <a href="listar_itens.php" class="list-group-item list-group-item-action">Meus Itens</a>
                <a href="armarios.php" class="list-group-item list-group-item-action">Locais de Armários</a>
                <a href="agendamentos.php" class="list-group-item list-group-item-action">Agendamentos</a>
                <a href="historico_doacoes.php" class="list-group-item list-group-item-action">Histórico de Doações</a>
                <a href="perfil.php" class="list-group-item list-group-item-action">Meu Perfil</a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Conteúdo principal -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Cadastrar Item