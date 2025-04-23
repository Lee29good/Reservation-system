<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservationId = $_POST['reservation_id'];

    $stmt = $pdo->prepare("UPDATE reservation SET status = 'cancelled' WHERE reservation_id = ?");
    if ($stmt->execute([$reservationId])) {
        // ✅ 修改這裡：取消成功後導回主頁並附上狀態參數
        header("Location: /Reservation-system/index.php?cancel=success");
        exit;
    } else {
        // ❌ 取消失敗
        header("Location: /Reservation-system/index.php?cancel=fail");
        exit;
    }
}
?>