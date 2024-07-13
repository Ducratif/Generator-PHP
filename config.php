<?php
// Chemin vers le fichier de configuration
define('CONFIG_FILE', 'config.json');

// Fonction pour vérifier si une configuration existe
function configExists() {
    return file_exists(CONFIG_FILE);
}

// Fonction pour lire la configuration
function readConfig() {
    $configFile = 'config.json';
    if (file_exists($configFile)) {
        $configData = file_get_contents($configFile);
        return json_decode($configData, true);
    }
    return null;
}

function saveConfig($config) {
    $configFile = 'config.json';
    $configData = json_encode($config, JSON_PRETTY_PRINT);
    file_put_contents($configFile, $configData);
    //file_put_contents('config.json', json_encode($config));
}

// Fonction pour écrire la configuration
function writeConfig($data) {
    file_put_contents(CONFIG_FILE, json_encode($data));
}

// Fonction pour supprimer la configuration
function deleteConfig() {
    if (configExists()) {
        unlink(CONFIG_FILE);
    }
}

function loadConfig() {
    if (file_exists('config.json')) {
        $config = json_decode(file_get_contents('config.json'), true);
        return $config;
    }
    return null;
}
?>
