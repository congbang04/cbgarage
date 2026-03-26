import { sendChatMessage } from "../../services/chat-ai-api.js";

// Helper: Format text (In đậm và xuống dòng)
export function formatMessage(text) {
    if (!text) return "";
    let formatted = text.replace(/\*\*(.*?)\*\*/g, "<b>$1</b>");
    formatted = formatted.replace(/\n/g, "<br>");
    return formatted;
}

// Helper: Cuộn xuống cuối khung chat
export function scrollToBottom(scrollContainer) {
    if (scrollContainer && scrollContainer.length) {
        scrollContainer.scrollTop(scrollContainer[0].scrollHeight);
    }
}

/**
 * Hàm xử lý gửi tin nhắn dùng chung cho cả Full Chat và Widget
 * * @param {Object} params Object chứa các tham số:
 * - sessionId: ID của cuộc trò chuyện hiện tại
 * - messageText: Nội dung tin nhắn
 * - inputEl: Element ô nhập liệu (jQuery object)
 * - sendBtn: Element nút gửi (jQuery object)
 * - appendContainer: Element chứa tin nhắn để append thẻ div vào (jQuery object)
 * - scrollContainer: Element có thanh cuộn (jQuery object)
 * - onSuccess: Hàm callback chạy khi thành công (Tùy chọn)
 */
export function processSendMessage({
    sessionId,
    messageText,
    inputEl,
    sendBtn,
    appendContainer,
    scrollContainer,
    onSuccess = null,
}) {
    if (!messageText || !sessionId) return;

    // 1. Render tin nhắn của User
    inputEl.val("");
    appendContainer.append(
        `<div class="chat-bubble user">${formatMessage(messageText)}</div>`,
    );
    scrollToBottom(scrollContainer);

    // 2. Render hiệu ứng Bot đang gõ
    let typingId = "typing-" + Date.now();
    appendContainer.append(`
        <div id="${typingId}" class="chat-bubble bot text-muted small fst-italic">
            <i class="fas fa-circle-notch fa-spin me-2"></i> Đang phân tích...
        </div>
    `);
    scrollToBottom(scrollContainer);

    // 3. Khóa Input và Nút gửi
    inputEl.prop("disabled", true);
    sendBtn.prop("disabled", true).removeClass("active");

    // 4. Gọi API
    sendChatMessage(sessionId, messageText)
        .done((res) => {
            // Xóa hiệu ứng typing
            $(`#${typingId}`).remove();

            // Render tin nhắn của Bot
            appendContainer.append(
                `<div class="chat-bubble bot">${formatMessage(res.reply)}</div>`,
            );

            // Gọi callback nếu có truyền vào (VD: cập nhật lại Sidebar)
            if (onSuccess) onSuccess(res);
        })
        .fail((err) => {
            $(`#${typingId}`).remove();
            appendContainer.append(
                `<div class="chat-bubble bot text-danger">Lỗi kết nối. Vui lòng thử lại.</div>`,
            );
        })
        .always(() => {
            // Mở khóa UI
            inputEl.prop("disabled", false);
            sendBtn.prop("disabled", false);
            inputEl.focus();
            scrollToBottom(scrollContainer);
        });
}
