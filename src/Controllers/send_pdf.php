<?php
//src/Controllers/send_pdf.php
require_once __DIR__ .'/../Models/db_operations.php';
require_once __DIR__ .'/../Services/telegram_bot.php';
require_once __DIR__ .'/../Services/email_bot.php';

// Connect to the database
$conn = connectToDatabase();

// Retrieve recipients from the database
$recipients = getRecipientsFromDatabase($conn);

// Start the session
session_start();

// Retrieve PDF file path from session
$pdfFilePath = $_SESSION['pdf_file_path'] ?? '';

// Check if PDF file path exists
if (!$pdfFilePath || !file_exists($pdfFilePath)) {
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'PDF file not found.'];
    header("Location: ../../index.php");
    exit;
}

// Counter for successful sends
$successfulSends = 0;

// Loop through each recipient and send the PDF
foreach ($recipients as $recipient) {
    $recipientType = $recipient['recipient_type'];
    $recipientContact = $recipient['recipient_contact'];

    if ($recipientType === 'email') {
        // Send PDF via Email
        if (sendPDFviaEmail($recipientContact, $pdfFilePath)) {
            $successfulSends++; // Increment the counter if send was successful
        }
    } elseif ($recipientType === 'telegram') {
        // Send PDF via Telegram
        if (sendPDFviaTelegram($recipientContact, $pdfFilePath)) {
            $successfulSends++; // Increment the counter if send was successful
        }
    }
}

// Set notification message based on successful sends
if ($successfulSends == count($recipients)) {
    $_SESSION['notification'] = ['type' => 'success', 'message' => 'PDF sent successfully to all recipients.'];
} else {
    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to send PDF to some recipients.'];
}

// Redirect back to index.php after sending
header("Location: ../../index.php");
exit;
