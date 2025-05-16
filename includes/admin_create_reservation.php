<?php 
session_start(); 
header('Content-Type: application/json');

// 確認管理員已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '尚未登入']);
    exit;
}

// 驗證是否為管理員（如果需要）
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'message' => '只有管理員可以執行此操作']);
    exit;
}

require_once 'db.php';

// 取得前端傳來的資料
$data = json_decode(file_get_contents('php://input'), true);

// 驗證並過濾輸入資料
$seat_id = filter_var($data['seat_id'] ?? null, FILTER_VALIDATE_INT);
$start_date_raw = trim($data['start_date'] ?? '');
$end_date_raw = trim($data['end_date'] ?? '');
$borrower_name_raw = trim(strip_tags($data['borrower_name'] ?? ''));

// 基本檢查
if (!$seat_id || !$start_date_raw || !$end_date_raw || !$borrower_name_raw) {
    echo json_encode(['success' => false, 'message' => '資料不完整或格式錯誤']);
    exit;
}

// 驗證日期格式（確保是 YYYY-MM-DD）
$start_dt = DateTime::createFromFormat('Y-m-d', $start_date_raw);
$end_dt = DateTime::createFromFormat('Y-m-d', $end_date_raw);

if (!$start_dt || $start_dt->format('Y-m-d') !== $start_date_raw) {
    echo json_encode(['success' => false, 'message' => '開始日期格式錯誤']);
    exit;
}
if (!$end_dt || $end_dt->format('Y-m-d') !== $end_date_raw) {
    echo json_encode(['success' => false, 'message' => '結束日期格式錯誤']);
    exit;
}

// 確認結束日期在開始日期之後
if ($end_dt < $start_dt) {
    echo json_encode(['success' => false, 'message' => '結束日期必須在開始日期之後']);
    exit;
}

// ✅ 新增檢查：開始日期不可早於今天，且不可超過 30 天後
$today = new DateTime('today');
$maxDate = (clone $today)->modify('+30 days');

if ($start_dt < $today) {
    echo json_encode(['success' => false, 'message' => '開始日期不可早於今天']);
    exit;
}
if ($start_dt > $maxDate) {
    echo json_encode(['success' => false, 'message' => '開始日期不可晚於 30 天後（' . $maxDate->format('Y-m-d') . '）']);
    exit;
}

// 檢查租借人姓名只允許中英文、數字及空白
if (!preg_match('/^[\p{L}\p{N}\s]+$/u', $borrower_name_raw)) {
  echo json_encode(['success' => false, 'message' => '租借人姓名格式不正確，只能包含中文、英文、數字與空白']);
  exit;
}

// 將清理後的資料存回變數
$start_date = $start_date_raw;
$end_date = $end_date_raw;
$borrower_name = $borrower_name_raw;

try {
    $pdo->beginTransaction();
    
    // 1. 查 borrower_name 對應的 user_id
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
    $stmt->execute([':username' => $borrower_name]);
    $borrower = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$borrower) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => '找不到使用者：' . htmlspecialchars($borrower_name)]);
        exit;
    }
    
    $borrower_user_id = $borrower['user_id'];
    
    // 2. 確認座位沒被預約
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservation 
                            WHERE seat_id = :seat_id 
                            AND status != 'cancelled'
                            AND (start_date <= :end_date AND end_date >= :start_date)");
    $stmt->execute([
        ':seat_id' => $seat_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);
    if ($stmt->fetchColumn() > 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => '該座位在選定時段已被預約']);
        exit;
    }
    
    // 3. 確認使用者沒有重複預約
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservation 
                            WHERE user_id = :user_id 
                            AND status != 'cancelled'
                            AND (start_date <= :end_date AND end_date >= :start_date)");
    $stmt->execute([
        ':user_id' => $borrower_user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);
    if ($stmt->fetchColumn() > 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => '此使用者在選定時段已有預約']);
        exit;
    }
    
    // 4. 新增預約
    $stmt = $pdo->prepare("INSERT INTO reservation (user_id, seat_id, start_date, end_date, status)
                            VALUES (:user_id, :seat_id, :start_date, :end_date, 'reserved')");
    $result = $stmt->execute([
        ':user_id' => $borrower_user_id,
        ':seat_id' => $seat_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);
    
    if ($result) {
        $pdo->commit();
        echo json_encode([
            'success' => true, 
            'message' => '預約成功',
            'reservation_id' => $pdo->lastInsertId()
        ]);
    } else {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => '預約建立失敗']);
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    if ($e->getCode() == 23000) { // 唯一性錯誤
        if (strpos($e->getMessage(), 'uq_user_time_status') !== false) {
            echo json_encode(['success' => false, 'message' => '此使用者在選定時段已有預約']);
        } else if (strpos($e->getMessage(), 'uq_seat_time_status') !== false) {
            echo json_encode(['success' => false, 'message' => '該座位在選定時段已被預約']);
        } else {
            echo json_encode(['success' => false, 'message' => '預約衝突，請選擇其他時段或座位']);
        }
    } else {
        error_log('預約系統錯誤: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => '資料庫錯誤，請聯繫管理員']);
    }
}
?>