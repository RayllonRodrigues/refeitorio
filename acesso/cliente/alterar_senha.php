<?php
require_once '../../config.php'; // Corrigido o caminho para o arquivo config.php

// Inicializar a sessão se ainda não estiver iniciada
// Inicializar a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpar todas as sessões para evitar salvar em cache
if (isset($_SESSION)) {
    session_unset();
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_senha'])) {
    $clienteId = $_POST['cliente_id'];
    $novaSenha = $_POST['nova_senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Verificar se as senhas coincidem
    if ($novaSenha === $confirmarSenha) {
        // Atualizar a senha no banco de dados apenas para o cliente especificado
        $query = "UPDATE clientes SET senha = :novaSenha WHERE cpf = :clienteId";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':novaSenha', $novaSenha);
        $stmt->bindValue(':clienteId', $clienteId);

        // Verificar se a atualização foi bem-sucedida
        if ($stmt->execute()) {
            // Mensagem de sucesso
            echo "<script>alert('Senha alterada com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao atualizar a senha. Tente novamente!');</script>";
        }
    } else {
        echo "<script>alert('A nova senha e a confirmação não coincidem!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <br>
        <h2>Alterar Senha</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="cliente_id">CPF:</label>
                <input type="text" class="form-control" name="cliente_id" required>
            </div>
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" class="form-control" name="nova_senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" class="form-control" name="confirmar_senha" required>
            </div>
            <button type="submit" class="btn btn-primary" name="alterar_senha">Alterar Senha</button>
            <a href="/refeitorio/login.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
