<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obter a lista de produtos em estoque
$query = "SELECT * FROM estoque";
$stmt = $conn->prepare($query);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestão de Estoque</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Gestão de Estoque</h2>

        <a href="adicionar_produto.php" class="btn btn-primary mb-3">Adicionar Produto</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?php echo $produto['nome']; ?></td>
                        <td><?php echo $produto['quantidade']; ?></td>
                        <td>
                            <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="remover_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-danger">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
