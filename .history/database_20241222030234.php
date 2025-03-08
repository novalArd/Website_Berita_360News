<?php
$host = 'localhost';
$dbname = 'news_management';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda

try {
    // Membuat koneksi ke database menggunakan PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menampilkan pesan jika koneksi gagal
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Fungsi untuk memeriksa keberadaan pengguna dalam database tanpa hashing password
function checkUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password"); // Query SQL untuk mencari username dan password
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user : false; // Mengembalikan data pengguna jika ditemukan, false jika tidak
}
?>
