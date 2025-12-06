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

    <style>
        /* ==========================
           LOGIN PAGE STYLING
        ========================== */

        .login-wrapper {
            min-height: calc(100vh - 150px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 380px;
            background: #fff;
            padding: 35px 40px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 15px;
            letter-spacing: 0.3px;
        }

        .login-box input {
            width: 100%;
            padding: 11px 14px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .login-box input:focus {
            border-color: #2c7df0;
            outline: none;
        }

        .login-box input[type="submit"] {
            background: linear-gradient(to right, #2c7df0, #235bd7);
            color: white;
            border: none;
            cursor: pointer;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-box input[type="submit"]:hover {
            background: linear-gradient(to right, #235bd7, #1c47b3);
        }

        .login-error {
            margin-bottom: 15px;
            background: #ffecec;
            color: #b51f2e;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .login-box p {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>

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

    </div>

</div>

</body>
</html>
