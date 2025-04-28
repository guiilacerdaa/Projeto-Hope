<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Buscar todos os armários
$sql = "SELECT * FROM armarios";
$result = $conn->query($sql);

$armarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $armarios[] = $row;
    }
}

echo json_encode($armarios);
?>