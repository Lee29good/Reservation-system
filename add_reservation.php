<?php
session_start();
// 可加上登入驗證：若未登入則跳轉 login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>新增預約</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .reservation-btn {
            margin-bottom: 15px;
            padding: 15px 25px;
            font-size: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px;
            color: white;
            transition: transform 0.2s;
        }
        .reservation-btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
        .small-room { background: linear-gradient(90deg, #45c4b0, #37b59f); }
        .large-room { background: linear-gradient(90deg, #00a8cc, #007ea7); }
        .daily-research { background: linear-gradient(90deg, #a1c349, #82b300); }
        .longterm-research { background: linear-gradient(90deg, #95d5b2, #74c69d); }

        .icon {
            font-size: 24px;
            margin-right: 12px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <a href="reserve_small.php" class="btn reservation-btn small-room">
                <span><i class="icon">💬</i>小型討論室</span>
                ➤
            </a>
            <a href="reserve_large.php" class="btn reservation-btn large-room">
                <span><i class="icon">👥</i>大型討論室</span>
                ➤
            </a>
            <a href="reserve_daily.php" class="btn reservation-btn daily-research">
                <span><i class="icon">🏛️</i>當日研究室</span>
                ➤
            </a>
            <a href="reserve_longterm.php" class="btn reservation-btn longterm-research">
                <span><i class="icon">✏️</i>長期研究室</span>
                ➤
            </a>
        </div>
    </div>
</body>
</html>
