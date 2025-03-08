<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Harap login sebagai admin.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    $articleId = $_POST['article_id'];


    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute(['id' => $articleId]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Artikel tidak ditemukan.");
    }

    $stmt = $conn->prepare("DELETE FROM articles WHERE id = :id");
    $stmt->execute(['id' => $articleId]);

    echo "<script>alert('Artikel berhasil dihapus!'); window.location.href='hapus_artikel_admin.php';</script>";
}


$stmt = $conn->prepare("SELECT id, judul, tanggal, views, likes, penulis FROM articles ORDER BY tanggal DESC");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hapus Artikel</title>
  <link rel="stylesheet" href="hapus_artikel_admin.css">
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
        <li class="profil"><a href="profile_admin.php" class="active">Profil</a></li>
        <li class="verifikasiArtikel"><a href="verifikasi_artikel.php">Verifikasi Artikel</a></li>
        <li class="hapusArtikel"><a href="hapus_artikel_admin.php">Hapus Artikel</a></li>
        <li class="daftarAkun"><a href="daftar_akun.php">Daftar Akun</a></li>
        <li class="formulirPenulis"><a href="form_penulis.php">Formulir Penulis</a></li>
        <li class="tambahArtikel"><a href="Tambah_admin.php">Tambah Artikel</a></li>
        <li class="editArtikel"><a href="edit_artikel_admin.php">Edit Artikel</a></li>
        <li class="semuaArtikel"><a href="artikel_saya_admin.php">Semua Artikel</a></li>
      </ul>
      <a href="../logout.php" class="logout">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Hapus Artikel</h2>
      <table class="article-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Artikel</th>
                <th>Penulis</th>
                <th>Tanggal Dibuat</th>
                <th>Views</th>
                <th>Likes</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $index => $article): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($article['judul']); ?></td>
                    <td><?= htmlspecialchars($article['penulis']); ?></td>
                    <td><?= htmlspecialchars($article['tanggal'] ?? 'Tidak tersedia'); ?></td>
                    <td><?= htmlspecialchars($article['views'] ?? 0); ?></td>
                    <td><?= htmlspecialchars($article['likes'] ?? 0); ?></td>
                    <td>
                        <form method="POST" action="hapus_artikel_admin.php">
                            <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
                            <button type="submit" class="delete-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
