<?php
session_start();
header('Content-Type: application/json');
include 'connect.php';

$question = $_POST['question'] ?? '';
$topic_id = $_POST['topic_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$payload = json_encode(['question' => $question]);
$payload_text = json_encode(['text' => $question]);

// 1. Mengirimkan request ke API Predict untuk mendapatkan hasil prediksi
$ch = curl_init('http://127.0.0.1:8080/predict/'); // endpoint API Predict
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload_text)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_text);

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['response' => 'Gagal menghubungi server lokal untuk prediksi']);
    exit;
}
curl_close($ch);

// 2. Mendecode response dari API Predict
$responseData = json_decode($response, true);
if (!$responseData || !isset($responseData['label'])) {
    echo json_encode([
        'response' => 'Gagal decode response dari API Predict: ' . $response,
    ]);
    exit;
}

$predict = $responseData['label'] ?? 'Prediksi tidak tersedia';

$ch = curl_init('http://127.0.0.1:8080/chat/'); // endpoint FastAPI kamu
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['response' => 'Gagal menghubungi server lokal']);
    exit;
}
curl_close($ch);

$responseData = json_decode($response, true);
if (!$responseData || !isset($responseData['reply'])) {
    echo json_encode([
        'response' => 'Gagal decode response: ' . $response,
        'topic_id' => $topic_id
    ]);
    exit;
}

// 5. Gabungkan pertanyaan dengan hasil prediksi
$question_with_prediction = $question . ' (' . $predict . ')';

$reply = $responseData['reply'] ?? 'Maaf, tidak bisa memproses saat ini.';

if ($topic_id == null && $user_id != null) {
    // Insert topic baru
    $stmt = $pdo->prepare("INSERT INTO chat_topics (user_id, title) VALUES (?, ?)");
    $stmt->execute([$user_id, $question_with_prediction]);

    // Ambil id_topic yang baru saja dimasukkan
    $topic_id = $pdo->lastInsertId(); // Ambil ID terakhir yang dimasukkan
}

// Jika user login, simpan ke database
if ($user_id && $topic_id) {
    // Insert pesan dari user
    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'user', ?)");
    $stmt->execute([$topic_id, $user_id, $question]);

    // Insert balasan dari bot
    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'bot', ?)");
    $stmt->execute([$topic_id, $user_id, $reply]);
}

echo json_encode([
    'response' => $reply,
    'topic_id' => $topic_id 
]);
