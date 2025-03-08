<?php
session_start(); // Memulai session
$loggedIn = isset($_SESSION['user_id']); 
$username = $loggedIn ? $_SESSION['username'] : ''; 
require 'database.php'; // Koneksi database


// Query untuk "Konten-1" Beranda
$stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
    FROM articles 
    JOIN users ON articles.author_id = users.id 
    WHERE articles.page_target = 'Beranda' 
      AND articles.section = 'konten-1' 
      AND articles.is_verified = 1 
    ORDER BY articles.tanggal DESC 
    LIMIT 1
");
$stmt->execute();
$konten1ArticleBeranda = $stmt->fetch(PDO::FETCH_ASSOC);

// Query untuk "Konten-2" Beranda
$stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
    FROM articles 
    JOIN users ON articles.author_id = users.id 
    WHERE articles.page_target = 'Beranda' 
      AND articles.section = 'konten-2' 
      AND articles.is_verified = 1 
    ORDER BY articles.tanggal DESC 
    LIMIT 5
");
$stmt->execute();
$konten2Articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query untuk "Editor Pick" Beranda
$stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
    FROM articles 
    JOIN users ON articles.author_id = users.id 
    WHERE articles.page_target = 'Beranda' 
      AND articles.section = 'konten-editor-pick' 
      AND articles.is_verified = 1 
    ORDER BY articles.tanggal DESC 
    LIMIT 5
");
$stmt->execute();
$editorPickArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query untuk "Story War" Beranda
$stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
    FROM articles 
    JOIN users ON articles.author_id = users.id 
    WHERE articles.page_target = 'Beranda' 
      AND articles.section = 'story-war' 
      AND articles.is_verified = 1 
    ORDER BY articles.tanggal DESC 
    LIMIT 1
");
$stmt->execute();
$storyWarArticle = $stmt->fetch(PDO::FETCH_ASSOC);

// Query untuk "Semua Class" Beranda dengan filter kategori (jika ada)
$selectedCategory = isset($_GET['kategori']) ? $_GET['kategori'] : null;

