<?php 
include('includes/header.php');
include('includes/auth_check.php');

// Verificar se o ID do armário foi informado
if (!isset($_GET['armario_id'])) {
    $_SESSION['mensagem'] = "Armário não especificado.";
    $_SESSION['tipo_mensagem'] = "danger";
    header("Location: armarios.php");
    exit();
}

$armario_id = (int)$_GET['armario_id'];

// Buscar informações do armário
$stmt = $conn->prepare("SELECT * FROM armarios WHERE id = ? AND status = 'disponivel'");
$stmt->bind_param("i", $armario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['mensagem'] = "Armário não encontrado ou não disponível.";
    $_SESSION['tipo_mensagem'] = "danger";
    header("Location: armarios.php");
    exit();
}

$armario = $result->fetch_assoc();

// Buscar itens necessários da instituição
$stmt = $conn->prepare("SELECT * FROM itens_necessarios WHERE instituicao_id = ? AND status = 'ativo'");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$itens_result = $stmt->get_result();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu lateral (mesmo do painel.php) -->
            <div class="list-group">
                <a href="painel.php" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="cadastrar_item.php" class="list-group-item list-group-item-action">Cadastrar Item Necessário</a>
                <a href="listar_itens.php" class="list-group-item list-group-item-action">Meus Itens</a>
                <a href="armarios.php" class="list-group-item list-group-item-action active">Locais de Armários</a>
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
                    <h4>Agendar Retirada</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Mostrar mensagens de erro ou sucesso, se houver
                    if (isset($_SESSION['mensagem'])) {
                        echo '<div class="alert alert-' . $_SESSION['tipo_mensagem'] . '">' . $_SESSION['mensagem'] . '</div>';
                        unset($_SESSION['mensagem']);
                        unset($_SESSION['tipo_mensagem']);
                    }
                    ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Dados do Armário</h5>
                            <p><strong>Nome:</strong> <?php echo $armario['nome']; ?></p>
                            <p><strong>Endereço:</strong> <?php echo $armario['endereco']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <div id="map" style="width: 100%; height: 200px;"></div>
                        </div>
                    </div>
                    
                    <form action="processar_agendamento.php" method="post">
                        <input type="hidden" name="armario_id" value="<?php echo $armario_id; ?>">
                        <input type="hidden" name="tipo" value="retirada">
                        
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Selecione o Item para Retirada</label>
                            <?php if ($itens_result->num_rows > 0): ?>
                                <select class="form-control" id="item_id" name="item_id" required>
                                    <option value="">Selecione um item</option>
                                    <?php while ($item = $itens_result->fetch_assoc()): ?>
                                        <option value="<?php echo $item['id']; ?>">
                                            <?php echo $item['nome'] . ' - ' . $item['quantidade'] . ' unidades - ' . ucfirst($item['categoria']); ?> </option>
                                            <?php endwhile; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-danger">Você não possui itens cadastrados.</p>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="data_retirada" class="form-label">Data da Retirada</label>
                            <input type="date" class="form-control" id="data_retirada" name="data_retirada" required>
                        </div>

                        <div class="mb-3">
                            <label for="hora_retirada" class="form-label">Hora da Retirada</label>
                            <input type="time" class="form-control" id="hora_retirada" name="hora_retirada" required>
                        </div>

                        <button type="submit" class="btn btn-success">Agendar Retirada</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- (Opcional) Carregar o mapa (caso vá usar Leaflet ou Google Maps) -->
<script>
    function initMap() {
        var armarioLocalizacao = { lat: <?php echo $armario['latitude']; ?>, lng: <?php echo $armario['longitude']; ?> };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: armarioLocalizacao
        });
        var marker = new google.maps.Marker({
            position: armarioLocalizacao,
            map: map
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=SEU_API_KEY&callback=initMap" async defer></script>

<?php include('includes/footer.php'); ?>
                                            
                                