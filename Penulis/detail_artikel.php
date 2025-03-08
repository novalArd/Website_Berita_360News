<?php
session_start();
require '../database.php';

if (!isset($_GET['id'])) {
    die("Artikel tidak ditemukan.");
}

$articleId = $_GET['id'];


$stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = :id");
$stmt->execute(['id' => $articleId]);


$stmt = $conn->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Artikel tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($article['judul']); ?></title>
</head>
<body>
  <h1><?= htmlspecialchars($article['judul']); ?></h1>
  <p><?= htmlspecialchars($article['konten']); ?></p>
  <p><strong>Views:</strong> <?= htmlspecialchars($article['views']); ?></p>
  <p><strong>Likes:</strong> <?= htmlspecialchars($article['likes']); ?></p>
  <form method="POST" action="like_artikel.php">
      <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
      <button type="submit">Suka</button>
  </form>
  <a href="artikel_saya.php">Kembali ke Artikel Saya</a>
</body>
</html>