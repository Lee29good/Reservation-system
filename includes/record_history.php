<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php'; // 假設這裡已正確建立了 $pdo

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => '未登入']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 接收查詢參數
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

try {
    $sql = "SELECT r.*, s.room_type, s.position 
            FROM reservation r
            JOIN seat s ON r.seat_id = s.seat_id
            WHERE r.user_id = :user_id 
              AND r.status = 'reserved'";

    // 如果有提供日期，就加上篩選條件（交集邏輯）
    if ($start_date && $end_date) {
        $sql .= " AND NOT (r.end_date < :start_date OR r.start_date > :end_date)";
    }

    $sql .= " ORDER BY r.start_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // 若有傳時間才綁定
    if ($start_date && $end_date) {
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
    }

    $stmt->execute();
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$history) {
        error_log("查無資料 for user_id: $user_id, start: $start_date, end: $end_date");
    }

    $json = json_encode($history);

    if ($json === false) {
        error_log("JSON 編碼失敗：" . json_last_error_msg());
        http_response_code(500);
        echo json_encode(['error' => 'JSON 編碼失敗']);
        exit;
    }

    echo $json;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'SQL 執行失敗', 'details' => $e->getMessage()]);
}