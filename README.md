# PDF Sender

PDF Sender is a simple web application that allows users to upload PDF files and send them to recipients via email or Telegram. It uses PHP for server-side scripting and MongoDB as the database to store recipient information.

## Features

- Upload PDF files
- Send PDF files to recipients via email or Telegram
- Add, edit, and delete recipients
- CSRF protection
- Notification system for user feedback

## Prerequisites

Before running the application, ensure you have the following installed:

- PHP
- MongoDB
- Composer

## Installation

1. Clone the repository:


git clone Jackman108/letter_carrier


2. Install dependencies:

composer install


3. Configure your SMTP settings in `config.php`:

```php
$config = [
    'smtpHost' => 'your-smtp-host',
    'smtpUsername' => 'your-smtp-username',
    'smtpPassword' => 'your-smtp-password',
    'smtpPort' => 587,
];

Run the PHP server:

php -S localhost:9000
Access the application in your web browser at http://localhost:9000.
