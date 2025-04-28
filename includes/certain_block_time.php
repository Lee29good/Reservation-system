<?php
// 開啟 session，檢查是否已登入
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

// 連接資料庫
require_once 'db.php';

// 檢查 GET 是否提供 block_id
if (!isset($_GET['block_id']) || empty($_GET['block_id'])) {
    echo json_encode(['success' => false, 'message' => '缺少 block_id']);
    exit;
}

$block_id = intval($_GET['block_id']);

try {
    // 查詢 block_time 表中該筆資料
    $sql = "SELECT block_id, block_start_date, block_end_date, seat_id, reason 
            FROM block_time 
            WHERE block_id = :block_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':block_id', $block_id, PDO::PARAM_INT);
    $stmt->execute();

    $block = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($block) {
        echo json_encode(['success' => true] + $block);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到資料']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
}
?>