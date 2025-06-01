<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST["username"]]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        header("Location: index.php");
    } else {
        echo "Invalid login!";
    }
}
?>

<form method="post">
  <input type="text" name="username" required placeholder="Username"><br>
  <input type="password" name="password" required placeholder="Password"><br>
  <button type="submit">Login</button>
</form>