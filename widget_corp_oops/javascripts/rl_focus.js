document.addEventListener("DOMContentLoaded", function () {
    const error = document.getElementById("form-error");
    const usernameInput = document.getElementById("username");

    if (!usernameInput) return;

    if (error) {
        // If there's an error message, focus username (screen reader will read the message)
        usernameInput.focus();
    } else {
        // No error → first page load (or redirected from register), still focus username
        usernameInput.focus();
    }
});
