<?php
// wallet.php

// Inclua o arquivo de configuração do banco de dados
require_once 'config.php';

session_start();

if (!isset($_SESSION['email'])) {
    // Se o usuário não estiver autenticado, redirecione para a página de login
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

// Recupere o saldo do usuário do banco de dados
$sql = "SELECT balance FROM users WHERE email = '$email'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$balance = $row['balance'];

// Exiba a página da carteira HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Minha Carteira</title>
</head>
<body>
    <h2>Minha Carteira</h2>
    <p>Saldo: $<?php echo $balance; ?></p>

    <h3>Depósito</h3>
    <form method="POST" action="">
        <input type="number" name="deposit_amount" placeholder="Valor do depósito" step="0.01" required><br>
        <input type="submit" value="Depositar">
    </form>

    <h3>Saque</h3>
    <form method="POST" action="">
        <input type="number" name="withdraw_amount" placeholder="Valor do saque" step="0.01" required><br>
        <input type="submit" value="Sacar">
    </form>
</body>
</html>
