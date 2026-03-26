import { sendChatRequest } from "../services/chat-ai-api.js";

$(document).ready(function () {
    // ==========================================
    // 1. UI LOGIC: ẨN/HIỆN WIDGET TRANG CHỦ
    // ==========================================
    const chatWidget = $("#chat-widget");
    const fabBtn = $("#fab-chat-btn");
    const closeBtn = $("#close-chat");

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

    // ==========================================
    // 2. CORE CHAT LOGIC (Dùng chung)
    // ==========================================

    // Khởi tạo các biến, kiểm tra xem đang ở trang full hay trang có widget
    let isWidget = $("#chat-input").length > 0;
    let isFullChat = $("#full-chat-input").length > 0;

    let inputEl = isFullChat ? $("#full-chat-input") : $("#chat-input");
    let sendBtn = isFullChat ? $("#full-send-btn") : $("#send-btn");
    let messagesContainer = isFullChat
        ? $("#full-chat-messages")
        : $("#chat-messages");

    // Hàm render Markdown cơ bản (xuống dòng và in đậm)
    function formatMessage(text) {
        // Thay **text** thành <b>text</b>
        let formattedText = text.replace(/\*\*(.*?)\*\*/g, "<b>$1</b>");
        // Thay \n thành <br>
        formattedText = formattedText.replace(/\n/g, "<br>");
        return formattedText;
    }

    // Hàm cuộn xuống đáy
    function scrollToBottom() {
        if (messagesContainer.length) {
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
        }
    }

    // Hàm gửi tin nhắn
    function sendMessage() {
        let messageText = inputEl.val().trim();
        if (messageText === "") return;

        // 1. Hiển thị tin nhắn user
        inputEl.val("");
        messagesContainer.append(`
            <div class="chat-bubble user">
                ${messageText}
            </div>
        `);
        scrollToBottom();

        // 2. Hiển thị Typing Effect của Bot
        let typingId = "typing-" + Date.now();
        messagesContainer.append(`
            <div id="${typingId}" class="chat-bubble bot text-muted small fst-italic">
                <i class="fas fa-circle-notch fa-spin me-1"></i> Đang phân tích...
            </div>
        `);
        scrollToBottom();

        // Khóa input trong lúc chờ
        inputEl.prop("disabled", true);
        sendBtn.prop("disabled", true);

        // 3. Gọi API qua module chat-ai-api
        sendChatRequest(messageText)
            .done(function (response) {
                // Xóa icon typing
                $(`#${typingId}`).remove();

                // Format và hiển thị kết quả từ Gemini
                let htmlReply = formatMessage(response.reply);
                messagesContainer.append(`
                    <div class="chat-bubble bot">
                        ${htmlReply}
                    </div>
                `);
            })
            .fail(function (xhr) {
                $(`#${typingId}`).remove();

                messagesContainer.append(`
                    <div class="chat-bubble bot text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Đã có lỗi xảy ra. Vui lòng thử lại.
                    </div>
                `);
            })
            .always(function () {
                // Mở khóa input
                inputEl.prop("disabled", false);
                sendBtn.prop("disabled", false);
                inputEl.focus();
                scrollToBottom();
            });
    }

    // ==========================================
    // 3. BIND EVENTS
    // ==========================================
    if (sendBtn.length) {
        // Nhấn nút Gửi
        sendBtn.on("click", sendMessage);

        // Nhấn phím Enter
        inputEl.on("keypress", function (e) {
            if (e.which === 13) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
});
