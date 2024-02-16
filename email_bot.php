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
    $mail->Host = 'smtp.example.com'; // Укажите адрес вашего SMTP-сервера
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com'; // Укажите вашу электронную почту
    $mail->Password = 'your_password'; // Укажите пароль от вашей почты
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // Порт SMTP

    // Устанавливаем отправителя и получателя
    $mail->setFrom('your_email@example.com', 'Your Name'); // Укажите ваше имя и адрес электронной почты
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
    } else {
        return false; // Ошибка при отправке письма
    }
}
?>
