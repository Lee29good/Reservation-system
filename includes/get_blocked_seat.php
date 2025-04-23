<?php
header('Content-Type: application/json');
require_once 'db.php';

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

if (!$start || !$end) {
    echo json_encode(['error' => '缺少參數 start 或 end']);
    exit;
}

try {
    // 查詢在所選日期區間內有被封鎖的座位
    $sql = "
        SELECT DISTINCT seat_id
        FROM block_time
        WHERE NOT (
            block_end_date < :start OR
            block_start_date > :end
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':start' => $start,
        ':end' => $end
    ]);

    $blockedSeatIds = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'seat_id');
    echo json_encode($blockedSeatIds);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤：' . $e->getMessage()]);
}