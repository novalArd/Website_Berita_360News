<?php
session_start();
// Memeriksa apakah pengguna telah login
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : '';

require '../database.php';

// Query untuk mengambil artikel untuk section "konten-1" dan kategori "Bisnis"
$stmt = $conn->prepare("SELECT * FROM articles WHERE page_target = 'Bisnis' AND section = 'konten-1' AND is_verified = 1 ORDER BY tanggal DESC");
$stmt->execute();
$konten1ArticlesBisnis = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM articles WHERE page_target = 'Bisnis' AND section = 'konten-2' AND is_verified = 1 ORDER BY tanggal DESC LIMIT 6 ");
$stmt->execute();
$konten2ArticlesBisnis = $stmt->fetchAll(PDO::FETCH_ASSOC);

 // Query untuk mengambil artikel berdasarkan kategori "Bisnis" untuk bagian "Pilihan Untukmu"
 $stmt = $conn->prepare("SELECT * FROM articles WHERE page_target = 'Bisnis' AND section = 'pilihan-untukmu' AND is_verified = 1 ORDER BY tanggal DESC LIMIT 4");
 $stmt->execute();
 $pilihanUntukmuArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - Bisnis</title>
    <link rel="stylesheet" href="Kbisnis.css">
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
                        <img src="../gambar/LOGOO.png" alt="">
                    </a>
                </div>
                <nav id="navMenu" class="hidden">
                    <div class="labelCari">
                        <input type="text" placeholder="Cari Berita" class="pencarian">
                        <button class="pencarian-btn"><b>Cari</b></button>
                    </div>
                    <a class="kategori" id="beranda" href="../beranda.php">BERANDA</a>
                    <a class="kategori" id="bisnis" href="Kbisnis.php">BISNIS</a>
                    <a class="kategori" id="keuangan" href="Kkeuangan.html">KEUANGAN</a>
                    <a class="kategori" id="olahraga" href="Kolahraga.html">OLAHRAGA</a>
                    <a class="kategori" id="internasional" href="Kinternasional.html">INTERNASIONAL</a>
                    <a class="kategori" id="budaya" href="Kbudaya.html">BUDAYA</a>
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
						<a href="../Admin/profile_admin.php"><img src="../gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<!-- Tautan ke profil admin -->
						<?php elseif ($_SESSION['role'] === 'penulis'): ?>
						<a href="../penulis/profile.php"><img src="../gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<!-- Tautan ke profil penulis -->
						<?php elseif ($_SESSION['role'] === 'pengguna'): ?>
						<a href="../pengguna/profile_pengguna.php"><img src="../gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<?php endif; ?>
					</div>
					<?php else: ?>
					<a href="../pagelogin.html"><button id="login-btn">MASUK</button></a>
					<a href="../pageDaftar.html"><button id="daftar-btn">DAFTAR</button></a>
					<?php endif; ?>
            </div>
        </div>
    </header>
    <main>
        <h1><b>BIS</b>NIS</h1>
        <div class="konten1-flex">
    <?php if (!empty($konten1ArticlesBisnis)): ?>
        <?php foreach ($konten1ArticlesBisnis as $index => $article): ?>
            <?php if ($index === 0): ?>g
                <!-- Artikel Utama -->
                <div class="panel-flex-column">
                    <div class="genre">
                        <p><?= htmlspecialchars($article['kategori']); ?></p>
                    </div>
                    <div class="judul-konten">
                        <h3><?= htmlspecialchars($article['judul']); ?></h3>
                    </div>
                    <div class="tgl-penulis">
                        <label><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
                        <label>By <?= htmlspecialchars($article['penulis']); ?></label>
                    </div>
                    <div class="penjelasan-konten">
                        <p>
                            <?= htmlspecialchars(substr($article['konten'], 0, 150)); ?>...
                            <a href="../tes/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"><b>SELENGKAPNYA</b></a>
                        </p>
                    </div>
                </div>
                <div class="img-ekonomi-hotline">
                    <img src="../assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>">
                </div>
            <?php else: ?>
                <!-- Artikel Samping -->
                <div class="konten-kanan-hotline">
                    <img src="../assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>">
                    <p class="subjudul-konten-kanan"><b><?= htmlspecialchars($article['judul']); ?></b></p>
                    <div class="tgl-penulis-konten">
                        <label><?= date('d M Y', strtotime($article['tanggal'])); ?>,</label>
                        <label><?= date('H:i', strtotime($article['tanggal'])); ?> WIB</label>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Belum ada artikel yang tersedia untuk section ini.</p>
    <?php endif; ?>
</div>

            <p class="terbaru"><b>TER</b>BARU</p>
            <div class="konten-terbaru">
					<?php foreach ($konten2ArticlesBisnis as $article): ?>
					<div class="panel">
						<a href="penulis/artikel.php?id=<?= $article['id']; ?>">
							<img src="../assets/<?= htmlspecialchars($article['gambar']); ?>" id="gambar-konten2" alt="<?= htmlspecialchars($article['judul']); ?>" />
						</a>
						<a href="../penulis/artikel.php?id=<?= $article['id']; ?>" class="judul-link">
							<p class="subjudul-konten-2">
								<b><?= htmlspecialchars($article['judul']); ?></b>
							</p>
						</a>
						<p class="ringkasan-konten-2"><?= htmlspecialchars(substr($article['konten'], 0, 150)); ?>...</p>
						<div class="penulis-tgl-konten">
							<label><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
							<label>
								by <b><?= htmlspecialchars($article['penulis']); ?></b>
							</label>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
                <p class="judul-pilihan-untukmu"><b>PILIHAN</b> UNTUKMU</p>
