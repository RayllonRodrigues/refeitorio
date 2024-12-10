<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Excluir item de cardápio
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $query = "DELETE FROM cardapio WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['sucesso'] = 'Item de cardápio excluído com sucesso!';
        header("Location: gerenciar_cardapio.php");
        exit();
    } else {
        $_SESSION['erro'] = 'Ocorreu um erro ao excluir o item de cardápio.';
        header("Location: gerenciar_cardapio.php");
        exit();
    }
}

// Obter a lista de itens de cardápio
$query = "SELECT * FROM cardapio";
$stmt = $conn->prepare($query);
$stmt->execute();
$cardapios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cardápio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Sistema de Refeitório</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard_admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="salvar_cardapio.php">Cadastrar Item de Cardápio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gerenciar_cardapio.php">Gerenciar Cardápio</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h3 class="mt-4">Gerenciar Cardápio</h3>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Disponível</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cardapios as $cardapio): ?>
                    <tr>
                        <td><?php echo $cardapio['id']; ?></td>
                        <td><?php echo $cardapio['nome']; ?></td>
                        <td><?php echo $cardapio['descricao']; ?></td>
                        <td><?php echo $cardapio['preco']; ?></td>
                        <td><?php echo $cardapio['disponivel'] ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo $cardapio['categoria']; ?></td>
                        <td>
                            <a href="editar_cardapio.php?id=<?php echo $cardapio['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="gerenciar_cardapio.php?excluir=<?php echo $cardapio['id']; ?>" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
