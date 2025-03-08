<?php
session_start();
require '../database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID artikel tidak valid.']);
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Anda harus login untuk menyukai artikel.']);
        exit;
    }

    $articleId = (int) $_POST['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT has_liked FROM article_interactions WHERE article_id = :article_id AND user_id = :user_id");
    $stmt->execute(['article_id' => $articleId, 'user_id' => $userId]);
    $interaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$interaction) {

        $stmt = $conn->prepare("INSERT INTO article_interactions (article_id, user_id, has_liked) VALUES (:article_id, :user_id, 1)");
        $stmt->execute(['article_id' => $articleId, 'user_id' => $userId]);

        $stmt = $conn->prepare("UPDATE articles SET likes = likes + 1 WHERE id = :id");
        $stmt->execute(['id' => $articleId]);

        $stmt = $conn->prepare("SELECT likes FROM articles WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
        $likes = $stmt->fetchColumn();

        echo json_encode(['success' => true, 'likes' => $likes]);
    } elseif (!$interaction['has_liked']) {
 
        $stmt = $conn->prepare("UPDATE article_interactions SET has_liked = 1 WHERE article_id = :article_id AND user_id = :user_id");
        $stmt->execute(['article_id' => $articleId, 'user_id' => $userId]);

        $stmt = $conn->prepare("UPDATE articles SET likes = likes + 1 WHERE id = :id");
        $stmt->execute(['id' => $articleId]);

        $stmt = $conn->prepare("SELECT likes FROM articles WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
        $likes = $stmt->fetchColumn();

        echo json_encode(['success' => true, 'likes' => $likes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Anda sudah menyukai artikel ini.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode permintaan tidak valid.']);
}
?>
