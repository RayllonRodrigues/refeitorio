<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obter a lista de usuários cadastrados
$query = "SELECT * FROM clientes";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Lista de Usuários</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Lista de Usuários Cadastrados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($usuarios) > 0) {
                    foreach ($usuarios as $usuario) {
                        echo '<tr>';
                        echo '<td>' . $usuario['nome'] . '</td>';
                        echo '<td>' . $usuario['cpf'] . '</td>';
                        echo '<td><a href="carteira_digital.php?cliente_id=' . $usuario['id'] . '">Visualizar Carteira Digital</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Nenhum usuário cadastrado.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="dashboard_admin.php" class="btn btn-primary">Voltar</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
