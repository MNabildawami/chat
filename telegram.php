<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__."/vendor/autoload.php";

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\SymfonyCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

// ========== GANTI TOKEN DI SINI ==========
$config = [
    'telegram' => [
        'token' => '8577180364:AAFbbSiNooLDI3oqrJq4W7iw75hYIsQfdaI'
    ]
];
// =========================================

// Load Telegram Driver
DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

// Setup cache (buat folder cache jika belum ada)
if (!file_exists(__DIR__.'/cache')) {
    mkdir(__DIR__.'/cache', 0777, true);
}

$adapter = new FilesystemAdapter('', 0, __DIR__.'/cache');
$botman = BotManFactory::create($config, new SymfonyCache($adapter));

// ==================== COMMAND /start ====================
$botman->hears('/start|start', function (BotMan $bot) {
    $user = $bot->getUser();
    $firstName = $user->getFirstName();
    
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "🤖 <b>Selamat Datang, {$firstName}!</b>\n\n" .
                  "Saya adalah chatbot assistant yang siap membantu Anda 24/7.\n\n" .
                  "Ketik /help untuk melihat daftar perintah yang tersedia.",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== COMMAND /help ====================
$botman->hears('/help|help|bantuan', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $helpText = "📋 <b>DAFTAR PERINTAH</b>\n\n" .
                "<b>🔹 Command Utama:</b>\n" .
                "/start - Mulai bot\n" .
                "/help - Bantuan lengkap\n" .
                "/info - Info tentang bot\n" .
                "/myid - Lihat ID Telegram Anda\n\n" .
                
                "<b>🔹 Perintah Teks:</b>\n" .
                "• <code>assalamualaikum</code> - Salam\n" .
                "• <code>saya [nama]</code> - Perkenalan\n" .
                "• <code>jalan [nama] nomor [no]</code> - Info alamat\n" .
                "• <code>pesan [angka]</code> - Pesan item\n\n" .
                
                "<b>🔹 Media:</b>\n" .
                "• <code>logo</code> - Kirim gambar\n" .
                "• <code>video</code> - Kirim video\n" .
                "• <code>audio</code> - Kirim audio\n" .
                "• <code>pdf</code> - Kirim file PDF\n\n" .
                
                "<b>🔹 Utilitas:</b>\n" .
                "• <code>jam</code> - Cek waktu sekarang\n" .
                "• <code>tanggal</code> - Cek tanggal hari ini\n" .
                "• <code>hitung [n] [+/-/x//] [n]</code> - Kalkulator\n\n" .
                
                "<b>💡 Contoh Penggunaan:</b>\n" .
                "<code>saya Budi</code>\n" .
                "<code>jalan Sudirman nomor 123</code>\n" .
                "<code>pesan 10</code>\n" .
                "<code>hitung 5 + 3</code>";
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => $helpText,
        'parse_mode' => 'HTML'
    ]);
});

// ==================== COMMAND /info ====================
$botman->hears('/info|info', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $infoText = "ℹ️ <b>INFORMASI BOT</b>\n\n" .
                "🤖 <b>Nama:</b> Chatbot Assistant\n" .
                "📱 <b>Platform:</b> Telegram\n" .
                "⚙️ <b>Framework:</b> BotMan 2.0\n" .
                "📅 <b>Dibuat:</b> November 2024\n" .
                "🔧 <b>Versi:</b> 1.0.0\n\n" .
                "<b>Fitur Utama:</b>\n" .
                "✅ Respon otomatis 24/7\n" .
                "✅ Pattern matching\n" .
                "✅ Kalkulator sederhana\n" .
                "✅ Info waktu real-time\n" .
                "✅ Media sharing (foto, video, audio, file)\n" .
                "✅ Fallback message\n\n" .
                "Ketik /help untuk bantuan lengkap.";
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => $infoText,
        'parse_mode' => 'HTML'
    ]);
});

