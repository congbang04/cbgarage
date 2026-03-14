import { loadSlots } from "./booking-core.js";
let currentChannel = null;
function leaveChannel() {
    if (!currentChannel) return;

    Echo.leave(currentChannel);
    console.log("Rời kênh:", currentChannel);

    currentChannel = null;
}
function joinChannel(date) {
    const channelName = `slots.${date}`;
    if (channelName === currentChannel) return;

    leaveChannel();

    currentChannel = channelName;
    console.log("Đang lắng nghe:", channelName);

    Echo.channel(channelName).listen(".slot.updated", () => {
        loadSlots();
    });
    loadSlots();
}
export function initRealtimeSlots() {
    const date = $("input[name='date']").val();

    if (!date) {
        leaveChannel();
        return;
    }

    joinChannel(date);

    loadSlots();
}
