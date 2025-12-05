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
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<div id="nav-bar">
    <div id="nav-block">
    <a href="home.php">HOME</a>
    <a href="contact.php">CONTACT US</a>

    </div>
    <div id="login-block">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        <form method="POST" action="login.php">
    <input type="text" placeholder="USERNAME" name="username" required/>
    <input type="password" placeholder="PASSWORD" name="password" required/>
    <input type="submit" value="LOGIN">
    </form> 
    <p class="signup-text">Haven't signed up yet?<a href="register.php"> Click here.</a></p>
    
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

<?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>

    <div class="welcome-box">
        <p>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

            <a class="nav-btn" href="admin-dashboard.php">
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
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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

<div id="main">
    <h1 class="main-title">Prime-OKG</h1>
</div>



<div id="main-block">
    <div id="post-block">
    <h1>Recent Posts</h1><br>

<?php
$posts = $pdo->query("SELECT p.id, p.title, p.content, p.image, p.createdAt, 
                                     u.u_name AS author_name
                              FROM okgposts p 
                              INNER JOIN users u ON u.id = p.author_id 
                              ORDER BY p.createdAt DESC") ->fetchAll(PDO::FETCH_ASSOC);

    
  
?>

<?php if (empty($posts)): ?>
        <p>No posts yet — login and be the first!</p>
    <?php else: ?>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image" class="post-image">
                    <?php endif; ?>
                    <div class="post-content">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <p style="color:#666; font-size:14px;">
                    
                    <?= htmlspecialchars($post['author_name']) ?> • 
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
    <h1>Footer</h1>
</div>
<script src="script.js" async></script>
</body>

</html>