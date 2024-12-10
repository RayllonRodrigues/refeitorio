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
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;
    $categoria = $_POST['categoria'];

    // Validar os dados do formulário
    if (empty($nome) || empty($preco) || empty($categoria)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        // Inserir o novo item de cardápio no banco de dados
        $query = "INSERT INTO cardapio (nome, descricao, preco, disponivel, categoria) VALUES (:nome, :descricao, :preco, :disponivel, :categoria)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':disponivel', $disponivel);
        $stmt->bindParam(':categoria', $categoria);

        if ($stmt->execute()) {
            $sucesso = 'Item de cardápio cadastrado com sucesso!';
        } else {
            $erro = 'Ocorreu um erro ao cadastrar o item de cardápio.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Cadastrar Item de Cardápio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
    <div class="container mt-4">
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao"></textarea>
            </div>
            <div class="form-group">
                <label for="preco">Preço*</label>
                <input type="number" class="form-control" id="preco" name="preco" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="disponivel">Disponível</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="disponivel" name="disponivel">
                    <label class="form-check-label" for="disponivel">Sim</label>
                </div>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria*</label>
                <select class="form-control" id="categoria" name="categoria" required>
                    <option value="">Selecione a categoria</option>
                    <option value="Saladas">Saladas</option>
                    <option value="Pratos Principais">Pratos Principais</option>
                    <option value="Acompanhamentos">Acompanhamentos</option>
                    <option value="Sobremesas">Sobremesas</option>
                    <option value="Bebidas">Bebidas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>
</html>
