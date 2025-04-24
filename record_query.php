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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url("images/bg.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed; /* ✅ 讓背景固定 */
        }

        .query-button {
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .query-button:hover {
            transform: scale(1.03);
            filter: brightness(1.1);
            transition: all 0.2s ease-in-out;
        }

        .btn-purple { background-color: #9b59b6; }
        .btn-blue { background-color: #2980b9; }
        .btn-green { background-color: #1abc9c; }
        .btn-red { background-color: #e74c3c; }
        .btn-yellow { background-color: #f1c40f; color: #1f2a38; }

        .btn-purple:hover { background-color: #a678b3; }
        .btn-blue:hover { background-color: #3498db; }
        .btn-green:hover { background-color: #48c9b0; }
        .btn-red:hover { background-color: #ec7063; }
        .btn-yellow:hover { background-color: #f4d03f; }

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
        <button type="button" class="btn query-button btn-purple w-100" onclick="openCancelModal()">預約取消紀錄查詢</button>
        <button type="button" class="btn query-button btn-blue w-100" onclick="openHistoryModal()">預約歷史記錄查詢</button>
        <button type="button" class="btn query-button btn-green w-100" onclick="openUsageModal()">所有使用紀錄查詢</button>
        <button type="button" class="btn query-button btn-red w-100" onclick="openViolationModal()">違規記錄查詢</button>
        <button type="button" class="btn query-button btn-yellow w-100" onclick="openBannedModal()">暫停預約時間查詢</button>
    </div>

    <!-- 預約取消紀錄 Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">預約取消紀錄查詢</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body" id="cancelContainer">
                    <!-- JavaScript 載入內容 -->
                </div>
            </div>
        </div>
    </div>

    <!-- 預約歷史記錄 Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">預約歷史記錄查詢</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body" id="historyContainer">
                    <!-- JavaScript 載入內容 -->
                </div>
            </div>
        </div>
    </div>

    <!-- 空間使用紀錄 Modal -->
    <div class="modal fade" id="usageModal" tabindex="-1" aria-labelledby="usageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usageModalLabel">所有使用紀錄查詢</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body" id="usageContainer">
                    <!-- JavaScript 載入內容 -->
                </div>
            </div>
        </div>
    </div>

    <!-- 違規記錄 Modal -->
    <div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="violationModalLabel">違規記錄查詢</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body" id="violationContainer">
                    <!-- JavaScript 載入違規記錄 -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bannedModal" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannedModalLabel">暫停預約時間</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body" id="bannedContainer">
                    <!-- JavaScript 載入內容 -->
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function openHistoryModal() {
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    fetch(`/Reservation-system/includes/record_history.php?start_date=${startInput.value}&end_date=${endInput.value}`)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('historyContainer');
        container.innerHTML = '';

        if (!data || data.length === 0) {
            container.innerHTML = `<div class="alert alert-warning">(選擇時段)目前沒有預約記錄。</div>`;
            new bootstrap.Modal(document.getElementById('historyModal')).show(); // ✅ OK
            return;
        }

        const fragment = document.createDocumentFragment();

        const now = new Date().toISOString().split('T')[0];
        data.forEach(record => {
            const seatDiv = document.createElement('div');
            seatDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-info';

            const leftDiv = document.createElement('div');
            leftDiv.innerHTML = `<strong>座位：</strong> ${record.seat_id} (${record.position})`;

            // 依據狀態設定文字與顏色
            let statusText = '';
            let statusClass = '';
            const endDate = record.end_date;

            if (record.status === 'cancelled') {
                statusText = '已取消';
                statusClass = 'text-danger';
            } else if (record.status === 'checked_in') {
                statusText = '已簽到';
                statusClass = 'text-success';
            } else if (record.status === 'reserved' && endDate < now) {
                statusText = '未簽到';
                statusClass = 'text-warning';
            } else if (record.status === 'reserved') {
                statusText = '已預約';
                statusClass = 'text-primary';
            }

            const rightDiv = document.createElement('div');
            rightDiv.className = 'text-end small';
            rightDiv.innerHTML = `
                <div><strong>時間：</strong>${record.start_date} ~ ${record.end_date}</div>
                <div><strong>房型：</strong>${record.room_type}</div>
                <div><strong>狀態：</strong><span class="${statusClass}">${statusText}</span></div>
            `;

            seatDiv.appendChild(leftDiv);
            seatDiv.appendChild(rightDiv);
            container.appendChild(seatDiv); // 假設 container 是你要放進去的父層
        });

        container.appendChild(fragment);
        new bootstrap.Modal(document.getElementById('historyModal')).show();
    })
    .catch(err => {
        console.error("載入歷史記錄錯誤：", err);
        document.getElementById('historyContainer').innerHTML = `
            <div class="alert alert-danger">載入失敗，請稍後再試。</div>
        `;
    });
}

function openCancelModal() {
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    fetch(`/Reservation-system/includes/record_cancelled.php?start_date=${startInput.value}&end_date=${endInput.value}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('cancelContainer');
            container.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = `<div class="alert alert-warning">(選擇時段)目前沒有取消紀錄。</div>`;
                new bootstrap.Modal(document.getElementById('cancelModal')).show();
                return;
            }

            const fragment = document.createDocumentFragment();

            data.forEach(record => {
                const seatDiv = document.createElement('div');
                seatDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-danger';

                const leftDiv = document.createElement('div');
                leftDiv.innerHTML = `<strong>座位：</strong> ${record.seat_id} (${record.position})`;

                const rightDiv = document.createElement('div');
                rightDiv.className = 'text-end small';
                rightDiv.innerHTML = `
                    <div><strong>時間：</strong>${record.start_date} ~ ${record.end_date}</div>
                    <div><strong>房型：</strong>${record.room_type}</div>
                    <div><strong>狀態：</strong><span class="text-danger">已取消</span></div>
                `;

                seatDiv.appendChild(leftDiv);
                seatDiv.appendChild(rightDiv);
                fragment.appendChild(seatDiv);
            });

            container.appendChild(fragment);
            new bootstrap.Modal(document.getElementById('cancelModal')).show();
        })
        .catch(err => {
            console.error("載入取消紀錄錯誤：", err);
            document.getElementById('cancelContainer').innerHTML = `
                <div class="alert alert-danger">載入失敗，請稍後再試。</div>
            `;
        });
}

function openUsageModal() {
    fetch(`/Reservation-system/includes/reservation_all_record.php`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('usageContainer');
            container.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = `<div class="alert alert-warning">目前沒有使用紀錄。</div>`;
                new bootstrap.Modal(document.getElementById('usageModal')).show();
                return;
            }

            const fragment = document.createDocumentFragment();
            const now = new Date();

            data.forEach(record => {
                const usageDiv = document.createElement('div');
                usageDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-success';

                const leftDiv = document.createElement('div');
                leftDiv.innerHTML = `<strong>座位：</strong> ${record.seat_id} (${record.position})`;

                const endDate = new Date(record.end_date);
                let statusText = '';
                let statusClass = '';

                if (record.status === 'cancelled') {
                    statusText = '已取消';
                    statusClass = 'text-danger';
                } else if (record.status === 'checked_in') {
                    statusText = '已簽到';
                    statusClass = 'text-success';
                } else if (record.status === 'reserved' && endDate < now) {
                    statusText = '未簽到';
                    statusClass = 'text-success';
                } else if (record.status === 'reserved') {
                    statusText = '已預約';
                    statusClass = 'text-primary';
                }   

                const rightDiv = document.createElement('div');
                rightDiv.className = 'text-end small';
                rightDiv.innerHTML = `
                    <div><strong>時間：</strong>${record.start_date} ~ ${record.end_date}</div>
                    <div><strong>房型：</strong>${record.room_type}</div>
                    <div><strong>狀態：</strong><span class="${statusClass}">${statusText}</span></div>
                `;

                usageDiv.appendChild(leftDiv);
                usageDiv.appendChild(rightDiv);
                fragment.appendChild(usageDiv);
            });

            container.appendChild(fragment);
            new bootstrap.Modal(document.getElementById('usageModal')).show();
        })
        .catch(err => {
            console.error("載入使用紀錄錯誤：", err);
            document.getElementById('usageContainer').innerHTML = `
                <div class="alert alert-danger">載入失敗，請稍後再試。</div>
            `;
        });
}

function openViolationModal() {
    fetch('/Reservation-system/includes/record_violation.php')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('violationContainer');
        container.innerHTML = '';

        if (!data || data.length === 0) {
            container.innerHTML = `<div class="alert alert-warning">目前沒有違規記錄。</div>`;
            new bootstrap.Modal(document.getElementById('violationModal')).show();
            return;
        }

        const fragment = document.createDocumentFragment();

        data.forEach(record => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-danger';

            const left = document.createElement('div');
            left.innerHTML = `<strong>座位：</strong> ${record.seat_id}`;

            const right = document.createElement('div');
            right.className = 'text-end small';
            right.innerHTML = `
                <div><strong>違規時間：</strong> ${record.violation_date}</div>
                <div><strong>違規類型：</strong> <span class="text-danger">${record.violation_type}</span></div>
            `;

            itemDiv.appendChild(left);
            itemDiv.appendChild(right);
            fragment.appendChild(itemDiv);
        });

        container.appendChild(fragment);
        new bootstrap.Modal(document.getElementById('violationModal')).show();
    })
    .catch(err => {
        console.error("違規紀錄載入錯誤：", err);
        document.getElementById('violationContainer').innerHTML = `
            <div class="alert alert-danger">載入失敗，請稍後再試。</div>
        `;
    });
}

function openBannedModal() {
    fetch('/Reservation-system/includes/block_time.php')
    .then(res => res.json())
    .then(data => {
        console.log(data);
        const container = document.getElementById('bannedContainer');
        container.innerHTML = '';

        if (!data || data.length === 0) {
            container.innerHTML = `<div class="alert alert-info">目前沒有任何暫停預約的時段。</div>`;
            new bootstrap.Modal(document.getElementById('bannedModal')).show();
            return;
        }

        const fragment = document.createDocumentFragment();

        data.forEach(seat => {
            const banDiv = document.createElement('div');
            banDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-warning';

            const left = document.createElement('div');
            left.innerHTML = `<strong>原因：</strong>${seat.reason}`;

            const right = document.createElement('div');
            right.className = 'text-end small';
            right.innerHTML = `
                <div><strong>區間：</strong>${seat.block_start_date} ~ ${seat.block_end_date}</div>
                <div><strong>空間：</strong>${seat.seat_id}</div>
            `;

            banDiv.appendChild(left);
            banDiv.appendChild(right);
            fragment.appendChild(banDiv);
        });

        container.appendChild(fragment);
        new bootstrap.Modal(document.getElementById('bannedModal')).show();
    })
    .catch(err => {
        console.error("載入暫停預約時間錯誤：", err);
        document.getElementById('bannedContainer').innerHTML = `
            <div class="alert alert-danger">載入失敗，請稍後再試。</div>
        `;
    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>