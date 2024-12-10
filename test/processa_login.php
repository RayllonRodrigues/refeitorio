<?php
// Arquivo: processa_login.php

// Verificar se foi feito um envio de formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Realizar a validação do CPF e senha

    // Verificar se os campos estão preenchidos
    if (empty($_POST['cpf']) || empty($_POST['senha'])) {
        header('Location: login.php?erro=campos');
        exit();
    }

    // Obter os valores do formulário de login
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    // Conectar ao banco de dados
    // ...

    // Consultar o banco de dados para obter os dados do usuário
    // ...

    // Realizar a validação da senha
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $senhaArmazenada = $row['senha'];

        if (password_verify($senha, $senhaArmazenada)) {
            // Senha correta, redirecionar para o dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            // Senha incorreta
            header('Location: login.php?erro=senha');
            exit();
        }
    } else {
        // Usuário não encontrado
        header('Location: login.php?erro=usuario');
        exit();
    }
} else {
    // Redirecionar para a página de login caso não seja um envio de formulário válido
    header('Location: login.php');
    exit();
}
