<?php
$servername = "localhost";
$username = "root";  // Usuário padrão do XAMPP
$password = "";      // Senha padrão vazia do XAMPP
$dbname = "hope_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Configurar conjunto de caracteres
$conn->set_charset("utf8mb4");
?>