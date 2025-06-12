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
$new_title = $data['title'] ?? '';

if ($topic_id && $new_title) {
    $stmt = $pdo->prepare("UPDATE chat_topics SET title = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([substr($new_title, 0, 255), $topic_id, $_SESSION['user_id']]);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
}
