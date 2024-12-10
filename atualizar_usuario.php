<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Verificar se o ID do cliente foi fornecido
if (!isset($_GET['id'])) {
    header("Location: dashboard_admin.php");
    exit();
}

// Obtém o ID do cliente a ser atualizado
$id = $_GET['id'];

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $curso = $_POST['curso'];

    // Verificar se uma nova foto foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto']['tmp_name'];

        // Ler o conteúdo do arquivo de imagem
        $conteudoFoto = file_get_contents($foto);

        // Preparar a query de atualização com a foto
        $query = "UPDATE clientes SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, curso = :curso, foto = :foto WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':foto', $conteudoFoto, PDO::PARAM_LOB);
    } else {
        // Preparar a query de atualização sem a foto
        $query = "UPDATE clientes SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, curso = :curso WHERE id = :id";
        $stmt = $conn->prepare($query);
    }

    // Bind dos parâmetros da query
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':curso', $curso);
    $stmt->bindParam(':id', $id);

    // Executar a query de atualização
    $stmt->execute();

    // Verificar se a atualização foi bem-sucedida
    if ($stmt->rowCount() > 0) {
        // Definir uma mensagem de sucesso
        $_SESSION['success_message'] = "Cliente atualizado com sucesso.";

        // Redirecionar para a página de dashboard do administrador
        header("Location: dashboard_admin.php");
        exit();
    } else {
        // Exibir uma mensagem de erro caso a atualização falhe
        $error = "Falha ao atualizar os dados do cliente. Por favor, tente novamente.";
    }
}

// Obter os dados do cliente a ser atualizado
$query = "SELECT * FROM clientes WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o cliente foi encontrado
if (!$cliente) {
    header("Location: dashboard_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Atualizar Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Atualizar Cliente</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $cliente['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $cliente['cpf']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $cliente['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $cliente['telefone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="curso">Curso</label>
                <input type="text" class="form-control" id="curso" name="curso" value="<?php echo $cliente['curso']; ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
</body>
</html>
