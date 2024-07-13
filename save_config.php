<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['apikey']) && isset($data['id_discord'])) {
    writeConfig($data);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Données de configuration invalides']);
}
?>
