<?php
$host = 'localhost';
$dbname = 'lib_reservation';    
$user = 'root';           
$pass = '';              

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "資料庫連線成功"; // 測試用
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>
