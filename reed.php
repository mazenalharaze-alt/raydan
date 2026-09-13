<?php

// الإعدادات الأساسية وحقوق الملكية
define('TOKEN', '8661200783:AAGkM4nqnpK52SkqmG6ZpYO_U6eBh0OHuwQ');
define('OWNER', 6020615597);
define('DEV_USERNAME', '@woo4o'); // معرف المطور الخاص بك
define('DEV_NAME', 'ريدان (Raydan)'); // اسمك الكريم

// ملف تخزين البيانات للحالات ولغة المستخدم
$storage_file = __DIR__ . '/storage.json';

function load_storage() {
    global $storage_file;
    if (file_exists($storage_file)) {
        return json_decode(file_get_contents($storage_file), true) ?: ['states' => [], 'langs' => []];
    }
    return ['states' => [], 'langs' => []];
}

function save_storage($data) {
    global $storage_file;
    file_put_contents($storage_file, json_encode($data, JSON_UNESCAPED_UNICODE));
}

$storage = load_storage();

function get_user_lang($uid) {
    global $storage;
    return $storage['langs'][$uid] ?? 'ar';
}

function set_user_lang($uid, $lang) {
    global $storage;
    $storage['langs'][$uid] = $lang;
    save_storage($storage);
}

function get_user_state($uid) {
    global $storage;
    return $storage['states'][$uid] ?? 'IDLE';
}

function set_user_state($uid, $state) {
    global $storage;
    $storage['states'][$uid] = $state;
    save_storage($storage);
}

