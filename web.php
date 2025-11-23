<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS Headers - PENTING untuk web widget!
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__."/vendor/autoload.php";

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\File;


// ===== KONFIGURASI WEB DRIVER (BUKAN TELEGRAM!) =====
$config = [];

// Load Web Driver untuk widget
DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

// Create BotMan instance
$botman = BotManFactory::create($config);

// ==================== COMMAND /start ====================
$botman->hears('/start|start', function (BotMan $bot) {
    $bot->reply("🤖 *Selamat Datang!*\n\n" .
                "Saya adalah chatbot assistant yang siap membantu Anda 24/7.\n\n" .
                "Ketik *help* untuk melihat daftar perintah.");
});

/*
|--------------------------------------------------------------------------
| HELP COMMAND
|--------------------------------------------------------------------------
*/

$botman->hears('help|bantuan', function (BotMan $bot) {

    $helpText = '
        <div style="font-family: Inter, sans-serif; line-height: 1.6; padding: 12px;">

        <h3 style="margin-top:0;">📋 <b>DAFTAR PERINTAH CHATBOT</b></h3>

        <p><b>✨ Perintah Dasar:</b></p>
        <ul>
            <li><code>assalamualaikum</code> – Memberi salam</li>
            <li><code>saya [nama]</code> – Perkenalan</li>
            <li><code>jalan [nama] nomor [no]</code> – Memberi alamat</li>
            <li><code>pesan [jumlah]</code> – Pesan sesuatu</li>
        </ul>

        <p><b>🎵 Media:</b></p>
        <ul>
            <li><code>logo</code> / <code>gambar</code> – Kirim gambar</li>
            <li><code>video</code> – Kirim video</li>
            <li><code>audio</code> / <code>musik</code> – Kirim audio</li>
            <li><code>pdf</code> / <code>file</code> – Kirim dokumen</li>
        </ul>

        <p><b>⚙️ Utilitas:</b></p>
        <ul>
            <li><code>jam</code> / <code>waktu</code> – Cek waktu</li>
            <li><code>tanggal</code> – Cek tanggal</li>
        </ul>

        <p><b>🧮 Kalkulator:</b></p>
        <ul>
            <li><code>hitung 10 + 4</code></li>
            <li><code>hitung 5 x 5</code></li>
            <li><code>hitung 20 - 6</code></li>
        </ul>

        <br>
        <p><b>📝 Contoh penggunaan:</b></p>
        <ul>
            <li><code>saya Budi</code></li>
            <li><code>pesan 2</code></li>
            <li><code>hitung 12 / 3</code></li>
            <li><code>audio</code></li>
        </ul>

        </div>
    ';

    $bot->reply($helpText);
});

// ==================== 1. SALAM ====================
$botman->hears('assalamualaikum|salam', function (BotMan $bot) {
    $bot->reply('وعليكم السلام ورحمة الله وبركاته');
    $bot->reply('Waalaikumsalam Warahmatullahi Wabarakatuh! 🤲');
    $bot->reply('Ada yang bisa saya bantu? Ketik *help* untuk bantuan.');
});

// ==================== 2. SALAM VARIASI ====================
$botman->hears('halo|hai|hello|hi', function (BotMan $bot) {
    $greetings = [
        "👋 Halo! Senang bertemu dengan Anda!",
        "👋 Hi! Apa kabar hari ini?",
        "👋 Hello! Ada yang bisa saya bantu?",
        "👋 Hai! Selamat datang!"
    ];
    
    $bot->reply($greetings[array_rand($greetings)]);
    $bot->reply("Ketik *help* untuk melihat daftar perintah.");
});

// ==================== 3. LOGO ====================
$botman->hears('logo|gambar', function (BotMan $bot) {
    $attachment = new Image('https://botman.io/img/logo.png');
    $message = OutgoingMessage::create('Ini logo BotMan! 🤖')
        ->withAttachment($attachment);
    $bot->reply($message);
});

// ==================== 3A. VIDEO ====================
$botman->hears('video', function (BotMan $bot) {

    $attachment = new Video('https://drive.google.com/file/d/1aiUMk-xLK0M8Z3z4I6Xlx2xs4_Iyp3YR/view');

    $message = OutgoingMessage::create("Berikut video yang Anda minta 🎥")
                ->withAttachment($attachment);
    $bot->reply($message);
});


// ==================== 3B. AUDIO ====================
$botman->hears('audio', function (BotMan $bot) {

    $attachment = new audio('https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3');

    $message = OutgoingMessage::create("Berikut video yang Anda minta 🎥")
                ->withAttachment($attachment);

    $bot->reply($message);
});


