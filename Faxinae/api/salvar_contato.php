<?php
// Define que a resposta será em formato JSON
header('Content-Type: application/json; charset=utf-8');

// Importa a conexão com o banco ($pdo)
require_once 'conexao.php';

// Garante que o acesso foi feito via método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

// Captura e limpa os dados enviados pelo formulário
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

// Validação básica no servidor
if (empty($nome) || empty($email) || empty($mensagem)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

try {
    // Prepara o comando SQL usando parâmetros (:nome, :email, :mensagem) para evitar SQL Injection
    $sql = "INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)";
    $stmt = $pdo->prepare($sql);
    
    // Executa substituindo os parâmetros pelos valores reais
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':mensagem' => $mensagem
    ]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Mensagem enviada com sucesso! Em breve entraremos em contato.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
}
?>