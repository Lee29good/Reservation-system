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
        html, body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: url("images/bg.jpg");
            background-size: cover;      /* 背景填滿畫面 */
            background-repeat: no-repeat; /* 不重複 */
            background-position: center;  /* 置中 */
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
            <!-- 小型討論室 -->
            <button class="btn reservation-btn small-room w-100 text-start mb-2" data-bs-toggle="collapse" data-bs-target="#smallRoomCollapse">
                <span><i class="icon">💬</i> 小型討論室</span> ➤
            </button>

            <!-- 小型討論室展開內容 -->
            <div id="smallRoomCollapse" class="collapse">
                <div class="alert alert-danger text-center">※ 限2人以上使用</div>

                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        1F－小型討論室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="1F－小型討論室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        6F－小型討論室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="6F－小型討論室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        7F－小型討論室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="7F－小型討論室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        8F－小型討論室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="8F－小型討論室">預約</a>
                    </li>
                </ul>
            </div>

            <!-- 大型討論室 -->
            <button class="btn reservation-btn large-room w-100 text-start mb-2" data-bs-toggle="collapse" data-bs-target="#largeRoomCollapse">
                <span><i class="icon">💬</i> 大型討論室</span> ➤
            </button>

            <!-- 大型討論室展開內容 -->
            <div id="largeRoomCollapse" class="collapse">
                <div class="alert alert-danger text-center">※ 限5人以上使用</div>

                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        4F－大型討論室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="4F－大型討論室">預約</a>
                    </li>
                </ul>
            </div>

            <!-- 當日研究室 -->
            <button class="btn reservation-btn daily-research w-100 text-start mb-2" data-bs-toggle="collapse" data-bs-target="#researchRoomCollapse">
                <span><i class="icon">💬</i> 當日研究室</span> ➤
            </button>

            <!-- 當日研究室展開內容 -->
            <div id="researchRoomCollapse" class="collapse">
                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        4F－當日研究室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="4F－當日研究室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        5F－當日研究室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="5F－當日研究室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        6F－當日研究室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="6F－當日研究室">預約</a>
                    </li>
                </ul>
            </div>
            
            <!-- 長期研究室 -->
            <button class="btn reservation-btn longterm-research w-100 text-start mb-2" data-bs-toggle="collapse" data-bs-target="#longtermRoomCollapse">
                <span><i class="icon">💬</i> 長期研究室</span> ➤
            </button>
            <div id="longtermRoomCollapse" class="collapse">
                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        7F－長期研究室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="7F－長期研究室">預約</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        8F－長期研究室
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reservationModal" data-room="8F－長期研究室">預約</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 預約需知 Modal -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">預約需知</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>請確認以下事項，並遵守使用規範：</p>
                    <ul>
                        <li>每人每時段只能借用一個座位。</li>
                        <li>每座位每時段只能借給一個人。</li>
                        <li>請遵守借用規定，並在借用後及時歸還座位。</li>
                        <li>預約後將收到確認通知，請保持聯絡方式有效。</li>
                    </ul>
                    <hr>
                    <p class="fw-bold">各室預約限制：</p>
                    <ul>
                        <li><strong>當日研究室：</strong> 只能選擇「當天日期」，不可跨天預約。</li>
                        <li><strong>長期研究室：</strong> 最多可連續預約 15 天。</li>
                        <li><strong>小型討論室：</strong> 最多可連續預約 3 天。</li>
                        <li><strong>大型討論室：</strong> 最多可連續預約 3 天。</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button id="confirmReservationBtn" class="btn btn-primary">確認</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="makeReservationModal" tabindex="-1" aria-labelledby="makeReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong id="selectedRoomLabel"></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label fw-semibold">開始日期</label>
                            <input type="date" class="form-control form-control-lg" id="startDate" name="startDate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label fw-semibold">結束日期</label>
                            <input type="date" class="form-control form-control-lg" id="endDate" name="endDate" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary px-4 py-2" id="checkAvailabilityBtn">查詢可預約空間</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="selectSeatModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <strong id="selectSeatTitle"></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="seatSelectionContainer">
                    <!-- 動態插入座位 -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button class="btn btn-primary" id="confirmSeatBtn">下一步</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapseToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');

    collapseToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-bs-target'));

            collapseToggles.forEach(otherToggle => {
                const otherTarget = document.querySelector(otherToggle.getAttribute('data-bs-target'));
                if (otherTarget !== target) {
                    const bsCollapse = bootstrap.Collapse.getInstance(otherTarget);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            });

            const bsTarget = bootstrap.Collapse.getInstance(target);
            if (!bsTarget) {
                new bootstrap.Collapse(target, {
                    toggle: true
                });
            }
        });
    });
});

let selectedRoom = null;

