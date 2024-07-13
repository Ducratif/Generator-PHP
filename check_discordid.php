<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$idDiscord = $data['id_discord'];

$url = "https://api.ducragen.com/check_apikey.php/?id_discord=$idDiscord";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'Erreur lors de la requête à l\'API']);
    exit;
}

$responseData = json_decode($response, true);

if ($responseData['success'] && $responseData['message'] === "The apikey is not banned") {
    include 'config.php';
    $config = readConfig();
    $config['id_discord'] = $idDiscord;
    saveConfig($config);
}

echo $response;
?>
