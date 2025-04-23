<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;

if (!$start || !$end) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT r.start_date, r.end_date, s.room_type, s.position, s.seat_id 
        FROM reservation r
        JOIN seat s ON r.seat_id = s.seat_id
        WHERE r.user_id = :user_id
          AND r.status = 'cancelled'
          AND r.start_date >= :start AND r.end_date <= :end";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':user_id' => $user_id,
    ':start' => $start,
    ':end' => $end,
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));