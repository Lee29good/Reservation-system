<?php
session_start();

// ✅ 如果沒登入就導回 login 頁面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 取出使用者資訊
$username = $_SESSION['username'];
$is_admin = $_SESSION['is_admin'];

// 連接資料庫
include './includes/db.php';

// 取出使用者的 ID 並取得當前日期
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// 查詢cancelled,狀態為 reserved 的預約
$sql = "SELECT r.reservation_id, r.start_date, r.end_date, s.room_type, s.position, s.seat_id, r.status 
        FROM reservation r
        JOIN seat s ON r.seat_id = s.seat_id
        WHERE r.user_id = :user_id 
          AND r.end_date >= :current_date 
          AND r.status IN ('reserved', 'checked_in')";

try {
    if (!isset($pdo)) {
        throw new Exception("資料庫連接未初始化！");
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasReservation = !empty($reservations);

    // 在這裡你可以把 $reservations 拿去渲染到 HTML 或傳回前端
} catch (Exception $e) {
    // 可依需求記錄錯誤日誌，但不顯示錯誤給使用者
    // error_log($e->getMessage());
    header("Location: error.php"); // 或導去錯誤頁面
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>預約查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url("images/bg.jpg");
            background-size: cover;      /* 背景填滿畫面 */
            background-repeat: no-repeat; /* 不重複 */
            background-position: center;  /* 置中 */
            background-attachment: fixed; /* ✅ 讓背景固定 */
        }
        .navbar {
            background-color: #1f2a38;
        }
        .navbar a {
            color: white !important;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 3rem;
            color: #888;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container text-center mt-5">
         <!-- ✅ 成功取消預約的提示訊息 -->
        <?php if (isset($_GET['cancel']) && $_GET['cancel'] === 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                預約已成功取消。
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="icon mb-3">🔍</div>
        <?php if (!$hasReservation): ?>
            <h4 class="mb-5 text-muted">查無預約資料</h4>
            <?php else: ?>
            <h4 class="mb-5 text-muted">您有預約資訊</h4>
            <div class="row justify-content-center">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="col-12 col-md-8 col-lg-6"> <!-- 控制最大寬度 -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white" style="min-height: 60px;">
                                <span>預約空間：<?= htmlspecialchars($reservation['seat_id']) ?></span>
                                <?php if ($reservation['status'] === 'reserved'): ?>
                                    <div class="d-flex gap-1">
                                        <form action="/Reservation-system/includes/cancel_reservation.php" method="POST" onsubmit="return confirm('確定要取消這筆預約嗎？');" class="m-0">
                                            <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['reservation_id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">取消預約</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body text-start">
                                <p><strong>空間類型：</strong><?= htmlspecialchars($reservation['room_type']) ?></p>
                                <p><strong>開始日期：</strong><?= htmlspecialchars($reservation['start_date']) ?></p>
                                <p><strong>結束日期：</strong><?= htmlspecialchars($reservation['end_date']) ?></p>

                                <!-- 顯示簽到狀態 -->
                                <p class="mt-3">
                                    <strong>目前狀態：</strong>
                                    <?php if ($reservation['status'] === 'checked_in'): ?>
                                        <span class="text-success">✅ 已簽到</span>
                                    <?php elseif ($reservation['status'] === 'reserved'): ?>
                                        <span class="text-primary">📅 已預約</span>
                                    <?php elseif ($reservation['status'] === 'cancelled'): ?>
                                        <span class="text-danger">❌ 已取消</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>