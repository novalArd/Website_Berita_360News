<?php
session_start();
require '../database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['id'];
    $action = $_POST['action'];
    $section = $_POST['section'] ?? null;

    if ($action === 'approve' && $section) {
        // Setujui artikel dan atur section
        $stmt = $conn->prepare("UPDATE articles SET status = 'approved', section = :section WHERE id = :id");
        $stmt->execute(['section' => $section, 'id' => $articleId]);
        echo "<script>alert('Artikel berhasil disetujui dan ditambahkan ke section!'); window.location.href='verifikasi_artikel.php';</script>";
    } elseif ($action === 'reject') {
        // Tolak artikel
        $stmt = $conn->prepare("UPDATE articles SET status = 'rejected' WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
        echo "<script>alert('Artikel ditolak.'); window.location.href='verifikasi_artikel.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan. Pastikan semua field diisi.'); window.location.href='verifikasi_artikel.php';</script>";
    }
}
?>