// ==================== COMMAND /myid ====================
$botman->hears('/myid|myid', function (BotMan $bot) {
    $userId = $bot->getUser()->getId();
    $username = $bot->getUser()->getUsername();
    $firstName = $bot->getUser()->getFirstName();
    $lastName = $bot->getUser()->getLastName();
    
    $bot->typesAndWaits(1);
    
    $idText = "👤 <b>INFORMASI AKUN ANDA</b>\n\n" .
              "🆔 <b>User ID:</b> <code>{$userId}</code>\n" .
              "👤 <b>Nama Depan:</b> {$firstName}\n";
    
    if ($lastName) {
        $idText .= "👤 <b>Nama Belakang:</b> {$lastName}\n";
    }
    
    if ($username) {
        $idText .= "📝 <b>Username:</b> @{$username}\n";
    }
    
    $idText .= "\n💡 <i>ID ini diperlukan jika admin ingin mengirim pesan langsung ke Anda.</i>";
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $userId,
        'text' => $idText,
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 1. SALAM ====================
$botman->hears('assalamualaikum', function (BotMan $bot) {
    $user = $bot->getUser();
    $firstName = $user->getFirstName();
    
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "وعليكم السلام ورحمة الله وبركاته\n\n" .
                  "Waalaikumsalam Warahmatullahi Wabarakatuh, <b>{$firstName}</b> 🤲\n\n" .
                  "Semoga Anda selalu dalam lindungan Allah SWT. Aamiin.\n" .
                  "Ada yang bisa saya bantu hari ini? 😊",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 2. SALAM VARIASI ====================
$botman->hears('halo|hai|hello|hi', function (BotMan $bot) {
    $firstName = $bot->getUser()->getFirstName();
    
    $bot->typesAndWaits(1);
    
    $greetings = [
        "👋 Halo, <b>{$firstName}</b>! Senang bertemu dengan Anda!",
        "👋 Hi, <b>{$firstName}</b>! Apa kabar hari ini?",
        "👋 Hello, <b>{$firstName}</b>! Ada yang bisa saya bantu?",
        "👋 Hai, <b>{$firstName}</b>! Selamat datang kembali!"
    ];
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => $greetings[array_rand($greetings)] . "\n\nKetik /help untuk melihat daftar perintah.",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 3. KIRIM GAMBAR/LOGO ====================
$botman->hears('logo|gambar', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendPhoto', [
        'chat_id' => $bot->getUser()->getId(),
        'photo' => 'https://botman.io/img/logo.png',
        'caption' => '🤖 Ini logo BotMan!'
    ]);
});

// ==================== 4. KIRIM VIDEO ====================
$botman->hears('video', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => '🎬 <b>Mengirim video...</b>',
        'parse_mode' => 'HTML'
    ]);
    
    $bot->sendRequest('sendVideo', [
        'chat_id' => $bot->getUser()->getId(),
        'video' => 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4',
        'caption' => '🎥 Ini contoh video untuk Anda!'
    ]);
});

// ==================== 5. KIRIM AUDIO ====================
$botman->hears('audio|musik', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => '🎵 <b>Mengirim audio...</b>',
        'parse_mode' => 'HTML'
    ]);
    
    $bot->sendRequest('sendAudio', [
        'chat_id' => $bot->getUser()->getId(),
        'audio' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
        'caption' => '🎵 Ini contoh audio untuk Anda!'
    ]);
});

// ==================== 6. KIRIM FILE PDF ====================
$botman->hears('pdf|file|dokumen', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => '📄 <b>Mengirim dokumen PDF...</b>',
        'parse_mode' => 'HTML'
    ]);
    
    $bot->sendRequest('sendDocument', [
        'chat_id' => $bot->getUser()->getId(),
        'document' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        'caption' => '📄 Ini contoh file PDF untuk Anda!'
    ]);
});

