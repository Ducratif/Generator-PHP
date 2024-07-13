<?php
require 'config.php';

function makeApiRequest($url) {
    $response = file_get_contents($url);
    return json_decode($response, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $apiKey = $_POST['apikey'];

    if ($action === 'fetchServices') {
        $url = "https://api.ducragen.com/services.php/?apikey=" . urlencode($apiKey);
        $result = makeApiRequest($url);
        echo json_encode($result);
    } elseif ($action === 'generateAccount') {
        $plan = $_POST['plangen'];
        $service = $_POST['service'];
        $url = "https://api.ducragen.com/generate.php?apikey=" . urlencode($apiKey) . "&plangen=" . urlencode($plan) . "&service=" . urlencode($service);
        $result = makeApiRequest($url);
        echo json_encode($result);
    }
}
?>
