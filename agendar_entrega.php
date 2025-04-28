<?php
// agendar_entrega.php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verificar se o usuário está logado como instituição
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'instituicao') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Processar formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $endereco_entrega = filter_input(INPUT_POST, 'endereco_entrega', FILTER_SANITIZE_STRING);
    $data_entrega = filter_input(INPUT_POST, 'data_entrega', FILTER_SANITIZE_STRING);
    $hora_entrega = filter_input(INPUT_POST, 'hora_entrega', FILTER_SANITIZE_STRING);
    $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING);
    $itens_ids = $_POST['itens'] ?? [];
    
    // Validar dados
    if (empty($endereco_entrega) || empty($data_entrega) || empty($hora_entrega) || empty($itens_ids)) {
        $message = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        try {
            // Iniciar transação
            $pdo->beginTransaction();
            
            // Criar agendamento
            $stmt = $pdo->prepare("INSERT INTO agendamentos (instituicao_id, endereco_entrega, data_entrega, hora_entrega, 
                                 observacoes, tipo, status, data_criacao) 
                                 VALUES (:instituicao_id, :endereco_entrega, :data_entrega, :hora_entrega, 
                                 :observacoes, 'entrega', 'pendente', NOW())");
            $stmt->execute([
                'instituicao_id' => $user_id,
                'endereco_entrega' => $endereco_entrega,
                'data_entrega' => $data_entrega, 
                'hora_entrega' => $hora_entrega,
                'observacoes' => $observacoes
            ]);
            
            $agendamento_id = $pdo->lastInsertId();
            
            // Adicionar itens ao agendamento
            foreach ($itens_ids as $item_id) {
                $stmt = $pdo->prepare("INSERT INTO agendamento_itens (agendamento_id, item_id) VALUES (:agendamento_id, :item_id)");
                $stmt->execute([
                    'agendamento_id' => $agendamento_id,
                    'item_id' => $item_id
                ]);
                
                // Atualizar status do item para "agendado"
                $stmt = $pdo->prepare("UPDATE itens_doacao SET status = 'agendado' WHERE id = :item_id");
                $stmt->execute(['item_id' => $item_id]);
            }
            
            // Confirmar transação
            $pdo->commit();
            
            $message = "Agendamento de entrega realizado com sucesso! Um entregador parceiro entrará em contato.";
        } catch (PDOException $e) {
            // Reverter transação em caso de erro
            $pdo->rollBack();
            $message = "Erro ao criar agendamento: " . $e->getMessage();
        }
    }
}

// Buscar itens disponíveis para doação
$itens_disponiveis = [];
try {
    $stmt = $pdo->prepare("
        SELECT id.* FROM itens_doacao id
        JOIN necessidades n ON id.necessidade_id = n.id
        WHERE n.instituicao_id = :instituicao_id AND id.status = 'disponivel'
    ");
    $stmt->execute(['instituicao_id' => $user_id]);
    $itens_disponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message .= " Erro ao buscar itens disponíveis: " . $e->getMessage();
}

// Buscar endereço da instituição para pré-preencher
$instituicao_info = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM instituicoes WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $instituicao_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message .= " Erro ao buscar informações da instituição: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Entrega - Hope</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <h1>Agendar Entrega de Doações</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'sucesso') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="endereco_entrega">Endereço de Entrega:</label>
                <textarea name="endereco_entrega" id="endereco_entrega" class="form-control" required><?php echo $instituicao_info['endereco'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="data_entrega">Data de Entrega:</label>
                <input type="date" name="data_entrega" id="data_entrega" class="form-control" 
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                <small class="form-text text-muted">Entregas devem ser agendadas com pelo menos 1 dia de antecedência.</small>
            </div>
            
            <div class="form-group">
                <label for="hora_entrega">Horário de Entrega:</label>
                <select name="hora_entrega" id="hora_entrega" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="08:00">08:00 - 10:00</option>
                    <option value="10:00">10:00 - 12:00</option>
                    <option value="14:00">14:00 - 16:00</option>
                    <option value="16:00">16:00 - 18:00</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes" class="form-control" 
                          placeholder="Instruções especiais para entrega, pontos de referência, etc."></textarea>
            </div>
            
            <div class="form-group">
                <label>Selecione os Itens para Entrega:</label>
                <?php if (count($itens_disponiveis) > 0): ?>
                    <div class="itens-checkbox">
                        <?php foreach ($itens_disponiveis as $item): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="itens[]" id="item-<?php echo $item['id']; ?>" 
                                       value="<?php echo $item['id']; ?>">
                                <label for="item-<?php echo $item['id']; ?>">
                                    <?php echo $item['nome']; ?> - Quantidade: <?php echo $item['quantidade']; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Não há itens disponíveis para entrega no momento.</p>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Agendar Entrega</button>
        </form>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>