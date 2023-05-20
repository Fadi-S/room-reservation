function sendMessage(title, message, type, time = 0) {
    const flash = {
        title,
        message,
        type,
        important: time,
    };

    window.dispatchEvent(new CustomEvent("flash", { detail: flash }));
}

function success(title, message, time = 0) {
    sendMessage(title, message, "success", time);
}

function error(title, message, time = 0) {
    sendMessage(title, message, "error", time);
}

function warning(title, message, time = 0) {
    sendMessage(title, message, "warning", time);
}

function clipboard(title, message, time = 0) {
    sendMessage(title, message, "clipboard", time);
}

export default { sendMessage, success, error, warning, clipboard };
