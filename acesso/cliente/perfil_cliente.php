<?php
session_start();
require_once '../../config.php'; // Corrigido o caminho para o arquivo config.php

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

        // Gerar o HTML do crachá com a foto do cliente
        $html = '
            <html>
            <head>
                <title>Carteira Digital do Aluno</title>
                <style>
                    /* Estilos para o crachá */
                    /* Adicione estilos personalizados aqui */

                    /* Exemplo básico de estilos */
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                    }
                    .cracha {
                        width: 300px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #f1f1f1;
                        border: 1px solid #ccc;
                    }
                    .foto {
                        width: 150px;
                        height: 150px;
                        margin: 0 auto;
                        border: 1px solid #ccc;
                    }
                    .nome {
                        margin-top: 10px;
                        font-weight: bold;
                    }
                    .qrcode {
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="cracha">
                    <div class="foto">
                        <img src="data:image/jpeg;base64,' . base64_encode($foto) . '" alt="Foto do Cliente" style="width: 150px; height: 150px;">
                    </div>
                    <div class="nome">' . $nome . '</div>
                    <div class="qrcode">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($cpf) . '&size=150x150" alt="QR Code" style="width: 150px;">
                    </div>
                </div>
            </body>
            </html>
        ';

        // Exibir o HTML do crachá diretamente na página
        echo $html;
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistema de Refeitório - Gerar Carteirinha</title>
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
                    <a class="nav-link" href="/refeitorio/login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Gerar Carteirinha</h2>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Insira seu CPF</h3>
                <form method="POST" action="/refeitorio/acesso/cliente/perfil_cliente.php">
                    <input type="text" name="cpf" placeholder="CPF" required>
                    <button type="submit">Gerar Carteirinha</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
