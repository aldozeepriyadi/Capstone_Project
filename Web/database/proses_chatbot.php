<?php
session_start();
header('Content-Type: application/json');
include 'connect.php';

$question = $_POST['question'] ?? '';
$topic_id = $_POST['topic_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

$payload = json_encode(['question' => $question]);

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

$reply = $responseData['reply'] ?? 'Maaf, tidak bisa memproses saat ini.';

// Jika user login, simpan ke database
if ($user_id && $topic_id) {
    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'user', ?)");
    $stmt->execute([$topic_id, $user_id, $question]);

    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, 'bot', ?)");
    $stmt->execute([$topic_id, $user_id, $reply]);
}

echo json_encode([
    'response' => $reply,
    'topic_id' => $topic_id 
]);
