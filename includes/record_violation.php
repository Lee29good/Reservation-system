<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;
$username = $_GET['username'] ?? null;

// 補上結束時間的時分秒（確保整天都有涵蓋）
if ($end_date) {
    $end_date .= ' 23:59:59';
}

try {
    // 查詢是否為管理員
    $checkAdminStmt = $pdo->prepare("SELECT is_admin FROM users WHERE user_id = :user_id");
    $checkAdminStmt->execute([':user_id' => $user_id]);
    $user = $checkAdminStmt->fetch(PDO::FETCH_ASSOC);
    $isAdmin = $user && $user['is_admin'] == 1;

    $params = [];
    $where = "WHERE 1=1";

    // 管理員可以依 username 搜尋
    if ($isAdmin && $username) {
        $where .= " AND u.username = :username";
        $params[':username'] = $username;
    } 
    // 一般使用者只能看自己的
    elseif (!$isAdmin) {
        $where .= " AND vr.user_id = :user_id";
        $params[':user_id'] = $user_id;
    }

    // 日期篩選
    if ($start_date && $end_date) {
        $where .= " AND vr.violation_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $start_date;
        $params[':end_date'] = $end_date;
    }

    // 管理員查詢時 JOIN user 資料
    $sql = $isAdmin
        ? "
            SELECT vr.violation_id, vr.user_id, u.username, vr.seat_id, vr.violation_date, vr.violation_type
            FROM violation_record vr
            JOIN users u ON vr.user_id = u.user_id
            $where
            ORDER BY vr.violation_date DESC
        "
        : "
            SELECT vr.violation_id, vr.user_id, vr.seat_id, vr.violation_date, vr.violation_type
            FROM violation_record vr
            $where
            ORDER BY vr.violation_date DESC
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤：' . $e->getMessage()]);
}
?>