<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

function envValue(string $key): string
{
    $value = $_ENV[$key] ?? getenv($key);
    return $value !== false ? (string) $value : '';
}

try {

    $data = json_decode(file_get_contents('php://input'), true);

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $message = trim($data['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        throw new Exception('Preencha todos os campos.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido.');
    }

    $apiKey = envValue('RESEND_API_KEY');
    $mailFrom = envValue('MAIL_FROM');
    $mailFromName = envValue('MAIL_FROM_NAME');
    $mailTo = envValue('MAIL_TO');

    if ($apiKey === '' || $mailFrom === '' || $mailTo === '') {
        throw new Exception('Configuração de e-mail incompleta no servidor.');
    }

    $htmlBody = "
        <h2>Novo contato pelo portfólio</h2>
        <p><strong>Nome:</strong> {$name}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Mensagem:</strong></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $textBody =
        "Nome: {$name}\n\n" .
        "E-mail: {$email}\n\n" .
        "Mensagem:\n{$message}";

    $payload = [
        'from'     => "{$mailFromName} <{$mailFrom}>",
        'to'       => [$mailTo],
        'reply_to' => $email,
        'subject'  => 'Novo contato do portfólio',
        'html'     => $htmlBody,
        'text'     => $textBody,
    ];

    $ch = curl_init('https://api.resend.com/emails');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT        => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new Exception('Falha na conexão com o serviço de e-mail: ' . $curlError);
    }

    $result = json_decode($response, true);

    if ($httpCode >= 400) {
        $apiMessage = $result['message'] ?? 'Erro desconhecido do serviço de e-mail.';
        throw new Exception($apiMessage);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Mensagem enviada com sucesso!'
    ]);

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}