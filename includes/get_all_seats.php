<?php
require 'db.php'; // 這裡會取得 $pdo

$room_type = $_GET['room_type'] ?? '';
$position = $_GET['position'] ?? ''; // 加入 position

// 用 prepared statement 查詢
$sql = "SELECT * FROM seat WHERE room_type = ? AND position = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$room_type, $position]);

$seats = $stmt->fetchAll(PDO::FETCH_ASSOC); // 把結果抓出來轉成陣列

header('Content-Type: application/json'); // 設定正確的 Content-Type
echo json_encode($seats); // 輸出成 JSON 給前端使用
?>