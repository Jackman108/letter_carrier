<!-- templates/edit_recipients_template.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        #editForm {
            margin-top: 20px;
            display: none;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #005f6b;
        }
    </style>
</head>
<body>
<form action="../../index.php" method="GET">
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
            <td><?php echo $recipientItem['recipient_type']; ?></td>
            <td>
                <button class="edit-btn" data-id="<?php echo $recipientItem['_id']; ?>">
                    <?php echo $recipientItem['recipient_contact']; ?>
                </button>
            </td>
            <td>
                <form class="delete-form" action="handle_recipients.php" method="POST">
                    <input type="hidden" name="deleteRecipient" value="<?php echo $recipientItem['_id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<form action="handle_recipients.php" method="POST">
    <h2>Add Recipient</h2>
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

<form id="editForm" action="handle_recipients.php" method="POST">
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
            const type = row.querySelector('td:nth-child(2)').textContent.trim(); // Удаление лишних пробелов
            const contact = row.querySelector('td:nth-child(3)').textContent.trim(); // Удаление лишних пробелов

            // Fill edit form fields with recipient data
            document.getElementById('editRecipient').value = id;
            document.getElementById('editRecipientType').value = type;
            document.getElementById('editRecipientContact').value = contact;

            // Show the edit form
            document.getElementById('editForm').style.display = 'block';

            // Scroll down to the edit form
            document.getElementById('editForm').scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>



</body>
</html>
