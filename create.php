<?php
require 'config.php';
require 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$_POST["title"], $_POST["content"], $_SESSION["user_id"]]);
    header("Location: index.php");
}
?>

<form method="post">
  <input type="text" name="title" placeholder="Post Title" required><br>
  <textarea name="content" placeholder="Post Content" required></textarea><br>
  <button type="submit">Create</button>
</form>