// القواميس (عربي / إنجليزي) باسمك وحقوقك
$lang_text = [
    'ar' => [
        'welcome' => "أهلاً بك يا %s!\n\nأنا بوت مساعدك الشامل المتعدد المهام.\nتصميم وتطوير: " . DEV_NAME . " (" . DEV_USERNAME . ")",
        'cancelled' => "تم الإلغاء.",
        'btn_tiktok' => "🎬 تيك توك",
        'btn_youtube' => "📥 يوتيوب",
        'btn_shorten' => "🔗 اختصار رابط",
        'btn_ai' => "🤖 الذكاء الاصطناعي",
        'btn_crypto' => "💰 العملات الرقمية",
        'btn_proxies' => "🌐 بروكسيات",
        'btn_info' => "📊 معلوماتي",
        'btn_about' => "ℹ️ حول البوت",
        'btn_lang' => "🌐 Language: English",
        'btn_cancel' => "❌ إلغاء",
        'prompt_tiktok' => "أرسل رابط تيك توك الآن:",
        'prompt_youtube' => "أرسل رابط يوتيوب الآن:",
        'prompt_shorten' => "أرسل الرابط المراد اختصاره:",
        'prompt_ai' => "🤖 وضع الذكاء الاصطناعي مَفعل.\nأرسل رسالتك أو اضغط إلغاء.",
        'fetching_crypto' => "⏳ جاري جلب أسعار العملات الرقمية...",
        'fetching_proxies' => "⏳ جاري جلب قائمة البروكسيات...",
        'proxies_error' => "❌ تعذر جلب البروكسيات حالياً.",
        'no_proxies' => "⚠️ لم يتم العثور على بروكسيات في المصدر.",
        'my_info' => "معلوماتك:\nالآي دي: <code>%s</code>\nالنقاط: %d\nالدعوات: %d",
        'about' => "🤖 بوت المساعد الشامل\n👑 المطور: " . DEV_NAME . "\n💬 المعرف: " . DEV_USERNAME . "\n⚡ الإصدار: 2.2 (خاص)",
        'processing_tiktok' => "⏳ جاري معالجة رابط تيك توك...",
        'tiktok_error' => "❌ فشل جلب فيديو تيك توك. تأكد من صحة الرابط.",
        'processing_youtube' => "⏳ جاري معالجة رابط يوتيوب...",
        'youtube_error' => "❌ فشل جلب فيديو يوتيوب. تأكد من صحة الرابط أو جرب مقطعاً آخر.",
        'no_mp4' => "⚠️ لم يتم العثور على فيديو بصيغة MP4 لهذا الرابط.",
        'shortening' => "⏳ جاري اختصار الرابط...",
        'shorten_error' => "❌ فشل اختصار الرابط.",
        'shortened_success' => "✅ الرابط المختصر:\n<code>%s</code>",
        'ai_thinking' => "🤖 جاري التفكير...",
        'ai_error' => "❌ خدمة الذكاء الاصطناعي غير متوفرة حالياً.",
        'use_buttons' => "استخدم الأزرار بالأسفل للتنقل."
    ],
    'en' => [
        'welcome' => "Welcome %s!\n\nI am your all-in-one assistant bot.\nDeveloped by " . DEV_NAME . " (" . DEV_USERNAME . ")",
        'cancelled' => "Cancelled.",
        'btn_tiktok' => "🎬 TikTok",
        'btn_youtube' => "📥 YouTube",
        'btn_shorten' => "🔗 Shorten",
        'btn_ai' => "🤖 AI",
        'btn_crypto' => "💰 Crypto",
        'btn_proxies' => "🌐 Proxies",
        'btn_info' => "📊 My Info",
        'btn_about' => "ℹ️ About",
        'btn_lang' => "🌐 اللغة: العربية",
        'btn_cancel' => "❌ Cancel",
        'prompt_tiktok' => "Send TikTok link:",
        'prompt_youtube' => "Send YouTube link:",
        'prompt_shorten' => "Send URL to shorten:",
        'prompt_ai' => "🤖 AI Mode ON.\nSend your message or press Cancel.",
        'fetching_crypto' => "⏳ Fetching crypto prices...",
        'fetching_proxies' => "⏳ Fetching proxy list...",
        'proxies_error' => "❌ Failed to fetch proxies.",
        'no_proxies' => "⚠️ No proxies found in the source.",
        'my_info' => "Your Info:\nID: <code>%s</code>\nPoints: %d\nInvites: %d",
        'about' => "🤖 All-in-One Bot\n👑 Developer: " . DEV_NAME . "\n💬 Contact: " . DEV_USERNAME . "\n⚡ Version: 2.2 (Custom)",
        'processing_tiktok' => "⏳ Processing your TikTok link...",
        'tiktok_error' => "❌ Failed to fetch TikTok video.",
        'processing_youtube' => "⏳ Processing your YouTube link...",
        'youtube_error' => "❌ Failed to fetch YouTube video. Please check the link.",
        'no_mp4' => "⚠️ No MP4 video found in this link.",
        'shortening' => "⏳ Shortening URL...",
        'shorten_error' => "❌ Failed to shorten URL.",
        'shortened_success' => "✅ Shortened URL:\n<code>%s</code>",
        'ai_thinking' => "🤖 Thinking...",
        'ai_error' => "❌ AI service is unavailable.",
        'use_buttons' => "Use the buttons below."
    ]
];

function t($key, $uid, ...$args) {
    global $lang_text;
    $lang = get_user_lang($uid);
    $text = $lang_text[$lang][$key] ?? $lang_text['ar'][$key] ?? $key;
    if (!empty($args)) {
        return sprintf($text, ...$args);
    }
    return $text;
}

function main_kb($uid) {
    return json_encode([
        "keyboard" => [
            [t('btn_tiktok', $uid), t('btn_ai', $uid)],
            [t('btn_youtube', $uid), t('btn_shorten', $uid)],
            [t('btn_crypto', $uid), t('btn_proxies', $uid)],
            [t('btn_info', $uid), t('btn_about', $uid)],
            [t('btn_lang', $uid)]
        ],
        "resize_keyboard" => true
    ]);
}

function cancel_kb($uid) {
    return json_encode([
        "keyboard" => [[t('btn_cancel', $uid)]],
        "resize_keyboard" => true
    ]);
}

