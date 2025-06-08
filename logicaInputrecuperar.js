function sendCode() {
    alert("Código enviado al correo.");
    document.getElementById("code").classList.remove("hidden");
}

document.getElementById("code").addEventListener("input", function() {
    if (this.value.length > 0) {
        document.getElementById("newPassword").classList.remove("hidden");
        document.getElementById("confirmPassword").classList.remove("hidden");
        document.getElementById("submitBtn").classList.remove("hidden");
    }
});