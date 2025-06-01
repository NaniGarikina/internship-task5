<?php
require 'config.php';
require 'session.php';

$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$_POST["title"], $_POST["content"], $id]);
    header("Location: index.php");
} else {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}
?>

<form method="post">
  <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br>
  <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea><br>
  <button type="submit">Update</button>
</form>