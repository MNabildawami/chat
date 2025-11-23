<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__."/vendor/autoload.php";

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$config = [];

// Load Web Driver
DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

// Create BotMan instance
$botman = BotManFactory::create($config);

// ==================== 1. SALAM SEDERHANA ====================
$botman->hears('assalamualaikum', function (BotMan $bot) {
    $bot->reply('waalaikumsalam');
    $bot->reply('hi');
});

// ==================== 2. MENGIRIM GAMBAR ====================
$botman->hears('logo', function (BotMan $bot) {
    $attachment = new Image('https://botman.io/img/logo.png');
    $message = OutgoingMessage::create('Ini logonya')
        ->withAttachment($attachment);
    $bot->reply($message);
});

// ==================== 3. MENGIRIM VIDEO ====================
$botman->hears('video', function (BotMan $bot) {
    $attachment = new Video('https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4');
    $message = OutgoingMessage::create('Ini videonya')
        ->withAttachment($attachment);
    $bot->reply($message);
});

// ==================== 4. MENGIRIM AUDIO ====================
$botman->hears('audio', function (BotMan $bot) {
    $attachment = new Audio('https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3');
    $message = OutgoingMessage::create('Ini audionya')
        ->withAttachment($attachment);
    $bot->reply($message);
});

// ==================== 5. MENGIRIM FILE PDF ====================
$botman->hears('pdf', function (BotMan $bot) {
    $attachment = new File('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');
    $message = OutgoingMessage::create('Ini file PDF nya')
        ->withAttachment($attachment);
    $bot->reply($message);
});

// ==================== 6. PATTERN: saya {name} ====================
$botman->hears('saya {name}', function ($bot, $name) {
    $bot->reply('nama anda adalah '.$name);
});

// ==================== 7. PATTERN: jalan {address} nomor {number} ====================
$botman->hears('jalan {address} nomor {number}', function ($bot, $address, $number) {
    $bot->reply('anda tinggal di jalan '.$address.' nomor '.$number);
});

// ==================== 8. REGEX: pesan [angka] ====================
$botman->hears('pesan ([0-9]+)', function ($bot, $number) {
    $bot->reply('anda memesan '.$number);
});

// ==================== FALLBACK ====================
$botman->fallback(function(BotMan $bot) {
    $bot->reply('Maaf, saya tidak memahami pesan anda');
});

// Listen
$botman->listen();
?>