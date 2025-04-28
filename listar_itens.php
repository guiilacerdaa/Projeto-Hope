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
                <a href="cadastrar_item.php" class="list-group-item list-group-item-action">Cadastrar Item Necessário</a>
                <a href="listar_itens.php" class="list-group-item list-group-item-action active">Meus Itens</a>
                <a href="armarios.php" class="list-group-item list-group-item-action">Locais de Armários</a>
                <a href="agendamentos.php" class="list-group-item list-group-item-action">Agendamentos</a>
                <a href="historico_doacoes.php" class="list-group-item list-group-item-action">Histórico de Doações</a>
                <a href="perfil.php" class="list-group-item list-group-item-action">Meu Perfil</a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">Sair</a>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Conteúdo principal -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Meus Itens Necessários</h2>
                <a href="cadastrar_item.php" class="btn btn-primary">+ Novo Item</a>
            </div>
            
            <?php
            // Mostrar mensagens de erro ou sucesso, se houver
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="alert alert-' . $_SESSION['tipo_mensagem'] . '">' . $_SESSION['mensagem'] . '</div>';
                unset($_SESSION['mensagem']);
                unset($_SESSION['tipo_mensagem']);
            }
            
            // Buscar itens da instituição
            $stmt = $conn->prepare("SELECT * FROM itens_necessarios WHERE instituicao_id = ? ORDER BY data_cadastro DESC");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>Quantidade</th>
                                        <th>Status</th>
                                        <th>Data de Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['nome']; ?></td>
                                            <td><?php echo ucfirst($row['categoria']); ?></td>
                                            <td><?php echo $row['quantidade']; ?></td>
                                            <td>
                                                <?php 
                                                switch ($row['status']) {
                                                    case 'ativo':
                                                        echo '<span class="badge bg-success">Ativo</span>';
                                                        break;
                                                    case 'inativo':
                                                        echo '<span class="badge bg-secondary">Inativo</span>';
                                                        break;
                                                    case 'concluido':
                                                        echo '<span class="badge bg-primary">Concluído</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-info">' . ucfirst($row['status']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($row['data_cadastro'])); ?></td>
                                            <td>
                                                <a href="editar_item.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                                <a href="mudar_status_item.php?id=<?php echo $row['id']; ?>&status=<?php echo $row['status'] == 'ativo' ? 'inativo' : 'ativo'; ?>" class="btn btn-sm <?php echo $row['status'] == 'ativo' ? 'btn-secondary' : 'btn-success'; ?>">
                                                    <?php echo $row['status'] == 'ativo' ? 'Desativar' : 'Ativar'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Você ainda não cadastrou nenhum item necessário. <a href="cadastrar_item.php">Cadastre seu primeiro item</a>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>