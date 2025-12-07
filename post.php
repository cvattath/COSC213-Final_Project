<?php
session_start();
require __DIR__ . '/db.php';

$pdo = get_pdo();

$post_id = $_GET['id'] ?? null;

if (!is_numeric($post_id)) {
    die("Invalid post.");
}

$stmt = $pdo->prepare("
    SELECT p.*, u.u_name AS author_name
    FROM okgposts p
    INNER JOIN users u ON u.id = p.author_id
    WHERE p.id = ?
");
$stmt->execute([$post_id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="viewpost.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<div id="nav-bar">
    <div id="nav-block">
    <a href="home.php">HOME</a>
    <a href="contact.php">CONTACT US</a>

    </div>
    <div id="login-block">
      
<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>

    <div class="welcome-box">
        <p>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

            <a class="nav-btn" href="admin_dashboard.php">
                Admin Dashboard
            </a>

        <?php else: ?>

            <a class="nav-btn" href="dashboard.php">
                Dashboard
            </a>

            <a class="nav-btn" href="dashboard.php#new-post">
                Create Post
            </a>

        <?php endif; ?>

        <a class="nav-btn logout-btn" href="logout.php">Logout</a>
    </div>

<?php else: ?>

    <!-- SHOW LOGIN FORM IF NOT LOGGED IN -->
    <form method="POST" action="login.php">
        <input type="text" placeholder="USERNAME" name="username" required />
        <input type="password" placeholder="PASSWORD" name="password" required />
        <input type="submit" value="LOGIN">
    </form>

    <p class="signup-text">
        Haven't signed up yet?
        <a href="register.php"> Click here.</a>
    </p>
    <p class="signup-text">Are you an admin?<a href="admin_login.php"> Welcome Back</a></p>

<?php endif; ?>
        
    </div>

  </div>

<div class="post-container">

    <h1 class="post-title">
        <?= htmlspecialchars($post['title']) ?>
    </h1>

    <p class="post-meta">
        By <strong><?= htmlspecialchars($post['author_name']) ?></strong> |
        <?= date('F j, Y g:i A', strtotime($post['createdAt'])) ?>
    </p>

    <?php if (!empty($post['image'])): ?>
        <img
            src="<?= htmlspecialchars($post['image']) ?>"
            class="post-image"
        >
    <?php endif; ?>

    <div class="post-body">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <a href="home.php" class="back-btn">
        ← Back to posts
    </a>

</div>

<div id="footer">
  <div class="footer-wrapper">

    <!-- LEFT COLUMN -->
    <div class="footer-brand">
      <h1 class="footer-title">Prime-OKG</h1>
      <p class="footer-tagline">
        Where everyday stories turn into shared moments. 
      </p>
    </div>

    <!-- CENTER COLUMN -->
    <div class="footer-links">
      <a href="home.php">Home</a>
      <a href="contact.php">Contact</a>
      <a href="register.php">Register</a>
      <a href="admin_login.php">Admin</a>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="footer-social">
      <i class="bi bi-instagram"></i>
      <i class="bi bi-twitter-x"></i>
      <i class="bi bi-facebook"></i>
      <i class="bi bi-envelope-fill"></i>
    </div>

    <!-- BOTTOM -->
    <p class="footer-copy">
      © <?php echo date("Y"); ?> Prime-OKG | All Rights Reserved.
    </p>

  </div>
</div>

</body>
</html>

