import { getSessions, createSession } from "../../services/chat-ai-api.js";
import { processSendMessage } from "./send-message.js";

$(document).ready(function () {
    // 1. UI LOGIC: ẨN/HIỆN WIDGET
    const chatWidget = $("#chat-widget");
    const fabBtn = $("#fab-chat-btn");
    const closeBtn = $("#close-chat");

    // 🌟 Tên key dùng để lưu trong Local Storage (Đồng bộ với Full Chat)
    const STORAGE_KEY = "garage_chat_session_id";

    fabBtn.on("click", function (e) {
        e.preventDefault();
        chatWidget.addClass("show");
        fabBtn.hide();
        setTimeout(() => $("#chat-input").focus(), 300);
    });

    closeBtn.on("click", function () {
        chatWidget.removeClass("show");
        fabBtn.show();
    });

    // 2. LOGIC WIDGET CHAT
    let widgetSessionId = null;
    const inputEl = $("#chat-input");
    const sendBtn = $("#send-btn");
    const messagesContainer = $("#chat-messages");

    // 3. Gửi tin nhắn
    function execSendMessage() {
        let text = inputEl.val().trim();
        if (!text) return;

        const sendData = () => {
            // Gọi hàm dùng chung từ send-message.js
            processSendMessage({
                sessionId: widgetSessionId,
                messageText: text,
                inputEl: inputEl,
                sendBtn: sendBtn,
                appendContainer: messagesContainer,
                scrollContainer: messagesContainer,
            });
        };

        // Nếu chưa có session (tin nhắn đầu tiên từ widget), tạo mới session
        if (!widgetSessionId) {
            inputEl.prop("disabled", true);
            sendBtn.prop("disabled", true);

            createSession()
                .done((res) => {
                    widgetSessionId = res.session.id;

                    // 🌟 LƯU VÀO LOCAL STORAGE
                    // Khi bạn bấm nút sang trang Full Chat, JS trang kia sẽ đọc biến này và mở đúng đoạn chat
                    localStorage.setItem(STORAGE_KEY, widgetSessionId);

                    sendData();
                })
                .fail(() => {
                    inputEl.prop("disabled", false);
                    sendBtn.prop("disabled", false);
                    alert("Đã có lỗi xảy ra khi khởi tạo cuộc trò chuyện.");
                });
        } else {
            // Nếu đã có widgetSessionId (đang chat các câu tiếp theo), gửi luôn
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
});
