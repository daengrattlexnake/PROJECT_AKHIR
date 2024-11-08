document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    togglePassword.addEventListener('click', function () {
        // Cek apakah password saat ini terlihat
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Ubah menjadi teks
            togglePassword.src = 'assets/hide-icon.png'; // Ganti ikon ke hide
        } else {
            passwordInput.type = 'password'; // Ubah kembali ke password
            togglePassword.src = 'assets/show-icon.png'; // Ganti ikon ke show
        }
    });
});
