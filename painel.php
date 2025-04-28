<?php 
include('includes/header.php');
include('includes/auth_check.php');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu lateral -->
            <div class="list-group">
                <a href="painel.php" class="list-group-item list-group-item-action active">Dashboard</a>
                <a href="cadastrar_item.php" class="list-group-item list-group-item-action">Cadastrar Item Necessário</a>
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
            <h2>Bem-vindo, <?php echo $_SESSION['user_nome']; ?></h2>
            
            <?php
            // Mostrar mensagens de erro ou sucesso, se houver
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="alert alert-' . $_SESSION['tipo_mensagem'] . '">' . $_SESSION['mensagem'] . '</div>';
                unset($_SESSION['mensagem']);
                unset($_SESSION['tipo_mensagem']);
            }
            ?>
            
            <div class="row mt-4">
                <!-- Cartões de estatísticas -->
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Itens Necessários</h5>
                            <p class="card-text display-4">
                                <?php
                                // Contar itens necessários da instituição
                                $sql = "SELECT COUNT(*) as total FROM itens_necessarios WHERE instituicao_id = ? AND status = 'ativo'";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                ?>
                            </p>
                            <a href="listar_itens.php" class="text-white">Ver detalhes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Doações Recebidas</h5>
                            <p class="card-text display-4">
                                <?php
                                // Contar doações recebidas
                                $sql = "SELECT COUNT(*) as total FROM doacoes WHERE instituicao_id = ? AND status = 'entregue'";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                ?>
                            </p>
                            <a href="historico_doacoes.php" class="text-white">Ver histórico</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Agendamentos</h5>
                            <p class="card-text display-4">
                                <?php
                                // Contar agendamentos pendentes
                                $sql = "SELECT COUNT(*) as total FROM agendamentos WHERE instituicao_id = ? AND status = 'agendado'";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                ?>
                            </p>
                            <a href="agendamentos.php" class="text-white">Ver agendamentos</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabela de agendamentos recentes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Agendamentos Recentes</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Data/Hora</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Listar agendamentos recentes
                            $sql = "SELECT * FROM agendamentos WHERE instituicao_id = ? ORDER BY data_hora DESC LIMIT 5";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $_SESSION['user_id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . ucfirst($row['tipo']) . "</td>";
                                    echo "<td>" . date('d/m/Y H:i', strtotime($row['data_hora'])) . "</td>";
                                    echo "<td>" . ucfirst($row['status']) . "</td>";
                                    echo "<td>
                                        <a href='detalhe_agendamento.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Detalhes</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Nenhum agendamento encontrado</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>