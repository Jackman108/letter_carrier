<?php
// telegram_webhook.php

// Include necessary files and initialize session if needed
require_once  'db_operations.php';


function handleTelegramWebhook(): void
{
    try {
        $update = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        // Обработка ошибки JSON, если необходимо
    }

    if (isset($update['message'])) {
        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $pdfFilePath = null;

        if (isset($message['document'])) {
            $document = $message['document'];
            $fileId = $document['file_id'];
            $mimeType = $document['mime_type'];

            // Проверяем, является ли файл PDF
            if ($mimeType === 'application/pdf') {
                // Получаем путь к файлу
                $pdfFilePath = getPDFFilePath($fileId);
            } else {
                // Игнорируем файлы, не являющиеся PDF
                return;
            }
        }

        // Сохраняем только путь к PDF файлу в базе данных
        storePDFFilePathInDatabase($chatId, $pdfFilePath);
    }
}

// Define the function to get PDF file path
function getPDFFilePath($fileId)
{
    // Logic to retrieve the file path based on the file ID
    // This could involve querying a database or fetching from a file system
    // For demonstration purposes, let's assume you're storing file paths in a database
    $conn = connectToDatabase();
    $collection = $conn->selectCollection('pdf_files');
    $document = $collection->findOne(['file_id' => $fileId]);
    if ($document) {
        return $document['file_path'];
    } else {
        return null; // Or handle the case where the file path is not found
    }
}

// Функция для сохранения пути к PDF файлу в базе данных
function storePDFFilePathInDatabase($chatId, $pdfFilePath): void
{
    try {
        $conn = connectToDatabase();
        $collection = $conn->selectCollection('telegram_messages');

        $insertOneResult = $collection->insertOne([
            'chat_id' => $chatId,
            'pdf_file_path' => $pdfFilePath,
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($insertOneResult->getInsertedCount() === 1) {
            echo "PDF file path stored successfully.";
        } else {
            echo "Failed to store PDF file path.";
        }
    } catch (RuntimeException $e) {
        echo "Failed to store PDF file path: " . $e->getMessage();
    }
}

// Вызываем функцию обработки вебхука
handleTelegramWebhook();

