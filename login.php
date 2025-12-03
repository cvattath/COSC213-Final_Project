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
            // Fetch user by username
            $stmt = $pdo->prepare("SELECT id, u_name, pass FROM users WHERE u_name = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['pass'])) {
                // Success! Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['u_name'];
                $_SESSION['logged_in'] = true;

                // Redirect to dashboard
                header("Location: dashboard.php");
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
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id="nav-bar">
        <div id="nav-block">
    <a href="home.php">HOME</a>
    <a href="contact.php">CONTACT US</a>

    </div>
    <div id="login-block">
        <form method="POST" action="login.php">
    <input type="text" placeholder="USERNAME" name="username" required/>
    <input type="password" placeholder="PASSWORD" name="password" required/>
    <input type="submit" value="LOGIN">
    </form> 
    <p class="signup-text">Haven't signed up yet?<a href="register.php"> Click here.</a></p>
    
    </div> </div>
    <div style="padding: 50px; text-align:center; color:red;">
        <h2><?= htmlspecialchars($error ?? '') ?></h2>
        <p><a href="home.php">← Back to home</a></p>
    </div>
</body>
</html>