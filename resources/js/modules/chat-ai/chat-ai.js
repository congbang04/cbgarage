import {
    getSessions,
    createSession,
    getMessages,
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

    // Init
    loadSessions();
});
