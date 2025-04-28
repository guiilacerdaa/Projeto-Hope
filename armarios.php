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
            <h2>Locais de Armários para Doação</h2>
            <p>Encontre o armário mais próximo para retirada de doações ou agende uma entrega.</p>
            
            <?php
            // Mostrar mensagens de erro ou sucesso, se houver
            if (isset($_SESSION['mensagem'])) {
                echo '<div class="alert alert-' . $_SESSION['tipo_mensagem'] . '">' . $_SESSION['mensagem'] . '</div>';
                unset($_SESSION['mensagem']);
                unset($_SESSION['tipo_mensagem']);
            }
            ?>
            
            <!-- Mapa com os armários -->
            <div class="card mb-4">
                <div class="card-body">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
            
            <!-- Tabela de armários -->
            <div class="card">
                <div class="card-header">
                    <h5>Armários Disponíveis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabela-armarios">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Endereço</th>
                                    <th>Distância</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dados serão preenchidos via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let map;
let userLocation;
let markers = [];

function initMap() {
    // Inicializar o mapa com localização de São Paulo
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -23.550520, lng: -46.633308},
        zoom: 11
    });
    
    // Tentar obter a localização do usuário
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            // Centralizar o mapa na localização do usuário
            map.setCenter(userLocation);
            
            // Adicionar marcador para a localização do usuário
            new google.maps.Marker({
                position: userLocation,
                map: map,
                title: 'Sua localização',
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                }
            });
            
            // Carregar os armários
            carregarArmarios();
        }, function() {
            // Em caso de erro, apenas carregar os armários
            carregarArmarios();
        });
    } else {
        // Se geolocalização não estiver disponível, apenas carregar os armários
        carregarArmarios();
    }
}

function carregarArmarios() {
    // Fazer requisição AJAX para obter os armários
    fetch('api/armarios.php')
        .then(response => response.json())
        .then(data => {
            // Limpar tabela
            const tbody = document.querySelector('#tabela-armarios tbody');
            tbody.innerHTML = '';
            
            // Limpar marcadores anteriores
            markers.forEach(marker => marker.setMap(null));
            markers = [];
            
            // Adicionar armários ao mapa e à tabela
            data.forEach(armario => {
                // Adicionar marcador no mapa
                const position = {
                    lat: parseFloat(armario.latitude),
                    lng: parseFloat(armario.longitude)
                };
                
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: armario.nome
                });
                
                // Adicionar informação ao clicar no marcador
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div>
                            <h5>${armario.nome}</h5>
                            <p>${armario.endereco}</p>
                            <p>Status: ${armario.status === 'disponivel' ? 'Disponível' : 'Indisponível'}</p>
                        </div>
                    `
                });
                
                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
                
                markers.push(marker);
                
                // Calcular distância
                let distancia = 'N/A';
                if (userLocation) {
                    const d = calcularDistancia(
                        userLocation.lat, userLocation.lng,
                        parseFloat(armario.latitude), parseFloat(armario.longitude)
                    );
                    distancia = d.toFixed(1) + ' km';
                }
                
                // Adicionar à tabela
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${armario.nome}</td>
                    <td>${armario.endereco}</td>
                    <td>${distancia}</td>
                    <td>${armario.status === 'disponivel' 
                        ? '<span class="badge bg-success">Disponível</span>' 
                        : '<span class="badge bg-danger">Indisponível</span>'}
                    </td>
                    <td>
                        ${armario.status === 'disponivel' 
                            ? `<a href="agendar_retirada.php?armario_id=${armario.id}" class="btn btn-sm btn-primary">Agendar Retirada</a>` 
                            : '<button class="btn btn-sm btn-secondary" disabled>Indisponível</button>'}
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar armários:', error);
            alert('Erro ao carregar os armários. Por favor, tente novamente mais tarde.');
        });
}

function calcularDistancia(lat1, lon1, lat2, lon2) {
    // Fórmula de Haversine para calcular distância entre dois pontos geográficos
    const R = 6371; // Raio da Terra em km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const d = R * c;
    return d;
}
</script>

<!-- Carregar API do Google Maps (substitua YOUR_API_KEY pela sua chave de API) -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
</script>

<?php include('includes/footer.php'); ?>