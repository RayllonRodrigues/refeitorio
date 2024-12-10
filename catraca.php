<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        img {
            display: block;
            margin: 20px auto;
            max-width: 300px;
            border-radius: 5px;
        }

        h2,
        p {
            text-align: center;
        }

        .message {
            text-align: center;
            font-style: italic;
            color: #888;
            margin-top: 20px;
        }

        .echo-message {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Catraca de Acesso</h1>
        <form method="POST">
            <label for="cpf">Digite seu CPF:</label><br>
            <input type="text" id="cpf" name="cpf" maxlength="11" required><br><br>
            <button type="submit">Registrar Acesso</button>
            <button type="submit" name="ticket">TICKET</button>
        </form>

        <?php
        // Verificar se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Incluir o arquivo de configuração
            require_once 'config.php';

            // Conectar ao banco de dados
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Processar o formulário
            $data_atual = date("Y-m-d");
            if (isset($_POST['cpf'])) {
                $cpf_cliente = $_POST['cpf'];
                $sql_verificar = "SELECT * FROM registros WHERE cpf_cliente = '$cpf_cliente' AND data_acesso = '$data_atual'";
                $result_verificar = $conn->query($sql_verificar);

                if ($result_verificar->num_rows > 0) {
                    // O cliente já almoçou hoje
                    $row_verificar = $result_verificar->fetch_assoc();
                    $sql_dados = "SELECT nome, curso, foto, alojamento FROM clientes WHERE cpf = '$cpf_cliente'";
                    $result_dados = $conn->query($sql_dados);

                    if ($result_dados->num_rows > 0) {
                        $row_dados = $result_dados->fetch_assoc();
                        echo "<h2>{$row_dados['nome']}</h2>";
                        echo "<p>Curso: {$row_dados['curso']}</p>";
                        $imagem_base64 = base64_encode($row_dados['foto']);
                        echo '<img src="data:image/jpeg;base64,' . $imagem_base64 . '" alt="Imagem do Cliente">';
                        // Verificar se o cliente pode fazer duas refeições
                        if ($row_dados['alojamento'] === 'Sim'  && $row_verificar['total_acessos'] < 2) {
                            echo '<div class="echo-message">Acesso liberado para duas refeições. Bom apetite!</div>';
                            $sql = "UPDATE registros SET total_acessos = '2' WHERE cpf_cliente = '$cpf_cliente'";

                            if ($conn->query($sql) === TRUE) {
                                // Registro inserido com sucesso
                            } else {
                                echo '<div class="echo-message">Erro ao registrar acesso: ' . $conn->error . '</div>';
                            }
                        } else {
                        
                        }

                        if ($row_dados['alojamento'] === 'Não' && $row_verificar['total_acessos'] < 1) {
                            echo '<div class="echo-message">Acesso liberado para uma refeição. Bom apetite!</div>';
                            $sql = "INSERT INTO registros (cpf_cliente, data_acesso, valor_refeicao, total_acessos,receita) VALUES ('$cpf_cliente', '$data_atual', '15.00', 1, '0.0')";
                            $conn->query($sql);
                        } else {
                            echo '<div class="echo-message">Acesso bloqueado. Limite de refeições excedido.</div>';
                        }
                    } else {
                        echo "Dados do cliente não encontrados.";
                    }
                } else {
                    // Registrar o acesso do cliente
                    //$valor_refeicao = ($_POST['ticket'] ? 5.00 : 15.00); // Definir o valor da refeição
                    $sql_registrar = "INSERT INTO registros (cpf_cliente, data_acesso, valor_refeicao, total_acessos, receita) VALUES ('$cpf_cliente', '$data_atual', '15.00', 1, '0.0')";
                    if ($conn->query($sql_registrar) === TRUE) {
                        $sql_dados = "SELECT nome, curso, foto, alojamento FROM clientes WHERE cpf = '$cpf_cliente'";
                        $result_dados = $conn->query($sql_dados);

                        if ($result_dados->num_rows > 0) {
                            $row_dados = $result_dados->fetch_assoc();
                            echo "<h2>{$row_dados['nome']}</h2>";
                            echo "<p>Curso: {$row_dados['curso']}</p>";
                            $imagem_base64 = base64_encode($row_dados['foto']);
                            echo '<img src="data:image/jpeg;base64,' . $imagem_base64 . '" alt="Imagem do Cliente">';
                            echo '<div class="echo-message">Acesso registrado com sucesso.</div>';
                        } else {
                            echo "Dados do cliente não encontrados.";
                        }
                    } else {
                        echo '<div class="echo-message">Erro ao registrar acesso: ' . $conn->error . '</div>';
                    }
                }
            }

            // Fechar a conexão com o banco de dados
            $conn->close();
        }
        ?>

    </div>
    <center> <a href="dashboard_admin.php" class="btn btn-lg btn-light fw-bold border-white bg-white">Voltar</a></center>


    <script>
        // Função para enviar o formulário quando o botão "TICKET" for clicado
        document.getElementById("ticketButton").addEventListener("click", function() {
            // Adicione uma ação aqui, como redirecionar para outra página
            alert("Ação do botão TICKET");
        });
    </script>


</body>

</html>