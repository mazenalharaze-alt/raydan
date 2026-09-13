<?php

// الإعدادات الأساسية
define('TOKEN', '8661200783:AAGkM4nqnpK52SkqmG6ZpYO_U6eBh0OHuwQ'); // توكن بوتك
define('OWNER', 6020615597);  // ايديك
define('DEV_USERNAME', '@woo4o');

// إعداد السجلات (Logging) المبسط
function logger($level, $message) {
    // يمكن تركها فارغة أو لتسجيل الأخطاء
}

class Core {
    public $users = [];
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

    public function get_user($uid) {
        if (!isset($this->users[$uid])) {
            $this->users[$uid] = ["points" => 0, "invites" => 0];
        }
        return $this->users[$uid];
    }

    private function curl_request($url, $method = 'GET', $data = null, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $default_headers = ["User-Agent: Mozilla/5.0"];
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($default_headers, $headers));

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return false;
        }
        return $response;
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
        if (!$fx_resp) return null;
        $fx = json_decode($fx_resp, true);
        $iqd = $fx["rates"]["IQD"] ?? 1310;

        $params = http_build_query(["vs_currency" => "usd", "ids" => implode(",", array_keys($this->coins))]);
        $crypto_resp = $this->curl_request($this->endpoints["crypto"] . "?" . $params);
        if (!$crypto_resp) return null;
        $data = json_decode($crypto_resp, true);

        $lines = [];
        foreach ($data as $c) {
            $k = $c["id"];
            if (!isset($this->coins[$k])) continue;
            list($sym, $name, $icon) = $this->coins[$k];
            $p = $c["current_price"];
            $ch = $c["price_change_percentage_24h"] ?? 0;
            $mc = $c["market_cap"];
            $vol = $c["total_volume"];
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

// تخزين الحالات (كمثال مبسط للويب هوك)
$core = new Core();
$user_states = [];

// لوحات المفاتيح
function main_kb() {
    return json_encode([
        "keyboard" => [
            ["🎬 TikTok", "🤖 AI"],
            ["📥 YouTube", "🔗 Shorten"],
            ["💰 Crypto", "🌐 Proxies"],
            ["📊 My Info", "ℹ️ About"]
        ],
        "resize_keyboard" => true
    ]);
}

function cancel_kb() {
    return json_encode([
        "keyboard" => [["❌ Cancel"]],
        "resize_keyboard" => true
    ]);
}

// دالة للتواصل مع Telegram API
function bot_api($method, $data = []) {
    $url = "https://api.telegram.org/bot" . TOKEN . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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

// المعالجة الرئيسية للرسائل الواردة عبر الويب هوك
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if ($update && isset($update['message']['text'])) {
    $message = $update['message'];
    $chat_id = $message['chat']['id'];
    $text = $message['text'];
    $user = $message['from'];
    $uid = $user['id'];
    $mention = "<a href='tg://user?id={$uid}'>" . htmlspecialchars($user['first_name'] ?? 'User') . "</a>";

    $core->get_user($uid);

    if ($text === '/start') {
        $msg = "Welcome $mention!\n\nI am your all-in-one assistant bot.\nDeveloped by " . DEV_USERNAME;
        send_message($chat_id, $msg, main_kb());
        exit;
    }

    if ($text === '❌ Cancel' || $text === '/cancel') {
        send_message($chat_id, "Cancelled.", main_kb());
        exit;
    }

    $menu_options = ["🎬 TikTok", "📥 YouTube", "🔗 Shorten", "🤖 AI", "💰 Crypto", "🌐 Proxies", "📊 My Info", "ℹ️ About"];
    
    if (in_array($text, $menu_options)) {
        switch ($text) {
            case "🎬 TikTok":
                send_message($chat_id, "Send TikTok link:", cancel_kb());
                break;
            case "📥 YouTube":
                send_message($chat_id, "Send YouTube link:", cancel_kb());
                break;
            case "🔗 Shorten":
                send_message($chat_id, "Send URL to shorten:", cancel_kb());
                break;
            case "🤖 AI":
                send_message($chat_id, "🤖 AI Mode ON.\nSend your message or press Cancel.", cancel_kb());
                break;
            case "💰 Crypto":
                $wait = send_message($chat_id, "⏳ Fetching crypto prices...");
                $data = $core->crypto_data();
                if (!$data) {
                    edit_message($chat_id, $wait['result']['message_id'], "❌ Failed to fetch crypto data.\nPlease try again later.");
                } else {
                    edit_message($chat_id, $wait['result']['message_id'], substr($data, 0, 4096));
                }
                break;
            case "🌐 Proxies":
                $wait = send_message($chat_id, "⏳ Fetching proxy list...");
                $proxies = $core->get_proxies();
                if (!$proxies) {
                    edit_message($chat_id, $wait['result']['message_id'], "❌ Failed to fetch proxies.\nPlease try again later.");
                    exit;
                }
                $lines = array_filter(explode("\n", $proxies), function($l) {
                    return strpos(trim($l), "https://t.me/") === 0;
                });
                if (empty($lines)) {
                    edit_message($chat_id, $wait['result']['message_id'], "⚠️ No proxies found in the source.");
                    exit;
                }
                $chunks = array_chunk(array_values($lines), 10);
                $sent = 0;
                foreach (array_slice($chunks, 0, 5) as $chunk) {
                    send_message($chat_id, implode("\n\n", $chunk));
                    $sent++;
                }
                if ($sent > 0) delete_message($chat_id, $wait['result']['message_id']);
                break;
            case "📊 My Info":
                $info = $core->get_user($uid);
                $msg = "Your Info:\nID: <code>{$uid}</code>\nPoints: {$info['points']}\nInvites: {$info['invites']}";
                send_message($chat_id, $msg);
                break;
            case "ℹ️ About":
                $msg = "Bot Developer: " . DEV_USERNAME . "\nVersion: 2.0\nFeatures: TikTok, YouTube, AI, Crypto, URL Shortener, MTProto Proxies";
                send_message($chat_id, $msg);
                break;
        }
        exit;
    }

    // روابط التحميل أو الذكاء الاصطناعي بناءً على محتوى الرسالة
    if (filter_var($text, FILTER_VALIDATE_URL)) {
        if (strpos($text, 'tiktok.com') !== false) {
            $wait = send_message($chat_id, "⏳ Processing your TikTok link...");
            $data = $core->tiktok_download($text);
            if (!$data || empty($data['success'])) {
                edit_message($chat_id, $wait['result']['message_id'], "❌ Failed to fetch TikTok video.");
            } else {
                $d = $data['data'];
                $title = htmlspecialchars($d['title'] ?? '');
                $author = htmlspecialchars($d['author']['username'] ?? '');
                $caption = "<b>{$title}</b>\nBy: {$author}";
                $video_url = !empty($d['downloads']['videoHD']) ? $d['downloads']['videoHD'] : $d['downloads']['videoSD'];
                bot_api('sendVideo', ['chat_id' => $chat_id, 'video' => $video_url, 'caption' => $caption, 'parse_mode' => 'HTML']);
                delete_message($chat_id, $wait['result']['message_id']);
            }
        } elseif (strpos($text, 'youtube.com') !== false || strpos($text, 'youtu.be') !== false) {
            $wait = send_message($chat_id, "⏳ Processing your YouTube link...");
            $data = $core->youtube_download($text);
            if (!$data || !empty($data['error'])) {
                edit_message($chat_id, $wait['result']['message_id'], "❌ Failed to fetch YouTube video.");
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
                    edit_message($chat_id, $wait['result']['message_id'], "⚠️ No MP4 video found.");
                }
            }
        } else {
            // اعتبارها كاختصار رابط
            $wait = send_message($chat_id, "⏳ Shortening URL...");
            $result = $core->shorten_url($text);
            if (!$result) {
                edit_message($chat_id, $wait['result']['message_id'], "❌ Failed to shorten URL.");
            } else {
                edit_message($chat_id, $wait['result']['message_id'], "✅ Shortened URL:\n<code>{$result}</code>");
            }
        }
    } else {
        // رسالة نصية عادية تعتبر ذكاء اصطناعي AI
        $wait = send_message($chat_id, "🤖 Thinking...");
        $res = $core->ai_chat($text);
        if (!$res) {
            edit_message($chat_id, $wait['result']['message_id'], "❌ AI service is unavailable.");
        } else {
            edit_message($chat_id, $wait['result']['message_id'], substr($res, 0, 4096));
        }
    }
}
?>

