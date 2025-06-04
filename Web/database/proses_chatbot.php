<?php
require 'connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if (!isset($_POST['question']) || empty(trim($_POST['question']))) {
    echo json_encode(['response' => 'Pertanyaan kosong atau tidak dikirim.']);
    exit;
}

$question = trim($_POST['question']);
$id_topik = 0; // Bisa diganti sesuai kebutuhan


// Kirim ke Gemini API
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
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['response' => 'Gagal menghubungi Gemini: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    file_put_contents("log.txt", "HTTP $http_code: " . $response);
    echo json_encode(['response' => 'Gagal mendapatkan respons dari Gemini API.']);
    exit;
}

$json = json_decode($response, true);

if (!isset($json['candidates'][0]['content']['parts'][0]['text'])) {
    file_put_contents("log.txt", "Invalid JSON: " . $response);
    echo json_encode(['response' => 'Gemini tidak memberikan jawaban yang dapat diproses.']);
    exit;
}

$answer = $json['candidates'][0]['content']['parts'][0]['text'];

$created = date('Y-m-d H:i:s');
$stmt = $pdo->prepare("INSERT INTO chat (id_topik, question, answer, created) VALUES (?, ?, ?, ?)");
$stmt->execute([$id_topik, $question, $answer, $created]);

echo json_encode(['response' => $answer]);
file_put_contents("log.txt", $response); // Untuk debug