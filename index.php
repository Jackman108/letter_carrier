<?php
//index.php
session_start();
require_once  'db_operations.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Sender</title>

</head>
<body>
<h1>Recipient List</h1>
<ul>
    <?php foreach ($recipients as $recipient): ?>
        <li>- <?php echo $recipient['recipient_type']; ?>: <?php echo $recipient['recipient_contact']; ?></li>
    <?php endforeach; ?>
</ul>
<h2>Upload PDF</h2>
<form action="upload_pdf.php" method="POST" enctype="multipart/form-data" target="_self">
    <input type="file" name="pdfFile" required>
    <!-- Include CSRF token in the form -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit">Upload PDF</button>
</form>

<h2>Edit Recipients</h2>
<form action="edit_recipients.php" method="POST">
    <button type="submit">Go to Page for Editing</button>
</form>

<h2>Send PDF to Recipients</h2>
<form action="send_pdf.php" method="POST">
    <!-- Include CSRF token in the form -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit">Send PDF to Recipients</button>
</form>
<iframe src="<?php echo $pdfFilePath; ?>" style="width: 100%; height: 500px;"></iframe>

</body>
</html>
