<?php
session_start();
require __DIR__ . '/db.php';

$pdo = get_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = "Please fill in all fields.";

    } else {

        try {

            $stmt = $pdo->prepare(
                "SELECT id, u_name, pass, role FROM users WHERE u_name = ? LIMIT 1"
            );

            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['pass'])) {

                $_SESSION['user_id']    = $user['id'];
                $_SESSION['username']  = $user['u_name'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['logged_in'] = true;

                if ($_SESSION['role'] === 'admin') {

                    header("Location: admin_dashboard.php");

                } else {

                    header("Location: dashboard.php");

                }

                exit;

            } else {

                $error = "Invalid username or password.";

            }

        } catch (Exception $e) {

            $error = "Login failed. Please try again later.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
    <link rel="stylesheet" href="userlogin.css">
</head>
<body>

<div class="login-wrapper">

    <div class="login-box">

        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="login-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <input
                type="text"
                placeholder="Username"
                name="username"
                required
            >

            <input
                type="password"
                placeholder="Password"
                name="password"
                required
            >

            <input type="submit" value="Log In">

        </form>

        <p>
            Haven't signed up yet?
            <a href="register.php">Create an account</a>
        </p>
        <p>
            Are you an admin?
            <a href="admin_login.php">Log in here</a>
        </p>

    </div>

</div>

</body>
</html>
