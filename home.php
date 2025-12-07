<?php
    session_start(); 

require __DIR__ . '/db.php';

$pdo = get_pdo();

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="home.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
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
        <form method="POST" action="login.php">
    <input type="text" placeholder="USERNAME" name="username" required/>
    <input type="password" placeholder="PASSWORD" name="password" required/>
    <input type="submit" value="LOGIN">
    </form> 
    <p class="signup-text">Haven't signed up yet?<a href="register.php"> Click here</a></p>
    <p class="signup-text">Are you an owner?<a href="admin_login.php"> Welcome Back</a></p>
    
    </div>

  </div>

<div id="main">
    <h1 class="main-title">Prime-OKG</h1>
</div>



<div id="main-block">
    <div id="post-block">
    <h1 class ="filter-checkbox">Recent Posts</h1>
    
    <label class ="filter-checkbox" for="localhikes">
        <input type="checkbox" class="cat-filter" value="1">
        Local Hikes
    </label>
    
    <label class ="filter-checkbox" for="localFoods">
        <input type="checkbox" class="cat-filter" value="2">
        Local News
    </label>
    
    <label class ="filter-checkbox" for="localFoods">
        <input type="checkbox" class="cat-filter"  value="3">
        Local Foods
    </label>

<!--------Filtering post based in the catgories----------->
<?php

$selected_cats = $_GET['cat'] ?? [];
$selected_cats = array_filter($selected_cats, 'is_numeric');


$sql = "SELECT p.id, p.title, p.content, p.image, p.createdAt, u.u_name AS author_name
       FROM okgposts p 
        INNER JOIN users u ON u.id = p.author_id";

if (!empty($selected_cats)) {
    $placeholders = str_repeat('?,', count($selected_cats) - 1) . '?';
    $sql .= " WHERE p.cat_id IN ($placeholders)";
}


$sql .= " ORDER BY p.createdAt DESC";
$new_sql = $pdo->prepare($sql);
$new_sql->execute($selected_cats);
$posts = $new_sql->fetchAll(pdo::FETCH_ASSOC);
 
  
?>

<?php if (empty($posts)): ?>
        <p>No posts yet — login and be the first!</p>

<?php else: ?>
    <div class="post-grid" id="posts-container">

<?php foreach ($posts as $post): ?>
    <div class="post-card">

    <?php if (!empty($post['image'])): ?>
    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image" class="post-image">

 <?php endif; ?>

    <div class="post-content">
    <h2><?= htmlspecialchars($post['title']) ?></h2>

    <p class="post-a-d">
    <strong><?= htmlspecialchars($post['author_name']) ?></strong> • 
    <?= date('F j, Y \a\t g:i A', strtotime($post['createdAt'])) ?>
    </p>
    <p>
    <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

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
<script src="script.js" defer></script>

</body>

</html>