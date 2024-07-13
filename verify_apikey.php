<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$apiKey = $data['apikey'];

if (empty($apiKey)) {
    echo json_encode(['error' => 'API Key is missing']);
    exit;
}

$url = "https://api.ducragen.com/verify.php/?apikey=$apiKey";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'Curl error: ' . $curl_error]);
    exit;
}

if ($http_status !== 200) {
    echo json_encode(['error' => 'HTTP error: ' . $http_status]);
    exit;
}

$responseData = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON decode error: ' . json_last_error_msg()]);
    exit;
}

//if ($responseData['error']) {
//    echo json_encode(['error' => 'ERREUR, TON API EXISTE PAS !']);
//    exit;
//}

if ($responseData['success']) {
    include 'config.php';
    $config = ['apikey' => $apiKey];
    saveConfig($config);
}

echo json_encode($responseData);
?>
