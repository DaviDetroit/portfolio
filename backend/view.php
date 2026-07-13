<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';

session_start();

header('Content-Type: application/json');

try {

    $sessionId = session_id();

    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ipHash = hash('sha256', $ip);

    $page = $_SERVER['HTTP_REFERER'] ?? '/';

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (stripos($userAgent, 'OPR') !== false || stripos($userAgent, 'Opera') !== false) {
        $browser = 'Opera';
    } elseif (stripos($userAgent, 'Edg') !== false) {
        $browser = 'Edge';
    } elseif (stripos($userAgent, 'Firefox') !== false) {
        $browser = 'Firefox';
    } elseif (stripos($userAgent, 'Chrome') !== false) {
        $browser = 'Chrome';
    } elseif (stripos($userAgent, 'Safari') !== false) {
        $browser = 'Safari';
    } else {
        $browser = 'Unknown';
    }

    // Sistema Operacional
    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Android') !== false) {
        $os = 'Android';
    } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
        $os = 'iOS';
    } elseif (stripos($userAgent, 'Mac') !== false) {
        $os = 'macOS';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        $os = 'Linux';
    } else {
        $os = 'Unknown';
    }

    if (preg_match('/mobile/i', $userAgent)) {
        $device = 'Mobile';
    } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
        $device = 'Tablet';
    } else {
        $device = 'Desktop';
    }

    if (!isset($_COOKIE['portfolio_visit'])) {
        setcookie(
            'portfolio_visit',
            '1',
            time() + 86400,
            '/'
        );

        $stmt = $pdo->prepare("
            INSERT INTO visits (
                session_id,
                ip_hash,
                page,
                browser,
                os,
                device
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $sessionId,
            $ipHash,
            $page,
            $browser,
            $os,
            $device
        ]);
    }

    echo json_encode([
        "success" => true
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}