<?php
require 'db.php';

$room_type = $_GET['room_type'];
$start = $_GET['start']; // e.g. '2025-04-21'
$end = $_GET['end'];     // e.g. '2025-04-22'

$sql = "
    SELECT DISTINCT r.seat_id
    FROM reservation r
    JOIN seat s ON r.seat_id = s.seat_id
    WHERE s.room_type = :room_type
    AND NOT (r.end_date < :start OR r.start_date > :end)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':room_type' => $room_type,
    ':start' => $start,
    ':end' => $end
]);

$reservedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($reservedSeats);
?>