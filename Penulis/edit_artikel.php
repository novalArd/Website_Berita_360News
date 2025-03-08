<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'penulis') {
    die("Akses ditolak. Harap login terlebih dahulu.");
}

$userId = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT id, judul FROM articles WHERE author_id = :author_id ORDER BY tanggal DESC");
$stmt->execute(['author_id' => $userId]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['article_id'])) {
    $articleId = $_GET['article_id'];

    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id AND author_id = :author_id");
    $stmt->execute([
        'id' => $articleId,
        'author_id' => $userId
    ]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Artikel tidak ditemukan atau Anda tidak memiliki izin untuk mengedit artikel ini.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['article_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $imagePath = $_POST['current_image'];

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../assets/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Gagal mengunggah gambar.");
        }
    }

    $stmt = $conn->prepare("UPDATE articles SET judul = :judul, konten = :konten, kategori = :kategori, gambar = :gambar WHERE id = :id AND author_id = :author_id");
    $stmt->execute([
        ':judul' => $title,
        ':konten' => $content,
        ':kategori' => $category,
        ':gambar' => $imagePath,
        ':id' => $articleId,
        ':author_id' => $userId
    ]);

    echo "<script>alert('Artikel berhasil diperbarui!'); window.location.href='edit_artikel.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Artikel</title>
  <link rel="stylesheet" href="edit_artikel.css">
</head>
<body>
  <div class="container">
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

    <main class="main-content">
      <h2>Edit Artikel</h2>

      <!-- Form Pilih Artikel -->
      <form method="GET" action="edit_artikel.php">
        <label for="article-id">Pilih Artikel untuk Diedit:</label>
        <select id="article-id" name="article_id" required>
          <?php foreach ($articles as $articleItem): ?>
            <option value="<?= $articleItem['id']; ?>"><?= htmlspecialchars($articleItem['judul']); ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn-edit" type="submit">Edit Artikel</button>
      </form>

      <!-- Form Edit Artikel -->
      <?php if (isset($article)): ?>
      <form method="POST" enctype="multipart/form-data" action="edit_artikel.php">
        <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
        <input type="hidden" name="current_image" value="<?= $article['gambar']; ?>">

        <div class="form-group">
          <label for="title">Judul Artikel</label>
          <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['judul']); ?>" required>
        </div>
        <div class="form-group">
          <label for="category">Kategori</label>
          <select id="category" name="category" required>
            <option value="">Pilih Kategori</option>
            <option value="Bisnis" <?= $article['kategori'] === 'Bisnis' ? 'selected' : ''; ?>>Bisnis</option>
            <option value="Keuangan" <?= $article['kategori'] === 'Keuangan' ? 'selected' : ''; ?>>Keuangan</option>
            <option value="Olahraga" <?= $article['kategori'] === 'Olahraga' ? 'selected' : ''; ?>>Olahraga</option>
            <option value="Internasional" <?= $article['kategori'] === 'Internasional' ? 'selected' : ''; ?>>Internasional</option>
            <option value="Budaya" <?= $article['kategori'] === 'Budaya' ? 'selected' : ''; ?>>Budaya</option>
          </select>
        </div>
        <div class="form-group">
          <label for="content">Konten</label>
          <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($article['konten']); ?></textarea>
        </div>
        <div class="form-group">
          <label for="image">Gambar</label>
          <input type="file" id="image" name="image" accept="image/*">
          <p>Gambar saat ini: <?= htmlspecialchars($article['gambar']); ?></p>
        </div>
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
      </form>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
