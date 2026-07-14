<?php

declare(strict_types=1);

require __DIR__ . "/db.php";

header("Content-Type: application/json");

$visitorId = $_COOKIE["visitor_id"] ?? "";

if ($visitorId === "") {
    $visitorId = bin2hex(random_bytes(32));

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

$ip = $_SERVER["REMOTE_ADDR"] ?? "";

$userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";

$browser = getBrowser($userAgent);
$os = getOS($userAgent);
$device = getDevice($userAgent);

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
    "success" => true
]);