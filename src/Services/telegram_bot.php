<?php
//src/Services/telegram_bot.php

require_once __DIR__ .'/../Controllers/telegram_webhook.php';

// Function to send PDF via Telegram
function sendPDFviaTelegram($chatId, $pdfFilePath): bool|string
{
    $botToken = '6908090266:AAG-zOVOFUSGd3et8Sbi8ByNohmZCjiySW8';
    $url = "https://api.telegram.org/bot$botToken/sendDocument";

    $postData = array(
        'chat_id' => $chatId,
        'caption' => 'Проверка работы',
        'document' => new CURLFile(realpath($pdfFilePath))
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

