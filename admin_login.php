<?php
session_start();

/* --------------------
 HARD CODED ADMIN LOGIN
---------------------*/
define("ADMIN_USER", "admin");
define("ADMIN_PASS", "admin123");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {

        // mark as admin
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = "admin";
        $_SESSION['username'] = "Admin";

        header("Location: admin_dashboard.php");
        exit;

    } else {

        $msg = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Admin Login</h2>

<?php if($msg): ?>
<div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<form method="POST">

<input type="text" name="username" placeholder="Admin username" required>
<input type="password" name="password" placeholder="Password" required>

<button class="sub-btn">Login</button>

</form>

</div>

</body>
</html>
