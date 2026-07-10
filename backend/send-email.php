<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 1);




use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {

    $data = json_decode(file_get_contents("php://input"), true);

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $message = trim($data['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        throw new Exception('Preencha todos os campos.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido.');
    }

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_USERNAME'];
    $mail->Password = $_ENV['MAIL_PASSWORD'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) $_ENV['MAIL_PORT'];

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        $_ENV['MAIL_FROM'],
        $_ENV['MAIL_FROM_NAME']
    );

    $mail->addAddress($_ENV['MAIL_TO']);

    $mail->addReplyTo(
        $email,
        $name
    );

    $mail->isHTML(true);

    $mail->Subject = "Novo contato do portfólio";

    $mail->Body = "
        <h2>Novo contato</h2>

        <p><strong>Nome:</strong> {$name}</p>

        <p><strong>E-mail:</strong> {$email}</p>

        <p><strong>Mensagem:</strong></p>

        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $mail->AltBody =
        "Nome: {$name}\n\n" .
        "E-mail: {$email}\n\n" .
        "Mensagem:\n{$message}";

    $mail->send();

    

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}