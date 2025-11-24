<?php
    session_start(); 

require __DIR__ . '/db.php';

$pdo = get_pdo();
$posts = $pdo->query("SELECT * FROM local_blog ORDER BY createdAt DESC")->fetchAll();

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
    <a href="home.html">HOME</a>
    <a href="contact.html">CONTACT US</a>

    </div>
    <div id="login-block">
        <form method="POST" action="dashboard.php">
    <input type="text" placeholder="USERNAME" name="username" required/>
    <input type="password" placeholder="PASSWORD" name="password" required/>
    <input type="submit" value="LOGIN">
    </form> 
    <p class="signup-text">Haven't signed up yet?<a href="register.html"> Click here.</a></p>
    
    </div>
</div>

<div id="main">
    <div id="left-side-block">
        <p>This is the left side block</p>
    </div>
    <h1 class="main-title">Prime-OKG</h1>
    <div id="right-side-block">
        <p>This is the right side block</p>
    </div>
</div>



    <div id="main-block">
    <div id="post-block">
    <h1>Recent Posts</h1><br>
    <div class="post-block-home">

    <div class="post-card">
<?php if (empty($posts)): ?>
                <p style="text-align:center; color:#999;">No posts yet — login and be the first!</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image" style="width:100%; max-height:400px; object-fit:cover; border-radius:12px; margin-bottom:15px;">
                        <?php endif; ?>

                        <h2><?= htmlspecialchars($post['title']) ?></h2>
                        <p style="color:#666; font-size:14px;">
                            <?= htmlspecialchars($post['author']) ?> • 
                            <?= date('F j, Y \a\t g:i A', strtotime($post['created_at'])) ?>
                        </p>
                        <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <p>This is a short description of the third post.</p>
    </div>

</div>

</div>

</div>

<div id="footer">
    <h1>Footer</h1>
</div>
<script src="script.js" async></script>
</body>

</html>