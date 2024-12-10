<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Verificar se o CPF foi fornecido no formulário
if (isset($_POST['cpf'])) {
    // Obter o CPF fornecido pelo usuário
    $cpf = $_POST['cpf'];

    // Consultar o banco de dados para obter os dados do cliente com base no CPF
    $query = "SELECT * FROM clientes WHERE cpf = :cpf";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o cliente foi encontrado
    if ($cliente) {
        // Dados do cliente
        $nome = $cliente['nome'];
        $foto = $cliente['foto']; // Assume que a foto é armazenada como BLOB no banco de dados

        // Mostrar a foto e o nome do cliente
        echo '<img src="data:image/jpeg;base64,' . base64_encode($foto) . '" alt="Foto do Cliente" style="width: 150px; height: 150px;"><br>';
        echo 'Nome: ' . $nome . '<br>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Autenticação</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Autenticação para Almoço</h2>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Insira seu CPF</h3>
                <form method="POST" action="autenticacao.php">
                    <input type="text" name="cpf" placeholder="CPF" required>
                    <button type="submit">Autenticar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
