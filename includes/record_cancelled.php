<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php'; // 已正確建立 $pdo

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => '未登入']);
    exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false; // 判斷是否 admin
$username = trim($_GET['username'] ?? ''); // username，順便 trim 掉空白

$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

try {
    $params = []; // 綁定參數
    $sql = "SELECT r.*, s.room_type, s.position, u.username
            FROM reservation r
            JOIN seat s ON r.seat_id = s.seat_id
            JOIN users u ON r.user_id = u.user_id
            WHERE r.status = 'cancelled'"; // 查詢取消的紀錄

    if ($is_admin) {
        if ($username !== '') {
            // Admin 且有輸入 username，才加 username 篩選
            $sql .= " AND u.username = :username";
            $params[':username'] = $username;
        }
        // Admin 沒有輸入 username，不加條件，直接查詢所有
    } else {
        // 一般使用者，只能查自己的取消紀錄
        $sql .= " AND r.user_id = :user_id";
        $params[':user_id'] = $user_id;
    }

    if ($start_date && $end_date) {
        $sql .= " AND NOT (r.end_date < :start_date OR r.start_date > :end_date)";
        $params[':start_date'] = $start_date;
        $params[':end_date'] = $end_date;
    }

    $sql .= " ORDER BY r.start_date DESC";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $cancelledHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$cancelledHistory) {
        error_log("查無資料 (user_id: $user_id, username: $username, start: $start_date, end: $end_date)");
    }

    $json = json_encode($cancelledHistory);
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