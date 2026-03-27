import {
    getSessions,
    getMessages,
    deleteSession,
} from "../../services/admin-ai-api.js";

$(document).ready(function () {
    let currentSessionId = null;
    let searchTimeout = null;

    // 🌟 Quản lý ngày hiện tại bằng JS Date
    let currentDateObj = new Date();

    const sessionListEl = $("#admin-session-list");
    const chatContentEl = $("#admin-chat-content");
    const searchInput = $("#search-input");
    const filterDateInput = $("#filter-date");
    const btnPrevDay = $("#btn-prev-day");
    const btnNextDay = $("#btn-next-day");
    const chatHeaderTitle = $("#chat-header-title");
    const btnDeleteLog = $("#btn-delete-log");

    // --- LOGIC XỬ LÝ NGÀY THÁNG ---

    // Hàm chuyển đổi Date object sang chuỗi YYYY-MM-DD cho thẻ <input type="date">
    function formatDateForInput(date) {
        let d = new Date(date),
            month = "" + (d.getMonth() + 1),
            day = "" + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = "0" + month;
        if (day.length < 2) day = "0" + day;

        return [year, month, day].join("-");
    }

    // Hàm kiểm tra xem date1 có phải là cùng ngày/tháng/năm với date2 không
    function isSameDate(date1, date2) {
        return (
            date1.getFullYear() === date2.getFullYear() &&
            date1.getMonth() === date2.getMonth() &&
            date1.getDate() === date2.getDate()
        );
    }

    // Cập nhật giao diện UI ngày tháng
    function updateDateUI() {
        filterDateInput.val(formatDateForInput(currentDateObj));

        let today = new Date();
        // Nếu ngày đang chọn là hôm nay hoặc lớn hơn hôm nay -> Khóa nút Next
        if (currentDateObj >= today || isSameDate(currentDateObj, today)) {
            btnNextDay.prop("disabled", true);
        } else {
            btnNextDay.prop("disabled", false);
        }
    }

    // --- LOGIC LOAD DATA ---

    function resetRightPanel() {
        currentSessionId = null;
        chatHeaderTitle.text("Chọn một cuộc trò chuyện để xem");
        btnDeleteLog.addClass("d-none");
        chatContentEl.html(`
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50">
                <i class="fas fa-calendar-day fa-4x mb-3"></i>
                <h5>Đã đổi ngày, vui lòng chọn lại phiên làm việc</h5>
            </div>
        `);
    }

    function loadSessions() {
        let searchQuery = searchInput.val();
        let dateQuery = filterDateInput.val(); // Sẽ gửi YYYY-MM-DD lên Server

        sessionListEl.html(
            '<div class="text-center p-4 text-muted"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>',
        );

        getSessions(searchQuery, dateQuery).done(function (res) {
            sessionListEl.empty();
            if (res.sessions.length === 0) {
                sessionListEl.html(
                    '<div class="text-center p-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 opacity-50"></i><br>Không có đoạn chat nào trong ngày này.</div>',
                );
                return;
            }

            res.sessions.forEach((session) => {
                let activeClass =
                    session.id === currentSessionId
                        ? "active bg-primary-subtle text-dark"
                        : "";
                sessionListEl.append(`
                    <a href="#" class="list-group-item list-group-item-action py-3 border-bottom session-item ${activeClass}" data-id="${session.id}">
                        <div class="d-flex w-100 justify-content-between mb-1">
                            <strong class="mb-1">${session.user_name}</strong>
                            <small class="${session.id === currentSessionId ? "" : "text-muted"}">${session.time}</small>
                        </div>
                        <div class="small text-truncate ${session.id === currentSessionId ? "opacity-75" : "text-muted"}">${session.title}</div>
                    </a>
                `);
            });
        });
    }

    function formatText(text) {
        let formatted = text.replace(/\*\*(.*?)\*\*/g, "<b>$1</b>");
        formatted = formatted.replace(/\n/g, "<br>");
        return formatted;
    }

    function loadChatMessages(sessionId) {
        currentSessionId = sessionId;
        $(".session-item").removeClass("active bg-primary-subtle text-dark");
        $(`.session-item[data-id="${sessionId}"]`).addClass(
            "active bg-primary-subtle text-dark",
        );
        btnDeleteLog.removeClass("d-none");
        chatContentEl.html(
            '<div class="text-center mt-5 text-muted"><i class="fas fa-spinner fa-spin"></i> Đang tải tin nhắn...</div>',
        );

        getMessages(sessionId).done(function (res) {
            chatHeaderTitle.text(
                `Phiên chat #${res.session_info.id} - ${res.session_info.name}`,
            );
            chatContentEl.empty();

            if (res.messages.length === 0) {
                chatContentEl.html(
                    '<div class="text-center mt-5 text-muted">Chưa có nội dung chat.</div>',
                );
                return;
            }

            let initial = res.session_info.name.charAt(0).toUpperCase();

            res.messages.forEach((msg) => {
                if (msg.sender === "model") {
                    chatContentEl.append(`
                        <div class="d-flex flex-row mb-3">
                            <div class="p-2 me-3 border bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">🤖</div>
                            <div class="p-3 bg-white border rounded shadow-sm" style="max-width: 70%;">
                                ${formatText(msg.content)}
                            </div>
                        </div>
                    `);
                } else {
                    chatContentEl.append(`
                        <div class="d-flex flex-row-reverse mb-3">
                            <div class="p-2 ms-3 border bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">${initial}</div>
                            <div class="p-3 bg-primary text-white border rounded shadow-sm" style="max-width: 70%;">
                                ${formatText(msg.content)}
                            </div>
                        </div>
                    `);
                }
            });
            chatContentEl.scrollTop(chatContentEl[0].scrollHeight);
        });
    }

    btnDeleteLog.on("click", function () {
        if (!currentSessionId) return;
        Swal.fire({
            title: "Xóa log hội thoại này?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
        }).then((result) => {
            if (result.isConfirmed) {
                deleteSession(currentSessionId).done(function (res) {
                    Swal.fire("Đã xóa!", res.message, "success");
                    resetRightPanel(); // Reset khung bên phải
                    loadSessions();
                });
            }
        });
    });

    sessionListEl.on("click", ".session-item", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        if (id !== currentSessionId) loadChatMessages(id);
    });

    searchInput.on("keyup", function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadSessions, 500);
    });

    // --- CÁC SỰ KIỆN NÚT NGÀY THÁNG ---

    btnPrevDay.on("click", function () {
        // Trừ đi 1 ngày
        currentDateObj.setDate(currentDateObj.getDate() - 1);
        updateDateUI();
        resetRightPanel(); // Khi đổi ngày thì xóa giao diện đoạn chat cũ đi
        loadSessions();
    });

    btnNextDay.on("click", function () {
        // Cộng thêm 1 ngày
        currentDateObj.setDate(currentDateObj.getDate() + 1);
        updateDateUI();
        resetRightPanel();
        loadSessions();
    });

    filterDateInput.on("change", function () {
        // Khi user tự dùng lịch chọn 1 ngày bất kỳ
        if ($(this).val()) {
            currentDateObj = new Date($(this).val());
            updateDateUI();
            resetRightPanel();
            loadSessions();
        }
    });

    updateDateUI(); // Set input thành hôm nay và khóa nút Next
    loadSessions(); // Load dữ liệu hôm nay
});
