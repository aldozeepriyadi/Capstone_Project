<?php
session_start();
require 'connect.php';

header('Content-Type: application/json');

if (!isset($_POST['question']) || empty(trim($_POST['question']))) {
    echo json_encode(['response' => 'Pertanyaan kosong.']);
    exit;
}

$question = trim($_POST['question']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$topic_id = isset($_POST['topic_id']) && $_POST['topic_id'] !== 'null' ? intval($_POST['topic_id']) : null;

// Buat topik baru jika belum ada dan user login
if ($user_id && !$topic_id) {
    $title = substr($question, 0, 50); // Gunakan awal pertanyaan sebagai judul
    $stmt = $pdo->prepare("INSERT INTO chat_topics (user_id, title, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $title]);
    $topic_id = $pdo->lastInsertId();
}

// Kirim ke Gemini
$apiKey = 'AIzaSyCP6h86P5XwLb-yQvWp5g527y39Wti4XgQ';
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $question]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200 || !$response) {
    echo json_encode(['response' => 'Gagal mendapatkan respons dari Gemini.']);
    exit;
}

$json = json_decode($response, true);
$answer = $json['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, tidak ada jawaban.";

// Simpan pertanyaan dan jawaban jika user login
if ($user_id && $topic_id) {
    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'user', ?)");
    $stmt->execute([$topic_id, $user_id, $question]);

    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'bot', ?)");
    $stmt->execute([$topic_id, $user_id, $answer]);
}

echo json_encode(['response' => $answer, 'topic_id' => $topic_id]);
