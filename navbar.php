<nav class="navbar navbar-expand-lg px-4 py-2">
    <div class="container-fluid">
        <div class="d-flex w-100 nav-buttons">
            <!-- 左側選單項目 -->
            <a class="flex-fill text-center nav-link-custom" href="index.php">📅 預約狀況</a>
            <!-- 右側三個選單 -->
            <a class="flex-fill text-center nav-link-custom" href="add_reservation.php">➕ 新增預約</a>
            <a class="flex-fill text-center nav-link-custom" href="record_query.php">⚠️ 紀錄查詢</a>
            <a class="flex-fill text-center nav-link-custom" href="logout.php">✔️ 登出</a>
        </div>
    </div>
</nav>

<style>
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
        color: #ffd700; /* 金色點綴 */
    }

    .nav-link-custom:active {
        background-color: rgba(255, 255, 255, 0.2);
        transform: scale(0.98);
    }

    .nav-buttons {
        gap: 10px; /* 選單之間有一點點間隔 */
    }
</style>