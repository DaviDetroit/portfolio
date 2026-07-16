<?php

declare(strict_types=1);

require __DIR__ . "/db.php";

header("Content-Type: application/json");


const COOLDOWN_SECONDS = 1800;

$userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";

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


$page = $_POST["page"] ?? $_GET["page"] ?? "home";
$referer = $_SERVER["HTTP_REFERER"] ?? null;
$ip = getRealIp();

$browser = getBrowser($userAgent);
$os = getOS($userAgent);
$device = getDevice($userAgent);

$shouldCount = true;

if (!$isNewVisitor) {
    $stmt = $pdo->prepare("
        SELECT TIMESTAMPDIFF(SECOND, visited_at, NOW()) AS seconds_ago
        FROM visits
        WHERE visitor_id = ?
        ORDER BY visited_at DESC
        LIMIT 1
    ");
    $stmt->execute([$visitorId]);
    $secondsSinceLastVisit = $stmt->fetchColumn();

    if ($secondsSinceLastVisit !== false && (int)$secondsSinceLastVisit < COOLDOWN_SECONDS) {
        $shouldCount = false;
    }
}

if ($shouldCount) {
    $location = getLocationByIp($ip);

    $stmt = $pdo->prepare("
        INSERT INTO visits
        (
            visitor_id,
            ip,
            country,
            city,
            browser,
            os,
            device,
            page,
            referer
        )
        VALUES
        (
            ?,
            ?,
            ?,
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
        $location["country"],
        $location["city"],
        $browser,
        $os,
        $device,
        $page,
        $referer
    ]);
}

function getLocationByIp(string $ip): array
{
    $empty = ["country" => null, "city" => null];

    if ($ip === '' || isPrivateIp($ip)) {
        return $empty;
    }

    $context = stream_context_create([
        "http" => ["timeout" => 2] 
    ]);

    $response = @file_get_contents(
        "https://ipwho.is/{$ip}",
        false,
        $context
    );

    if ($response === false) {
        return $empty;
    }

    $data = json_decode($response, true);

    if (!is_array($data) || empty($data["success"])) {
        return $empty;
    }

    return [
        "country" => $data["country"] ?? null,
        "city" => $data["city"] ?? null
    ];
}

function isPrivateIp(string $ip): bool
{
    return filter_var(
        $ip,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    ) === false;
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