<?php
require __DIR__.'/db.php';
$pdo = get_pdo();

if (!isset($_GET['id'])) {
    die("Post not found.");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT p.*, u.u_name
    FROM okgposts p
    JOIN users u ON p.author_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($post['title']) ?></title>

<link rel="stylesheet" href="home.css">

<style>

/* ===================
   FULL POST PAGE
=================== */

.article-container{
    max-width:900px;
    margin:60px auto;
    background:#ffffff;
    border-radius:15px;
    padding:40px;
    box-shadow:0 10px 35px rgba(0,0,0,0.15);
}

.article-title{
    font-size:38px;
    margin-bottom:10px;
}

.article-meta{
    color:#888;
    font-size:14px;
    margin-bottom:20px;
}

.article-image{
    width:100%;
    border-radius:14px;
    margin:20px 0;
}

.article-body{
    font-size:17px;
    line-height:1.8;
}

.back-link{
    display:inline-block;
    margin-top:20px;
    color:#b85f3a;
    text-decoration:none;
    font-weight:bold;
}

.back-link:hover{
    text-decoration:underline;
}

</style>
</head>

<body>

<div class="article-container">

    <h1 class="article-title">
        <?= htmlspecialchars($post['title']) ?>
    </h1>

    <div class="article-meta">
        By <?= htmlspecialchars($post['u_name']) ?>
        • <?= date('F j, Y \a\t g:i A', strtotime($post['createdAt'])) ?>
    </div>

    <?php if ($post['image']): ?>
        <img 
            src="<?= htmlspecialchars($post['image']) ?>" 
            class="article-image"
            alt="Post Image">
    <?php endif; ?>

    <div class="article-body">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <a class="back-link" href="home.php">
        ← Back to Home
    </a>

</div>

</body>
</html>
