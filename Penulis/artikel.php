<?php
session_start(); 
$loggedIn = isset($_SESSION['user_id']); 
$username = $loggedIn ? $_SESSION['username'] : ''; 

require '../database.php';


if (!isset($_GET['id'])) {
    die("Artikel tidak ditemukan.");
}

$articleId = $_GET['id'];


if ($loggedIn) {
    $stmt = $conn->prepare("SELECT has_viewed FROM article_interactions WHERE article_id = :article_id AND user_id = :user_id");
    $stmt->execute(['article_id' => $articleId, 'user_id' => $_SESSION['user_id']]);
    $interaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$interaction) {
        $stmt = $conn->prepare("INSERT INTO article_interactions (article_id, user_id, has_viewed) VALUES (:article_id, :user_id, 1)");
        $stmt->execute(['article_id' => $articleId, 'user_id' => $_SESSION['user_id']]);

        $stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
    } elseif (!$interaction['has_viewed']) {
        $stmt = $conn->prepare("UPDATE article_interactions SET has_viewed = 1 WHERE article_id = :article_id AND user_id = :user_id");
        $stmt->execute(['article_id' => $articleId, 'user_id' => $_SESSION['user_id']]);

        $stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
    }
}

$stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Artikel tidak ditemukan.");
}


$layout = $article['layout'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['judul']); ?> - News360</title>
    <link rel="stylesheet" href="artikel.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wdth,wght@75,700&family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="like.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="left-section">
                <div class="logo">
                    <a href="../beranda.php">
                        <img src="../gambar/LOGOO.png" alt="Logo">
                    </a>
                </div>
                <nav id="navMenu" class="hidden">
                    <a class="kategori" id="beranda" href="../beranda.php">BERANDA</a>
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
                <div class="loginAkun-btn">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="../Admin/profile_admin.php"><img src="../gambar/icons8-user-100.png" alt=""></a> <!-- Tautan ke profil -->
                    <?php elseif ($_SESSION['role'] === 'penulis'): ?>
                    <a href="profile.php"><img src="../gambar/icons8-user-100.png" alt=""></a> <!-- Tautan ke profil -->
                    <?php elseif ($_SESSION['role'] === 'pengguna'): ?>
                    <a href="../pengguna/profile_pengguna.php"><img src="../gambar/icons8-user-100.png" alt=""></a> <!-- Tautan ke profil -->
                    <?php endif; ?>
                </div>
                <?php else: ?>
                    <a href="../pagelogin.html"><button id="login-btn">MASUK</button></a>
                    <a href="../pageDaftar.html"><button id="daftar-btn">DAFTAR</button></a>
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
                    <div class="layout" id="layout1">
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar" class="gambar-sampul">
                        <p class="subjudul-isi"><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
                    </div>
                <?php elseif ($layout === 'layout2'): ?>
                    <div class="layout" id="layout2">
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar 1" class="gambar-sampul">
                        <p class="subjudul-isi"><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
                        <div class="gambar-fiks">
                            <img src="<?= htmlspecialchars($article['gambar2']); ?>" alt="Gambar 2" class="gambar-footer">
                        </div>
                    </div>
                <?php elseif ($layout === 'layout3'): ?>
                    <div class="layout" id="layout3">
                        <img src="<?= htmlspecialchars($article['gambar']); ?>" alt="Gambar 1" class="gambar-sampul">
                        <p class="subjudul-isi"><?= nl2br(htmlspecialchars($article['konten'])); ?></p>
                        <p><?= nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
                        <div class="gambar-isi-akhir">
                            <img src="<?= htmlspecialchars($article['gambar2']); ?>" alt="Gambar 2" class="gambar-footer" id="gambar-footer1">
                            <img src="<?= htmlspecialchars($article['gambar3']); ?>" alt="Gambar 3" class="gambar-footer" id="gambar-footer2">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="konten-footer">
            <div class="bagian-kiri">
                <img src="../gambar/LOGOO.png" alt="">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis officiis ipsa similique vero commodi voluptate non provident cupiditate laboriosam.</p>
            </div>
            <div class="footer-tengah">
                <p>KATEGORI</p>
                <div class="f-tengah">
                    <a class="kategori-ftr" href="#">POLITIK</a>
                    <a class="kategori-ftr" href="#">BISNIS</a>
                    <a class="kategori-ftr" href="#">KEUANGAN</a>
                    <a class="kategori-ftr" href="#">OLAHRAGA</a>
                    <a class="kategori-ftr" href="#">INTERNASIONAL</a>
                    <a class="kategori-ftr" href="#">BUDAYA</a>
                </div>
            </div>
            <div class="bagian-kanan">
                <p>TENTANG KAMI</p>
                <div class="social-media">
                    <a href="#">@360news.com</a>
                    <a href="#">088201012020</a>
                    <div class="gambar-sosmed">
                        <a href="#">
                            <img alt="Facebook logo" height="24" src="../gambar/logos_facebook.png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="Github logo" height="24" src="../gambar/download 111(1).png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="X logo" height="24" src="../gambar/download 22(1).png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="Instagram logo" height="24" src="../gambar/skill-icons_instagram.png" width="24"/>
                           </a>
                    </div>
                </div>     
            </div>
        </div>
    </footer>
</body>
</html>
