<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => '未登入']);
    exit;
}

require_once 'db.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $violation_id = $data['violation_id'] ?? null;

    if (!$violation_id) {
        echo json_encode(['success' => false, 'error' => '缺少違規ID']);
        exit;
    }

    // 驗證：只允許刪自己的 or 管理員可以刪
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
        SELECT vr.user_id, u.is_admin
        FROM violation_record vr
        JOIN users u ON u.user_id = :user_id
        WHERE vr.violation_id = :violation_id
    ");
    $stmt->execute([
        ':violation_id' => $violation_id,
        ':user_id' => $user_id
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode(['success' => false, 'error' => '找不到資料或無權限']);
        exit;
    }

    // 一般使用者只能刪自己的違規
    if ($result['user_id'] != $user_id && $result['is_admin'] != 1) {
        echo json_encode(['success' => false, 'error' => '無權限刪除']);
        exit;
    }

    // 刪除
    $deleteStmt = $pdo->prepare("DELETE FROM violation_record WHERE violation_id = :violation_id");
    $deleteStmt->execute([':violation_id' => $violation_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => '資料庫錯誤：' . $e->getMessage()]);
}
?>