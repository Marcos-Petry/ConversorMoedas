<?php

require_once __DIR__ . '/../src/service/awesomeApi/servicoEconomia.php';

use src\service\awesomeApi\ServicoEconomia;

$servicoEconomia = new ServicoEconomia();
$moedas = $servicoEconomia->consultarMoedas(); // Obtém a lista de moedas
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moedas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Conversor de Moedas</h1>
    </header>

    <main>
        <div class="converter">
            <form id="conversorMoeda" action="../src/controller/controllerEconomia.php" method="POST">
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" placeholder="Digite o valor" required>

                <div class="campoMoeda">
                    <div class="opcaoMoeda">
                        <label for="deMoeda1">De:</label>
                        <select id="deMoeda1" name="deMoeda1">
                            <option value="" disabled selected>Selecione...</option>
                            <?php
                            foreach ($moedas as $codigo => $nome) {
                                echo "<option value=\"$codigo\">$codigo - $nome</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="opcaoMoeda">
                        <label for="paraMoeda2">Para:</label>
                        <select id="paraMoeda2" name="paraMoeda2">
                            <option value="" disabled selected>Selecione...</option>
                            <?php
                            foreach ($moedas as $codigo => $nome) {
                                echo "<option value=\"$codigo\">$codigo - $nome</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <button type="submit">Converter</button>
            </form>

            <div id="result">
                <!-- O resultado pós processamento será apresentado aqui-->
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; Conversor de Moedas</p>
        <p>By <a href="https://github.com/Marcos-Petry" target="_blank" class="link-github">Marcos Petry</a></p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const valorInput = document.getElementById('valor');

            valorInput.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/\D/g, '');
                value = (value / 100).toFixed(2).replace('.', ','); // Formata o valor como decimal
                value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'); // Adiciona pontos a cada 3 dígitos

                e.target.value = value ? value : '';
            });

            document.getElementById('conversorMoeda').addEventListener('submit', function(event) {
                event.preventDefault(); // Impede o envio padrão do formulário

                const formData = new FormData(this); // Captura os dados do formulário

                console.log('Formulário enviado!'); // Para verificar se o evento é chamado

                fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Se não for 200, tenta ler a resposta como JSON
                            return response.json().then(err => {
                                throw new Error(err.error || 'Erro desconhecido');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data); // Log da resposta
                        document.getElementById('result').innerHTML = `Valor Convertido em ${data.valorConvertido}`;
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        // Apresenta apenas a mensagem de erro
                        document.getElementById('result').innerHTML = error.message;
                    });
            });

        });
    </script>
</body>

</html>