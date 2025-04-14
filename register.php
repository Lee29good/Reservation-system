<?php
session_start();
require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ✅ 基本驗證
    if (empty($username) || empty($email) || empty($password)) {
        $error = "請填寫所有欄位。";
    } elseif ($password !== $confirm_password) {
        $error = "兩次密碼輸入不一致。";
    } else {
        // ✅ 檢查帳號或 email 是否重複
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $error = "使用者名稱或 Email 已被註冊。";
        } else {
            // ✅ 加密密碼並寫入資料庫
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, is_admin, create_date, update_date)
                                   VALUES (?, ?, ?, 0, NOW(), NOW())");
            $stmt->execute([$username, $hashed_password, $email]);

            $success = "註冊成功！請登入系統。";
            header("refresh:2;url=login.php"); // 2 秒後跳轉
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>註冊 | 圖書館預約系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f2f4f8;
        }
        .register-box {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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

<div class="register-box">
    <div class="form-title">註冊帳號</div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?><br>
            <small>系統將在 <span id="countdown">2</span> 秒後跳轉至 <a href="login.php">登入頁面</a>。</small>
        </div>

        <script>
            let seconds = 2;
            const countdownEl = document.getElementById('countdown');
            const interval = setInterval(() => {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(interval);
                } else {
                    countdownEl.textContent = seconds;
                }
            }, 1000);
        </script>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="register.php">
        <div class="mb-3">
            <label for="username" class="form-label">使用者名稱</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">電子信箱</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">確認密碼</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <!-- 顯示前端錯誤 -->
        <div class="alert alert-danger d-none" id="clientError"></div>

        <button type="submit" class="btn btn-success w-100">建立帳號</button>
        <div class="mt-3 text-center">
            登入帳號?<a href="register.php"> 登入</a>
        </div>
    </form>

    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const pwd = document.getElementById('password');
        const cpwd = document.getElementById('confirm_password');
        const errorBox = document.getElementById('clientError');

        if (pwd.value !== cpwd.value) {
            e.preventDefault(); // 停止提交
            pwd.value = '';
            cpwd.value = '';
            errorBox.textContent = "兩次密碼輸入不一致，請重新輸入。";
            errorBox.classList.remove('d-none');
            pwd.focus();
        }
    });
    </script>

</div>

</body>
</html>
