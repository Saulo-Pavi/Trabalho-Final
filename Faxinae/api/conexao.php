<?php
// Configurações do banco local (padrão XAMPP/Laragon)
$host = 'localhost';
$dbname = 'faxinae_db';
$usuario = 'root';
$senha = ''; // No XAMPP e Laragon padrão a senha do root é vazia

try {
    // Cria a conexão usando PDO (mais seguro contra SQL Injection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $senha);
    
    // Configura o PDO para lançar exceções caso ocorra algum erro de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Se der erro na conexão, devolve um JSON explicando o problema
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na conexão: ' . $e->getMessage()]);
    exit;
}
?>