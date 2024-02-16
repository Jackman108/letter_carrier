<?php
//edit_recipients.php

session_start();
include 'db_operations.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'Edit Recipient'</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
<form action="index.php" method="GET">
    <button type="submit">Вернуться на главную страницу</button>
</form>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($recipients as $recipientItem): ?>
        <tr>
            <td><?php echo $recipientItem['_id']; ?></td>
            <td>
                <?php echo $recipientItem['recipient_type']; ?>
            </td>
            <td>
                <button class="edit-btn" data-id="<?php echo $recipientItem['_id']; ?>">
                    <?php echo $recipientItem['recipient_contact']; ?>
                </button>
            </td>
            <td>
                <form class="delete-form" action="handle_recipients.php" method="POST">
                    <input type="hidden" name="deleteRecipient" value="<?php echo $recipientItem['_id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<form action="handle_recipients.php" method="POST">
    <h2>Add Recipient</h2>
    <!-- Add fields for Recipient's Type and Contact -->
    <label for="recipientType">Recipient's Type:</label><br>
    <select id="recipientType" name="recipientType">
        <option value="email">Email</option>
        <option value="telegram">Telegram</option>
    </select><br>

    <label for="recipientContact">Recipient's Contact:</label><br>
    <input type="text" id="recipientContact" name="recipientContact"><br>
    <!-- Include CSRF token in the form -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit" name="addRecipient">Add Recipient</button>
</form>

<form id="editForm" action="handle_recipients.php" method="POST" style="display: none;">
    <h2>Edit Recipient</h2>
    <input type="hidden" name="editRecipient" id="editRecipient">
    <label for="editRecipientType">Recipient's Type:</label><br>
    <select id="editRecipientType" name="recipientType">
        <option value="email">Email</option>
        <option value="telegram">Telegram</option>
    </select><br>
    <label for="editRecipientContact">Recipient's Contact:</label><br>
    <input type="text" id="editRecipientContact" name="recipientContact"><br>
    <!-- Include CSRF token in the form -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit" name="editRecipientSubmit" id="editRecipientSubmit">Edit Recipient</button>
</form>

<script>
    // Edit button click event listener
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const id = row.querySelector('td:first-child').textContent;
            const type = row.querySelector('td:nth-child(2)').textContent;
            const contact = row.querySelector('td:nth-child(3)').textContent;

            // Fill edit form fields with recipient data
            document.getElementById('editRecipient').value = id;
            document.getElementById('editRecipientType').value = type;
            document.getElementById('editRecipientContact').value = contact;

            // Show the edit form
            document.getElementById('editForm').style.display = 'block';
        });
    });
</script>

</body>
</html>
