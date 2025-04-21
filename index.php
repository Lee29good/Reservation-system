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
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>預約查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url("images/bg.jpg");
            background-size: cover;      /* 背景填滿畫面 */
            background-repeat: no-repeat; /* 不重複 */
            background-position: center;  /* 置中 */
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
        <div class="icon mb-3">🔍</div>
        <?php if (!$hasReservation): ?>
            <h4 class="mb-5 text-muted">查無預約資料</h4>
        <?php else: ?>
            <h4 class="mb-5 text-muted">您有預約資訊</h4>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">預約違規記錄：</div>
                    <div class="card-body">
                        <?= $user['violation_record'] ? htmlspecialchars($user['violation_record']) : '無' ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">預約違規停權：</div>
                    <div class="card-body">
                        <?= $user['banned'] ? '是' : '無' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>