class Core {
    public $endpoints = [
        "tiktok" => "https://www.tikvault.app/api/download",
        "ai" => "https://qudata.com/ru/includes/sendmail/chat.php",
        "crypto" => "https://api.coingecko.com/api/v3/coins/markets",
        "fx" => "https://open.er-api.com/v6/latest/USD",
        "yt" => "https://api.vidssave.net/api/yt",
        "short" => "https://clck.ru/--",
        "proxy" => "https://raw.githubusercontent.com/ALIILAPRO/MTProtoProxy/main/mtproto.txt"
    ];
    public $coins = [
        "bitcoin" => ["BTC", "Bitcoin", "₿"],
        "solana" => ["SOL", "Solana", "◎"],
        "the-open-network" => ["TON", "Toncoin", "💎"]
    ];

    private function curl_request($url, $method = 'GET', $data = null, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $default_headers = ["User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)"];
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($default_headers, $headers));

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response ?: false;
    }

    public function tiktok_download($url) {
        $payload = json_encode(["url" => $url]);
        $headers = ["Content-Type: application/json"];
        $response = $this->curl_request($this->endpoints["tiktok"], 'POST', $payload, $headers);
        if ($response) return json_decode($response, true);
        return null;
    }

    public function ai_chat($message) {
        $uuid = bin2hex(random_bytes(16));
        $payload = http_build_query([
            "message" => $message,
            "dialogs[0][role]" => "user",
            "dialogs[0][content]" => $message,
            "userid" => $uuid
        ]);
        $response = $this->curl_request($this->endpoints["ai"], 'POST', $payload);
        return $response ?: null;
    }

    public function crypto_data() {
        $fx_resp = $this->curl_request($this->endpoints["fx"]);
        $iqd = 1310;
        if ($fx_resp) {
            $fx = json_decode($fx_resp, true);
            $iqd = $fx["rates"]["IQD"] ?? 1310;
        }

        $params = http_build_query(["vs_currency" => "usd", "ids" => implode(",", array_keys($this->coins))]);
        $crypto_resp = $this->curl_request($this->endpoints["crypto"] . "?" . $params);
        if (!$crypto_resp) {
            return "₿ Bitcoin (BTC)\nPrice: $65,000.00\n\n◎ Solana (SOL)\nPrice: $140.00\n\n💎 Toncoin (TON)\nPrice: $5.50\n\n*(Fallback Data)*";
        }
        $data = json_decode($crypto_resp, true);
        if (!is_array($data)) return "₿ Bitcoin | ◎ Solana | 💎 Toncoin (Data temporarily unavailable)";

        $lines = [];
        foreach ($data as $c) {
            $k = $c["id"];
            if (!isset($this->coins[$k])) continue;
            list($sym, $name, $icon) = $this->coins[$k];
            $p = $c["current_price"] ?? 0;
            $ch = $c["price_change_percentage_24h"] ?? 0;
            $mc = $c["market_cap"] ?? 0;
            $vol = $c["total_volume"] ?? 0;
            $emoji = $ch > 0 ? "🟢" : ($ch < 0 ? "🔴" : "⚪");
            
            $lines[] = "$icon $name ($sym)\nPrice: $" . number_format($p, 2) . " | " . number_format($p * $iqd, 0) . " IQD\n24h: $emoji " . number_format($ch, 2) . "%\nCap: $" . number_format($mc, 0) . "\nVol: $" . number_format($vol, 0);
        }
        return implode("\n\n", $lines);
    }

    public function youtube_download($url) {
        $response = $this->curl_request($this->endpoints['yt'] . "?url=" . urlencode($url));
        if ($response) return json_decode($response, true);
        return null;
    }

    public function shorten_url($url) {
        $response = $this->curl_request($this->endpoints['short'] . "?url=" . urlencode($url));
        return $response ?: null;
    }

    public function get_proxies() {
        $response = $this->curl_request($this->endpoints['proxy']);
        return $response ?: null;
    }
}

$core = new Core();

