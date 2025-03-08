<?php
session_start(); // Memulai session
$loggedIn = isset($_SESSION['user_id']); // Mengecek apakah pengguna sudah login
$username = $loggedIn ? $_SESSION['username'] : ''; // Mendapatkan username pengguna yang login
?>

<?php
require 'database.php'; // Koneksi database

// Query untuk mendapatkan artikel untuk konten-editor-pick
$stmt = $conn->prepare("SELECT * FROM articles WHERE section = 'konten-editor-pick' AND is_verified = 1 ORDER BY tanggal DESC LIMIT 5");
$stmt->execute();
$editorPickArticles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Artikel yang akan ditampilkan


$stmt = $conn->prepare("SELECT * FROM articles WHERE kategori = 'Bisnis' ORDER BY views DESC LIMIT 1");
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC); // Ambil artikel utama

try {
    // Query untuk mengambil semua artikel
    $stmt = $conn->prepare("SELECT * FROM articles ORDER BY tanggal DESC");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil semua data sebagai array asosiatif
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $articles = []; // Set ke array kosong jika terjadi error
}

function getTrendingArticles($conn, $kategori) {
    $stmt = $conn->prepare("SELECT id, judul FROM articles WHERE kategori = :kategori ORDER BY views DESC LIMIT 5");
    $stmt->execute(['kategori' => $kategori]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil artikel untuk setiap kategori
$trendingBudaya = getTrendingArticles($conn, 'Budaya');
$trendingBisnis = getTrendingArticles($conn, 'Bisnis');
$trendingOlahraga = getTrendingArticles($conn, 'Olahraga');
$trendingKeuangan = getTrendingArticles($conn, 'Keuangan');
$trendingInternasional = getTrendingArticles($conn, 'Internasional');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360NEWS</title>
    <link rel="stylesheet" href="beranda.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wdth,wght@75,700&family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="left-section">
                <div class="logo">
                    <a href="beranda.php">
                        <img src="gambar/LOGOO.png" alt="">
                    </a>
                </div>
                <nav id="navMenu" class="hidden">
                    <div class="labelCari">
                        <input type="text" placeholder="Cari Berita" class="pencarian">
                        <button class="pencarian-btn"><b>Cari</b></button>
                    </div>
                    <a class="kategori" id="beranda" href="beranda.php">BERANDA</a>
                    <a class="kategori" id="bisnis" href="../php-main-beta/">BISNIS</a>
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
            <a href="Admin/profile_admin.php"><img src="gambar/user.png" id="profil-icon" alt=""></a> <!-- Tautan ke profil admin -->
        <?php elseif ($_SESSION['role'] === 'penulis'): ?>
            <a href="tes/profile.php"><img src="gambar/user.png" id="profil-icon" alt=""></a> <!-- Tautan ke profil penulis -->
        <?php endif; ?>
    </div>
<?php else: ?>
                        <a href="pagelogin.html"><button id="login-btn">MASUK</button></a>
                        <a href="pageDaftar.html"><button id="daftar-btn">DAFTAR</button></a>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
    </header>

    <main>
        <div class="judul-dan-cari">
        <div class="hotline-judul">
            <h1><b>BERITA</b> UTAMA</h1>
        </div>
        <div class="pencarian-berita-beranda">
            <div class="container-cari">
                <input type="text" class="cari-input" placeholder="Cari Berita...">
            </div>
            <div class="cari-btn">
                <img src="gambar/icons8-search-50 (1).png" alt="">
            </div>
        </div>
    </div>
        <div class="konten">
            <div class="konten-1">
                <div class="layout">
                    <div class="genre">
                        <a href="kategori.php?kategori=Bisnis"><b>BISNIS</b></a> <!-- Tautan menuju kategori -->
                    </div>
             <div class="subjudul-konten">
                    <p class="judul-hotline">
                    <a href="tes/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"><b><?= htmlspecialchars($article['judul']); ?></b></a>
                    </p>
                 <div class="penulis-tgl">
                    <label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
                    <label for="">By <b><?= htmlspecialchars($article['penulis']); ?></b></label>
            </div>
         <div class="ringkasan">
                <p>
                <?= htmlspecialchars(substr($article['konten'], 0, 150)); ?>...
                <a href="tes/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"><b>SELENGKAPNYA</b></a>
                </p>
            </div>
        </div>
    </div>
<div class="layout">
    <img class="gambarHotline" src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="Bisnis">
</div>

                <div class="layout">
    <div class="trending-layout">
        <h3>#TRENDING</h3>

        <!-- Trending Budaya -->
        <div class="trending-card">
            <div class="nomor-genre">
                <label for="">1 · </label>
                <label for="">Budaya</label>
            </div>
            <?php
            $trendingBudaya = getTrendingArticles($conn, 'Budaya');
            if ($trendingBudaya): ?>
                <a href="tes/artikel.php?id=<?= $trendingBudaya[0]['id']; ?>">
                    <?= htmlspecialchars($trendingBudaya[0]['judul']); ?>
                </a>
            <?php else: ?>
                <a href="#">Tidak ada artikel trending</a>
            <?php endif; ?>
        </div>

        <!-- Trending Bisnis -->
        <div class="trending-card">
            <div class="nomor-genre">
                <label for="">2 · </label>
                <label for="">Bisnis</label>
            </div>
            <?php
            $trendingBisnis = getTrendingArticles($conn, 'Bisnis');
            if ($trendingBisnis): ?>
                <a href="tes/artikel.php?id=<?= $trendingBisnis[0]['id']; ?>">
                    <?= htmlspecialchars($trendingBisnis[0]['judul']); ?>
                </a>
            <?php else: ?>
                <a href="#">Tidak ada artikel trending</a>
            <?php endif; ?>
        </div>

        <!-- Trending Olahraga -->
        <div class="trending-card">
            <div class="nomor-genre">
                <label for="">3 · </label>
                <label for="">Olahraga</label>
            </div>
            <?php
            $trendingOlahraga = getTrendingArticles($conn, 'Olahraga');
            if ($trendingOlahraga): ?>
                <a href="tes/artikel.php?id=<?= $trendingOlahraga[0]['id']; ?>">
                    <?= htmlspecialchars($trendingOlahraga[0]['judul']); ?>
                </a>
            <?php else: ?>
                <a href="#">Tidak ada artikel trending</a>
            <?php endif; ?>
        </div>

        <!-- Trending Musik -->
        <div class="trending-card">
            <div class="nomor-genre">
                <label for="">4 · </label>
                <label for="">Keuangan</label>
            </div>
            <?php
            $trendingKeuangan = getTrendingArticles($conn, 'Keuangan');
            if ($trendingKeuangan): ?>
                <a href="tes/artikel.php?id=<?= $trendingKeuangan[0]['id']; ?>">
                    <?= htmlspecialchars($trendingKeuangan[0]['judul']); ?>
                </a>
            <?php else: ?>
                <a href="#">Tidak ada artikel trending</a>
            <?php endif; ?>
        </div>

        <!-- Trending Teknologi -->
        <div class="trending-card">
            <div class="nomor-genre">
                <label for="">5 · </label>
                <label for="">Internasional</label>
            </div>
            <?php
            $trendingInternasional = getTrendingArticles($conn, 'Internasional');
            if ($trendingInternasional): ?>
                <a href="tes/artikel.php?id=<?= $trendingInternasional[0]['id']; ?>">
                    <?= htmlspecialchars($trendingInternasional[0]['judul']); ?>
                </a>
            <?php else: ?>
                <a href="#">Tidak ada artikel trending</a>
            <?php endif; ?>
        </div>

        <!-- Link Semua Trending -->
        <div class="trending-card">
            <div class="semua-trending">
                <a href="semua_trending.php"><b>SEMUA TRENDING...</b></a>
            </div>
        </div>
    </div>
</div>

            </div>
            <div class="konten-2">
                         <?php foreach ($articles as $article): ?>
                <div class="panel">
                    <a href="tes/artikel.php?id=<?= $article['id']; ?>">
                        <img src="assets/<?= htmlspecialchars($article['gambar']); ?>" id="gambar-konten2" alt="<?= htmlspecialchars($article['judul']); ?>">
                    </a>
                    <a href="tes/artikel.php?id=<?= $article['id']; ?>" class="judul-link">
                        <p class="subjudul-konten-2"><b><?= htmlspecialchars($article['judul']); ?></b></p>
                    </a>
                    <p class="ringkasan-konten-2"><?= htmlspecialchars($article['konten']); ?></p>
                    <div class="penulis-tgl-konten">
                        <label><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
                        <label>by <b><?= htmlspecialchars($article['penulis']); ?></b></label>
                    </div>
                </div>
                 <?php endforeach; ?>
            </div>

        </div>
        <div class="konten-editor-pick">
    <h1><b>TERPO</b>PULER</h1>
    <div class="kontainer">
        <!-- Bagian Gambar Kiri -->
        <div class="gambar-kiri">
            <?php foreach (array_slice($editorPickArticles, 0, 2) as $article): ?>
                <div class="card">
                    <img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="" class="gambar-sisi">
                    <div class="card-konten">
                        <h3>
                            <a href="tes/artikel.php?id=<?= $article['id']; ?>">
                                <?= htmlspecialchars($article['judul']); ?>
                            </a>
                        </h3>
                        <div class="penulis-tgl-konten">
                            <label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
                            <label for="">by <b><?= htmlspecialchars($article['penulis']); ?></b></label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bagian Main Card -->
        <?php if (!empty($editorPickArticles[2])): ?>
            <div class="main-card">
                <img src="assets/<?= htmlspecialchars($editorPickArticles[2]['gambar']); ?>" alt="">
                <div class="main-card-konten">
                    <div class="penulis-tgl-konten">
                        <label for=""><?= date('d M Y', strtotime($editorPickArticles[2]['tanggal'])); ?></label>
                        <label for="">by <b><?= htmlspecialchars($editorPickArticles[2]['penulis']); ?></b></label>
                    </div>
                    <h2 class="subjudul-main-konten">
                        <a href="tes/artikel.php?id=<?= $editorPickArticles[2]['id']; ?>">
                            <b><?= htmlspecialchars($editorPickArticles[2]['judul']); ?></b>
                        </a>
                    </h2>
                    <p class="ringkasan-main-konten">
                        <?= htmlspecialchars(substr($editorPickArticles[2]['konten'], 0, 200)); ?>...
                        <a href="tes/artikel.php?id=<?= $editorPickArticles[2]['id']; ?>"><b>SELENGKAPNYA</b></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Bagian Gambar Kanan -->
        <div class="gambar-kanan">
            <?php foreach (array_slice($editorPickArticles, 3) as $article): ?>
                <div class="card">
                    <img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="" class="gambar-sisi">
                    <div class="card-konten">
                        <h3>
                            <a href="tes/artikel.php?id=<?= $article['id']; ?>">
                                <?= htmlspecialchars($article['judul']); ?>
                            </a>
                        </h3>
                        <div class="penulis-tgl-konten">
                            <label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
                            <label for="">by <b><?= htmlspecialchars($article['penulis']); ?></b></label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


        <div class="story-war">
            <div class="kontainer-story">
                <div class="konten-kiri">
                    <h1><b>STORY:</b> OTOMOTIF</h1>
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta molestiae eum officia adipisci.</b></p>
                    <div class="tgl-penulis-story">
                        <div class="nama-jabatan">
                            <p class="nama-penulis">Sade Gui Ucar</p>
                            <p class="jabatan">Diplomat</p>
                        </div>
                        <p class="tgl-penulis">5 May 2024</p>
                    </div>
                    <p class="ringkasan-story-konten">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil lorem19 Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit blanditiis impedit vitae voluptatum voluptate cupiditate itaque Lorem ipsum dolor sit amet consectetur adipisicing. Lorem ipsum dolor sit. voluptatibus quae ipsum illum repellat est reiciendis, molestiae corporis consectetur sequi temporibus reprehenderit dolorem. voluptatem perferendis molestias, fuga, unde minus iure modi repudiandae harum, nulla voluptate eaque! Beatae, lorem18 quo eum. <b>SELENGKAPNYA</b></p>
                </div>
                <div class="konten-kanan">
                    <img src="gambar/Yellow Ferrari F8 On Slate Brick Road  3D Sublimation 20oz Skinny Straight Tumblr Wrap  300 DPI PNG Commercial Use  Supercar Enthusiast Gift.jpg" alt="">
                </div>
            </div>
        </div>
        <div class="semua-class">
            <h1><b>></b> LAINNYA</h1>
            <div class="grid-conten">
                <div class="grid-card-konten">
                    <img src="gambar/16-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/84-367x267 (1).jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/28-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/29-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/49-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/64-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/7-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
                <div class="grid-card-konten">
                    <img src="gambar/85-367x267.jpg" alt="">
                    <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                </div>
            </div>
        </div>
        <div class="semua-berita-btn">
            <div class="card-btn-semua-berita">
                <button>SEMUA BERITA</button>
            </div>
        </div>

    </main>

    <footer>
        <div class="konten-footer">
            <div class="bagian-kiri">
                <img src="gambar/LOGOO.png" alt="">
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
                            <img alt="Facebook logo" height="24" src="gambar/logos_facebook.png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="Github logo" height="24" src="gambar/download 111(1).png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="X logo" height="24" src="gambar/download 22(1).png" width="24"/>
                           </a>
                           <a href="#">
                            <img alt="Instagram logo" height="24" src="gambar/skill-icons_instagram.png" width="24"/>
                           </a>
                    </div>
                </div>     
            </div>
        </div>
    </footer>        
    <script src="nav-mobile.js"></script>
</body>
</html>