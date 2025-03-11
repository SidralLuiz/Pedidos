<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="style-main.css">
    <script>
        function gerarCaixas() {
            // Pega o valor selecionado no select
            const tamanho = document.getElementById("tamanho").value;
            const caixasDiv = document.getElementById("caixas-dinamicas");
            caixasDiv.innerHTML = "";  // Limpa as caixas anteriores

            if (tamanho) {
                // Número de caixas de texto de acordo com o tamanho da pizza
                let quantidadeCaixas = 0;

                if (tamanho === "Média") {
                    quantidadeCaixas = 4;  // 2 caixas para pizza média
                } else if (tamanho === "Grande") {
                    quantidadeCaixas = 6;  // 3 caixas para pizza grande
                } else if (tamanho === "Extragrande") {
                    quantidadeCaixas = 8;  // 4 caixas para pizza extragrande
                }

                // Cria as caixas de texto dinamicamente
                for (let i = 1; i <= quantidadeCaixas; i++) {
                    const caixa = document.createElement("div");
                    caixa.classList.add("caixa-input");

                    const label = document.createElement("label");
                    label.textContent = `Ingrediente ${i}:`;
                    caixa.appendChild(label);

                    const input = document.createElement("input");
                    input.type = "text";
                    input.name = `ingrediente${i}`; // Nome do campo será 'ingrediente1', 'ingrediente2', etc.
                    input.placeholder = `Digite o ingrediente ${i}`;
                    caixa.appendChild(input);

                    caixasDiv.appendChild(caixa);
                }
            }
        }
    </script>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
    <label for="v1">Insira seu nome:</label>
    <input type="text" name="nome" id="nome" required>
    <br>
    <label for="v2">Insira seu telefone:</label>
    <input type="text" name="telefone" id="telefone" required>
    <br>
    
    <label for="tamanho">Escolha o tamanho da pizza:</label>
    <select id="tamanho" name="tamanho" onchange="gerarCaixas()" required>
        <option value="Média">Média</option>
        <option value="Grande">Grande</option>
        <option value="Extragrande">Extragrande</option>
    </select>
    <br>

    <div id="caixas-dinamicas"></div>

    <input type="submit" value="Enviar">
</form>

<?php
    include('connect.php'); // A função de conexão com o banco de dados

    // Verificar se o formulário foi submetido
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Coletar os dados do formulário
        $nome = $_POST['nome'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $tamanho = $_POST['tamanho'] ?? null;

        // Coletar os ingredientes dinamicamente
        $ingredientes = [];
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($_POST["ingrediente$i"])) {
                $ingredientes[] = $_POST["ingrediente$i"]; // Adiciona ao array se o campo não estiver vazio
            }
        }

        // Validar os dados
        if (empty($nome) || empty($telefone) || empty($tamanho) || empty($ingredientes)) {
            echo "Por favor, preencha todos os campos obrigatórios!";
        } else {
            // Conectar ao banco de dados
            $conn = conectarBanco();
            $codigoPedido = 0001;
            $codigoPedido += 1;

            // Transformar o array de ingredientes em uma string separada por vírgulas
            $sabores = implode(", ", $ingredientes);

            // Preparar a consulta SQL
            $stmt = $conn->prepare("INSERT INTO Pedido (codigoPedido, sabores, tamanho) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $codigoPedido, $sabores, $tamanho);

            // Executar a consulta
            if ($stmt->execute()) {
            $msg = "Pedido registrado com sucesso!";
            } else {
            $msg = "Erro ao registrar pedido: . $stmt->error";
            }
        }
    }
?>
<h1><?echo $msg?></h1>
</body>
</html>
