export function sendChatRequest(messageText) {
    return $.ajax({
        url: "/chat/send",
        type: "POST",
        data: {
            message: messageText,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
}
