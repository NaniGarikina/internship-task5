<?php
require 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $role = 'editor';

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        echo "Registered successfully!";
    } else {
        echo "All fields are required.";
    }
}
?>

<form method="post">
  <input type="text" name="username" required placeholder="Username"><br>
  <input type="password" name="password" required placeholder="Password"><br>
  <button type="submit">Register</button>
</form>