if ($selectedCategory) {
    $stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE articles.page_target = 'Beranda' 
          AND articles.section = 'semua-class' 
          AND articles.is_verified = 1 
          AND articles.kategori = :kategori 
        ORDER BY articles.tanggal DESC
    ");
    $stmt->execute(['kategori' => $selectedCategory]);
    $semuaClassArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->prepare("SELECT articles.*, users.username AS penulis 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE articles.page_target = 'Beranda' 
          AND articles.section = 'semua-class' 
          AND articles.is_verified = 1 
        ORDER BY articles.tanggal DESC
    ");
    $stmt->execute();
    $semuaClassArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getArticlesByCategory($conn, $kategori) {
    $stmt = $conn->prepare("SELECT * FROM articles WHERE kategori = :kategori AND page_target = 'Beranda' AND section = 'semua-class' AND is_verified = 1 ORDER BY tanggal DESC LIMIT 4");
    $stmt->execute(['kategori' => $kategori]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$bisnisArticles = getArticlesByCategory($conn, 'Bisnis');
$keuanganArticles = getArticlesByCategory($conn, 'Keuangan');
$olahragaArticles = getArticlesByCategory($conn, 'Olahraga');
$internasionalArticles = getArticlesByCategory($conn, 'Internasional');
$budayaArticles = getArticlesByCategory($conn, 'Budaya');


function getTrendingArticles($conn, $kategori) {
    $stmt = $conn->prepare("SELECT id, judul FROM articles WHERE kategori = :kategori AND page_target = 'Beranda' ORDER BY views DESC LIMIT 5");;
    $stmt->execute(['kategori' => $kategori]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$trendingBudaya = getTrendingArticles($conn, 'Budaya');
$trendingBisnis = getTrendingArticles($conn, 'Bisnis');
$trendingOlahraga = getTrendingArticles($conn, 'Olahraga');
$trendingKeuangan = getTrendingArticles($conn, 'Keuangan');
$trendingInternasional = getTrendingArticles($conn, 'Internasional');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>360NEWS</title>
		<link rel="stylesheet" href="beranda.css" />
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet" />
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wdth,wght@75,700&family=Public+Sans:ital,wght@0,600;1,600&display=swap" rel="stylesheet" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
	</head>
	<body>
		<header>
			<div class="header-container">
				<div class="left-section">
					<div class="logo">
						<a href="beranda.php">
							<img src="gambar/LOGOO.png" alt="" />
						</a>
					</div>
					<nav id="navMenu" class="hidden">
						<div class="labelCari">
							<input type="text" placeholder="Cari Berita" class="pencarian" />
							<button class="pencarian-btn"><b>Cari</b></button>
						</div>
						<a class="kategori" id="beranda" href="beranda.php">BERANDA</a>
						<a class="kategori" id="bisnis" href="Kategori-Page/Kbisnis.php">BISNIS</a>
						<a class="kategori" id="keuangan" href="Kategori-Page/kkeuangan.php">KEUANGAN</a>
						<a class="kategori" id="olahraga" href="Kategori-Page/Kolahraga.php">OLAHRAGA</a>
						<a class="kategori" id="internasional" href="Kategori-Page/Kinternasional.php">INTERNASIONAL</a>
						<a class="kategori" id="budaya" href="Kategori-Page/Kbudaya.php">BUDAYA</a>
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
						<a href="Admin/profile_admin.php"><img src="gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<!-- Tautan ke profil admin -->
						<?php elseif ($_SESSION['role'] === 'penulis'): ?>
						<a href="penulis/profile.php"><img src="gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<!-- Tautan ke profil penulis -->
						<?php elseif ($_SESSION['role'] === 'pengguna'): ?>
						<a href="pengguna/profile_pengguna.php"><img src="gambar/icons8-user-100.png" id="profil-icon" alt="" /></a>
						<?php endif; ?>
					</div>
					<?php else: ?>
					<a href="pagelogin.html"><button id="login-btn">MASUK</button></a>
					<a href="pageDaftar.html"><button id="daftar-btn">DAFTAR</button></a>
					<?php endif; ?>
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
                    <input type="text" id="searchInput" class="cari-input" placeholder="Cari Berita..." />
                    <div class="cari-btn">
                        <img src="gambar/icons8-search-50 (1).png" alt="Cari">
                    </div>
                    <div class="hasil-pencarian" id="searchResults"></div>
                </div>
            </div>
        </div>
			<div class="konten">
				<div class="konten-1">
					<?php if ($konten1ArticleBeranda): ?>
					<div class="layout">
						<div class="genre">
							<!-- Tautan  menuju kategori -->
							<a href="kategori.php?kategori=<?= htmlspecialchars($$konten1ArticleBeranda['kategori']); ?>">
								<b><?= htmlspecialchars(strtoupper($konten1ArticleBeranda['kategori'])); ?></b>
							</a>
						</div>
						<div class="subjudul-konten">
							<p class="judul-hotline">
								<!-- Tautan  menuju artikel -->
								<a href="penulis/artikel.php?id=<?= htmlspecialchars($konten1ArticleBeranda['id']); ?>">
									<b><?= htmlspecialchars($konten1ArticleBeranda['judul']); ?></b>
								</a>
							</p>
							<div class="penulis-tgl">
								<label><?= date('d M Y', strtotime($konten1ArticleBeranda['tanggal'])); ?></label>
								<label>
									By <b><?= htmlspecialchars($konten1ArticleBeranda['penulis']); ?></b>
								</label>
							</div>
							<div class="ringkasan">
								<p>
									<?= htmlspecialchars(substr($konten1ArticleBeranda['konten'], 0, 150)); ?>...
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($konten1ArticleBeranda['id']); ?>">
										<b>SELENGKAPNYA</b>
									</a>
								</p>
							</div>
						</div>
					</div>
					<div class="layout">
						<img class="gambarHotline" src="assets/<?= htmlspecialchars($konten1ArticleBeranda['gambar']); ?>" alt="<?= htmlspecialchars($konten1ArticleBeranda['kategori']); ?>" />
					</div>
					<?php else: ?>
					<div class="layout">
						<p>Belum ada artikel untuk ditampilkan di section ini.</p>
					</div>
					<?php endif; ?>
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
								<a href="penulis/artikel.php?id=<?= $trendingBudaya[0]['id']; ?>">
									<?= htmlspecialchars($trendingBudaya[0]['judul']); ?>
								</a>
								<?php else: ?>
								<a href="#">Tidak ada artikel trending</a>
								<?php endif; ?>
							</div>
							<!-- Trending Bisnis -->
							<div class="trending-card">
								<div class="nomor-genre">
									<label for="">2 Â· </label>
									<label for="">Bisnis</label>
								</div>
								<?php
            $trendingBisnis = getTrendingArticles($conn, 'Bisnis');
            if ($trendingBisnis): ?>
								<a href="penulis/artikel.php?id=<?= $trendingBisnis[0]['id']; ?>">
									<?= htmlspecialchars($trendingBisnis[0]['judul']); ?>
								</a>
								<?php else: ?>
								<a href="#">Tidak ada artikel trending</a>
								<?php endif; ?>
							</div>

							<!-- Trending Olahraga -->
							<div class="trending-card">
								<div class="nomor-genre">
									<label for="">3 Â· </label>
									<label for="">Olahraga</label>
								</div>
								<?php
            $trendingOlahraga = getTrendingArticles($conn, 'Olahraga');
            if ($trendingOlahraga): ?>
								<a href="penulis/artikel.php?id=<?= $trendingOlahraga[0]['id']; ?>">
									<?= htmlspecialchars($trendingOlahraga[0]['judul']); ?>
								</a>
								<?php else: ?>
								<a href="#">Tidak ada artikel trending</a>
								<?php endif; ?>
							</div>

							<!-- Trending Musik -->
							<div class="trending-card">
								<div class="nomor-genre">
									<label for="">4 Â· </label>
									<label for="">Keuangan</label>
								</div>
								<?php
            $trendingKeuangan = getTrendingArticles($conn, 'Keuangan');
            if ($trendingKeuangan): ?>
								<a href="penulis/artikel.php?id=<?= $trendingKeuangan[0]['id']; ?>">
									<?= htmlspecialchars($trendingKeuangan[0]['judul']); ?>
								</a>
								<?php else: ?>
								<a href="#">Tidak ada artikel trending</a>
								<?php endif; ?>
							</div>

							<!-- Trending Teknologi -->
							<div class="trending-card">
								<div class="nomor-genre">
									<label for="">5 Â· </label>
									<label for="">Internasional</label>
								</div>
								<?php
            $trendingInternasional = getTrendingArticles($conn, 'Internasional');
            if ($trendingInternasional): ?>
								<a href="penulis/artikel.php?id=<?= $trendingInternasional[0]['id']; ?>">
									<?= htmlspecialchars($trendingInternasional[0]['judul']); ?>
								</a>
								<?php else: ?>
								<a href="#">Tidak ada artikel trending</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="konten-2">
					<?php foreach ($konten2Articles as $article): ?>
					<div class="panel">
						<a href="penulis/artikel.php?id=<?= $article['id']; ?>">
							<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" id="gambar-konten2" alt="<?= htmlspecialchars($article['judul']); ?>" />
						</a>
						<a href="penulis/artikel.php?id=<?= $article['id']; ?>" class="judul-link">
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
			</div>
			<div class="konten-editor-pick">
				<h1><b>TERPO</b>PULER</h1>
				<div class="kontainer">
					<!-- Bagian Gambar Kiri -->
					<div class="gambar-kiri">
						<?php foreach (array_slice($editorPickArticles, 0, 2) as $article): ?>
						<div class="card">
							<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="" class="gambar-sisi" />
							<div class="card-konten">
								<h3>
									<a href="penulis/artikel.php?id=<?= $article['id']; ?>">
										<?= htmlspecialchars($article['judul']); ?>
									</a>
								</h3>
								<div class="penulis-tgl-konten">
									<label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
									<label for="">
										by <b><?= htmlspecialchars($article['penulis']); ?></b>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>

					<!-- Bagian Main Card -->
					<?php if (!empty($editorPickArticles[2])): ?>
					<div class="main-card">
						<img src="assets/<?= htmlspecialchars($editorPickArticles[2]['gambar']); ?>" alt="" />
						<div class="main-card-konten">
							<div class="penulis-tgl-konten">
								<label for=""><?= date('d M Y', strtotime($editorPickArticles[2]['tanggal'])); ?></label>
								<label for="">
									by <b><?= htmlspecialchars($editorPickArticles[2]['penulis']); ?></b>
								</label>
							</div>
							<h2 class="subjudul-main-konten">
								<a class="subjudul-main-konten-a" href="penulis/artikel.php?id=<?= $editorPickArticles[2]['id']; ?>">
									<b><?= htmlspecialchars($editorPickArticles[2]['judul']); ?></b>
								</a>
							</h2>
							<p class="ringkasan-main-konten">
								<?= htmlspecialchars(substr($editorPickArticles[2]['konten'], 0, 200)); ?>...
								<a href="penulis/artikel.php?id=<?= $editorPickArticles[2]['id']; ?>"><b>SELENGKAPNYA</b></a>
							</p>
						</div>
					</div>
					<?php endif; ?>
					<!-- Bagian Gambar Kanan -->
					<div class="gambar-kanan">
						<?php foreach (array_slice($editorPickArticles, 3) as $article): ?>
						<div class="card">
							<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="" class="gambar-sisi" />
							<div class="card-konten">
								<h3>
									<a href="penulis/artikel.php?id=<?= $article['id']; ?>">
										<?= htmlspecialchars($article['judul']); ?>
									</a>
								</h3>
								<div class="penulis-tgl-konten">
									<label for=""><?= date('d M Y', strtotime($article['tanggal'])); ?></label>
									<label for="">
										by <b><?= htmlspecialchars($article['penulis']); ?></b>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php if ($storyWarArticle): ?>
			<div class="story-war">
				<div class="kontainer-story">
					<div class="konten-kiri">
						<h1>
							<b>STORY:</b>
							<?= htmlspecialchars($storyWarArticle['kategori']); ?>
						</h1>
						<p>
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($storyWarArticle['id']); ?>">
								<b><?= htmlspecialchars(substr($storyWarArticle['judul'], 0, 100)); ?></b>
							</a>
						</p>
						<div class="tgl-penulis-story">
							<div class="nama-jabatan">
								<p class="nama-penulis"><?= htmlspecialchars($storyWarArticle['penulis']); ?></p>
								<p class="jabatan">Penulis</p>
							</div>
							<p class="tgl-penulis"><?= date('d M Y', strtotime($storyWarArticle['tanggal'])); ?></p>
						</div>
						<p class="ringkasan-story-konten">
							<?= htmlspecialchars(substr($storyWarArticle['konten'], 0, 2000)); ?>...
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($storyWarArticle['id']); ?>">
								<b>SELENGKAPNYA</b>
							</a>
						</p>
					</div>
					<div class="konten-kanan">
						<img src="assets/<?= htmlspecialchars($storyWarArticle['gambar']); ?>" alt="<?= htmlspecialchars($storyWarArticle['judul']); ?>" />
					</div>
				</div>
			</div>
			<?php else: ?>
			<div class="story-war">
				<div class="kontainer-story">
					<p>Belum ada artikel untuk ditampilkan di bagian ini.</p>
				</div>
			</div>
			<?php endif; ?>

			<div class="semua-class">
				<h1><b>></b> LAINNYA</h1>
				<div class="grid-conten">
					<div class="judul-kategori">
						<label>BISNIS</label>
					</div>
					<div class="kategori-lainnya" id="bisnis">
						<?php if (!empty($bisnisArticles)): ?>
						<?php foreach ($bisnisArticles as $article): ?>
						<div class="grid-card-konten">
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>">
								<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>" />
							</a>
							<p>
								<b>
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"> <?= htmlspecialchars(substr($article['judul'], 0, 50)); ?>...</a>
								</b>
							</p>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<p>Belum ada artikel untuk kategori ini.</p>
						<?php endif; ?>
					</div>

					<!-- Kategori Keuangan -->
					<div class="judul-kategori">
						<label>KEUANGAN</label>
					</div>
					<div class="kategori-lainnya" id="keuangan">
						<?php if (!empty($keuanganArticles)): ?>
						<?php foreach ($keuanganArticles as $article): ?>
						<div class="grid-card-konten">
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>">
								<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>" />
							</a>
							<p>
								<b>
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"> <?= htmlspecialchars(substr($article['judul'], 0, 50)); ?>...</a>
								</b>
							</p>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<p>Belum ada artikel untuk kategori ini.</p>
						<?php endif; ?>
					</div>

					<!-- Kategori Olahraga -->
					<div class="judul-kategori">
						<label>OLAHRAGA</label>
					</div>
					<div class="kategori-lainnya" id="olahraga">
						<?php if (!empty($olahragaArticles)): ?>
						<?php foreach ($olahragaArticles as $article): ?>
						<div class="grid-card-konten">
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>">
								<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>" />
							</a>
							<p>
								<b>
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"> <?= htmlspecialchars(substr($article['judul'], 0, 50)); ?>...</a>
								</b>
							</p>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<p>Belum ada artikel untuk kategori ini.</p>
						<?php endif; ?>
					</div>

					<!-- Kategori Internasional -->
					<div class="judul-kategori">
						<label>INTERNASIONAL</label>
					</div>
					<div class="kategori-lainnya" id="internasional">
						<?php if (!empty($internasionalArticles)): ?>
						<?php foreach ($internasionalArticles as $article): ?>
						<div class="grid-card-konten">
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>">
								<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>" />
							</a>
							<p>
								<b>
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"> <?= htmlspecialchars(substr($article['judul'], 0, 50)); ?>...</a>
								</b>
							</p>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<p>Belum ada artikel untuk kategori ini.</p>
						<?php endif; ?>
					</div>

					<!-- Kategori Budaya -->
					<div class="judul-kategori">
						<label>BUDAYA</label>
					</div>
					<div class="kategori-lainnya" id="budaya">
						<?php if (!empty($budayaArticles)): ?>
						<?php foreach ($budayaArticles as $article): ?>
						<div class="grid-card-konten">
							<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>">
								<img src="assets/<?= htmlspecialchars($article['gambar']); ?>" alt="<?= htmlspecialchars($article['judul']); ?>" />
							</a>
							<p>
								<b>
									<a href="penulis/artikel.php?id=<?= htmlspecialchars($article['id']); ?>"> <?= htmlspecialchars(substr($article['judul'], 0, 50)); ?>...</a>
								</b>
							</p>
						</div>
						<?php endforeach; ?>
						<?php else: ?>
						<p>Belum ada artikel untuk kategori ini.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="semua-berita-btn">
				<div class="card-btn-semua-berita">
					<button id="semua-berita-btn">SEMUA BERITA</button>
				</div>
			</div>
		</main>

		<footer>
			<div class="konten-footer">
				<div class="bagian-kiri">
					<img src="gambar/LOGOO.png" alt="" />
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
								<img alt="Facebook logo" height="24" src="gambar/logos_facebook.png" width="24" />
							</a>
							<a href="https://github.com/novalArd">
								<img alt="Github logo" height="24" src="gambar/download 111(1).png" width="24" />
							</a>
							<a href="#">
								<img alt="X logo" height="24" src="gambar/download 22(1).png" width="24" />
							</a>
							<a href="https://www.instagram.com/nvlanss/">
								<img alt="Instagram logo" height="24" src="gambar/skill-icons_instagram.png" width="24" />
							</a>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<script src="nav-mobile.js"></script>
		<script src="semua-berita-btn.js"></script>
		<script src="live_search.js"></script>
	</body>
</html>
