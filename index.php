<?php
require 'config.php';
require 'session.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

if ($search) {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            WHERE posts.title LIKE ? OR posts.content LIKE ?
                            ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bindValue(1, "%$search%");
    $stmt->bindValue(2, "%$search%");
    $stmt->bindValue(3, $start, PDO::PARAM_INT);
    $stmt->bindValue(4, $limit, PDO::PARAM_INT);
    $stmt->execute();

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ? OR content LIKE ?");
    $countStmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bindValue(1, $start, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();

    $countStmt = $conn->query("SELECT COUNT(*) FROM posts");
}

$posts = $stmt->fetchAll();
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task 3 - Enhanced Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .container { max-width: 800px; }
        .search-bar { max-width: 500px; margin: 0 auto; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Blog Posts</h2>
        <div>
            <a href="create.php" class="btn btn-success">Add New Post</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>

    <form method="GET" class="mb-4 search-bar">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php foreach ($posts as $post): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">By <?= $post['username'] ?> | <?= $post['created_at'] ?></small><br>
                <div class="mt-2">
                    <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                    <a href="delete.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?');" class="btn btn-sm btn-outline-danger">Delete</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= $search ? 'search=' . urlencode($search) . '&' : '' ?>page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
