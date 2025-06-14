<?php
require 'config.php';
require 'session.php';

// 🔒 Role check: only admin allowed
if ($_SESSION['role'] !== 'admin') {
    echo "<h3 style='color:red;'>Access denied. Only admins can delete posts.</h3>";
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Secure deletion with prepared statement
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
