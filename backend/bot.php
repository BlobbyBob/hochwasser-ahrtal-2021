<?php

use BlobbyBob\HochwasserAhrtal2021\Complaint;
use BlobbyBob\HochwasserAhrtal2021\Correction;
use BlobbyBob\HochwasserAhrtal2021\Contact;
use BlobbyBob\HochwasserAhrtal2021\PersonalMedia;
use TelegramBot\Api\BotApi;

if (php_sapi_name() != 'cli') {
    die('Only CLI access allowed');
}

require 'vendor/autoload.php';
require __DIR__ . '/config.php';

try {
    $bot = new BotApi(TELEGRAM_BOT_TOKEN);

    $pdo = new PDO(sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST), DB_USER, DB_PASS);
    $pdo->exec('SET CHARACTER SET UTF8MB4');
    $stmt = $pdo->prepare('SELECT * FROM personalMedia WHERE UNIX_TIMESTAMP(date) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - 3*3600');
    $stmt->execute();

    /** @var $personalMedia PersonalMedia */
    while (($personalMedia = $stmt->fetchObject(PersonalMedia::class))) {
        foreach (TELEGRAM_CHAT_IDS as $chatId) {
            $bot->sendMessage($chatId, mb_convert_encoding("Neue Upload-Anfrage von $personalMedia->name: $personalMedia->request", "UTF-8"));
        }
    }

    $stmt = $pdo->prepare('SELECT * FROM contact WHERE UNIX_TIMESTAMP(date) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - 3*3600');
    $stmt->execute();

    /** @var $contact Contact */
    while (($contact = $stmt->fetchObject(Contact::class))) {
        foreach (TELEGRAM_CHAT_IDS as $chatId) {
            $bot->sendMessage($chatId, mb_convert_encoding("Neue Anfrage von $contact->name: $contact->request", "UTF-8"));
        }
    }

    $stmt = $pdo->prepare('SELECT * FROM complaints WHERE UNIX_TIMESTAMP(date) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - 3*3600');
    $stmt->execute();

    /** @var $complaint Complaint */
    while (($complaint = $stmt->fetchObject(Complaint::class))) {
        foreach (TELEGRAM_CHAT_IDS as $chatId) {
            $bot->sendMessage($chatId, mb_convert_encoding("Neue Beschwerde von $complaint->name: $complaint->request", "UTF-8"));
        }
    }

    $stmt = $pdo->prepare('SELECT * FROM corrections WHERE UNIX_TIMESTAMP(date) > UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - 3*3600');
    $stmt->execute();

    /** @var $correction Correction */
    while (($correction = $stmt->fetchObject(Correction::class))) {
        foreach (TELEGRAM_CHAT_IDS as $chatId) {
            $bot->sendMessage($chatId, mb_convert_encoding("Neuer Korrekturvorschlag", "UTF-8"));
        }
    }

} catch (\TelegramBot\Api\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

