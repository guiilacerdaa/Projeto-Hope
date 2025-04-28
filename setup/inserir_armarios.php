<?php
require_once '../config/database.php';

// Dados de exemplo para armários em diferentes locais
$armarios = [
    [
        'nome' => 'Armário Centro',
        'endereco' => 'Av. Paulista, 1000, São Paulo, SP',
        'latitude' => -23.565690,
        'longitude' => -46.652141,
        'status' => 'disponivel'
    ],
    [
        'nome' => 'Armário Norte',
        'endereco' => 'Av. Santos Dumont, 500, São Paulo, SP',
        'latitude' => -23.518458,
        'longitude' => -46.627379,
        'status' => 'disponivel'
    ],
    [
        'nome' => 'Armário Sul',
        'endereco' => 'Av. Ibirapuera, 2000, São Paulo, SP',
        'latitude' => -23.601453,
        'longitude' => -46.667039,
        'status' => 'disponivel'
    ],
    [
        'nome' => 'Armário Leste',
        'endereco' => 'Av. Sapopemba, 1500, São Paulo, SP',
        'latitude' => -23.610878,
        'longitude' => -46.505740,
        'status' => 'disponivel'
    ],
    [
        'nome' => 'Armário Oeste',
        'endereco' => 'Av. Francisco Morato, 1000, São Paulo, SP',
        'latitude' => -23.584507,
        'longitude' => -46.735659,
        'status' => 'disponivel'
    ],
];

// Inserir armários no banco de dados
foreach ($armarios as $armario) {
    $stmt = $conn->prepare("INSERT INTO armarios (nome, endereco, latitude, longitude, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdds", $armario['nome'], $armario['endereco'], $armario['latitude'], $armario['longitude'], $armario['status']);
    $stmt->execute();
}

echo "Armários inseridos com sucesso!";
?>