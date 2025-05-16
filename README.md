
# 📚 圖書館座位預約系統 Reservation System

一套功能完善的圖書館座位預約管理系統，提供一般使用者與管理員雙角色功能。支援座位預約、取消、違規管理、封鎖時段設定等功能。前端以 PHP 實作，後端使用 MySQL 儲存資料。

## 📁 專案結構

```bash
library-reservation/
├── index.php
├── login.php
├── logout.php
├── navbar.php
├── record_query.php
├── register.php
│
├── admin/
│   ├── admin_navbar.php
│   ├── index.php
│   ├── add_reservation.php
│   └── record_query.php
│
├── includes/
│   ├── add_block_time.php
│   ├── add_violation.php
│   ├── admin_create_reservation.php
│   ├── block_time.php
│   ├── cancel_reservation.php
│   ├── certain_block_time.php
│   ├── check_in.php
│   ├── create_reservation.php
│   ├── db.pgp
│   ├── delete_violation.php
│   ├── edit_block_time.php
│   ├── get_all_seats.php
│   ├── get_blocked_seat.php
│   ├── record_history.php
│   ├── record_violation.php
│   ├── reservation_all_record.php
│
├── images/
│   └── bg.png
```

## 🧩 資料表設計 (MySQL)

### 1. `users` 使用者表

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_admin TINYINT(1) DEFAULT 0,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. `seat` 座位表

```sql
CREATE TABLE seat (
    seat_id INT AUTO_INCREMENT PRIMARY KEY,
    room_type VARCHAR(50) NOT NULL,
    position VARCHAR(20) NOT NULL,
    has_power_outlet BOOLEAN DEFAULT FALSE,
    status ENUM('available', 'reserved', 'disabled') DEFAULT 'available',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 3. `reservation` 預約紀錄表

```sql
CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    seat_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('reserved', 'cancelled','checked_in') DEFAULT 'reserved',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (seat_id) REFERENCES seat(seat_id),

    UNIQUE KEY uq_user_time_status (user_id, start_date, end_date, status),
    UNIQUE KEY uq_seat_time_status (seat_id, start_date, end_date, status)
);
```

### 4. `block_time` 封鎖時間表

```sql
CREATE TABLE block_time (
    block_id INT AUTO_INCREMENT PRIMARY KEY,
    block_start_date DATE NOT NULL,
    block_end_date DATE NOT NULL,
    seat_id INT(11) NOT NULL,
    reason VARCHAR(255) NOT NULL
);
```

### 5. `violation_record` 違規記錄表

```sql
CREATE TABLE violation_record (
    violation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    seat_id INT NOT NULL,
    violation_date DATETIME NOT NULL,
    violation_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (seat_id) REFERENCES seat(seat_id)
);
```

## 🛠️ 資料庫連線（PDO）

```php
<?php
$host = 'localhost';
$db   = 'library';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    echo '資料庫連線失敗: ' . $e->getMessage();
    exit;
}
?>
```

## 💡 使用 PDO 的優勢

| 特性 | PDO | MySQLi |
|------|-----|--------|
| 支援多種資料庫 | ✅ | ❌（僅支援 MySQL） |
| 預處理語句 | ✅ | ✅ |
| 面向對象 | ✅ | ✅ |
| 錯誤處理 | try-catch 例外 | 錯誤代碼或例外 |
| 可攜性 | 高 | 低 |

## 🧑‍💻 使用者角色與權限

### 🔹 一般使用者

- 預約座位
- 取消預約
- 查詢個人紀錄
- 查看違規記錄

### 🔸 管理員

- 新增 / 編輯 / 刪除預約
- 封鎖時段管理
- 管理違規紀錄
- 查詢所有預約與違規紀錄

## 🚀 TODO / 未來規劃

- ✅ 多日預約功能
- ✅ 管理員自動驗證使用者違規
- ⏳ 預約通知 Email 整合
- ⏳ 行動版 UI 優化
- ⏳ 角色權限管理界面優化

## 🧾 開發與版本

- 開發語言：PHP, MySQL
- 資料庫介面：PDO
- 系統版本：初版 v1.0