document.addEventListener('DOMContentLoaded', function () {
    const reservationBtns = document.querySelectorAll('.btn.btn-success');
    const confirmBtn = document.getElementById('confirmReservationBtn');

    // 使用者點選預約按鈕時，儲存選擇的房間名稱
    reservationBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            selectedRoom = this.getAttribute('data-room');
        });
    });

    // 使用者點選「確認」後，關閉第一個 Modal 並開啟第二個 Modal
    confirmBtn.addEventListener('click', function () {
        const reservationModal = bootstrap.Modal.getInstance(document.getElementById('reservationModal'));
        reservationModal.hide();

        const makeReservationModal = new bootstrap.Modal(document.getElementById('makeReservationModal'));
        makeReservationModal.show();

        // 也可以在這裡根據 selectedRoom 動態顯示房間名稱
        document.getElementById('selectedRoomLabel').textContent = selectedRoom || "未選擇";
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const selectedRoomLabel = document.getElementById('selectedRoomLabel');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    const today = new Date().toISOString().split('T')[0];
    startDateInput.value = today;
    function updateEndDateConstraints() {
        const roomType = selectedRoomLabel.textContent.trim();
        const startDate = new Date(startDateInput.value);

        if (!startDateInput.value) return; // 若未選擇開始日期，跳出

        let maxEndDate = new Date(startDate);

        if (roomType.includes("當日研究室")) {
            endDateInput.value = startDateInput.value;
            endDateInput.disabled = true;
        } else {
            endDateInput.disabled = false;

            if (roomType.includes("長期研究室")) {
                maxEndDate.setDate(startDate.getDate() + 15);
            } else if (roomType.includes("小型討論室") || roomType.includes("大型討論室")) {
                maxEndDate.setDate(startDate.getDate() + 2); // 開始+2 = 共3天
            } else {
                maxEndDate = null; // 若無限制，清除限制
            }

            if (maxEndDate) {
                endDateInput.min = startDateInput.value;
                endDateInput.max = maxEndDate.toISOString().split('T')[0];
                endDateInput.value = startDateInput.value;
            } else {
                endDateInput.removeAttribute('min');
                endDateInput.removeAttribute('max');
            }
        }
    }

    startDateInput.addEventListener('change', updateEndDateConstraints);

    // 每次打開 modal 時重新綁定（防止 room type 未更新）
    const makeReservationModal = document.getElementById('makeReservationModal');
    makeReservationModal.addEventListener('show.bs.modal', function () {
        setTimeout(() => {
            if (startDateInput.value) {
                updateEndDateConstraints();
            }
        }, 100);
    });
});

document.getElementById('checkAvailabilityBtn').addEventListener('click', () => {
    console.log("click check button");

    const fullLabel = document.getElementById('selectedRoomLabel').innerText.trim();
    const [position, roomType] = fullLabel.split('－').map(item => item.trim()); // 抓取位置與房型

    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    if (!startDate || !endDate) {
        alert('請選擇完整的開始與結束日期');
        return;
    }

    openSelectSeatModal(roomType, startDate, endDate, position); // 加入 position 參數
});

function openSelectSeatModal(roomType, startDate, endDate, position) {
    console.log("🟡 roomType:", roomType, "| start:", startDate, "| end:", endDate, "| position:", position);

    Promise.all([
        fetch(`/Reservation-system/includes/get_all_seats.php?room_type=${roomType}&position=${position}`)
            .then(res => res.json()),
        fetch(`/Reservation-system/includes/get_reserved_seats.php?room_type=${roomType}&start=${startDate}&end=${endDate}`)
            .then(res => res.json())
    ])
    .then(([seats, reservedSeatIds]) => {
        console.log("✅ 所有座位資料 seats:", seats);
        console.log("❌ 已被預約的座位 reservedSeatIds:", reservedSeatIds);

        const container = document.getElementById('seatSelectionContainer');
        container.innerHTML = ''; // 清空容器

        const fragment = document.createDocumentFragment();

        seats.forEach(seat => {
            const isReserved = reservedSeatIds.includes(Number(seat.seat_id));
            const hasPower = seat.has_power_outlet == 1;
            const powerIcon = hasPower ? '🔌有插座' : '';

            const reservedClass = isReserved ? 'bg-light text-muted border-secondary' : 'border-primary';

            const seatDiv = document.createElement('div');
            seatDiv.className = `d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2 ${reservedClass}`;

            // 左側：radio + 座位名稱
            const leftDiv = document.createElement('div');
            leftDiv.className = 'd-flex align-items-center gap-2';

            const seatInput = document.createElement('input');
            seatInput.type = 'radio';
            seatInput.className = 'form-check-input';
            seatInput.name = 'selectedSeat';
            seatInput.id = `seat${seat.seat_id}`;
            seatInput.value = seat.seat_id;
            seatInput.disabled = isReserved;

            const seatLabel = document.createElement('label');
            seatLabel.className = 'form-check-label fw-bold m-0';
            seatLabel.setAttribute('for', seatInput.id);
            seatLabel.innerHTML = `【${seat.seat_id}】`;

             // ➕ 加上「（已預約）」文字提示
            seatLabel.innerHTML = `【${seat.seat_id}】${isReserved ? '（已預約）' : ''}`;   
            
            leftDiv.appendChild(seatInput);
            leftDiv.appendChild(seatLabel);

            // 右側：日期與插座資訊
            const rightDiv = document.createElement('div');
            rightDiv.className = 'text-end small';
            if(startDate=endDate){
                rightDiv.innerHTML = `
                    <div>${startDate}</div>
                    <div class="text-success">${powerIcon}</div>
                `;
            }else{
                rightDiv.innerHTML = `
                    <div>${startDate}~${endDate}</div>
                    <div class="text-success">${powerIcon}</div>
                `;
            }
            

            seatDiv.appendChild(leftDiv);
            seatDiv.appendChild(rightDiv);
            fragment.appendChild(seatDiv);
        });

        container.appendChild(fragment);
        document.getElementById('selectSeatTitle').innerText = `${roomType}｜${startDate} ~ ${endDate}`;

        const modal = new bootstrap.Modal(document.getElementById('selectSeatModal'));
        modal.show();
    })
    .catch(err => {
        console.error("Error loading seats:", err);
        document.getElementById('seatSelectionContainer').innerHTML = `
            <div class="alert alert-danger">載入座位資料失敗，請稍後再試。</div>
        `;
    });
}

</script>
</html>
