<?php
session_start();
require 'connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['topic_id'])) {
    echo json_encode(['messages' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];
$topic_id = intval($_GET['topic_id']);

$stmt = $pdo->prepare("SELECT sender, message FROM messages WHERE topic_id = ? AND user_id = ? ORDER BY id ASC");
$stmt->execute([$topic_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['messages' => $messages]);
