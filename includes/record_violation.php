<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

try {
    $sql = "
        SELECT vr.violation_id, vr.user_id, vr.seat_id, vr.violation_date, vr.violation_type
        FROM violation_record vr
        WHERE vr.user_id = :user_id
        ORDER BY vr.violation_date DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤：' . $e->getMessage()]);
}