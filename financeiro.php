<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está autenticado como administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: login.php");
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

// Definir filtros padrão
$inicio = date('Y-m-01');
$fim = date('Y-m-t');

// Verificar se há filtros enviados
if (isset($_POST['filtro'])) {
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];
}

// Obter registros com base nos filtros
$query = "SELECT * FROM registros WHERE data_acesso BETWEEN :inicio AND :fim";
$stmt = $conn->prepare($query);
$stmt->bindParam(':inicio', $inicio);
$stmt->bindParam(':fim', $fim);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular o total de gastos
$totalGastos = 0;
foreach ($registros as $registro) {
    $totalGastos += $registro['valor_refeicao'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Financeiro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a class="nav-link" href="gerar_carteirinha.php">Carteiras</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gerar_carteirinha.php">Autenticação</a>
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
        <h2>Financeiro</h2>
        <form method="post" class="form-inline mb-3">
            <div class="form-group mr-3">
                <label for="inicio">Data Inicial:</label>
                <input type="date" class="form-control mx-sm-2" id="inicio" name="inicio" value="<?php echo $inicio; ?>">
            </div>
            <div class="form-group mr-3">
                <label for="fim">Data Final:</label>
                <input type="date" class="form-control mx-sm-2" id="fim" name="fim" value="<?php echo $fim; ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="filtro">Filtrar</button>
        </form>

        <div class="row">
            <div class="col-md-6">
                <h3>Total Gastos: R$ <?php echo number_format($totalGastos, 2, ',', '.'); ?></h3>
            </div>
        </div>
        
        <!-- Gráfico de quantidade de refeições por dia -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="graficoRefeicoesPorDia"></canvas>
            </div>
            <div class="col-md-6">
                <!-- Gráfico de gastos por dia -->
                <canvas id="graficoGastosPorDia"></canvas>
            </div>
        </div>

        <h3 class="mt-4">Registros</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CPF</th>
                    <th>Data Acesso</th>
                    <th>Valor Refeição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <td><?php echo $registro['id']; ?></td>
                        <td><?php echo $registro['cpf_cliente']; ?></td>
                        <td><?php echo $registro['data_acesso']; ?></td>
                        <td><?php echo $registro['valor_refeicao']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Dados para o gráfico de quantidade de refeições por dia
        var dadosRefeicoesPorDia = {
            labels: <?php echo json_encode(array_column($registros, 'data_acesso')); ?>,
            datasets: [{
                label: 'Quantidade de Refeições',
                data: <?php echo json_encode(array_column($registros, 'valor_refeicao')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Opções do gráfico de quantidade de refeições por dia
        var opcoesRefeicoesPorDia = {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        };

        // Criação do gráfico de quantidade de refeições por dia
        var graficoRefeicoesPorDia = new Chart(document.getElementById('graficoRefeicoesPorDia'), {
            type: 'bar',
            data: dadosRefeicoesPorDia,
            options: opcoesRefeicoesPorDia
        });

        // Dados para o gráfico de gastos por dia
        var dadosGastosPorDia = {
            labels: <?php echo json_encode(array_column($registros, 'data_acesso')); ?>,
            datasets: [{
                label: 'Gastos',
                data: <?php echo json_encode(array_column($registros, 'valor_refeicao')); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        // Opções do gráfico de gastos por dia
        var opcoesGastosPorDia = {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 50
                }
            }
        };

        // Criação do gráfico de gastos por dia
        var graficoGastosPorDia = new Chart(document.getElementById('graficoGastosPorDia'), {
            type: 'line',
            data: dadosGastosPorDia,
            options: opcoesGastosPorDia
        });
    </script>
</body>
</html>
