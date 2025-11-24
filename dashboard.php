<?php
session_start();


$_SESSION['username'] = 'admin';  
$_POST['username'] = 'admin';     
$_POST['password'] = '12345';      


if ($_POST['username'] !== 'admin' || $_POST['password'] !== '12345') {
    die("Wrong credentials. <a href='home.php'>Back</a>");
}

$conn = new mysqli("localhost:3307", "root", "", "local_blog"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $author  = $_SESSION['username'];

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $sql = "INSERT INTO posts (title, author, content, image) 
             VALUES ('$title', '$author', '$content', '$image')";

    if ($conn->query($sql) === TRUE) {
        $message = "Post uploaded successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Create Post</title></head>
<body style="font-family:Arial; text-align:center; margin-top:100px;">
    <h1 style="color:#d35400;">Create New Post</h1>
    <p style="color:green; font-weight:bold;"><?= $message ?></p>


<div style="max-width:700px; margin:50px auto; text-align:center;">
    <h1>Create New Post</h1>
    <h3 style="color:green;"><?= $message ?></h3>

    <form method="post" enctype="multipart/form-data">
        Title:<br>
        <input type="text" name="title" required style="width:100%; padding:10px;"><br><br>

        Content:<br>
        <textarea name="content" rows="8" required style="width:100%; padding:10px;"></textarea><br><br>

        Image (optional):<br>
        <input type="file" name="image"><br><br>

        <button type="submit" style="padding:12px 30px; background:#28a745; color:white; border:none;">
            Upload Post
        </button>
    </form>

    <br><br>
    <a href="home.php">← Back to Home</a>
</div>
