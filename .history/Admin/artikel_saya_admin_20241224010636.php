<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Harap login sebagai admin.");
}

// Ambil semua artikel dari database
$stmt = $conn->prepare("SELECT id, judul, tanggal, views, likes, penulis FROM articles ORDER BY tanggal DESC");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Semua Artikel</title>
  <link rel="stylesheet" href="artikel_saya_admin.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
    <div class="kembali-btn">
        <a href="../beranda.php"><button>Kembali</button></a>
      </div>
      <h3>Dashboard</h3>
      <ul>
      <li><a href="profile_admin.php" class="active">Profil</a></li>
        <li><a href="verifikasi_artikel.php">Verifikasi Artikel</a></li>
        <li><a href="hapus_artikel_admin.php">Hapus Artikel</a></li>
        <li><a href="daftar_akun.php">Daftar Akun</a></li>
        <li><a href="form_penulis.php">Formulir Penulis</a></li>
        <li><a href="Tambah_admin.php">Tambah Artikel</a></li>
        <li><a href="edit_artikel_admin.php">Edit Artikel</a></li>
        <li class="semuaArtikel"><a href="artikel_saya_admin.php">Semua Artikel</a></li>
      </ul>
      <a href="../logout.php" class="logout">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Semua Artikel</h2>
      <div class="article-list">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Judul Artikel</th>
              <th>Penulis</th>
              <th>Tanggal Dibuat</th>
              <th>Views</th>
              <th>Likes</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($articles as $index => $article): ?>
              <tr>
                <td id="nomor"><?= $index + 1; ?></td>
                <td><?= htmlspecialchars($article['judul']); ?></td>
                <td><?= htmlspecialchars($article['penulis']); ?></td>
                <td><?= htmlspecialchars($article['tanggal']); ?></td>
                <td><?= htmlspecialchars($article['views']); ?></td>
                <td><?= htmlspecialchars($article['likes']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
