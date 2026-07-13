<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';

header('Content-Type: application/json');

$cookie = "portfolio_view";

if (!isset($_COOKIE[$cookie])) {

    $pdo->exec("
        UPDATE site_views
        SET total = total + 1
        WHERE id = 1
    ");

    setcookie(
        $cookie,
        "1",
        time() + 86400,
        "/",
        "",
        true,
        true
    );
}

echo json_encode([
    "success" => true
]);