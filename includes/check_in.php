<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo "權限不足";
    exit;
}

$reservation_id = $_POST['reservation_id'] ?? null;

if (!$reservation_id) {
    echo "缺少 reservation_id";
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE reservation SET status = 'checked_in' WHERE reservation_id = :id");
    $stmt->execute([':id' => $reservation_id]);

    header("Location: ../admin/index.php?checkin=success");
    exit;
} catch (Exception $e) {
    echo "簽到失敗：" . $e->getMessage();
}
?>