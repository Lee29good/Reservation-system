<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false; // 如果沒有設定，預設不是管理員

// 獲取篩選參數（如果有提供）
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$username = isset($_GET['username']) ? $_GET['username'] : null;

$params = [];
$whereClauses = [];

if ($start_date && $end_date) {
    // 包括選擇日期範圍內的紀錄
    $whereClauses[] = "(r.start_date BETWEEN :start_date AND :end_date OR r.end_date BETWEEN :start_date AND :end_date OR (r.start_date <= :start_date AND r.end_date >= :end_date))";
    $params[':start_date'] = $start_date;
    $params[':end_date'] = $end_date;
}

if ($username) {
    $whereClauses[] = "u.username LIKE :username";
    $params[':username'] = '%' . $username . '%'; // 用於模糊查詢
}

$sql = "SELECT r.status, r.start_date, r.end_date, s.room_type, s.position, s.seat_id, u.username
        FROM reservation r
        JOIN seat s ON r.seat_id = s.seat_id
        JOIN users u ON r.user_id = u.user_id";

// 先判斷是否是管理員，如果不是，添加用戶ID限制
if (!$is_admin) {
    $whereClauses[] = "r.user_id = :user_id";
    $params[':user_id'] = $user_id;
}

// 然後添加WHERE子句
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

$sql .= " ORDER BY r.end_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));