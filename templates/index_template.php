<!-- templates/index_template.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Sender</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            height: auto;
        }
        .container {
            display: flex;
            max-width: 1000px;
            min-height: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .left-column {
            flex: 1;
            padding-right: 20px;
        }
        .right-column {
            flex: 1;
            padding-left: 20px;
            border-left: 1px solid #ccc;
        }
        h1, h2 {
            color: #333;
            margin-bottom: 8px;
        }
        ul {
            padding: 0;
            margin: 0;
        }
        li {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        input[type="file"] {
            margin-bottom: 10px;
            width: 100%;
        }
        iframe {
            border: 1px solid #ccc;
            margin-top: 20px;
            width: 100%;
            min-height: 500px; /* Set a minimum height for better visibility */
        }
        .error-message {
            color: #ff0000;
        }
        .button {
            display: block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .pdf-container {
            width: 100%;
            height: 250px;
            margin-top: 20px;
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-column">
        <h1>Recipient List</h1>
        <?php if (!empty($recipients)): ?>
            <table>
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Contact</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recipients as $recipient): ?>
                    <tr>
                        <td><?php echo $recipient['recipient_type']; ?></td>
                        <td><?php echo $recipient['recipient_contact']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error-message">No recipients available.</p>
        <?php endif; ?>
    </div>
    <div class="right-column">
        <h3>Upload PDF</h3>
        <form action="../src/Controllers/upload_pdf.php" method="POST" enctype="multipart/form-data" target="_self">
            <input type="file" name="pdfFile" required>
            <!-- Include CSRF token in the form -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="button">Upload PDF</button>
        </form>

        <h3>Edit Recipients</h3>
        <form action="../src/Controllers/edit_recipients.php" method="POST">
            <button type="submit" class="button">Go to Page for Editing</button>
        </form>

        <h3>Send PDF to Recipients</h3>
        <form action="../src/Controllers/send_pdf.php" method="POST">
            <!-- Include CSRF token in the form -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="button">Send PDF to Recipients</button>
        </form>
        <?php if (isset($pdfFilePath) && $pdfFilePath !== ""): ?>
            <div class="pdf-container">
                <iframe src="<?php echo $pdfFilePath; ?>" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        <?php else: ?>
            <p>Загрузите свой PDF файл</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
