<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    $query = "SELECT id, senha, nivel_acesso FROM clientes WHERE cpf = :cpf";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['senha'] === 'aluno@123') {
            header("Location: alterar_senha.php?cpf=$cpf");
            exit();
        } else {
            // Verificar a senha fornecida
            if ($user['senha'] === $senha) {
                $_SESSION['user_id'] = $user['id'];
                // Redirecionar para o painel adequado dependendo do nível de acesso
                if ($user['nivel_acesso'] === 'administrador') {
                    header("Location: dashboard_admin.php");
                    exit();
                } elseif ($user['nivel_acesso'] === 'cliente') {
                    header("Location: dashboard_cliente.php");
                    exit();
                }
            } else {
                echo "Senha incorreta. Por favor, tente novamente.";
            }
        }
    } else {
        echo "CPF não encontrado.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistema de Refeitório - Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        .form-signin input[type="cpf"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="senha"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">
    <main class="form-signin">
        <form method="POST" action="login.php">
            <img class="mb-4" src="/refeitorio/logo/logo.png" alt="" width="auto" height="72">
            <h1 class="h3 mb-3 fw-normal">Login</h1>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <div class="form-floating">
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
                <label for="cpf"></label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <label for="senha"></label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Entrar</button>
            <p class="mt-5 mb-3 text-muted">Rayllon Rodrigues &copy; IFTO Campus Araguatins</p>
        </form>
    </main>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
