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
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $preco_compra = $_POST['preco_compra'];
    $preco_venda = $_POST['preco_venda'];
    $data_validade = $_POST['data_validade'];
    $fornecedor = $_POST['fornecedor'];
    $localizacao = $_POST['localizacao'];
    $data_entrada = date('Y-m-d');

    // Validar os dados do formulário
    if (empty($nome) || empty($quantidade) || empty($categoria) || empty($preco_compra) || empty($preco_venda) || empty($data_validade) || empty($fornecedor) || empty($localizacao)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        // Inserir o novo item no estoque no banco de dados
        $query = "INSERT INTO estoque (nome, quantidade, descricao, categoria, preco_compra, preco_venda, data_validade, fornecedor, localizacao, data_entrada) VALUES (:nome, :quantidade, :descricao, :categoria, :preco_compra, :preco_venda, :data_validade, :fornecedor, :localizacao, :data_entrada)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':preco_compra', $preco_compra);
        $stmt->bindParam(':preco_venda', $preco_venda);
        $stmt->bindParam(':data_validade', $data_validade);
        $stmt->bindParam(':fornecedor', $fornecedor);
        $stmt->bindParam(':localizacao', $localizacao);
        $stmt->bindParam(':data_entrada', $data_entrada);

        if ($stmt->execute()) {
            $sucesso = 'Item adicionado ao estoque com sucesso!';
        } else {
            $erro = 'Ocorreu um erro ao adicionar o item ao estoque.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Item ao Estoque</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <h2>Adicionar Item ao Estoque</h2>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
    <?php endif; ?>

    <?php if (!empty($sucesso)): ?>
        <div class="alert alert-success"><?php echo $sucesso; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <input type="text" name="categoria" id="categoria" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="preco_compra">Preço de Compra:</label>
            <input type="number" name="preco_compra" id="preco_compra" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="preco_venda">Preço de Venda:</label>
            <input type="number" name="preco_venda" id="preco_venda" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="data_validade">Data de Validade:</label>
            <input type="date" name="data_validade" id="data_validade" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fornecedor">Fornecedor:</label>
            <input type="text" name="fornecedor" id="fornecedor" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="localizacao">Localização:</label>
            <input type="text" name="localizacao" id="localizacao" class="form-control" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Adicionar</button>
            <a href="gerenciar_estoque.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</body>
</html>
