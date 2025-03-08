document.querySelector('.login-btn button').addEventListener('click', function() {
    const username = document.querySelector('input[type=email]').value;
    const password = document.querySelector('#password').value;

    fetch('process_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `username=${username}&password=${password}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = 'beranda.php';
        } else {
            alert(data.message);
        }
    });
});
