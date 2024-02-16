<?php
//index.php
session_start();
include 'db_operations.php';

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
