<?php
include 'config.php';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si la configuration existe
$config = readConfig();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration</title>
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
        input, button {
            padding: 10px;
            margin: 10px;
            border: none;
            background: #333;
            color: #00ff00;
            font-size: 16px;
        }
        #error-message {
            color: red;
            margin-top: 20px;
        }
        button {
            display: inline-block;
            background-color: #333;
            color: #00ff00;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #555;
        }

        .button2 {
            display: inline-block;
            background-color: #555;
            color: #00ff00;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
        }
        .button2:hover {
            background-color: #777;
        }
    </style>
    <script>
        function showError(message) {
            const errorMessageDiv = document.getElementById('error-message');
            errorMessageDiv.style.display = 'block';
            errorMessageDiv.textContent = message;
        }

        async function verifyApiKey() {
            const apiKey = document.getElementById('apikey').value;
            console.log('API Key:', apiKey);  // Debug: Log API Key
            try {
                const response = await fetch('verify_apikey.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ apikey: apiKey })
                });
                console.log('Response Status:', response.status);  // Debug: Log response status

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log('Response Data:', data);  // Debug: Log response data

                if (data.success) {
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                    document.getElementById('apikeyHidden').value = apiKey;
                }
                else
                if (data.error){
                    showError(data.error || 'Votre apikey n\'est pas valide');
                }
                else {
                    showError(data.error || 'Votre apikey n\'est pas valide');
                }
            } catch (error) {
                console.log('Error:', error);  // Debug: Log error
                //showError('Erreur lors de la vérification de l\'API Key : ' + error.message);
                showError('Erreur lors de la vérification de l\'API Key, API peux être non valide.');
            }
        }

        async function verifyDiscordId() {
            const discordId = document.getElementById('id_discord').value;
            const apiKey = document.getElementById('apikeyHidden').value;
            console.log('Discord ID:', discordId);  // Debug: Log Discord ID
            try {
                const response = await fetch('check_discordid.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id_discord: discordId, apikey: apiKey })
                });
                console.log('Response Status:', response.status);  // Debug: Log response status

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log('Response Data:', data);  // Debug: Log response data

                if (data.success && data.message === "The apikey is not banned") {
                    document.getElementById('step2').style.display = 'none';
                    document.getElementById('completed').style.display = 'block';
                } else {
                    showError(data.error || 'Erreur inconnue');
                }
            } catch (error) {
                console.log('Error:', error);  // Debug: Log error
                //showError('Erreur lors de la vérification de l\'ID Discord : ' + error.message);
                showError('Erreur lors de la vérification de l\'ID Discord');
            }
        }

        async function deleteConfig() {
            try {
                await fetch('delete_config.php');
                window.location.href = 'index.php';
            } catch (error) {
                console.log('Error:', error);  // Debug: Log error
                showError('Erreur lors de la suppression de la configuration : ' + error.message);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php if ($config): ?>
            <p>La configuration est déjà faite :</p>
            <p>API Key : <?= htmlspecialchars($config['apikey']) ?></p>
            <p>ID Discord : <?= htmlspecialchars($config['id_discord'] ?? 'Non configuré') ?></p>
            <button onclick="deleteConfig()">Supprimer la configuration</button>
        <?php else: ?>
            <div id="step1">
                <h2>Configurer l'API Key</h2>
                <input type="text" id="apikey" placeholder="API Key">
                <button type="button" onclick="verifyApiKey()">Valider</button>
            </div>
            <div id="step2" style="display:none;">
                <h2>Configurer l'ID Discord</h2>
                <input type="text" id="id_discord" placeholder="ID Discord">
                <input type="hidden" id="apikeyHidden">
                <button type="button" onclick="verifyDiscordId()">Valider</button>
            </div>
            <div id="completed" style="display:none;">
                <p>Configuration terminée. Supprimez ce fichier pour éviter toute nouvelle configuration mal intentionnée.</p>
                <button type="button" onclick="deleteConfig()">Supprimer la configuration</button>
                <br>
                <a href="./dashboard.php" class="button2">Aller au Dashboard</a>
            </div>
            <div id="error-message" style="display:none;"></div>
        <?php endif; ?>
    </div>
</body>
</html>
