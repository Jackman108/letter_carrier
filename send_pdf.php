<?php
//send_pdf.php
include 'db_operations.php';
include 'telegram_bot.php';
include 'email_bot.php';

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
    header("Location: index.php");
    exit;
}

// Loop through each recipient and send the PDF
foreach ($recipients as $recipient) {
    $recipientType = $recipient['recipient_type'];
    $recipientContact = $recipient['recipient_contact'];

    if ($recipientType === 'email') {
        // Send PDF via Email
        sendPDFviaEmail($recipientContact, $pdfFilePath);
    } elseif ($recipientType === 'telegram') {
        // Send PDF via Telegram
        sendPDFviaTelegram($recipientContact, $pdfFilePath);
    }
}

// Redirect back to index.php after sending
$_SESSION['notification'] = ['type' => 'success', 'message' => 'PDF sent successfully to recipients.'];
header("Location: index.php");
exit;
