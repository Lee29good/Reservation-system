<?php
// 開啟 session，檢查是否已登入
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

// 連接資料庫
include '../includes/db.php';

// 取得前端傳來的資料
$data = json_decode(file_get_contents('php://input'), true);

$block_start_date = $data['block_start_date'] ?? '';
$block_end_date = $data['block_end_date'] ?? '';
$seat_id = $data['seat_id'] ?? '';
$reason = $data['reason'] ?? '';

// 檢查必要欄位是否存在
if (empty($block_start_date) || empty($block_end_date) || empty($seat_id) || empty($reason)) {
    echo json_encode(['success' => false, 'message' => '資料不完全']);
    exit;
}

// 檢查結束時間是否大於等於開始時間
if ($block_end_date < $block_start_date) {
    echo json_encode(['success' => false, 'message' => '結束時間必須大於等於開始時間']);
    exit;
}

try {
    // 檢查 seat_id 是否存在於座位資料表
    $checkSeatSql = "SELECT COUNT(*) FROM seat WHERE seat_id = :seat_id";
    $stmt = $pdo->prepare($checkSeatSql);
    $stmt->bindParam(':seat_id', $seat_id, PDO::PARAM_INT);
    $stmt->execute();
    $seatExists = $stmt->fetchColumn();

    if ($seatExists == 0) {
        echo json_encode(['success' => false, 'message' => '所選座位不存在']);
        exit;
    }

    // 插入資料到 block_time 表
    $sql = "INSERT INTO block_time (block_start_date, block_end_date, seat_id, reason) 
            VALUES (:block_start_date, :block_end_date, :seat_id, :reason)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':block_start_date', $block_start_date, PDO::PARAM_STR);
    $stmt->bindParam(':block_end_date', $block_end_date, PDO::PARAM_STR);
    $stmt->bindParam(':seat_id', $seat_id, PDO::PARAM_INT);
    $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '儲存失敗，請稍後再試']);
    }
} catch (Exception $e) {
    // 錯誤處理
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
}
?>