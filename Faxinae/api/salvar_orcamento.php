<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

// Captura os campos enviados pelo formulário
$servico     = trim($_POST['servico'] ?? 'Limpeza Empresarial');
$empresa     = trim($_POST['empresa'] ?? '');
$nome        = trim($_POST['nome'] ?? '');
$email       = trim($_POST['email'] ?? '');
$telefone    = trim($_POST['telefone'] ?? '');
$frequencia  = trim($_POST['frequencia'] ?? 'Não informada');
$tamanho     = trim($_POST['tamanho'] ?? 'Não informado');
$observacoes = trim($_POST['observacoes'] ?? '');

// Validação dos campos obrigatórios
if (empty($nome) || empty($telefone) || empty($email)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios (*).']);
    exit;
}

// Monta o nome completo (Responsável + Empresa, se houver)
$clienteCompleto = !empty($empresa) ? "$nome ($empresa)" : $nome;

// Agrupa os detalhes técnicos do orçamento em um texto estruturado
$detalhesCompletos = "Frequência: $frequencia | Tamanho: $tamanho | Obs: $observacoes";

try {
    $sql = "INSERT INTO orcamentos (nome, telefone, email, servico, detalhes) 
            VALUES (:nome, :telefone, :email, :servico, :detalhes)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome'     => $clienteCompleto,
        ':telefone' => $telefone,
        ':email'    => $email,
        ':servico'  => $servico,
        ':detalhes' => $detalhesCompletos
    ]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Solicitação de orçamento enviada com sucesso! Nossa equipe comercial retornará em breve.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar orçamento: ' . $e->getMessage()]);
}
?>