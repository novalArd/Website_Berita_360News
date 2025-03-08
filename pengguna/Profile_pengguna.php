<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pagelogin.html'); // Redirect jika belum login
    exit;
}

require '../database.php'; 

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}


$stmt = $conn->prepare("
    SELECT SUM(views) AS total_views, SUM(likes) AS total_likes 
    FROM articles 
    WHERE author_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$totalViews = $result['total_views'] ?? 0;
$totalLikes = $result['total_likes'] ?? 0;

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
        <li><a id="profil" href="Profile.php">Profile</a></li>
      </ul>
    <a href="../logout.php" class="logout">Logout</a>
  </aside>

    <!-- Main Content -->
   <div class="main-panel">  
    <div class="tampilan-flex">
      <div class="tampilan-baris">
        <div class="profile-foto">
          <p>PROFILE</p>
          <img src="../gambar/rb_2150611765.png" alt="">
        </div>
        <div class="edit-like-view">
          <button>EDIT PROFIL</button>
        </div>
      </div>
      <div class="profile-nama">
        <div class="nama-role">
          <p class="nama-lengkap"><?php echo htmlspecialchars($user['nama_lengkap']);?></p>
          <p><?php echo htmlspecialchars($user['role']);?></p>
        </div>
        <div class="role-kontak">
          <div class="penulis-role">
            
            <p><?php echo $user['jenis_kelamin'] == 'L' ? 'Laki-laki': 'Perempuan';?></p>
          </div>
          <div class="email-nohp">
            <p><?php echo htmlspecialchars($user['nomor_hp']);?></p>
            <p><?php echo htmlspecialchars($user['email']);?></p>
          </div>
        </div>
      </div> 
    </div>
  </div>
  </div>
</body>
</html>
