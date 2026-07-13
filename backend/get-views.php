<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT total
    FROM site_views
    WHERE id = 1
");

$views = $stmt->fetch();

echo json_encode([
    "views" => (int)$views["total"]
]);