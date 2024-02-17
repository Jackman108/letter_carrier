<?php
//email_bot.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send PDF via Email

function sendPDFviaEmail($recipientEmail, $pdfFilePath): bool {

    // Подключаем библиотеку PHPMailer
    require 'vendor/autoload.php';

    // Создаем экземпляр класса PHPMailer
    $mail = new PHPMailer();

    // Настройки SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Укажите адрес вашего SMTP-сервера
    $mail->SMTPAuth = true;
    $mail->Username = 'dev.js.eugene@gmail.com'; // Укажите вашу электронную почту
    $mail->Password = 'imkj xour sxnv okiq'; // Укажите пароль от вашей почты
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // Порт SMTP

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

