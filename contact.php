<?php
session_start();

require __DIR__ . "/db.php";
$pdo = get_pdo();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $msg = "Please fill in all required fields.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
    }
    else {

        $stmt = $pdo->prepare("
            INSERT INTO contacts(name,email,phone,subject,message)
            VALUES (?,?,?,?,?)
        ");

        $stmt->execute([
            $name,
            $email,
            $phone,
            $subject,
            $message
        ]);

        $msg = "✅ Thanks for contacting us! We'll respond as soon as we can.";

        // Clear old values
        $_POST = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Prime-OKG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="contact.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<!-- ==============================
 NAVBAR
================================ -->
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

<!-- ==============================
 HERO
================================ -->
<div id="main">
    <h1 class="main-title">Prime-OKG</h1>
</div>

<!-- ==============================
 CONTACT FORM
================================ -->
<div id="main-block">

<div class="container">

    <h1>Contact Us</h1>
    <p>Have a question or feedback? Send us a message below.</p>

    <?php if($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name *</label>
        <input type="text" name="name" required
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label>Email Address *</label>
        <input type="email" name="email" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label>Phone (optional)</label>
        <input type="text" name="phone"
            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

        <label>Subject</label>
        <input type="text" name="subject"
            value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">

        <label>Message *</label>
        <textarea name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button class="sub-btn">Send Message</button>

    </form>

</div>

</div>

<!-- ==============================
 FOOTER
================================ -->
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

<script src="script.js" async></script>
</body>
</html>
