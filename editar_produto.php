<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Inicializar variáveis de erro e sucesso
$erro = $sucesso = '';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se as chaves estão definidas
    if (isset($_POST['nome'], $_POST['quantidade'])) {
        // Obter os dados do formulário
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];

        // Validar os dados do formulário
        if (empty($nome) || empty($quantidade) || !is_numeric($quantidade)) {
            $erro = 'Por favor, preencha todos os campos corretamente.';
        } else {
            // Atualizar as informações do produto no estoque
            $query = "UPDATE estoque SET nome = :nome, quantidade = :quantidade WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':id', $_GET['id']);

            if ($stmt->execute()) {
                $sucesso = 'Produto atualizado com sucesso!';
            } else {
                $erro = 'Ocorreu um erro ao atualizar o produto.';
            }
        }
    } else {
        $erro = 'Por favor, preencha todos os campos corretamente.';
    }
}

// Obter as informações do produto a ser editado
$query = "SELECT * FROM estoque WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o produto foi encontrado
if (!$produto) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Editar Produto</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $produto['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade" id="quantidade" class="form-control" value="<?php echo $produto['quantidade']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
