<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.status,r.start_date, r.end_date, s.room_type, s.position, s.seat_id 
        FROM reservation r
        JOIN seat s ON r.seat_id = s.seat_id
        WHERE r.user_id = :user_id
        ORDER BY r.end_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':user_id' => $user_id,
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));