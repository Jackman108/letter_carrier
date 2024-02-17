<?php
// db_operations.php

require 'vendor/autoload.php';
require 'db_config.php';

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Driver\Exception\RuntimeException;

function connectToDatabase(): Database
{
    global $mongoHost, $mongoUsername, $mongoPassword, $mongoDatabase;

    // Establish MongoDB connection
    $mongoClient = new Client("mongodb://$mongoHost", [
        'username' => $mongoUsername,
        'password' => $mongoPassword,
        'db' => $mongoDatabase
    ]);

    return $mongoClient->selectDatabase($mongoDatabase);
}

function getRecipientsFromDatabase(Database $conn): array
{
    $collection = $conn->selectCollection('pdf_sender', (array)'recipients');

    if ($collection instanceof Collection) {
        try {
            $cursor = $collection->find();
            $recipients = [];
            foreach ($cursor as $document) {
                $recipients[] = $document;
            }
            return $recipients;
        } catch (RuntimeException $e) {
            return [];
        }
    } else {
        return [];
    }
}

function getRecipientById(Database $conn, string $recipientId): ?array
{
    try {
        $collection = $conn->selectCollection('pdf_sender', (array)'recipients');
        $recipient = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($recipientId)]);
        return $recipient ? (array) $recipient : null;
    } catch (RuntimeException $e) {
        // Handle database error
        return null;
    }
}
function addRecipientToDatabase(Database $conn, string $recipientType, string $recipientContact): string {
    try {
        $collection = $conn->selectCollection('pdf_sender', (array)'recipients');
        $insertOneResult = $collection->insertOne([
            'recipient_type' => $recipientType,
            'recipient_contact' => $recipientContact
        ]);
        return $insertOneResult->getInsertedId();
    } catch (RuntimeException $e) {
        // Handle database insertion error
        exit("Failed to add recipient to the database: " . $e->getMessage());
    }
}

function editRecipientInDatabase(Database $conn, string $recipientId, string $recipientType, string $recipientContact): bool {
    try {
        $collection = $conn->selectCollection('pdf_sender', (array)'recipients');
        $updateResult = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($recipientId)],
            ['$set' => [ 'recipient_type' => $recipientType, 'recipient_contact' => $recipientContact]]
        );
        return $updateResult->getModifiedCount() > 0;
    } catch (RuntimeException $e) {
        // Handle database update error
        exit("Failed to edit recipient in the database: " . $e->getMessage());
    }
}

function deleteRecipientFromDatabase(Database $conn, string $recipientId): bool
{
    try {
        $collection = $conn->selectCollection('pdf_sender', (array)'recipients');
        $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($recipientId)]);
        return $deleteResult->getDeletedCount() > 0;
    } catch (RuntimeException $e) {
        // Handle database deletion error
        exit("Failed to delete recipient from the database: " . $e->getMessage());
    }
}
