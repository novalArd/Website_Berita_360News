<?php
header('Content-Type: application/json');


require_once '../database.php';


if (!isset($conn)) {
    die(json_encode(['error' => 'Koneksi database tidak tersedia.']));
}

try {
    // Query untuk mengambil data statistik
    $stmt = $conn->query("SELECT 
        SUM(views) AS total_views, 
        SUM(likes) AS total_likes, 
        COUNT(*) AS total_articles 
    FROM articles");

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kembalikan data dalam format JSON
    echo json_encode([
        'labels' => ['Artikel', 'Like', 'Penonton'],
        'data' => [
            (int) $data['total_articles'], 
            (int) $data['total_likes'], 
            (int) $data['total_views']
        ]
    ]);
} catch (PDOException $e) {
    // Tampilkan error jika query gagal
    echo json_encode(['error' => $e->getMessage()]);
}
