<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => '請先登入'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);
$seat_id = $data['seat_id'] ?? null;
$start_date = $data['start_date'] ?? null;
$end_date = $data['end_date'] ?? null;

if (!$seat_id || !$start_date || !$end_date) {
    echo json_encode([
        'success' => false,
        'message' => '缺少必要欄位'
    ]);
    exit;
}

try {
    // ✅ 先檢查：該座位在該期間有無其他人預約
    $seatCheck = $pdo->prepare("
        SELECT 1 FROM reservation
        WHERE seat_id = :seat_id
          AND (
              (:start_date BETWEEN start_date AND end_date) OR
              (:end_date BETWEEN start_date AND end_date) OR
              (start_date BETWEEN :start_date AND :end_date) OR
              (end_date BETWEEN :start_date AND :end_date)
          )
        LIMIT 1
    ");
    $seatCheck->execute([
        ':seat_id' => $seat_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    if ($seatCheck->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => '此座位在指定期間內已有預約'
        ]);
        exit;
    }

    // ✅ 再檢查：此使用者是否在該期間內已有任何座位預約（不得重複預約不同座位）
    $userCheck = $pdo->prepare("
        SELECT 1 FROM reservation
        WHERE user_id = :user_id
          AND (
              (:start_date BETWEEN start_date AND end_date) OR
              (:end_date BETWEEN start_date AND end_date) OR
              (start_date BETWEEN :start_date AND :end_date) OR
              (end_date BETWEEN :start_date AND :end_date)
          )
        LIMIT 1
    ");
    $userCheck->execute([
        ':user_id' => $user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    if ($userCheck->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => '您在此期間已預約其他座位，無法重複預約'
        ]);
        exit;
    }

    // ✅ 通過所有檢查，新增預約
    $stmt = $pdo->prepare("
        INSERT INTO reservation (seat_id, user_id, start_date, end_date)
        VALUES (:seat_id, :user_id, :start_date, :end_date)
    ");
    $stmt->execute([
        ':seat_id' => $seat_id,
        ':user_id' => $user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    echo json_encode([
        'success' => true,
        'reservation_id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '伺服器錯誤：' . $e->getMessage()
    ]);
}