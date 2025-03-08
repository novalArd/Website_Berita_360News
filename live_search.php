<?php
require 'database.php'; // Pastikan file database Anda sudah terhubung

// Ambil query pencarian dari parameter URL
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($query)) {
    // Query untuk mencari artikel berdasarkan judul atau konten
    $stmt = $conn->prepare("SELECT id, judul FROM articles WHERE is_verified = 1 AND (judul LIKE :query OR konten LIKE :query) ORDER BY tanggal DESC LIMIT 10");
    $stmt->execute(['query' => '%' . $query . '%']);

    // Ambil hasil pencarian
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kirim hasil dalam format JSON
    echo json_encode($results);
} else {
    echo json_encode([]); // Jika tidak ada query, kirim array kosong
}
?>
