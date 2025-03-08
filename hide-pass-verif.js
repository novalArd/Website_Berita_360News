const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const passwordInput2 = document.getElementById('password-2');
const togglePassword2 = document.getElementById('togglePassword2');

// Fungsi untuk toggle visibility password
togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    if (type === 'password') {
        togglePassword.src = 'gambar/hide.png'; 
    } else {
        togglePassword.src = 'gambar/view.png'; 
    }
});

togglePassword2.addEventListener('click', () => {
    const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput2.setAttribute('type', type);

    if (type === 'password') {
        togglePassword2.src = 'gambar/hide.png'; 
    } else {
        togglePassword2.src = 'gambar/view.png'; 
    }
});

// Event listener untuk memeriksa input pada password field
passwordInput.addEventListener('input', () => {
    if (passwordInput.value.length > 0) {
        togglePassword.style.display = 'block';
    } else {
        togglePassword.style.display = 'none'; 
    }
});


passwordInput2.addEventListener('input', () => {
    if (passwordInput2.value.length > 0) {
        togglePassword2.style.display = 'block';
    } else {
        togglePassword2.style.display = 'none'; 
    }
});