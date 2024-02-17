<?php
//src/Controllers/edit_recipients.php

session_start();
require_once __DIR__ . '/../Models/db_operations.php';

// Connect to the database
$conn = connectToDatabase();
$recipients = getRecipientsFromDatabase($conn);

// Check if we are editing an existing recipient
$editingRecipient = isset($_POST['editRecipient']);

// Initialize variables
$recipientType = '';
$recipientContact = '';
$recipientId = '';

// If editing, populate variables with existing recipient data
if ($editingRecipient) {
    $recipientId = $_POST['editRecipient'];
    $recipient = getRecipientById($conn, $recipientId);

    // Check if recipient exists and keys are set before accessing them
    if (isset($recipient['recipient_type'], $recipient['recipient_contact']) && $recipient) {
        $recipientType = $recipient['recipient_type'];
        $recipientContact = $recipient['recipient_contact'];
    } else {
        // Сообщаем пользователю, что получатель не найден и предлагаем вернуться на страницу редактирования
        $_SESSION['notification'] = ['type' => 'error', 'message' => 'Recipient not found.'];
        header("Location: edit_recipients.php");
        exit;
    }
}
// Retrieve recipients from the database
$recipients = getRecipientsFromDatabase($conn);
include '../../templates/edit_recipients_template.php';

