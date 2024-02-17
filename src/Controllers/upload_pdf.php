<?php
// src/Controllers/upload_pdf.php

// Start the session
session_start();
require_once __DIR__ .'/../Models/db_operations.php';

// Check if file is uploaded successfully
if ($_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '/var/www/html/uploads/';
    $uploadFile = $uploadDir . basename($_FILES['pdfFile']['name']);
    $_SESSION['uploaded_file_path'] = $uploadDir . $uploadFile;

    // Move uploaded file to upload directory
    if (move_uploaded_file($_FILES['pdfFile']['tmp_name'], $uploadFile)) {

        // Store uploaded PDF file path in session
        $_SESSION['pdf_file_path'] = $uploadFile;

        // Redirect back to index.php after uploading
        header("Location: ../../index.php?uploaded_file=" . urlencode(basename($_FILES['pdfFile']['name'])));
        exit;
    } else {
        echo "Upload failed.";
    }
} else {
    echo "Upload failed with error code: " . $_FILES['pdfFile']['error'];
}

