<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login_admin.php");
    exit();
}

// Conexão com o banco de dados (usando PDO)
try {
    $db_host = "localhost";
    $db_name = "refeitorio";
    $db_user = "root";
    $db_pass = "41418162218";
    
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Obtém a quantidade de almoços por dia
$query = "SELECT DATE(data_acesso) AS dia, COUNT(*) AS quantidade FROM registros GROUP BY DATE(data_acesso)";
$stmt = $conn->prepare($query);
$stmt->execute();
$almocosPorDia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtém os gastos por dia
$query = "SELECT DATE(data_acesso) AS dia, SUM(valor_refeicao) AS valor FROM registros GROUP BY DATE(data_acesso)";
$stmt = $conn->prepare($query);
$stmt->execute();
$gastosPorDia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obter os dados dos usuários cadastrados
$query = "SELECT * FROM clientes";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Dashboard Administrativo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
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
                    <a class="nav-link" href="cardapio.php">Cardápio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="estoque.php">Estoque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gerar_carteirinha.php">Carteira Digital</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catraca.php">Catraca</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="financeiro.php">Financeiro</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Dashboard Administrativo</h2>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Quantidade de Almoços por Dia</h3>
                <canvas id="almocosPorDiaChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3>Gastos por Dia</h3>
                <canvas id="gastosPorDiaChart"></canvas>
            </div>
        </div>

        <center><h3 class="mt-4">Lista de Usuários Cadastrados</h3></center>
        <div class="row mb-3">
            <div class="col-md-6 offset-md-6">
            </div>
        </div>
        <table class="table" id="usuariosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Carteira Digital</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo $usuario['nome']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td>
                            <a href="gerar_carteirinha.php?id=<?php echo $usuario['id']; ?>" target="_blank">Visualizar Crachá</a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="excluirUsuario(<?php echo $usuario['id']; ?>)">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                            <button class="btn btn-sm btn-primary" onclick="atualizarUsuario(<?php echo $usuario['id']; ?>)">
                                <i class="fas fa-edit"></i> Atualizar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para excluir um usuário
        function excluirUsuario(id) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                // Enviar uma requisição AJAX para excluir o usuário
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4) {
                        if (this.status === 200) {
                            var response = JSON.parse(this.responseText);
                            if (response.status === 'success') {
                                alert(response.message);
                                // Recarregar a página para atualizar a lista de usuários
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        } else {
                            alert('Ocorreu um erro durante a exclusão do usuário.');
                        }
                    }
                };
                xhttp.open("POST", "excluir_usuario.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("id=" + id);
            }
        }

        // Função para atualizar um usuário
        function atualizarUsuario(id) {
            // Redirecionar para a página de atualização do usuário com o ID fornecido
            window.location.href = 'atualizar_usuario.php?id=' + id;
        }

        $(document).ready(function() {
            // Configuração do DataTables
            $('#usuariosTable').DataTable();

            // Configuração da pesquisa por nome
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#usuariosTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Dados para o gráfico de quantidade de almoços por dia
            var almocosPorDiaData = {
                labels: <?php echo json_encode(array_column($almocosPorDia, 'dia')); ?>,
                datasets: [{
                    label: 'Quantidade de Almoços',
                    data: <?php echo json_encode(array_column($almocosPorDia, 'quantidade')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            // Opções do gráfico de quantidade de almoços por dia
            var almocosPorDiaOptions = {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            };

            // Criação do gráfico de quantidade de almoços por dia
            var almocosPorDiaChart = new Chart(document.getElementById('almocosPorDiaChart'), {
                type: 'bar',
                data: almocosPorDiaData,
                options: almocosPorDiaOptions
            });

            // Dados para o gráfico de gastos por dia
            var gastosPorDiaData = {
                labels: <?php echo json_encode(array_column($gastosPorDia, 'dia')); ?>,
                datasets: [{
                    label: 'Gastos',
                    data: <?php echo json_encode(array_column($gastosPorDia, 'valor')); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            // Opções do gráfico de gastos por dia
            var gastosPorDiaOptions = {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 50
                    }
                }
            };

            // Criação do gráfico de gastos por dia
            var gastosPorDiaChart = new Chart(document.getElementById('gastosPorDiaChart'), {
                type: 'line',
                data: gastosPorDiaData,
                options: gastosPorDiaOptions
            });
        });
    </script>
</body>
</html>
