<?php
session_start();


require 'connect.php';

header('Content-Type: application/json');
if (isset($_SESSION['migrated'])) {
    echo json_encode(['message' => 'Sudah dimigrasikan']);
    exit;
}
$_SESSION['migrated'] = true;

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$chats = $data['chats'] ?? [];

if (empty($chats)) {
    echo json_encode(['error' => 'No guest chats']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Buat topik baru
    $firstUserMessage = null;
    foreach ($chats as $chat) {
        if ($chat['sender'] === 'user') {
            $firstUserMessage = $chat['message'];
            break;
        }
    }

    $title = $firstUserMessage ?? 'Topik Guest ' . date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO chat_topics (user_id, title) VALUES (?, ?)");
    $stmt->execute([$user_id, substr($title, 0, 50)]); // Batasi panjang judul
    $topic_id = $pdo->lastInsertId();

    // Masukkan pesan satu per satu
    $stmt = $pdo->prepare("INSERT INTO messages (topic_id, user_id, sender, message) VALUES (?, ?, ?, ?)");
    foreach ($chats as $chat) {
        $sender = $chat['sender'] === 'user' ? 'user' : 'bot';
        $message = $chat['message'];
        $stmt->execute([$topic_id, $user_id, $sender, $message]);
    }

    echo json_encode([
        'message' => 'Migrasi berhasil',
        'topic_id' => $topic_id
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