// ==================== 7. PATTERN: saya {name} ====================
$botman->hears('saya {name}', function ($bot, $name) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "👤 Nama Anda adalah: <b>" . ucfirst($name) . "</b>\n\n" .
                  "Senang berkenalan dengan Anda, " . ucfirst($name) . "! 🤝\n\n" .
                  "Dari mana asal Anda? Ketik: <code>dari [kota]</code>",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 8. PATTERN: dari {city} ====================
$botman->hears('dari {city}', function ($bot, $city) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "🏙️ Wah, <b>" . ucfirst($city) . "</b> adalah kota yang bagus!\n\n" .
                  "Terima kasih sudah berbagi informasi. 😊\n" .
                  "Ketik /help untuk melihat fitur lainnya.",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 9. PATTERN: jalan {address} nomor {number} ====================
$botman->hears('jalan {address} nomor {number}', function ($bot, $address, $number) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "📍 <b>ALAMAT LENGKAP</b>\n\n" .
                  "🛣️ <b>Jalan:</b> " . ucfirst($address) . "\n" .
                  "🔢 <b>Nomor:</b> " . $number . "\n\n" .
                  "✅ Alamat berhasil dicatat!",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 10. REGEX: pesan [angka] ====================
$botman->hears('pesan ([0-9]+)', function ($bot, $number) {
    $bot->typesAndWaits(1);
    
    $total = $number * 50000;
    
    $text = "🛒 <b>PESANAN DITERIMA</b>\n\n" .
            "📦 <b>Jumlah Item:</b> " . $number . " unit\n" .
            "💰 <b>Harga per Item:</b> Rp 50.000\n" .
            "💵 <b>Total Harga:</b> Rp " . number_format($total, 0, ',', '.') . "\n\n";
    
    if ($number > 10) {
        $text .= "🎉 <b>Selamat!</b> Anda mendapat diskon 10%!\n";
        $discount = $total * 0.1;
        $finalPrice = $total - $discount;
        $text .= "💰 <b>Total Setelah Diskon:</b> Rp " . number_format($finalPrice, 0, ',', '.') . "\n\n";
    } elseif ($number > 5) {
        $text .= "🎁 Anda mendapat diskon 5%!\n";
        $discount = $total * 0.05;
        $finalPrice = $total - $discount;
        $text .= "💰 <b>Total Setelah Diskon:</b> Rp " . number_format($finalPrice, 0, ',', '.') . "\n\n";
    }
    
    $text .= "✅ Pesanan Anda sedang diproses.\n";
    $text .= "📞 Tim kami akan menghubungi Anda segera.\n\n";
    $text .= "Terima kasih telah berbelanja! 🙏";
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => $text,
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 11. WAKTU ====================
$botman->hears('jam|waktu', function (BotMan $bot) {
    date_default_timezone_set('Asia/Jakarta');
    $time = date('H:i:s');
    
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "🕐 <b>WAKTU SAAT INI</b>\n\n" .
                  "⏰ Jam: <code>{$time}</code> WIB\n" .
                  "🌏 Zona Waktu: Indonesia (WIB)",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 12. TANGGAL ====================
$botman->hears('tanggal|hari ini', function (BotMan $bot) {
    date_default_timezone_set('Asia/Jakarta');
    $day = date('l');
    $date = date('d');
    $month = date('F');
    $year = date('Y');
    
    $days = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $months = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "📅 <b>TANGGAL HARI INI</b>\n\n" .
                  "📆 Hari: <b>{$days[$day]}</b>\n" .
                  "📅 Tanggal: <b>{$date} {$months[$month]} {$year}</b>\n" .
                  "🌏 Zona Waktu: Indonesia (WIB)",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 13. KALKULATOR ====================
$botman->hears('hitung {num1} {operator} {num2}', function ($bot, $num1, $operator, $num2) {
    $bot->typesAndWaits(1);
    
    $result = 0;
    $valid = true;
    $operatorSymbol = '';
    
    switch ($operator) {
        case '+':
        case 'tambah':
            $result = $num1 + $num2;
            $operatorSymbol = '➕';
            break;
        case '-':
        case 'kurang':
            $result = $num1 - $num2;
            $operatorSymbol = '➖';
            break;
        case 'x':
        case '*':
        case 'kali':
            $result = $num1 * $num2;
            $operatorSymbol = '✖️';
            break;
        case '/':
        case 'bagi':
            if ($num2 != 0) {
                $result = $num1 / $num2;
                $operatorSymbol = '➗';
            } else {
                $bot->sendRequest('sendMessage', [
                    'chat_id' => $bot->getUser()->getId(),
                    'text' => '❌ Error: Tidak bisa membagi dengan nol!'
                ]);
                $valid = false;
            }
            break;
        default:
            $bot->sendRequest('sendMessage', [
                'chat_id' => $bot->getUser()->getId(),
                'text' => '❌ Operator tidak valid! Gunakan: +, -, x, atau /'
            ]);
            $valid = false;
    }
    
    if ($valid) {
        $bot->sendRequest('sendMessage', [
            'chat_id' => $bot->getUser()->getId(),
            'text' => "🔢 <b>HASIL PERHITUNGAN</b>\n\n" .
                      "{$operatorSymbol} <code>{$num1} {$operator} {$num2}</code> = <b>{$result}</b>",
            'parse_mode' => 'HTML'
        ]);
    }
});

// ==================== 14. TERIMA KASIH ====================
$botman->hears('terima kasih|makasih|thanks|thank you', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $responses = [
        'Sama-sama! 😊 Senang bisa membantu!',
        'Terima kasih kembali! 🙏',
        'You\'re welcome! 😊',
        'Dengan senang hati! 😊'
    ];
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => $responses[array_rand($responses)] . "\n\nJangan ragu untuk menghubungi saya lagi ya! 💬",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== 15. SELAMAT TINGGAL ====================
$botman->hears('bye|dadah|sampai jumpa|selamat tinggal', function (BotMan $bot) {
    $firstName = $bot->getUser()->getFirstName();
    
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "👋 Sampai jumpa, <b>{$firstName}</b>!\n\n" .
                  "Terima kasih sudah menggunakan layanan kami.\n" .
                  "Semoga harimu menyenangkan! 😊✨\n\n" .
                  "Ketik /start untuk memulai lagi.",
        'parse_mode' => 'HTML'
    ]);
});

// ==================== FALLBACK ====================
$botman->fallback(function(BotMan $bot) {
    $bot->typesAndWaits(1);
    
    $bot->sendRequest('sendMessage', [
        'chat_id' => $bot->getUser()->getId(),
        'text' => "🤔 Maaf, saya tidak memahami pesan Anda.\n\n" .
                  "Ketik /help untuk melihat daftar perintah yang tersedia.",
        'parse_mode' => 'HTML'
    ]);
});

// Listen
$botman->listen();
?>