<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 登入成功
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        // ✅ 判斷是否為管理員
        if ($user['is_admin']) {
            header("Location: ./admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "帳號或密碼錯誤。";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>登入 | 圖書館預約系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;        /* 🔥 關鍵！讓背景圖片能填滿整個視窗 */
            margin: 0;           /* 移除預設外距 */
            padding: 0;          /* 移除預設內距 */
        }
        body {
            background-image: url("images/bg.jpg");
            background-size: cover;      /* 背景填滿畫面 */
            background-repeat: no-repeat; /* 不重複 */
            background-position: center;  /* 置中 */
            background-attachment: fixed; /* ✅ 讓背景固定 */
        }
        .login-box {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .form-title {
            font-size: 26px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="form-title">圖書館登入</div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">使用者名稱</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">登入</button>
    </form>

    <div class="mt-3 text-center">
        還沒有帳號？<a href="register.php">註冊</a>
    </div>
</div>

</body>
</html>
