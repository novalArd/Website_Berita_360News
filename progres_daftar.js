document.getElementById('daftar-button').addEventListener('click', function() {
    const email = document.getElementById('email').value.trim();
    const nama = document.getElementById('nama').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email || !nama || !username || !password) {
        alert('Semua field wajib diisi!');
        return;
    }

    fetch('process_register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&nama=${encodeURIComponent(nama)}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = 'beranda.php';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server. Silakan coba lagi.');
    });
});
