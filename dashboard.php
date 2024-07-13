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
        .section {
            background: #333;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
        }
        .section h2 {
            margin-bottom: 10px;
        }
        .button1 {
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
        .button1:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profil</h1>
        <div class="section">
            <h2>ID Discord</h2>
            <p><?php echo $idDiscord; ?></p>
        </div>
        <div class="section">
            <h2>Coins</h2>
            <p><?php echo $coins; ?></p>
        </div>
        <div class="section">
            <h2>Génération de compte</h2>
            <a href="gen.php" class="button1">Generation</a>
        </div>
        <div class="section">
            <h2>ATTENTION SOLDE</h2>
            <p>
                Lorsque vous effectuez cette requête, cela vous déduit votre solde de coins.
                Voici le tableau de solde déduit:
                <br>
                Free = 4 coins retirés<br>
                Basique = 3 coins retirés<br>
                Standard = 2 coins retirés<br>
                Premium = 1 coin retiré<br>
            </p>
        </div>
        </body>
        </html>