<div class="pilihan-untukmu">
    <?php if (!empty($pilihanUntukmuArticles)): ?>
        <?php foreach ($pilihanUntukmuArticles as $article): ?>
            <div class="panel-pilihan-untukmu">
                <img src="../assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>">
                <p class="subjudul-pilihan-untukmu">
                    <b><a href="../penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"><?= htmlspecialchars($article['judul']); ?></a></b>
                </p>
                <div class="tgl-jam-artikel">
                    <label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?>, </label>
                    <label for=""><?= date('H:i', strtotime($article['tanggal'])); ?> WIB</label>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Belum ada artikel untuk ditampilkan di bagian ini.</p>
    <?php endif; ?>
</div>
            <div class="konten-editor-pick-bisnis">
                <div class="kontainer">
                    <div class="gambar-kiri">
                        <div class="card">
                            <img src="../gambar/websiteplanet-dummy-820X500 (2).png" alt="" class="gambar-sisi">
                            <div class="card-konten">
                                <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                                <div class="penulis-tgl-konten">
                                    <label for="">5 May 2024</label>
                                    <label for="">by <b>Smith</b></label>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <img src="../gambar/websiteplanet-dummy-800X500.png" alt="" class="gambar-sisi">
                            <div class="card-konten">
                                <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                                <div class="penulis-tgl-konten">
                                    <label for="">5 May 2024</label>
                                    <label for="">by <b>Adam</b></label>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div class="main-card">
                        <img src="../gambar/websiteplanet-dummy-820X500 (3).png" alt="">
                        <div class="main-card-konten">
                            <div class="penulis-tgl-konten">
                                <label for="">5 May 2024</label>
                                <label for="">by <b>Louyi</b></label>
                            </div>
                                <h2 class="subjudul-main-konten"><b>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perspiciatis cupiditate officia velit!</b></h2>
                                <p class="ringkasan-main-konten">Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique quia ipsam, Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum Lorem ipsum dolor sit. porro quo numquam consequuntur dolores voluptatem error ex odio. totam iste fugiat voluptate excepturi. <b>SELENGKAPNYA</b></p>
                            </div>
                        </div>
                        <div class="gambar-kanan">
                            <div class="card">
                                <img src="../gambar/websiteplanet-dummy-800X400 (2).png" alt="" class="gambar-sisi">
                                <div class="card-konten">
                                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                                    <div class="penulis-tgl-konten">
                                        <label for="">5 May 2024</label>
                                        <label for="">by <b>Miya</b></label>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <img src="../gambar/websiteplanet-dummy-800X400 (3).png" alt="" class="gambar-sisi">
                                <div class="card-konten">
                                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                                    <div class="penulis-tgl-konten">
                                        <label for="">5 May 2024</label>
                                        <label for="">by <b>David</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sorotan-class">
                    <p class="sorotan-judul"><b>SORO</b>TAN</p>
                    <div class="grid-conten-sorotan">
                        <div class="grid-card-sorotan">
                            <img class="gambar-konten-sorotan" src="../gambar/16-367x267.jpg" alt="">
                            <div class="subjudul-view-like">
                                <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                                <div class="view-like">
                                    <div class="view">
                                        <img src="../gambar/view.png" alt="" height="20px">
                                        <label for="">11.400</label>
                                    </div>
                                    <div class="like">
                                        <img src="../gambar/mdi_like.png" alt="" height="20px">
                                        <label for="">500</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-card-sorotan">
                            <img class="gambar-konten-sorotan" src="../gambar/16-367x267.jpg" alt="">
                            <div class="subjudul-view-like">
                                <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                                <div class="view-like">
                                    <div class="view">
                                        <img src="../gambar/view.png" alt="" height="20px">
                                        <label for="">11.400</label>
                                    </div>
                                    <div class="like">
                                        <img src="../gambar/mdi_like.png" alt="" height="20px">
                                        <label for="">500</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-card-sorotan">
                            <img class="gambar-konten-sorotan" src="../gambar/16-367x267.jpg" alt="">
                            <div class="subjudul-view-like">
                                <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                                <div class="view-like">
                                    <div class="view">
                                        <img src="../gambar/view.png" alt="" height="20px">
                                        <label for="">11.400</label>
                                    </div>
                                    <div class="like">
                                        <img src="../gambar/mdi_like.png" alt="" height="20px">
                                        <label for="">500</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-card-sorotan">
                            <img class="gambar-konten-sorotan" src="../gambar/16-367x267.jpg" alt="">
                            <div class="subjudul-view-like">
                                <p><b>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta.</b></p>
                                <div class="view-like">
                                    <div class="view">
                                        <img src="../gambar/view.png" alt="" height="20px">
                                        <label for="">11.400</label>
                                    </div>
                                    <div class="like">
                                        <img src="../gambar/mdi_like.png" alt="" height="20px">
                                        <label for="">500</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div class="konten-footer">
                <div class="bagian-kiri">
                    <img src="../gambar/LOGOO.png" alt="">
                    <p>News360 adalah pusat informasi dan portal berita digital yang menghadirkan informasi terkini dari berbagai penjuru dunia dalam genggaman Anda.</p>
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
    
