<?php
session_start(); // Memulai session
$loggedIn = isset($_SESSION['user_id']); // Mengecek apakah pengguna sudah login
$username = $loggedIn ? $_SESSION['username'] : ''; // Mendapatkan username pengguna yang login

require '../database.php';

// Pastikan parameter ID ada
if (!isset($_GET['id'])) {
    die("Artikel tidak ditemukan.");
}


$articleId = $_GET['id'];

// Tingkatkan jumlah views
$stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
$stmt->execute(['id' => $articleId]);

// Query artikel berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Artikel tidak ditemukan.");
}

// Pastikan layout disesuaikan
$layout = $article['layout'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['judul']); ?> - News360</title>
    <link rel="stylesheet" href="artikel.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="like.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="left-section">
                <div class="logo">
                    <a href="beranda.php">
                        <img src="../gambar/LOGOO.png" alt="Logo">
                    </a>
                </div>
                <nav id="navMenu" class="hidden">
                    <div class="labelCari">
                        <input type="text" placeholder="Cari Berita" class="pencarian">
                        <button class="pencarian-btn"><b>Cari</b></button>
                    </div>
                    <a class="kategori" id="beranda" href="beranda.php">BERANDA</a>
                    <a class="kategori" id="bisnis" href="bisnis.php">BISNIS</a>
                    <a class="kategori" id="keuangan" href="keuangan.php">KEUANGAN</a>
                    <a class="kategori" id="olahraga" href="olahraga.php">OLAHRAGA</a>
                    <a class="kategori" id="internasional" href="internasional.php">INTERNASIONAL</a>
                    <a class="kategori" id="budaya" href="budaya.php">BUDAYA</a>
                </nav>
            </div>
            <div class="kanan-nav">
                <div class="ikon-menu" id="menuToggle">
                    <div class="baris"></div>
                    <div class="baris"></div>
                    <div class="baris"></div>
                </div>
                <?php if ($loggedIn): ?>
                    
                    <a href="tes/profile.php"><button id="profile-btn">Profil Saya</button></a> <!-- Tautan ke profil -->
                <?php else: ?>
                    <a href="pagelogin.html"><button id="login-btn">MASUK</button></a>
                    <a href="daftar.php"><button>DAFTAR</button></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Artikel -->
    <main class="article">
        <div class="container">
            <h1><?= htmlspecialchars($article['judul']); ?></h1>
            <p class="subtitle">Kategori: <?= htmlspecialchars($article['kategori']); ?></p>
            <div class="meta-info">
                <!-- Views -->
                <div class="views">
                    <img src="../gambar/mdi_eye-outline.png" alt="Views" class="icon">
                    <span><?= $article['views']; ?> kali</span>
                </div>

                <!-- Likes -->
                <div class="like-section">
                    <a href="#" id="like-button" data-id="<?= $article['id']; ?>">
                        <img src="../gambar/mdi_like.png" alt="Likes" class="icon">
                    </a>
                    <span id="like-count"><?= $article['likes']; ?></span>
                </div>
            </div>
            <p class="date"><?= date('j M Y, H:i T', strtotime($article['tanggal'])); ?></p>
            <div class="content">
                <?php if ($layout === 'layout1'): ?>
                    <div class="layout layout1">
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar" class="main-image">
                        <p><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                    </div>
                <?php elseif ($layout === 'layout2'): ?>
                    <div class="layout layout2">
                        <p><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar 1" class="main-image">
                        <img src="<?= htmlspecialchars($article['gambar2']); ?>" alt="Gambar 2" class="main-image">
                    </div>
                <?php elseif ($layout === 'layout3'): ?>
                    <div class="layout layout3">
                        <p><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar 1" class="main-image">
                        <img src="<?= htmlspecialchars($article['gambar2']); ?>" alt="Gambar 2" class="main-image">
                        <img src="<?= htmlspecialchars($article['gambar3']); ?>" alt="Gambar 3" class="main-image">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
