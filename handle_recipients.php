<?php
// handle_recipients.php

session_start();
require_once 'db_operations.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF токена
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Установка соединения с базой данных
    $conn = connectToDatabase();

    // Добавление, редактирование или удаление получателей
    if (isset($_POST['addRecipient'])) {
        // Добавление нового получателя
        $recipientType = $_POST['recipientType'];
        $recipientContact = $_POST['recipientContact'];

        // Проверка наличия необходимых данных
        if (!empty($recipientType) && !empty($recipientContact)) {
            $result = addRecipientToDatabase($conn, $recipientType, $recipientContact);
            if ($result) {
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Recipient added successfully'];
            } else {
                $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to add recipient'];
            }
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Recipient type or contact is empty'];
        }
    } elseif (isset($_POST['editRecipientSubmit'])) {
        // Редактирование существующего получателя
        $recipientId = $_POST['editRecipient'];
        $recipientType = $_POST['recipientType'];
        $recipientContact = $_POST['recipientContact'];

        // Проверка наличия необходимых данных
        if (!empty($recipientId) && !empty($recipientType) && !empty($recipientContact)) {
            // Редактирование получателя
            editRecipientInDatabase($conn, $recipientId, $recipientType, $recipientContact);
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Recipient edited successfully'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Recipient ID, type, or contact is empty'];
        }
    } elseif (isset($_POST['deleteRecipient'])) {
        // Удаление получателя
        $recipientId = $_POST['deleteRecipient'];
        $success = deleteRecipientFromDatabase($conn, $recipientId);
        if ($success) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Recipient deleted successfully'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to delete recipient'];
        }
    }

    // Перенаправление обратно на edit_recipients.php после обработки запроса
    header("Location: edit_recipients.php");
    exit;
} else {
    die("Invalid request.");
}

