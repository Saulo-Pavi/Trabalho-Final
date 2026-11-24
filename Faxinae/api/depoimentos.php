<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

$metodo = $_SERVER['REQUEST_METHOD'];

// ROTA 1: LISTAR DEPOIMENTOS (GET)
if ($metodo === 'GET') {
    try {
        // Busca os depoimentos ordenados do mais recente para o mais antigo
        // DATE_FORMAT já formata a data no padrão brasileiro (dd/mm/aaaa)
        $sql = "SELECT id, nome, perfil, comentario, foto_url, 
                       DATE_FORMAT(data_depoimento, '%d/%m/%Y') AS data_formatada 
                FROM depoimentos 
                ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        $depoimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['sucesso' => true, 'dados' => $depoimentos]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao buscar depoimentos: ' . $e->getMessage()]);
    }
    exit;
}

// ROTA 2: INSERIR NOVO DEPOIMENTO (POST)
if ($metodo === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $perfil = trim($_POST['perfil'] ?? '');
    $comentario = trim($_POST['comentario'] ?? '');

    if (empty($nome) || empty($perfil) || empty($comentario)) {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos do depoimento.']);
        exit;
    }

    try {
        // Usa CURDATE() para gravar automaticamente a data atual do servidor
        $sql = "INSERT INTO depoimentos (nome, perfil, comentario, foto_url, data_depoimento) 
                VALUES (:nome, :perfil, :comentario, NULL, CURDATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':perfil' => $perfil,
            ':comentario' => $comentario
        ]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Depoimento publicado com sucesso!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar depoimento: ' . $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['sucesso' => false, 'mensagem' => 'Método não suportado.']);
?>