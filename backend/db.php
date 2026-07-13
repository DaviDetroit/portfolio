<?php

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

// DEBUG TEMPORÁRIO — remover depois
header('Content-Type: text/plain');
echo "DB_HOST: " . var_export($host, true) . "\n";
echo "DB_PORT: " . var_export($port, true) . "\n";
echo "DB_NAME: " . var_export($db, true) . "\n";
echo "DB_USER: " . var_export($user, true) . "\n";
echo "DB_PASSWORD: " . (empty($pass) ? "VAZIA" : "definida (" . strlen($pass) . " caracteres)") . "\n";
echo "Extensão pdo_mysql carregada: " . (extension_loaded('pdo_mysql') ? "SIM" : "NÃO") . "\n";
echo "Drivers PDO disponíveis: " . implode(", ", PDO::getAvailableDrivers()) . "\n";

if (!$host || !$port || !$db || !$user || !$pass) {
    die("\nErro: variáveis de ambiente do banco não estão definidas!");
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "\nConexão bem-sucedida!";
    exit;

} catch (PDOException $e) {
    // DEBUG: mostra o erro real em vez de mensagem genérica
    die("\nErro de conexão real: " . $e->getMessage());
}