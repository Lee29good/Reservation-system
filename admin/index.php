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

if (!$is_admin) {
  header("Location: ../index.php");
  exit;
}

// 連接資料庫
include '../includes/db.php';

// 取得今天日期
$current_date = date('Y-m-d');

// ✅ 查詢「今天有預約的所有使用者」資料
$sql = "SELECT r.reservation_id, r.start_date, r.end_date, r.status,
               s.room_type, s.position, s.seat_id,
               u.username
        FROM reservation r
        JOIN seat s ON r.seat_id = s.seat_id
        JOIN users u ON r.user_id = u.user_id
        WHERE :current_date BETWEEN r.start_date AND r.end_date
          AND r.status IN ('reserved', 'checked_in')";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasReservation = !empty($reservations);

} catch (Exception $e) {
    header("Location: ../login.php");
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
            background-image: url("../images/bg.jpg");
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

    <?php include 'admin_navbar.php'; ?>

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
              <div class="col-12 col-md-8 col-lg-6">
                  <div class="card mb-4 shadow-sm">
                      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white" style="min-height: 60px;">
                          <span>預約空間：<?= htmlspecialchars($reservation['seat_id']) ?></span>
                          <?php if ($reservation['status'] === 'reserved'): ?>
                              <div class="d-flex gap-1">
                                  <form action="/Reservation-system/includes/check_in.php" method="POST" class="m-0">
                                      <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['reservation_id']) ?>">
                                      <button type="submit" class="btn btn-sm" style="background-color: #20c997; color: white;">簽到</button>
                                  </form>
                                  <form action="/Reservation-system/includes/cancel_reservation.php" method="POST" onsubmit="return confirm('確定要取消這筆預約嗎？');" class="m-0">
                                      <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['reservation_id']) ?>">
                                      <button type="submit" class="btn btn-danger btn-sm">取消預約</button>
                                  </form>
                              </div>
                          <?php endif; ?>
                      </div>

                      <div class="card-body text-start">
                          <p><strong>使用者：</strong><?= htmlspecialchars($reservation['username']) ?></p>
                          <p><strong>空間類型：</strong><?= htmlspecialchars($reservation['room_type']) ?></p>
                          <p><strong>座位位置：</strong><?= htmlspecialchars($reservation['seat_id']) ?></p>
                          <p><strong>開始日期：</strong><?= htmlspecialchars($reservation['start_date']) ?></p>
                          <p><strong>結束日期：</strong><?= htmlspecialchars($reservation['end_date']) ?></p>
                          <p><strong>目前狀態：</strong>
                              <?php if ($reservation['status'] === 'checked_in'): ?>
                                  <span class="text-success">✅ 已簽到</span>
                              <?php elseif ($reservation['status'] === 'reserved'): ?>
                                  <span class="text-danger">❌ 未簽到</span>
                              <?php elseif ($reservation['status'] === 'cancelled'): ?>
                                  <span class="text-muted">已取消</span>
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