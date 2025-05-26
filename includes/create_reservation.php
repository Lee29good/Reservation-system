<?php
session_start();
require_once 'db.php';
require 'vendor/autoload.php';     // 先載入所有 Composer 套件

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => '請先登入'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);
$seat_id = $data['seat_id'] ?? null;
$start_date = $data['start_date'] ?? null;
$end_date = $data['end_date'] ?? null;

if (!$seat_id || !$start_date || !$end_date) {
    echo json_encode([
        'success' => false,
        'message' => '缺少必要欄位'
    ]);
    exit;
}

// ✅ 檢查日期是否在未來 30 天內
$today = new DateTime();
$today->setTime(0, 0, 0);  // ⬅️ 重點！把時間歸零
$max_date = (clone $today)->modify('+30 days');
$start_dt = new DateTime($start_date);
$start_dt->setTime(0, 0, 0);  // ⬅️ 把傳入的時間也歸零
$end_dt = new DateTime($end_date);
$end_dt->setTime(0, 0, 0);

if ($start_dt < $today || $end_dt > $max_date) {
    echo json_encode([
        'success' => false,
        'message' => '預約日期必須是今天起算的 30 天內'
    ]);
    exit;
}

try {
    // ✅ 1. 檢查座位是否已被預約
    $seatCheck = $pdo->prepare("
        SELECT 1 FROM reservation
        WHERE seat_id = :seat_id
          AND status != 'cancelled'
          AND (
              (:start_date BETWEEN start_date AND end_date) OR
              (:end_date BETWEEN start_date AND end_date) OR
              (start_date BETWEEN :start_date AND :end_date) OR
              (end_date BETWEEN :start_date AND :end_date)
        )
        LIMIT 1
    ");
    $seatCheck->execute([
        ':seat_id' => $seat_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    if ($seatCheck->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => '此座位在指定期間內已有預約'
        ]);
        exit;
    }

    // ✅ 2. 檢查此使用者是否已有其他預約
    $userCheck = $pdo->prepare("
        SELECT 1 FROM reservation
        WHERE user_id = :user_id
          AND status != 'cancelled'
          AND (
              (:start_date BETWEEN start_date AND end_date) OR
              (:end_date BETWEEN start_date AND end_date) OR
              (start_date BETWEEN :start_date AND :end_date) OR
              (end_date BETWEEN :start_date AND :end_date)
        )
        LIMIT 1
    ");
    $userCheck->execute([
        ':user_id' => $user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    if ($userCheck->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => '您在此期間已預約其他座位，無法重複預約'
        ]);
        exit;
    }

    // ✅ 3. 插入預約
    $stmt = $pdo->prepare("
        INSERT INTO reservation (seat_id, user_id, start_date, end_date, status)
        VALUES (:seat_id, :user_id, :start_date, :end_date, 'reserved')
    ");
    $stmt->execute([
        ':seat_id' => $seat_id,
        ':user_id' => $user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);

    $reservation_id = $pdo->lastInsertId();

    // ✅ 4. 查出使用者 email 和名字
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = $user['username'] ?? '使用者';  
    $user_email = $user['email'] ?? null;

    if ($user_email) {
        // ✅ 5. 寄送 Email（使用 PHPMailer + Gmail SMTP）
        $mail = new PHPMailer(true);  // true 代表會自動 throw Exception

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'LiLee20020404@gmail.com';          // ← 改成你的 Gmail
            $mail->Password = 'xxxxxxxxxxxxxxxxxxxxx';                 // ← 剛剛產生的應用程式密碼
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('LiLee20020404@gmail.com', '座位預約系統');  // 寄件人
            $mail->addAddress($user_email, $user_name);                 // 收件人

            $mail->Subject = '座位預約成功通知';
            $mail->Body = <<<EOT
        親愛的 {$user_name}，您好：

        您已成功預約以下座位：

        座位編號：{$seat_id}
        預約期間：{$start_date} ~ {$end_date}

        請準時使用此座位，若不使用請務必提前取消預約。

        祝您有個愉快的一天！

        座位預約系統 敬上
        EOT;

            $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer 發信失敗：" . $mail->ErrorInfo);
            // ⚠️ 注意：為避免中斷流程，不要 echo 錯誤訊息，直接 log 即可
        }
    }

    echo json_encode([
        'success' => true,
        'reservation_id' => $reservation_id
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '伺服器錯誤：' . $e->getMessage()
    ]);
}
?>