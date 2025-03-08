<?php
// Koneksi ke database
require 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($email) || empty($nama) || empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
        exit;
    }

    // Periksa apakah email atau username sudah digunakan
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo json_encode(['status' => 'error', 'message' => 'Email atau username sudah terdaftar.']);
        exit;
    }

    try {
        // Insert data ke database
        $stmt = $conn->prepare("
            INSERT INTO users (email, nama_lengkap, username, password, role, created_at) 
            VALUES (:email, :nama, :username, :password, 'pengguna', NOW())
        ");
        $stmt->execute([
            'email' => $email,
            'nama' => $nama,
            'username' => $username,
            'password' => $password
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil. Silakan login.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid.']);
}
?>
