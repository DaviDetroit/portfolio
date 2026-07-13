<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM visits
");

$total = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "views" => (int) $total["total"]
]);