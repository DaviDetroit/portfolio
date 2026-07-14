<?php

declare(strict_types=1);

require __DIR__ . "/db.php";

header("Content-Type: application/json");

// Tempo mínimo (em segundos) entre duas contagens do mesmo visitante
// 1800 = 30 minutos. Ajusta pro valor que fizer sentido pra ti.
const COOLDOWN_SECONDS = 1800;

$userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";

// Ignora bots, health checks e crawlers de link preview
if (isBot($userAgent)) {
    echo json_encode([
        "success" => true,
        "counted" => false,
        "reason" => "bot"
    ]);
    exit;
}

$visitorId = $_COOKIE["visitor_id"] ?? "";
$isNewVisitor = false;

if ($visitorId === "") {
    $visitorId = bin2hex(random_bytes(32));
    $isNewVisitor = true;

    setcookie(
        "visitor_id",
        $visitorId,
        time() + (86400 * 365),
        "/",
        "",
        true,
        true
    );
}

$page = $_SERVER["HTTP_REFERER"] ?? "home";
$ip = getRealIp();

$browser = getBrowser($userAgent);
$os = getOS($userAgent);
$device = getDevice($userAgent);

// Se não é visitante novo, verifica se já passou o cooldown
$shouldCount = true;

if (!$isNewVisitor) {
    $stmt = $pdo->prepare("
        SELECT created_at
        FROM visits
        WHERE visitor_id = ?
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$visitorId]);
    $lastVisit = $stmt->fetchColumn();

    if ($lastVisit !== false) {
        $lastVisitTime = strtotime($lastVisit);
        $secondsSinceLastVisit = time() - $lastVisitTime;

        if ($secondsSinceLastVisit < COOLDOWN_SECONDS) {
            $shouldCount = false;
        }
    }
}

if ($shouldCount) {
    $stmt = $pdo->prepare("
        INSERT INTO visits
        (
            visitor_id,
            ip,
            browser,
            os,
            device,
            page
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $visitorId,
        $ip,
        $browser,
        $os,
        $device,
        $page
    ]);
}

function getRealIp(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]); 
    }

    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function isBot(string $userAgent): bool
{
    if ($userAgent === '') {
        return true;
    }

    $botSignatures = [
        'bot',
        'crawl',
        'spider',
        'headless',
        'facebookexternalhit',
        'WhatsApp',
        'Discordbot',
        'Slackbot',
        'TelegramBot',
        'Preview',
        'curl',
        'python-requests',
        'axios',
        'Go-http-client'
    ];

    foreach ($botSignatures as $signature) {
        if (stripos($userAgent, $signature) !== false) {
            return true;
        }
    }

    return false;
}

function getBrowser(string $userAgent): string
{
    if (str_contains($userAgent, 'Edg')) return 'Edge';
    if (str_contains($userAgent, 'OPR') || str_contains($userAgent, 'Opera')) return 'Opera';
    if (str_contains($userAgent, 'Chrome')) return 'Chrome';
    if (str_contains($userAgent, 'Firefox')) return 'Firefox';
    if (str_contains($userAgent, 'Safari')) return 'Safari';

    return 'Outro';
}

function getOS(string $userAgent): string
{
    if (str_contains($userAgent, 'Windows')) return 'Windows';
    if (str_contains($userAgent, 'Android')) return 'Android';
    if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) return 'iOS';
    if (str_contains($userAgent, 'Mac')) return 'macOS';
    if (str_contains($userAgent, 'Linux')) return 'Linux';

    return 'Outro';
}

function getDevice(string $userAgent): string
{
    if (preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent)) {
        return 'Mobile';
    }

    return 'Desktop';
}

echo json_encode([
    "success" => true,
    "counted" => $shouldCount
]);