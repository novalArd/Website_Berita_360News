<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Harap login sebagai admin.");
}

// Ambil daftar penulis dari database
$stmt = $conn->prepare("SELECT id, nama_lengkap, email, nomor_hp, jenis_kelamin, role FROM users WHERE role = 'penulis' ORDER BY nama_lengkap ASC");
$stmt->execute();
$penulisList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Penulis</title>
  <link rel="stylesheet" href="daftar_akun.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h3>Dashboard</h3>
      <ul>
        <li class="profil"><a href="profile_admin.php" class="active">Profil</a></li>
        <li class="verifikasiArtikel"><a href="verifikasi_artikel.php">Verifikasi Artikel</a></li>
        <li><a href="hapus_artikel_admin.php">Hapus Artikel</a></li>
        <li><a href="daftar_akun.php">Daftar Akun</a></li>
        <li><a href="form_penulis.php">Formulir Penulis</a></li>
        <li><a href="Tambah_admin.php">Tambah Artikel</a></li>
        <li><a href="edit_artikel_admin.php">Edit Artikel</a></li>
        <li><a href="artikel_saya_admin.php">Semua Artikel</a></li>
      </ul>
      <a href="../logout.php" class="logout">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Daftar Akun Penulis</h2>
      <table class="account-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Nomor HP</th>
            <th>Jenis Kelamin</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($penulisList as $index => $penulis): ?>
            <tr>
              <td><?= $index + 1; ?></td>
              <td><?= htmlspecialchars($penulis['nama_lengkap']); ?></td>
              <td><?= htmlspecialchars($penulis['email']); ?></td>
              <td><?= htmlspecialchars($penulis['nomor_hp']); ?></td>
              <td><?= $penulis['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
              <td><?= htmlspecialchars($penulis['role']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
