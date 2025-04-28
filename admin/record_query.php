<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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
            background-image: url("../images/bg.jpg");
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

<?php include 'admin_navbar.php'; ?>

<div class="container mt-5 text-center">
    <form method="GET" action="record_query.php" class="row justify-content-center mb-4">
        <div class="col-md-3">
            <label class="date-label">使用者名稱</label>
            <input type="text" name="username" class="form-control" placeholder="輸入使用者名稱">
        </div>
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
        <button type="button" class="btn query-button btn-purple w-100" onclick="openCancelModal()">預約取消紀錄</button>
        <button type="button" class="btn query-button btn-blue w-100" onclick="openHistoryModal()">預約歷史記錄</button>
        <button type="button" class="btn query-button btn-green w-100" onclick="openUsageModal()">所有使用紀錄</button>
        <button type="button" class="btn query-button btn-red w-100" onclick="openViolationModal()">違規記錄</button>
        <button type="button" class="btn query-button btn-yellow w-100" onclick="openBannedModal()">暫停預約時間</button>
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

    <!-- 新增違規記錄 Modal -->
    <div class="modal fade" id="addViolationModal" tabindex="-1" aria-labelledby="addViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addViolationModalLabel">新增違規記錄</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body">
                    <form id="addViolationForm">
                    <div class="mb-3">
                        <label for="violationUser" class="form-label">username</label>
                        <input type="text" class="form-control" id="violationUser" required>
                    </div>
                    <div class="mb-3">
                        <label for="violationSpace" class="form-label">違規空間</label>
                        <input type="text" class="form-control" id="violationSpace" required>
                    </div>
                    <div class="mb-3">
                        <label for="violationDate" class="form-label">違規日期</label>
                        <input type="date" class="form-control" id="violationDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="violationType" class="form-label">違規類型</label>
                        <input type="text" class="form-control" id="violationType" required>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" form="addViolationForm">儲存</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 暫停使用記錄 Modal -->
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

    <!-- 新增暫停時段 Modal -->
    <div class="modal fade" id="addBlockModal" tabindex="-1" aria-labelledby="addBlockModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addBlockModalLabel">新增暫停預約時段</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
          </div>
          <div class="modal-body">
            <form id="addBlockForm">
              <div class="mb-3">
                <label for="blockStart" class="form-label">開始日期</label>
                <input type="date" class="form-control" id="blockStart" required>
              </div>
              <div class="mb-3">
                <label for="blockEnd" class="form-label">結束日期</label>
                <input type="date" class="form-control" id="blockEnd" required>
              </div>
              <div class="mb-3">
                <label for="blockRoom" class="form-label">暫停空間</label>
                <input type="text" class="form-control" id="blockRoom" required>
              </div>
              <div class="mb-3">
                <label for="blockReason" class="form-label">原因</label>
                <textarea class="form-control" id="blockReason" rows="2" required></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary" form="addBlockForm">儲存</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 修改暫停時段的 Modal -->
    <div class="modal fade" id="editBlockModal" tabindex="-1" aria-labelledby="editBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBlockModalLabel">修改暫停時段</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBlockForm">
                        <input type="hidden" id="editBlockId">
                        <div class="mb-3">
                            <label for="editBlockStart" class="form-label">開始時間</label>
                            <input type="date" class="form-control" id="editBlockStart" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBlockEnd" class="form-label">結束時間</label>
                            <input type="date" class="form-control" id="editBlockEnd" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSeatId" class="form-label">房間 ID</label>
                            <input type="number" class="form-control" id="editSeatId" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBlockReason" class="form-label">原因</label>
                            <input type="text" class="form-control" id="editBlockReason" required>
                        </div>
                        <button type="submit" class="btn btn-primary">儲存修改</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openHistoryModal() {
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');
    const usernameInput = document.querySelector('input[name="username"]');

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    const url = `/Reservation-system/includes/record_history.php?start_date=${startInput.value}&end_date=${endInput.value}` + 
                (usernameInput && usernameInput.value.trim() !== '' ? `&username=${encodeURIComponent(usernameInput.value.trim())}` : '');

    fetch(url)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('historyContainer');
        container.innerHTML = '';

        if (!data || data.length === 0) {
            container.innerHTML = `<div class="alert alert-warning">(選擇時段)目前沒有預約記錄。</div>`;
            new bootstrap.Modal(document.getElementById('historyModal')).show();
            return;
        }

        const fragment = document.createDocumentFragment();
        const now = new Date().toISOString().split('T')[0];

        data.forEach(record => {
            const seatDiv = document.createElement('div');
            seatDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-info';

            // 🔥 左邊：使用者 + 座位（上下兩行）
            const leftDiv = document.createElement('div');
            leftDiv.className = 'd-flex flex-column'; // 一直排上下
            leftDiv.innerHTML = `
                <div><strong>使用者：</strong> ${record.username || '未知使用者'}</div>
                <div><strong>座位：</strong> ${record.seat_id} (${record.position})</div>
            `;

            // 🔥 右邊：時間、房型、狀態（維持原本右對齊）
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
            fragment.appendChild(seatDiv);
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
    const usernameInput = document.querySelector('input[name="username"]'); // 假設有一個用來輸入篩選的 username

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);
    const username = usernameInput ? usernameInput.value.trim() : '';

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    let url = `/Reservation-system/includes/record_cancelled.php?start_date=${startInput.value}&end_date=${endInput.value}`;
    
    // 如果是 admin 並且有填寫 username，則加入 username 作為篩選條件
    if (username) {
        url += `&username=${username}`;
    }

    fetch(url)
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

                // 修改 leftDiv，加入使用者資訊
                const leftDiv = document.createElement('div');
                leftDiv.className = 'd-flex flex-column ps-0';  // 用 flex-column 並移除 padding
                leftDiv.innerHTML = `
                    <div><strong>使用者：</strong> ${record.username || '未知使用者'}</div>
                    <div><strong>座位：</strong> ${record.seat_id} (${record.position})</div>
                `;

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
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');
    const usernameInput = document.querySelector('input[name="username"]'); // 假設有一個用來輸入篩選的 username

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);
    const username = usernameInput ? usernameInput.value.trim() : '';

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    let url = `/Reservation-system/includes/reservation_all_record.php?start_date=${startInput.value}&end_date=${endInput.value}`;
    
    // 如果有填寫 username，則加入 username 作為篩選條件
    if (username) {
        url += `&username=${username}`;
    }

    fetch(url)
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
                const itemDiv = document.createElement('div');
                itemDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-success';

                const leftDiv = document.createElement('div');
                leftDiv.innerHTML = `
                    <strong>座位：</strong> ${record.seat_id} (${record.position})
                    ${record.username ? `<div><strong>使用者：</strong>${record.username}</div>` : ''}
                `;

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

                itemDiv.appendChild(leftDiv);
                itemDiv.appendChild(rightDiv);
                fragment.appendChild(itemDiv);
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

// 顯示 Violation 紀錄
function openViolationModal() {
    const startInput = document.querySelector('input[name="start_date"]');
    const endInput = document.querySelector('input[name="end_date"]');
    const usernameInput = document.querySelector('input[name="username"]'); // ➤ 多抓 username

    const startDate = new Date(startInput.value);
    const endDate = new Date(endInput.value);

    if (startDate > endDate) {
        alert('結束日期不能早於開始日期');
        return;
    }

    // ➤ 根據 username 是否有輸入，決定要不要加到 URL
    const url = `/Reservation-system/includes/record_violation.php?start_date=${startInput.value}&end_date=${endInput.value}` +
                (usernameInput && usernameInput.value.trim() !== '' ? `&username=${encodeURIComponent(usernameInput.value.trim())}` : '');

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('violationContainer');
            container.innerHTML = '';

            const fragment = document.createDocumentFragment();

            if (!data || data.length === 0) {
                container.innerHTML = `<div class="alert alert-info">(選擇時段)目前沒有違規記錄。</div>`;
            } else {
                data.forEach(record => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-danger';

                    const left = document.createElement('div');
                    let userInfo = record.username ? `<div><strong>使用者：</strong>${record.username}</div>` : '';

                    left.innerHTML = `
                        <strong>違規類型：</strong><span class="text-danger">${record.violation_type}</span>
                        ${userInfo}
                    `;

                    const right = document.createElement('div');
                    right.className = 'text-end small';
                    right.innerHTML = `
                        <div><strong>違規時間：</strong>${record.violation_date}</div>
                        <div><strong>座位：</strong>${record.seat_id}</div>
                        <button class="btn btn-sm btn-outline-danger mt-2" onclick="deleteViolation('${record.violation_id}')">刪除</button>
                    `;

                    itemDiv.appendChild(left);
                    itemDiv.appendChild(right);
                    fragment.appendChild(itemDiv);
                });
            }

            const addButton = document.createElement('button');
            addButton.className = 'btn btn-success w-100 mt-3';
            addButton.textContent = '新增違規記錄';
            addButton.onclick = function () {
                openAddViolationModal();
            };

            container.appendChild(fragment);
            container.appendChild(addButton);

            new bootstrap.Modal(document.getElementById('violationModal')).show();
        })
        .catch(err => {
            console.error("違規紀錄載入錯誤：", err);
            document.getElementById('violationContainer').innerHTML = `
                <div class="alert alert-danger">載入失敗，請稍後再試。</div>
            `;
        });
}

