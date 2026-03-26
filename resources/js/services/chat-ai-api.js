export function getSessions() {
    return $.ajax({
        url: window.routes.chatGetSessions,
        type: "GET",
    });
}

export function createSession() {
    return $.ajax({
        url: window.routes.chatCreateSession,
        type: "POST",
    });
}

export function getMessages(sessionId) {
    return $.ajax({
        url: window.routes.chatGetMessagesBySession.replace(
            ":sessionId",
            sessionId,
        ),
        type: "GET",
    });
}

export function sendChatMessage(sessionId, messageText) {
    return $.ajax({
        url: window.routes.chatSend,
        type: "POST",
        data: {
            session_id: sessionId,
            message: messageText,
        },
    });
}
