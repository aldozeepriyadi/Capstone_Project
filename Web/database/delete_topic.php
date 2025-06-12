<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$topic_id = $data['topic_id'] ?? null;

if ($topic_id) {
    // Hapus pesan-pesan dulu
    $stmt1 = $pdo->prepare("DELETE FROM messages WHERE topic_id = ? AND user_id = ?");
    $stmt1->execute([$topic_id, $_SESSION['user_id']]);

    // Hapus topiknya
    $stmt2 = $pdo->prepare("DELETE FROM chat_topics WHERE id = ? AND user_id = ?");
    $stmt2->execute([$topic_id, $_SESSION['user_id']]);

    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
}