// 新增 violation 頁面
function openAddViolationModal() {
    console.log("開啟新增違規表單");
    // 關閉原本的 violationModal
    const violationModalEl = document.getElementById('violationModal');
    const violationModal = bootstrap.Modal.getInstance(violationModalEl);
    if (violationModal) violationModal.hide();

    // 開啟新增的 addViolationModal
    const addViolationModal = new bootstrap.Modal(document.getElementById('addViolationModal'));
    addViolationModal.show();
}

function deleteViolation(violationId) {
    if (!confirm('確定要刪除這筆違規記錄嗎？')) {
        return;
    }

    fetch(`/Reservation-system/includes/delete_violation.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ violation_id: violationId })
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            alert('刪除成功');
            // ➤ 把對應的違規項目從畫面上移除
            const button = document.querySelector(`button[onclick="deleteViolation('${violationId}')"]`);
            if (button) {
                const itemDiv = button.closest('.d-flex');
                if (itemDiv) itemDiv.remove();
            }
        } else {
            alert('刪除失敗：' + (response.error || '未知錯誤'));
        }
    })
    .catch(err => {
        console.error("刪除失敗：", err);
        alert('刪除失敗，請稍後再試。');
    });
}

// 新增違規記錄邏輯
// add_violation 新增違規記錄邏輯
document.getElementById('addViolationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // 收集表單資料
    const data = {
        username: document.getElementById('violationUser').value,
        violation_space: document.getElementById('violationSpace').value,
        violation_date: document.getElementById('violationDate').value,
        violation_type: document.getElementById('violationType').value
    };

    // 呼叫儲存用的 API：/Reservation-system/includes/add_violation.php
    fetch('/Reservation-system/includes/add_violation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            // 關閉 addViolationModal
            const addModalEl = document.getElementById('addViolationModal');
            const addModal = bootstrap.Modal.getInstance(addModalEl);
            if (addModal) addModal.hide();

            // 清空表單
            document.getElementById('addViolationForm').reset();

            // 重新開啟 violationModal 並刷新資料
            openViolationModal();
        } else {
            alert("儲存失敗：" + (res.message || "請稍後再試"));
        }
    })
    .catch(err => {
        console.error("儲存錯誤：", err);
        alert("儲存失敗，請稍後再試。");
    });
});

// 顯示 ban 紀錄
function openBannedModal() {
    fetch('/Reservation-system/includes/block_time.php')
    .then(res => res.json())
    .then(data => {
        console.log(data);
        const container = document.getElementById('bannedContainer');
        container.innerHTML = '';

        const fragment = document.createDocumentFragment();

        if (!data || data.length === 0) {
            container.innerHTML = `<div class="alert alert-info">目前沒有任何暫停預約的時段。</div>`;
        } else {
            data.forEach(block => {
                const banDiv = document.createElement('div');
                banDiv.className = 'd-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 border-warning';

                const left = document.createElement('div');
                left.innerHTML = `<strong>原因：</strong>${block.reason}`;

                const right = document.createElement('div');
                right.className = 'text-end small';
                right.innerHTML = `
                    <div><strong>區間：</strong>${block.block_start_date} ~ ${block.block_end_date}</div>
                    <div><strong>空間：</strong>${block.seat_id}</div>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="editBanned('${block.block_id}')">修改</button>
                `;

                banDiv.appendChild(left);
                banDiv.appendChild(right);
                fragment.appendChild(banDiv);
            });
        }

        // ✅ 新增一個新增按鈕
        const addButton = document.createElement('button');
        addButton.className = 'btn btn-success w-100 mt-3';
        addButton.textContent = '新增暫停預約時段';
        addButton.onclick = function () {
            openAddBannedModal(); // ➤ 自訂函式，開啟新增 Modal
        };

        container.appendChild(fragment);
        container.appendChild(addButton);

        new bootstrap.Modal(document.getElementById('bannedModal')).show();
    })
    .catch(err => {
        console.error("載入暫停預約時間錯誤：", err);
        document.getElementById('bannedContainer').innerHTML = `
            <div class="alert alert-danger">載入失敗，請稍後再試。</div>
        `;
    });
}

function editBanned(blockId) {
    console.log("準備修改 block_id =", blockId);

    // 透過 block_id 查詢資料並填入表單
    fetch(`/Reservation-system/includes/certain_block_time.php?block_id=${blockId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 填充資料到 Modal 表單中
                document.getElementById('editBlockId').value = blockId;
                document.getElementById('editBlockStart').value = data.block_start_date;
                document.getElementById('editBlockEnd').value = data.block_end_date;
                document.getElementById('editSeatId').value = data.seat_id;
                document.getElementById('editBlockReason').value = data.reason;

                // 關閉原本的 bannedModal
                const bannedModalEl = document.getElementById('bannedModal');
                const bannedModal = bootstrap.Modal.getInstance(bannedModalEl);
                if (bannedModal) bannedModal.hide();
                // 開啟 Modal
                const editModalEl = document.getElementById('editBlockModal');
                const editModal = new bootstrap.Modal(editModalEl);
                editModal.show();
            } else {
                alert("載入資料失敗");
            }
        })
        .catch(err => {
            console.error("錯誤：", err);
            alert("載入資料失敗，請稍後再試。");
        });
}

