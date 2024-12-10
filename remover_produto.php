<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Verificar se o ID do produto foi fornecido
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Remover o produto do estoque
$query = "DELETE FROM estoque WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_GET['id']);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Ocorreu um erro ao remover o produto.";
}
