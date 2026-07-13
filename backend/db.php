<?php

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');



if (!$host || !$port || !$db || !$user || !$pass) {
    die("Erro: variáveis de ambiente do banco não estão definidas!");
}

try {
    // Criando conexão PDO
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Mensagem de erro caso a conexão falhe
    die("Erro de conexão. Tente novamente mais tarde.");
}
