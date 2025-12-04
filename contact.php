<?php
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

        $msg = "Thanks for contacting us! We'll respond as soon as we can.";

        // Optional reset
        $_POST = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Main CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>Contact Us</h1>
    <p>Have a question or feedback? Send us a message below.</p>

    <?php if($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name *</label><br>
        <input type="text" name="name" required
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        <br><br>

        <label>Email Address *</label><br>
        <input type="email" name="email" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <br><br>

        <label>Phone (optional)</label><br>
        <input type="text" name="phone"
            value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <br><br>

        <label>Subject</label><br>
        <input type="text" name="subject"
            value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
        <br><br>

        <label>Message *</label><br>
        <textarea name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        <br><br>

        <button class="sub-btn">Send Message</button>

    </form>

</div>

</body>
</html>
d