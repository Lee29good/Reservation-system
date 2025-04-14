<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>紀錄查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('background.png') no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: #1f2a38;
        }

        .nav-link-custom {
            color: white;
            text-decoration: none;
            padding: 10px 0;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-weight: 500;
        }

        .nav-link-custom:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffd700;
        }

        .query-button {
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .btn-purple { background-color: #9b59b6; }
        .btn-blue { background-color: #2980b9; }
        .btn-green { background-color: #1abc9c; }
        .btn-red { background-color: #e74c3c; }
        .btn-yellow { background-color: #f1c40f; color: #1f2a38; }

        .form-control {
            text-align: center;
            font-size: 1rem;
        }

        .date-label {
            font-weight: 600;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5 text-center">
    <form method="GET" action="record_query.php" class="row justify-content-center mb-4">
        <div class="col-md-3">
            <label class="date-label">開始日期</label>
            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label class="date-label">結束日期</label>
            <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
    </form>

    <div class="col-md-6 mx-auto">
        <a href="record_deleted.php" class="btn query-button btn-purple w-100">預約刪除記錄查詢</a>
        <a href="record_history.php" class="btn query-button btn-blue w-100">預約歷史記錄查詢</a>
        <a href="record_room_usage.php" class="btn query-button btn-green w-100">空間使用紀錄查詢</a>
        <a href="record_violation.php" class="btn query-button btn-red w-100">違規記錄查詢</a>
        <a href="record_banned.php" class="btn query-button btn-yellow w-100">停權記錄查詢</a>
    </div>
</div>

</body>
</html>
