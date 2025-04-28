<?php
// 開啟 session，檢查是否已登入
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '未登入']);
    exit;
}

// 連線資料庫
require_once 'db.php'; // 注意路徑是否正確

header('Content-Type: application/json');

// 取得前端傳來的 JSON
$data = json_decode(file_get_contents('php://input'), true);

// 確認收到必要資料
if (empty($data['username']) || empty($data['violation_space']) || empty($data['violation_date']) || empty($data['violation_type'])) {
    echo json_encode(['success' => false, 'message' => '缺少必要欄位']);
    exit;
}

$username = trim($data['username']);
$violation_space = trim($data['violation_space']);
$violation_date = trim($data['violation_date']);
$violation_type = trim($data['violation_type']);

try {
    
    // 1. 查找 user_id
    $sqlUser = "SELECT user_id FROM users WHERE username = :username";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtUser->execute();
    $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        echo json_encode(['success' => false, 'message' => '找不到使用者']);
        exit;
    }
    $user_id = $userRow['user_id'];

    // 2. 查找 seat_id
    $sqlSeat = "SELECT seat_id FROM seat WHERE seat_id = :position";
    $stmtSeat = $pdo->prepare($sqlSeat);
    $stmtSeat->bindParam(':position', $violation_space, PDO::PARAM_STR);
    $stmtSeat->execute();
    $seatRow = $stmtSeat->fetch(PDO::FETCH_ASSOC);

    if (!$seatRow) {
        echo json_encode(['success' => false, 'message' => '找不到對應的座位']);
        exit;
    }
    $seat_id = $seatRow['seat_id'];


    // 3. 確認 reservation 中是否存在這筆資料
    $sqlReservation = "SELECT * FROM reservation 
    WHERE user_id = :user_id AND seat_id = :seat_id 
    AND :violation_date BETWEEN start_date AND end_date";
    $stmtReservation = $pdo->prepare($sqlReservation);
    $stmtReservation->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtReservation->bindParam(':seat_id', $seat_id, PDO::PARAM_INT);
    $stmtReservation->bindParam(':violation_date', $violation_date, PDO::PARAM_STR);
    $stmtReservation->execute();
    $reservationRow = $stmtReservation->fetch(PDO::FETCH_ASSOC);

    if (!$reservationRow) {
    echo json_encode(['success' => false, 'message' => '該使用者在此座位於違規日期沒有預約紀錄，無法登記違規']);
    exit;
    }

    // 4. 確認 violation_record 中是否已存在這筆紀錄
    $sqlCheckViolation = "SELECT * FROM violation_record 
                          WHERE user_id = :user_id AND seat_id = :seat_id AND violation_date = :violation_date";
    $stmtCheckViolation = $pdo->prepare($sqlCheckViolation);
    $stmtCheckViolation->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtCheckViolation->bindParam(':seat_id', $seat_id, PDO::PARAM_INT);
    $stmtCheckViolation->bindParam(':violation_date', $violation_date, PDO::PARAM_STR);
    $stmtCheckViolation->execute();
    $violationRow = $stmtCheckViolation->fetch(PDO::FETCH_ASSOC);

    if ($violationRow) {
        echo json_encode(['success' => false, 'message' => '此違規紀錄已存在，不能重複登記']);
        exit;
    }

    // 5. 寫入 violation_record
    $sqlInsert = "INSERT INTO violation_record (user_id, seat_id, violation_date, violation_type) 
                  VALUES (:user_id, :seat_id, :violation_date, :violation_type)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtInsert->bindParam(':seat_id', $seat_id, PDO::PARAM_INT);
    $stmtInsert->bindParam(':violation_date', $violation_date, PDO::PARAM_STR);
    $stmtInsert->bindParam(':violation_type', $violation_type, PDO::PARAM_STR);

    if ($stmtInsert->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '儲存失敗，請稍後再試']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => '系統錯誤: ' . $e->getMessage()]);
}
?>