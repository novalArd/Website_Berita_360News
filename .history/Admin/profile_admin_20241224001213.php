<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pagelogin.html'); // Redirect jika belum login atau bukan admin
    exit;
}

require '../database.php'; // Jalur diperbarui untuk mengakses database.php dari folder tes

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Hitung total artikel yang telah diverifikasi oleh admin (opsional jika dibutuhkan)
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_verified 
    FROM articles 
    WHERE status = 'approved' AND verifier_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Set nilai total artikel yang diverifikasi
$totalVerified = $result['total_verified'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Admin</title>
  <link rel="stylesheet" href="profile_admin.css?v=1.0">
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
        <li class="profil"><a href="profile_admin.php" class="active">Profil</a></li>
        <li><a href="verifikasi_artikel.php">Verifikasi Artikel</a></li>
        <li><a href="hapus_artikel_admin.php">Hapus Artikel</a></li>
        <li><a href="daftar_akun.php">Daftar Akun</a></li>
        <li><a href="form_penulis.php">Formulir Penulis</a></li>
        <li><a href="Tambah_admin.php">Tambah Artikel</a></li>
        <li><a href="edit_artikel_admin.php">Edit Artikel</a></li>
        <li><a href="artikel_saya_admin.php">Semua Artikel</a></li>
      </ul>
    <a href="../logout.php" class="logout">Logout</a>
  </aside>

    <!-- Main Content -->
    <div class="tampilan-flex">
      <div class="profile-foto">
        <p>PROFILE</p>
        <img src="../gambar/rb_2150611765.png" alt="">
      </div>
      <div class="profile-nama">
        <p class="nama-lengkap"><?php echo htmlspecialchars($user['nama_lengkap']);?></p>
        <div class="penulis-role">
          <p>Admin</p>
          <p class="char-pemisah">|</p>
          <p><?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki': 'Perempuan';?></p>
        </div>
        <div class="email-nohp">
          <p><?php echo htmlspecialchars($user['nomor_hp']);?></p>
          <p><?php echo htmlspecialchars($user['email']);?></p>
        </div>
      </div>
      <div class="edit-like-view">
        <button>EDIT PROFIL</button>
        <div class="like-view-card">
          <div class="like-view">
            <p class="jumlah" id="jumlah-verified"><?= $totalVerified; ?></p>
            <p class="keterangan" id="keterangan-verified">VERIFIKASI</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
