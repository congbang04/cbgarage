export function getSessions(searchQuery, dateQuery) {
    return $.ajax({
        url: window.routes.adminAiLogSessions,
        type: "GET",
        data: { search: searchQuery, date: dateQuery },
    });
}

export function getMessages(sessionId) {
    return $.ajax({
        url: window.routes.adminAiLogMessages.replace(":sessionId", sessionId),
        type: "GET",
    });
}

export function deleteSession(sessionId) {
    return $.ajax({
        url: window.routes.adminAiLogDelete.replace(":sessionId", sessionId),
        type: "DELETE",
    });
}