// ==================== 3C. FILE PDF ====================
$botman->hears('pdf|file|dokumen', function (BotMan $bot) {

    $attachment = new File('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf');

    $message = OutgoingMessage::create("Berikut file yang Anda minta 📄")
                ->withAttachment($attachment);

    $bot->reply($message);
});

// ==================== 4. PATTERN: saya {name} ====================
$botman->hears('saya {name}', function ($bot, $name) {
    $bot->reply('👤 Nama Anda adalah: *' . ucfirst($name) . '*');
    $bot->reply('Senang berkenalan dengan Anda, ' . ucfirst($name) . '! 🤝');
});

// ==================== 5. PATTERN: jalan {address} nomor {number} ====================
$botman->hears('jalan {address} nomor {number}', function ($bot, $address, $number) {
    $bot->reply('📍 *ALAMAT LENGKAP*');
    $bot->reply('🛣️ Jalan: ' . ucfirst($address));
    $bot->reply('🔢 Nomor: ' . $number);
    $bot->reply('✅ Alamat berhasil dicatat!');
});

// ==================== 6. REGEX: pesan [angka] ====================
$botman->hears('pesan ([0-9]+)', function ($bot, $number) {
    $total = $number * 50000;
    
    $text = "🛒 *PESANAN DITERIMA*\n\n" .
            "📦 Jumlah Item: " . $number . " unit\n" .
            "💰 Harga per Item: Rp 50.000\n" .
            "💵 Total Harga: Rp " . number_format($total, 0, ',', '.') . "\n";
    
    if ($number > 10) {
        $discount = $total * 0.1;
        $finalPrice = $total - $discount;
        $text .= "\n🎉 Diskon 10%!\n";
        $text .= "💰 Total: Rp " . number_format($finalPrice, 0, ',', '.');
    } elseif ($number > 5) {
        $discount = $total * 0.05;
        $finalPrice = $total - $discount;
        $text .= "\n🎁 Diskon 5%!\n";
        $text .= "💰 Total: Rp " . number_format($finalPrice, 0, ',', '.');
    }
    
    $text .= "\n\n✅ Pesanan sedang diproses!";
    
    $bot->reply($text);
});

// ==================== 7. WAKTU ====================
$botman->hears('jam|waktu', function (BotMan $bot) {
    date_default_timezone_set('Asia/Jakarta');
    $time = date('H:i:s');
    
    $bot->reply("🕐 *WAKTU SAAT INI*\n\n" .
                "⏰ Jam: " . $time . " WIB\n" .
                "🌏 Zona Waktu: Indonesia (WIB)");
});

// ==================== 8. TANGGAL ====================
$botman->hears('tanggal|hari ini', function (BotMan $bot) {
    date_default_timezone_set('Asia/Jakarta');
    $date = date('d F Y');
    $day = date('l');
    
    $days = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $bot->reply("📅 *TANGGAL HARI INI*\n\n" .
                "📆 Hari: " . $days[$day] . "\n" .
                "📅 Tanggal: " . $date);
});

// ==================== 9. KALKULATOR ====================
$botman->hears('hitung {num1} {operator} {num2}', function ($bot, $num1, $operator, $num2) {
    $result = 0;
    $valid = true;
    
    switch ($operator) {
        case '+':
        case 'tambah':
            $result = $num1 + $num2;
            break;
        case '-':
        case 'kurang':
            $result = $num1 - $num2;
            break;
        case 'x':
        case '*':
        case 'kali':
            $result = $num1 * $num2;
            break;
        case '/':
        case 'bagi':
            if ($num2 != 0) {
                $result = $num1 / $num2;
            } else {
                $bot->reply('❌ Error: Tidak bisa membagi dengan nol!');
                $valid = false;
            }
            break;
        default:
            $bot->reply('❌ Operator tidak valid! Gunakan: +, -, x, atau /');
            $valid = false;
    }
    
    if ($valid) {
        $bot->reply("🔢 *HASIL PERHITUNGAN*\n\n" .
                    $num1 . ' ' . $operator . ' ' . $num2 . ' = *' . $result . '*');
    }
});

// ==================== 10. TERIMA KASIH ====================
$botman->hears('terima kasih|makasih|thanks|thank you', function (BotMan $bot) {
    $responses = [
        'Sama-sama! 😊',
        'Terima kasih kembali! 🙏',
        'You\'re welcome! 😊',
        'Dengan senang hati! 😊'
    ];
    
    $bot->reply($responses[array_rand($responses)]);
});

// ==================== FALLBACK ====================
$botman->fallback(function(BotMan $bot) {
    $bot->reply("🤔 Maaf, saya tidak memahami pesan Anda.\n\n" .
                "Ketik *help* untuk melihat daftar perintah yang tersedia.");
});

// Listen
$botman->listen();
?>