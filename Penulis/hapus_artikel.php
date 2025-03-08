<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'penulis') {
    die("Akses ditolak. Harap login terlebih dahulu.");
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, judul, tanggal, views, likes FROM articles WHERE author_id = :author_id ORDER BY tanggal DESC");
$stmt->execute(['author_id' => $userId]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    $articleId = $_POST['article_id'];

    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id AND author_id = :author_id");
    $stmt->execute([
        'id' => $articleId,
        'author_id' => $userId
    ]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Artikel tidak ditemukan atau Anda tidak memiliki izin untuk menghapus artikel ini.");
    }

    $stmt = $conn->prepare("DELETE FROM articles WHERE id = :id AND author_id = :author_id");
    $stmt->execute([
        'id' => $articleId,
        'author_id' => $userId
    ]);

    echo "<script>alert('Artikel berhasil dihapus!'); window.location.href='hapus_artikel.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hapus Artikel Saya</title>
  <link rel="stylesheet" href="hapus_artikel.css">
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
        <li><a id="profil" href="Profile.php">Profile</a></li>
        <li><a id="tambahArtikel" href="Tambah.php">Tambah Artikel</a></li>
        <li><a id="editArtikel" href="edit_artikel.php">Edit Artikel</a></li>
        <li><a id="artikelSaya" href="artikel_saya.php">Artikel Saya</a></li>
        <li><a id="hapusArtikel" href="hapus_artikel.php">Hapus Artikel</a></li>
      </ul>
      <a href="../logout.php" class="logout">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Hapus Artikel Saya</h2>
      <table class="article-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Artikel</th>
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
                    <td><?= htmlspecialchars($article['tanggal']); ?></td>
                    <td><?= htmlspecialchars($article['views']); ?></td>
                    <td><?= htmlspecialchars($article['likes']); ?></td>
                    <td>
                        <form method="POST" action="hapus_artikel.php">
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