function openAddBannedModal() {
    console.log("開啟新增表單");
    // 關閉原本的 bannedModal
    const bannedModalEl = document.getElementById('bannedModal');
    const bannedModal = bootstrap.Modal.getInstance(bannedModalEl);
    if (bannedModal) bannedModal.hide();

    // 開啟新增的 addBlockModal
    const addModal = new bootstrap.Modal(document.getElementById('addBlockModal'));
    addModal.show();
}

// add_block新增暫停預約時間邏輯
document.getElementById('addBlockForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // 收集表單資料
    const data = {
        block_start_date: document.getElementById('blockStart').value,
        block_end_date: document.getElementById('blockEnd').value,
        seat_id: document.getElementById('blockRoom').value,
        reason: document.getElementById('blockReason').value
    };

    // 假設有一個儲存用的 API: /Reservation-system/includes/add_block_time.php
    fetch('/Reservation-system/includes/add_block_time.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            // 關閉 addBlockModal
            const addModalEl = document.getElementById('addBlockModal');
            const addModal = bootstrap.Modal.getInstance(addModalEl);
            if (addModal) addModal.hide();

            // 清空表單
            document.getElementById('addBlockForm').reset();

            // 重新開啟 bannedModal 並刷新資料
            openBannedModal();
        } else {
            alert("儲存失敗：" + (res.message || "請稍後再試"));
        }
    })
    .catch(err => {
        console.error("儲存錯誤：", err);
        alert("儲存失敗，請稍後再試。");
    });
});

//修改暫停預約時間邏輯
document.getElementById('editBlockForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const blockId = document.getElementById('editBlockId').value;

    const data = {
        block_id: blockId,
        block_start_date: document.getElementById('editBlockStart').value,
        block_end_date: document.getElementById('editBlockEnd').value,
        seat_id: document.getElementById('editSeatId').value,
        reason: document.getElementById('editBlockReason').value
    };

    console.log(data);

    fetch('/Reservation-system/includes/edit_block_time.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            // 關閉 Modal
            const editModalEl = document.getElementById('editBlockModal');
            const editModal = bootstrap.Modal.getInstance(editModalEl);
            if (editModal) editModal.hide();

            // 更新資料並重新顯示 bannedModal
            openBannedModal();
        } else {
            alert('儲存修改失敗：' + response.message);
        }
    })
    .catch(err => {
        console.error("儲存錯誤：", err);
        alert("儲存修改失敗，請稍後再試。");
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>