function bot_api($method, $data = []) {
    $url = "https://api.telegram.org/bot" . TOKEN . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

function send_message($chat_id, $text, $reply_markup = null, $parse_mode = "HTML") {
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => $parse_mode
    ];
    if ($reply_markup) {
        $data['reply_markup'] = $reply_markup;
    }
    return bot_api('sendMessage', $data);
}

function edit_message($chat_id, $message_id, $text, $parse_mode = "HTML") {
    return bot_api('editMessageText', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $text,
        'parse_mode' => $parse_mode
    ]);
}

function delete_message($chat_id, $message_id) {
    bot_api('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
}

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if ($update && isset($update['message']['text'])) {
    $message = $update['message'];
    $chat_id = $message['chat']['id'];
    $text = $message['text'];
    $user = $message['from'];
    $uid = $user['id'];
    $mention = "<a href='tg://user?id={$uid}'>" . htmlspecialchars($user['first_name'] ?? 'User') . "</a>";

    $current_state = get_user_state($uid);
    $lang = get_user_lang($uid);

    if ($text === '/start') {
        set_user_state($uid, 'IDLE');
        $msg = t('welcome', $uid, $mention);
        send_message($chat_id, $msg, main_kb($uid));
        exit;
    }

    if ($text === '❌ Cancel' || $text === '/cancel' || $text === '❌ إلغاء') {
        set_user_state($uid, 'IDLE');
        send_message($chat_id, t('cancelled', $uid), main_kb($uid));
        exit;
    }

    if ($text === '🌐 Language: English' || $text === '🌐 اللغة: العربية') {
        $new_lang = ($lang === 'ar') ? 'en' : 'ar';
        set_user_lang($uid, $new_lang);
        set_user_state($uid, 'IDLE');
        $msg = ($new_lang === 'en') ? "Language changed to English! 🇬🇧" : "تم تغيير اللغة إلى العربية! 🇸🇦";
        send_message($chat_id, $msg, main_kb($uid));
        exit;
    }

    $is_tiktok = ($text === $lang_text['ar']['btn_tiktok'] || $text === $lang_text['en']['btn_tiktok']);
    $is_youtube = ($text === $lang_text['ar']['btn_youtube'] || $text === $lang_text['en']['btn_youtube']);
    $is_shorten = ($text === $lang_text['ar']['btn_shorten'] || $text === $lang_text['en']['btn_shorten']);
    $is_ai = ($text === $lang_text['ar']['btn_ai'] || $text === $lang_text['en']['btn_ai']);
    $is_crypto = ($text === $lang_text['ar']['btn_crypto'] || $text === $lang_text['en']['btn_crypto']);
    $is_proxies = ($text === $lang_text['ar']['btn_proxies'] || $text === $lang_text['en']['btn_proxies']);
    $is_info = ($text === $lang_text['ar']['btn_info'] || $text === $lang_text['en']['btn_info']);
    $is_about = ($text === $lang_text['ar']['btn_about'] || $text === $lang_text['en']['btn_about']);

    if ($is_tiktok) {
        set_user_state($uid, 'TIKTOK');
        send_message($chat_id, t('prompt_tiktok', $uid), cancel_kb($uid));
        exit;
    }
    if ($is_youtube) {
        set_user_state($uid, 'YOUTUBE');
        send_message($chat_id, t('prompt_youtube', $uid), cancel_kb($uid));
        exit;
    }
    if ($is_shorten) {
        set_user_state($uid, 'SHORTEN');
        send_message($chat_id, t('prompt_shorten', $uid), cancel_kb($uid));
        exit;
    }
    if ($is_ai) {
        set_user_state($uid, 'AI');
        send_message($chat_id, t('prompt_ai', $uid), cancel_kb($uid));
        exit;
    }
    if ($is_crypto) {
        $wait = send_message($chat_id, t('fetching_crypto', $uid));
        $data = $core->crypto_data();
        edit_message($chat_id, $wait['result']['message_id'], $data);
        exit;
    }
    if ($is_proxies) {
        $wait = send_message($chat_id, t('fetching_proxies', $uid));
        $proxies = $core->get_proxies();
        if (!$proxies) {
            edit_message($chat_id, $wait['result']['message_id'], t('proxies_error', $uid));
            exit;
        }
        $lines = array_filter(explode("\n", $proxies), function($l) {
            return strpos(trim($l), "https://t.me/") === 0;
        });
        if (empty($lines)) {
            edit_message($chat_id, $wait['result']['message_id'], t('no_proxies', $uid));
            exit;
        }
        $chunks = array_chunk(array_values($lines), 10);
        $sent = 0;
        foreach (array_slice($chunks, 0, 5) as $chunk) {
            send_message($chat_id, implode("\n\n", $chunk));
            $sent++;
        }
        if ($sent > 0) delete_message($chat_id, $wait['result']['message_id']);
        exit;
    }
    if ($is_info) {
        $msg = sprintf(t('my_info', $uid), $uid, 0, 0);
        send_message($chat_id, $msg);
        exit;
    }
    if ($is_about) {
        send_message($chat_id, t('about', $uid));
        exit;
    }

    if ($current_state === 'TIKTOK') {
        set_user_state($uid, 'IDLE');
        $wait = send_message($chat_id, t('processing_tiktok', $uid));
        $data = $core->tiktok_download($text);
        if (!$data || empty($data['success'])) {
            edit_message($chat_id, $wait['result']['message_id'], t('tiktok_error', $uid));
        } else {
            $d = $data['data'];
            $title = htmlspecialchars($d['title'] ?? '');
            $author = htmlspecialchars($d['author']['username'] ?? '');
            $caption = "<b>{$title}</b>\nBy: {$author}";
            $video_url = !empty($d['downloads']['videoHD']) ? $d['downloads']['videoHD'] : $d['downloads']['videoSD'];
            bot_api('sendVideo', ['chat_id' => $chat_id, 'video' => $video_url, 'caption' => $caption, 'parse_mode' => 'HTML']);
            delete_message($chat_id, $wait['result']['message_id']);
        }
        exit;
    }

    if ($current_state === 'YOUTUBE') {
        set_user_state($uid, 'IDLE');
        $wait = send_message($chat_id, t('processing_youtube', $uid));
        $data = $core->youtube_download($text);
        if (!$data || !empty($data['error'])) {
            edit_message($chat_id, $wait['result']['message_id'], t('youtube_error', $uid));
        } else {
            $best = null;
            foreach ($data['medias'] ?? [] as $m) {
                if (($m['type'] ?? '') === 'video' && ($m['extension'] ?? '') === 'mp4') {
                    $best = $m;
                    break;
                }
            }
            if ($best) {
                $url = $best['url_proxy'] ?? $best['url'];
                bot_api('sendVideo', ['chat_id' => $chat_id, 'video' => $url, 'caption' => "YouTube Video"]);
                delete_message($chat_id, $wait['result']['message_id']);
            } else {
                edit_message($chat_id, $wait['result']['message_id'], t('no_mp4', $uid));
            }
        }
        exit;
    }

    if ($current_state === 'SHORTEN') {
        set_user_state($uid, 'IDLE');
        $wait = send_message($chat_id, t('shortening', $uid));
        $result = $core->shorten_url($text);
        if (!$result) {
            edit_message($chat_id, $wait['result']['message_id'], t('shorten_error', $uid));
        } else {
            edit_message($chat_id, $wait['result']['message_id'], sprintf(t('shortened_success', $uid), $result));
        }
        exit;
    }

    if ($current_state === 'AI') {
        set_user_state($uid, 'IDLE');
        $wait = send_message($chat_id, t('ai_thinking', $uid));
        $res = $core->ai_chat($text);
        if (!$res) {
            edit_message($chat_id, $wait['result']['message_id'], t('ai_error', $uid));
        } else {
            edit_message($chat_id, $wait['result']['message_id'], substr($res, 0, 4096));
        }
        exit;
    }

    send_message($chat_id, t('use_buttons', $uid), main_kb($uid));
}
?>
     
