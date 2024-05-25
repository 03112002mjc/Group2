function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
function toggleEyeIcon() {
    var passwordInput = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");
    var showPasswordBtn = document.getElementById("showPasswordBtn");
    if (passwordInput.value.length > 0) {
        eyeIcon.style.display = "block";
        showPasswordBtn.style.display = "block";
    } else {
        eyeIcon.style.display = "none";
        showPasswordBtn.style.display = "none";
    }
}