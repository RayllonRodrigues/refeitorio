<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $curso = $_POST['curso'];
    $alojado = isset($_POST['alojado']) ? $_POST['alojado'] : 'Nao';

    // Verificar se foi enviada uma foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto']['tmp_name'];

        // Ler o conteúdo da foto
        $fotoConteudo = file_get_contents($foto);

        // Preparar a consulta SQL para inserir os dados na tabela clientes
        $query = "INSERT INTO clientes (nome, cpf, email, telefone, curso, foto, senha, nivel_acesso, alojamento) VALUES (:nome, :cpf, :email, :telefone, :curso, :foto, 'aluno@123','cliente', :alojado)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':curso', $curso);
        $stmt->bindValue(':alojado', $alojado);
        $stmt->bindValue(':foto', $fotoConteudo, PDO::PARAM_LOB);
        $stmt->execute();

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Cliente cadastrado com sucesso.";

        // Redirecionar para a página de sucesso
        header("Location: sucesso.php");
        exit();
    } else {
        // Se não foi enviada uma foto, exibir uma mensagem de erro
        $error = "Erro ao enviar a foto.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Cadastrar Cliente</title>
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
                    <a class="nav-link" href="salvar_cliente.php">Cadastrar Cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gerar_carteirinha.php">Carteiras</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center">Cadastrar Cliente</h2>
        <?php if (isset($error)) { echo "<p class='alert alert-danger'>$error</p>"; } ?>
        <?php if (isset($_SESSION['success_message'])) { ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php } ?>
        <form method="POST" action="salvar_cliente.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" name="cpf" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" class="form-control" name="telefone" required>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <input type="text" class="form-control" name="curso" required>
            </div>
            <div class="form-group">
                <label for="alojado">Alojado:</label>
                <select class="form-control" name="alojado">
                    <option value="Sim">Sim</option>
                    <option value="Não" selected>Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control-file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
