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
    if (isset($_POST['nome'], $_POST['descricao'], $_POST['preco'], $_POST['categoria'])) {
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
            // Atualizar as informações do item de cardápio no banco de dados
            $query = "UPDATE cardapio SET nome = :nome, descricao = :descricao, preco = :preco, disponivel = :disponivel, categoria = :categoria WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':disponivel', $disponivel);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':id', $_GET['id']);

            if ($stmt->execute()) {
                $sucesso = 'Item de cardápio atualizado com sucesso!';
            } else {
                $erro = 'Ocorreu um erro ao atualizar o item de cardápio.';
            }
        }
    } else {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    }
}

// Obter as informações do item de cardápio a ser editado
$query = "SELECT * FROM cardapio WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$cardapio = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o item de cardápio foi encontrado
if (!$cardapio) {
    header("Location: gerenciar_cardapio.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cardápio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 500px;
        }
        .alert {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Cardápio</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $cardapio['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" class="form-control"><?php echo $cardapio['descricao']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" name="preco" id="preco" class="form-control" value="<?php echo $cardapio['preco']; ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="disponivel">Disponível:</label>
                <input type="checkbox" name="disponivel" id="disponivel" <?php echo $cardapio['disponivel'] ? 'checked' : ''; ?>>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <select name="categoria" id="categoria" class="form-control" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="entrada" <?php echo $cardapio['categoria'] === 'entrada' ? 'selected' : ''; ?>>Entrada</option>
                    <option value="principal" <?php echo $cardapio['categoria'] === 'principal' ? 'selected' : ''; ?>>Principal</option>
                    <option value="sobremesa" <?php echo $cardapio['categoria'] === 'sobremesa' ? 'selected' : ''; ?>>Sobremesa</option>
                    <option value="bebida" <?php echo $cardapio['categoria'] === 'bebida' ? 'selected' : ''; ?>>Bebida</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="gerenciar_cardapio.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
