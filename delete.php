<?php
require 'config.php';
require 'session.php';

$id = $_GET["id"];
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");