import {
    getSessions,
    createSession,
    getMessages,
    deleteSession,
} from "../../services/chat-ai-api.js";
import {
    processSendMessage,
    formatMessage,
    scrollToBottom,
} from "./send-message.js";

$(document).ready(function () {
    let currentSessionId = null;

    // Elements
    const sessionListEl = $("#session-list");
    const messagesEl = $("#full-chat-messages"); // Nơi append tin nhắn
    const scrollAreaEl = $("#chat-scroll-area"); // Nơi có thanh cuộn
    const inputEl = $("#full-chat-input");
    const sendBtn = $("#full-send-btn");
    const btnNewChat = $("#btn-new-chat");
    const welcomeScreenHTML = $(".welcome-screen").prop("outerHTML");
    const STORAGE_KEY = "garage_chat_session_id";

    // 1. Load Session Sidebar
    function loadSessions() {
        getSessions().done((res) => {
            sessionListEl.empty();
            if (res.sessions.length === 0) {
                sessionListEl.append(
                    '<div class="text-center p-3 text-muted small">Chưa có trò chuyện.</div>',
                );
                return;
            }

            let isSavedSessionExists = false;
            let savedSessionId = localStorage.getItem(STORAGE_KEY);

            res.sessions.forEach((session) => {
                let activeClass =
                    session.id === currentSessionId ? "active" : "";
                sessionListEl.append(`
                    <div class="session-item ${activeClass}" data-id="${session.id}">
                        <i class="far fa-comment-alt me-3"></i> 
                        <span class="text-truncate flex-grow-1">${session.title}</span>
                        
                        <div class="dropdown session-options">
                            <button class="btn-session-opt" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="font-size: 0.9rem;">
                                <li>
                                    <a class="dropdown-item text-danger btn-delete-session" href="#" data-id="${session.id}">
                                        <i class="fas fa-trash-alt me-2"></i> Xóa cuộc trò chuyện
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                `);
                if (savedSessionId && session.id == savedSessionId) {
                    isSavedSessionExists = true;
                }
            });
            if (savedSessionId && isSavedSessionExists && !currentSessionId) {
                switchSession(savedSessionId);
            }
        });
    }

    // 2. Chuyển Session (Load lịch sử chat)
    function switchSession(sessionId) {
        currentSessionId = sessionId;
        localStorage.setItem(STORAGE_KEY, sessionId);

        $(".session-item").removeClass("active");
        $(`.session-item[data-id="${sessionId}"]`).addClass("active");

        messagesEl.empty();
        messagesEl.append(
            '<div class="text-center text-muted my-3"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>',
        );

        getMessages(sessionId).done((res) => {
            messagesEl.empty();
            if (res.messages.length > 0) {
                res.messages.forEach((msg) => {
                    let cssClass = msg.sender === "user" ? "user" : "bot";
                    messagesEl.append(`
                        <div class="chat-bubble ${cssClass}">
                            ${formatMessage(msg.content)}
                        </div>
                    `);
                });
                scrollToBottom(scrollAreaEl);
            } else {
                if (welcomeScreenHTML) {
                    messagesEl.html(welcomeScreenHTML);
                }
            }
        });
    }

    // 3. Events Sidebar
    sessionListEl.on("click", ".session-item", function () {
        let id = $(this).data("id");
        if (id !== currentSessionId) switchSession(id);
    });

    btnNewChat.on("click", function () {
        if ($(".welcome-screen").length > 0) {
            inputEl.focus();
            return;
        }

        localStorage.removeItem(STORAGE_KEY);
        currentSessionId = null;
        $(".session-item").removeClass("active");
        messagesEl.html(welcomeScreenHTML);
        inputEl.val("");
        inputEl.focus();
    });

    // 4. Gửi tin nhắn (Sử dụng hàm dùng chung)
    function execSendMessage() {
        let text = inputEl.val().trim();
        if (!text) return;
        const sendData = () => {
            $(".welcome-screen").remove();

            processSendMessage({
                sessionId: currentSessionId,
                messageText: text,
                inputEl: inputEl,
                sendBtn: sendBtn,
                appendContainer: messagesEl,
                scrollContainer: scrollAreaEl,
                onSuccess: (res) => {
                    if (res.session_title) loadSessions();
                },
            });
        };

        if (!currentSessionId) {
            inputEl.prop("disabled", true);
            sendBtn.prop("disabled", true);

            createSession()
                .done((res) => {
                    currentSessionId = res.session.id;
                    localStorage.setItem(STORAGE_KEY, currentSessionId);
                    loadSessions();
                    sendData();
                })
                .fail(() => {
                    inputEl.prop("disabled", false);
                    sendBtn.prop("disabled", false);
                    alert("Đã có lỗi xảy ra khi khởi tạo cuộc trò chuyện.");
                });
        } else {
            sendData();
        }
    }

    sendBtn.on("click", execSendMessage);
    inputEl.on("keypress", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            execSendMessage();
        }
    });

    // Sự kiện click chọn đoạn chat
    sessionListEl.on("click", ".session-item", function () {
        let id = $(this).data("id");
        if (id !== currentSessionId) switchSession(id);
    });

    // NGĂN CHẶN SỰ KIỆN: Khi click vào dấu 3 chấm, không được kích hoạt sự kiện chọn đoạn chat
    sessionListEl.on("click", ".session-options", function (e) {
        e.stopPropagation();
    });

    // Xử lý khi click vào nút Xóa
    sessionListEl.on("click", ".btn-delete-session", function (e) {
        e.preventDefault();
        e.stopPropagation(); // Ngăn nổi bọt

        let sessionIdToDelete = $(this).data("id");
        let sessionElement = $(`.session-item[data-id="${sessionIdToDelete}"]`);

        // Sử dụng SweetAlert để xác nhận
        Swal.fire({
            title: "Xóa cuộc trò chuyện?",
            text: "Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                // Hiển thị loading nhẹ trên item
                sessionElement.css("opacity", "0.5");

                deleteSession(sessionIdToDelete)
                    .done(() => {
                        // 1. Xóa phần tử khỏi giao diện (cực mượt, không cần reload trang)
                        sessionElement.slideUp(300, function () {
                            $(this).remove();
                        });

                        // 2. Xử lý logic hiển thị
                        // NẾU user vừa xóa chính đoạn chat đang hiển thị trên màn hình lớn
                        if (sessionIdToDelete == currentSessionId) {
                            currentSessionId = null;
                            localStorage.removeItem(STORAGE_KEY);
                            messagesEl.html(welcomeScreenHTML); // Hiện lại màn hình Welcome
                            inputEl.val("").focus();
                        }

                        // Nếu xóa hết sạch danh sách, hiện thông báo rỗng
                        if ($(".session-item").length === 1) {
                            // 1 vì cái bị xóa đang chạy animation slideUp chưa mất hẳn
                            sessionListEl.html(
                                '<div class="text-center p-3 text-muted small">Chưa có trò chuyện.</div>',
                            );
                        }

                        Toast.fire({
                            icon: "success",
                            title: "Đã xóa đoạn chat",
                        });
                    })
                    .fail(() => {
                        sessionElement.css("opacity", "1");
                        Swal.fire(
                            "Lỗi!",
                            "Không thể xóa đoạn chat lúc này.",
                            "error",
                        );
                    });
            }
        });
    });
    // Init
    loadSessions();
});
