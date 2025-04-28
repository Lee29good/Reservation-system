<?php

header('Content-Type: application/json');
require_once 'db.php'; // 根據你的路徑調整

try {
    $sql = "
      SELECT block_start_date, block_end_date, seat_id, reason, block_id
      FROM block_time
      WHERE block_end_date >= CURDATE()
      ORDER BY seat_id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤：' . $e->getMessage()]);
}