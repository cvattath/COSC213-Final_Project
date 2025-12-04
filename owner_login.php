<?php
session_start();

// if already logged in, send to dashboard
if (!empty($_SESSION['is_owner']) && $_SESSION['is_owner'] === true) {
    header('Location: owner_dashboard.php');
    exit;
}

// owner's username and password
const OWNER_USERNAME = 'owner';
const OWNER_PASSWORD = 'owner';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === OWNER_USERNAME && $password === OWNER_PASSWORD) {
        // success login
        $_SESSION['is_owner'] = true;
        $_SESSION['owner_username'] = OWNER_USERNAME;
        header('Location: owner_dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Owner Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .owner-login-wrapper {
            max-width: 400px;
            margin: 60px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .owner-login-wrapper h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .owner-login-wrapper label {
            display: block;
            margin-bottom: 5px;
        }
        .owner-login-wrapper input[type="text"],
        .owner-login-wrapper input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }
        .owner-login-wrapper input[type="submit"] {
            width: 100%;
            padding: 8px;
            cursor: pointer;
        }
        .owner-error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
        .back-home {
            text-align: center;
            margin-top: 10px;
        }
        .back-home a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="owner-login-wrapper">
    <h2>Owner Login</h2>

    <?php if ($error): ?>
        <div class="owner-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="owner_login.php">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>

    <div class="back-home">
        <a href="home.php">←Home</a>
    </div>
</div>

</body>
</html>
