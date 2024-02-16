<?php
// telegram_webhook.php

// Include necessary files and initialize session if needed
include 'db_operations.php';

// Function to handle incoming messages from Telegram webhook
function handleTelegramWebhook(): void
{
    // Get the incoming message content
    $update = json_decode(file_get_contents('php://input'), true);

    if (isset($update['message'])) {
        // Handle incoming message
        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'];
        $pdfFilePath = null;

        if (isset($message['document'])) {
            // If the message contains a document, store the file path
            $document = $message['document'];
            $fileId = $document['file_id'];

            // Store the message in the database
            storeMessageInDatabase($chatId, $text, $pdfFilePath);
        }
    }
}

// Function to store messages in the database
function storeMessageInDatabase($chatId, $text, $pdfFilePath): void
{
    try {
        $conn = connectToDatabase();
        $collection = $conn->selectCollection('telegram_messages');

        $insertOneResult = $collection->insertOne([
            'chat_id' => $chatId,
            'text' => $text,
            'pdf_file_path' => $pdfFilePath,
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($insertOneResult->getInsertedCount() === 1) {
            echo "Message and PDF file stored successfully.";
        } else {
            echo "Failed to store message and PDF file.";
        }
    } catch (RuntimeException $e) {
        echo "Failed to store message and PDF file: " . $e->getMessage();
    }
}

// Handle incoming messages from Telegram webhook
handleTelegramWebhook();
?>
