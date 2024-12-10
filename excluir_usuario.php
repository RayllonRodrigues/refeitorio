<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o ID do usuário foi fornecido
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Excluir o usuário com o ID fornecido
        $query = "DELETE FROM clientes WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'message' => 'Usuário excluído com sucesso.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ocorreu um erro ao excluir o usuário.'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'ID do usuário não fornecido.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Método de requisição inválido.'
    ];
}

// Enviar a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);
