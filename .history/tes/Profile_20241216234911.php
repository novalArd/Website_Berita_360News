<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pagelogin.html'); // Redirect jika belum login
    exit;
}

require '../database.php'; // Jalur diperbarui untuk mengakses database.php dari folder tes

// Ambil data pengguna dari database berdasarkan sesi
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya</title>
  <link rel="stylesheet" href="profile.css?v=1.0">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="kembali-btn">
        <button>Kembali</button>
      </div>
    <h3>Dashboard</h3>
      <ul>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <!-- Menu untuk admin -->
        <li><a id="profil" href="admin.php">Profil</a></li>
        <li><a id="verifikasiArtikel" href="verifikasi.php">Verifikasi Artikel</a></li>
        <li><a id="hapusArtikel" href="hapus_artikel.php">Hapus Artikel</a></li>
        <li><a id="daftarAkun" href="daftar_akun.php">Daftar Akun</a></li>
        <li><a id="formulirPenulis" href="form_penulis.php">Formulir Penulis</a></li>
        <li><a id="artikelBaru" href="artikel_baru.php">Artikel Baru</a></li>
        <li><a id="editArtikel" href="edit_artikel.php">Edit Artikel</a></li>
        <?php elseif ($_SESSION['role'] === 'penulis'): ?>
        <!-- Menu untuk penulis -->
        <li><a id="profil" href="Profile.php">Profile</a></li>
        <li><a id="tambahArtikel" href="Tambah.php">Tambah Artikel</a></li>
        <li><a id="editArtikel" href="edit_artikel.php">Edit Artikel</a></li>
        <li><a id="artikelSaya" href="artikel_saya.php">Artikel Saya</a></li>
        <li><a id="hapusArtikel" href="hapus_artikel.php">Hapus Artikel</a></li>
        <?php endif; ?>
      </ul>
    <a href="../logout.php" class="logout">Logout</a>
  </aside>

    <!-- Main Content -->
     .profil
    <h2>Profil Saya</h2>
    <main class="main-content">
      <div class="profile-card" id="profil-nama">
        <div class="profile-header">
          <img src="profile-pic.jpg" alt="Foto Profil" class="profile-img">
          <div>
            <h3><?php echo htmlspecialchars($user['username']); ?></h3>
            <p>Penulis</p>
          </div>
        </div>
        <div class="profile-details">
          <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
          <p><strong>Gender:</strong> <?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
          <p><strong>Nomor HP:</strong> <?php echo htmlspecialchars($user['nomor_hp']); ?></p>
        </div>
      </div>
      <div class="profile-2">
      <div class="profile-card">
        <div class="profile-details">
          <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
          <p><strong>Gender:</strong> <?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
          <p><strong>Nomor HP:</strong> <?php echo htmlspecialchars($user['nomor_hp']); ?></p>
        </div>
      </div>
      <div class="profile-card">
        <div class="profile-details">
          <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
          <p><strong>Gender:</strong> <?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
          <p><strong>Nomor HP:</strong> <?php echo htmlspecialchars($user['nomor_hp']); ?></p>
        </div>
      </div>
      </div>
    </main>
  </div>
</body>
</html>
