<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Harap login terlebih dahulu.");
}

// Validasi keberadaan key 'role' dalam session
if (!isset($_SESSION['role'])) {
    die("Role pengguna tidak ditemukan. Harap login ulang.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $fullContent = $_POST['isi_artikel'];
    $layout = $_POST['layout'];
    $imagePath = null;
    $extraImage1 = null;
    $extraImage2 = null;

    // Validasi input
    if (empty($title) || empty($category) || empty($content) || empty($fullContent)) {
        die("Harap isi semua bidang yang diperlukan.");
    }

    // Upload gambar sampul
    $targetDir = "../assets/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($_FILES['image']['name'])) {
        $imagePath = $targetDir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Gagal mengunggah gambar sampul. Pastikan folder 'uploads' tersedia dan dapat ditulis.");
        }
    } else {
        die("Gambar sampul wajib diunggah.");
    }

    // Upload gambar tambahan untuk layout2 dan layout3
    if ($layout === 'layout2' || $layout === 'layout3') {
        if (!empty($_FILES['gambar2']['name'])) {
            $extraImage1 = $targetDir . basename($_FILES['gambar2']['name']);
            if (!move_uploaded_file($_FILES['gambar2']['tmp_name'], $extraImage1)) {
                die("Gagal mengunggah gambar tambahan 1.");
            }
        }
        if ($layout === 'layout3' && !empty($_FILES['gambar3']['name'])) {
            $extraImage2 = $targetDir . basename($_FILES['gambar3']['name']);
            if (!move_uploaded_file($_FILES['gambar3']['tmp_name'], $extraImage2)) {
                die("Gagal mengunggah gambar tambahan 2.");
            }
        }
    }

    // Simpan artikel ke database
    $stmt = $conn->prepare("INSERT INTO articles (kategori, judul, konten, isi_artikel, gambar, gambar2, gambar3, tanggal, penulis, author_id, layout) 
                            VALUES (:kategori, :judul, :konten, :isi_artikel, :gambar, :gambar2, :gambar3, :tanggal, :penulis, :author_id, :layout)");
    $stmt->execute([
        'kategori' => $category,
        'judul' => $title,
        'konten' => $content,
        'isi_artikel' => $fullContent,
        'gambar' => $imagePath,
        'gambar2' => $extraImage1,
        'gambar3' => $extraImage2,
        'tanggal' => date('Y-m-d'),
        'penulis' => $_SESSION['username'] ?? 'Admin',
        'author_id' => $_SESSION['user_id'] ?? null,
        'layout' => $layout
    ]);

    echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href='Tambah.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Artikel Baru</title>
  <link rel="stylesheet" href="Tambah.css">
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const layoutSelect = document.getElementById('layout');
        const extraImages = document.getElementById('extra-images');

        layoutSelect.addEventListener('change', function () {
            if (this.value === 'layout2' || this.value === 'layout3') {
                extraImages.style.display = 'block';
            } else {
                extraImages.style.display = 'none';
            }
        });
    });
  </script>
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
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <!-- Menu untuk admin -->
        <li><a href="admin.php">Profil</a></li>
        <li><a href="verifikasi.php">Verifikasi Artikel</a></li>
        <li><a href="hapus_artikel.php">Hapus Artikel</a></li>
        <li><a href="daftar_akun.php">Daftar Akun</a></li>
        <li><a href="form_penulis.php">Formulir Penulis</a></li>
        <li><a href="artikel_baru.php">Artikel Baru</a></li>
        <li><a href="edit_artikel.php">Edit Artikel</a></li>
        <?php elseif ($_SESSION['role'] === 'penulis'): ?>
        <!-- Menu untuk penulis -->
        <li><a href="Profile.php">Profile</a></li>
        <li><a href="Tambah.php">Tambah Artikel</a></li>
        <li><a href="edit_artikel.php">Edit Artikel</a></li>
        <li><a href="artikel_saya.php">Artikel Saya</a></li>
        <li><a href="hapus_artikel.php">Hapus Artikel</a></li>
        <?php endif; ?>
      </ul>
      <a href="logout.php" class="logout">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h2>Tambah Artikel Baru</h2>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Judul Artikel</label>
          <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
          <label for="category">Kategori</label>
          <select id="category" name="category" required>
            <option value="">Pilih Kategori</option> 
            <option value="Bisnis">Bisnis</option>
            <option value="Keuangan">Keuangan</option>
            <option value="Olahraga">Olahraga</option>
            <option value="Internasional">Internasional</option>
            <option value="Budaya">Budaya</option>
          </select>
        </div>
        <div class="form-group">
          <label for="layout">Pilih Layout</label>
          <select id="layout" name="layout" required>
            <option value="layout1">Layout 1 (Gambar sebelum teks)</option>
            <option value="layout2">Layout 2 (2 Gambar setelah teks)</option>
            <option value="layout3">Layout 3 (3 Gambar setelah teks)</option>
          </select>
        </div>
        <div class="form-group">
          <label for="content">Konten</label>
          <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
          <label for="isi_artikel">Isi Lengkap Artikel</label>
          <textarea id="isi_artikel" name="isi_artikel" rows="15" placeholder="Tulis isi lengkap artikel Anda" required></textarea>
        </div>
        <div class="form-group">
          <label for="image">Gambar Sampul</label>
          <input type="file" id="image" name="image" accept="image/*" required>
        </div>
        <div class="form-group-optional-images" id="extra-images" style="display: none;">
          <label for="gambar2">Gambar Tambahan (Opsional untuk Layout 2 & 3)</label>
          <input type="file" id="gambar2" name="gambar2" accept="image/*">
          <input type="file" id="gambar3" name="gambar3" accept="image/*">
        </div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="form-group">
          <label for="author_id">Pilih Penulis</label>
          <select id="author_id" name="author_id">
            <!-- Tambahkan opsi dari database -->
          </select>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn-submit">Tambahkan Artikel</button>
      </form>
    </main>
  </div>
</body>
</html>
