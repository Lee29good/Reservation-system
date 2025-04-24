<?php
session_start(); // ✅ 加上 session_start() 才能讀取登入者身份

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservationId = $_POST['reservation_id'];

    $stmt = $pdo->prepare("UPDATE reservation SET status = 'cancelled' WHERE reservation_id = ?");
    if ($stmt->execute([$reservationId])) {
        // ✅ 根據使用者是否為 admin 決定跳轉位置
        $redirectUrl = isset($_SESSION['is_admin']) && $_SESSION['is_admin']
            ? '/Reservation-system/admin/index.php?cancel=success'
            : '/Reservation-system/index.php?cancel=success';

        header("Location: $redirectUrl");
        exit;
    } else {
        // ❌ 取消失敗，兩種角色都回首頁
        $redirectUrl = isset($_SESSION['is_admin']) && $_SESSION['is_admin']
            ? '/Reservation-system/admin/index.php?cancel=fail'
            : '/Reservation-system/index.php?cancel=fail';

        header("Location: $redirectUrl");
        exit;
    }
}
?>