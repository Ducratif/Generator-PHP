<?php
require 'config.php';

$config = loadConfig();
if ($config === null) {
    header('Location: not_config.html');
    exit;
}

$apiKey = $config['apikey'];
$idDiscord = $config['id_discord'];

$url = "https://api.ducragen.com/verify.php/?apikey=" . urlencode($apiKey);
$response = file_get_contents($url);
$responseData = json_decode($response, true);

$coins = $responseData['coins'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            background: #0d0d0d;
            color: #00ff00;
            font-family: 'Courier New', Courier, monospace;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding-top: 50px;
            text-align: center;
        }
        .section {
            background: #333;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
        }
        .button {
            background-color: #333;
            color: #00ff00;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
        }
        .button:hover {
            background-color: #555;
        }
        select {
            background-color: #333;
            color: #00ff00;
            border: 1px solid #555;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            margin: 5px 0;
            width: 100%;
        }
        option {
            background-color: #333;
            color: #00ff00;
        }
        #result {
            margin-top: 20px;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        #debug {
            margin-top: 20px;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section">
            <h2>Coins</h2>
            <p><?php echo $coins; ?></p>
        </div>
        <div class="section">
            <h2>Génération de compte</h2>
            <form method="POST" id="generateForm">
                <label for="plangen">Plan :</label>
                <select id="plangen" name="plangen">
                    <option value="Free">Free</option>
                    <option value="Basique">Basique</option>
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                </select>
                <button type="button" id="validatePlan" class="button">Valider le plan</button>
                <br>
                <label for="service">Service :</label>
                <select id="service" name="service">
                    <option value="">Sélectionnez un plan d'abord</option>
                </select>
                <br>
                <button type="submit" class="button">Valider</button>
            </form>
            <div id="result">
                <p id="resultMessage"></p>
                <button id="hideResult" class="button">Masquer le résultat</button>
            </div>
        </div>
        <div id="debug">
        <a href="./dashboard.php" class="button">Retour au Dashboard</a>
        </div>
    </div>

    <script>
        function displayDebugMessage(message) {
            const debugDiv = document.getElementById('debug');
            debugDiv.textContent = message;
        }

        document.getElementById('validatePlan').addEventListener('click', function() {
            //displayDebugMessage('Button "Valider le plan" clicked');
            const apiKey = '<?php echo $apiKey; ?>';
            const plan = document.getElementById('plangen').value;
            //displayDebugMessage(`Selected plan: ${plan}`);

            const formData = new FormData();
            formData.append('action', 'fetchServices');
            formData.append('apikey', apiKey);

            fetch('api_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                //displayDebugMessage('Services fetched');
                const serviceSelect = document.getElementById('service');
                serviceSelect.innerHTML = '';
                if (data.success && data[plan]) {
                    data[plan].forEach(service => {
                        const option = document.createElement('option');
                        option.value = service;
                        option.textContent = service;
                        serviceSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Aucun service disponible';
                    serviceSelect.appendChild(option);
                }
            })
            .catch(error => {
                //displayDebugMessage(`Error: ${error}`);
            });
        });

        document.getElementById('generateForm').addEventListener('submit', function(event) {
            event.preventDefault();
            //displayDebugMessage('Button "Valider" clicked');
            const apiKey = '<?php echo $apiKey; ?>';
            const plan = document.getElementById('plangen').value;
            const service = document.getElementById('service').value;

            const formData = new FormData();
            formData.append('action', 'generateAccount');
            formData.append('apikey', apiKey);
            formData.append('plangen', plan);
            formData.append('service', service);

            fetch('api_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const result = document.getElementById('result');
                const resultMessage = document.getElementById('resultMessage');
                if (data.success) {
                    resultMessage.innerHTML = `Email: ${data.email}<br>Password: ${data.password}<br>Coins retirés: ${data.coins}`;
                    result.style.display = 'block';
                } else {
                    resultMessage.textContent = `Erreur: ${data.error}`;
                    result.style.display = 'block';
                }
            })
            .catch(error => {
                //displayDebugMessage(`Error: ${error}`);
            });
        });

        document.getElementById('hideResult').addEventListener('click', function() {
            document.getElementById('result').style.display = 'none';
        });
    </script>
</body
