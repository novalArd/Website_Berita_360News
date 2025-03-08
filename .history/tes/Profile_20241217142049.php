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
        <a href="../beranda.php"><button>Kembali</button></a>
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
     <div class="edit-profile">
      <p>Edit Profile</p>
     </div>
    <div class="tampilan-flex">
      <div class="profile-foto">
        <p>PROFILE</p>
        <img src="../gambar/rb_2150611765.png" alt="">
      </div>
      <div class="profile-nama">
        <p class="nama-lengkap"><?php echo htmlspecialchars($user['nama_lengkap']);?></p>
        <div class="penulis-role">
          <p><?php echo htmlspecialchars($user['role']);?></p>
          <p class="char-pemisah">|</p>
          <p><?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki': 'Perempuan';?></p>
        </div>
        <div class="email-nohp">
          <p><?php echo htmlspecialchars($user['nomor_hp']);?></p>
          <p><?php echo htmlspecialchars($user['email']);?></p>
        </div>
      </div>
    </div>
    
  </div>
</body>
</html>
