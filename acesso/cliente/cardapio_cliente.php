<?php
session_start();
require_once '../../config.php'; // Corrigido o caminho para o arquivo config.php

// Verificar se o usuário está autenticado como cliente
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'cliente') {
    header("Location: ../../dashboard_cliente.php"); // Corrigido o caminho para redirecionamento ao login.php
    exit();
}

// Obter a lista de itens de cardápio
$query = "SELECT * FROM cardapio";
$stmt = $conn->prepare($query);
$stmt->execute();
$itensCardapio = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Cardápio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/refeitorio/acesso/cliente/dashboard_cliente.php">Sistema de Refeitório</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/acesso/cliente/perfil_cliente.php"><i class="fas fa-home"></i> Carteira Digital</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/acesso/cliente/cardapio_cliente.php"><i class="fas fa-users"></i>Cardápio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-cog"></i> Informativos</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Lista de Itens de Cardápio</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Disponível</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itensCardapio as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['nome']; ?></td>
                        <td><?php echo $item['descricao']; ?></td>
                        <td><?php echo $item['preco']; ?></td>
                        <td><?php echo $item['disponivel'] ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo $item['categoria']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
