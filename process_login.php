<?php
session_start();
require 'database.php';

header('Content-Type: application/json');

// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username dan password harus diisi.']);
        exit;
    }

    // Cek pengguna di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['jenis_kelamin'] = $user['jenis_kelamin'];
        $_SESSION['nomor_hp'] = $user['nomor_hp'];

        echo json_encode(['status' => 'success', 'message' => 'Login berhasil']);
    } else {
        // Jika login gagal
        echo json_encode(['status' => 'error', 'message' => 'Username atau password salah.']);
    }
    exit;
}
