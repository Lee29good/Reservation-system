<?php
session_start();         // 開啟 Session
session_unset();         // 清除所有 Session 變數
session_destroy();       // 銷毀 Session

// 導回登入頁
header("Location: login.php");
exit;
