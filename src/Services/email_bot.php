<?php
//src/Services/email_bot.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require_once '../../config/config.php';

// Function to send PDF via Email
function sendPDFviaEmail($recipientEmail, $pdfFilePath): bool {
    global $config;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $config['smtpHost'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtpUsername'];
    $mail->Password = $config['smtpPassword'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['smtpPort'];


    // Устанавливаем отправителя и получателя
    $mail->setFrom ('dev.js.eugene@gmail.com', 'Eugene'); // Укажите ваше имя и адрес электронной почты
    $mail->addAddress($recipientEmail); // Адрес получателя

    // Добавляем вложение (PDF файл)
    $mail->addAttachment($pdfFilePath);

    // Устанавливаем тему письма
    $mail->Subject = 'PDF Attachment';

    // Устанавливаем содержимое письма (необязательно)
    $mail->Body = 'Please find attached PDF file.';

    // Отправляем письмо
    if ($mail->send()) {
        return true; // Письмо успешно отправлено
    }

    return false; // Ошибка при отправке письма
}

