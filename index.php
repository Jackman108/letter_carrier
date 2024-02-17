<?php
//index.php
session_start();
require_once __DIR__ .'/src/Models/db_operations.php';

// Generate CSRF token and store it in the session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Connect to the database
$conn = connectToDatabase();

// Retrieve recipients from the database
$recipients = getRecipientsFromDatabase($conn);

// Check if a file was uploaded
$uploadedFile = $_GET['uploaded_file'] ?? '';

$pdfFilePath = isset($uploadedFile) ? '/uploads/' . $uploadedFile : '';

// Check if recipient type is set before accessing it
$recipientType = $recipient['recipient_type'] ?? '';

// Check if recipient contact is set before accessing it
$recipientContact = $recipient['recipient_contact'] ?? '';
// Check if notification is set and display it
if (isset($_SESSION['notification'])) {
    $notificationType = $_SESSION['notification']['type'];
    $notificationMessage = $_SESSION['notification']['message'];

    // Output the notification message with appropriate styling based on its type
    if ($notificationType === 'success') {
        $notificationStyle = 'background-color: #d4edda; color: #155724; padding: 10px;';
    } elseif ($notificationType === 'error') {
        $notificationStyle = 'background-color: #f8d7da; color: #721c24; padding: 10px;';
    }

    echo "<div style='$notificationStyle'>$notificationMessage</div>";

    // Unset the notification after displaying it
    unset($_SESSION['notification']);
}
include 'templates/index_template.php';
