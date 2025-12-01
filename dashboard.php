<?php
session_start();


$_SESSION['username'] = 'admin';  
// $_POST['username'] = 'admin';     
// $_POST['password'] = '12345';
$_SESSION['user_id'] = 1;      


// if ($_POST['username'] !== 'admin' || $_POST['password'] !== '12345') {
//     die("Wrong credentials. <a href='home.php'>Back</a>");
// }

$conn = new mysqli("localhost:3307", "root", "", "local_blog"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$cat_id = 0;

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $img = $conn -> query("SELECT image FROM OKGPOSTS WHERE id = $id") -> fetch_assoc();
    if($img && $img['image'] && file_exists($img['image'])){
        unlink($img['image']);
    }

    $conn -> query("DELETE FROM OKGPOSTS where id = $id");
    $message = 'Post deleted successfully!';
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    $title   = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_SPECIAL_CHARS);
    $author_id = $_SESSION['user_id'];
    $cat_id  = $_POST['cat_id'] ?? 1;

    if (!is_dir('uploads')) mkdir('uploads', 0777, true);

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

  

    $sql = "INSERT INTO OKGPOSTS (title, author_id, cat_id, content, image) 
            VALUES ('$title', '$author_id', '$cat_id', '$content', '$image')";

    if ($conn->query($sql) === TRUE) {
        $message = "Post uploaded successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link href="style.css" rel="stylesheet">
</head>
<body class="body-dash">

<div class="container">
    <h1>Create New Post</h1>
    <a href="home.php" class="click-home">go to home</a>
    <?php if ($message) echo "<div class='msg'>$message</div>"; ?>

    <form method="post" enctype="multipart/form-data">
        <strong>Title</strong><br>
        <input type="text" name="title" required><br>

        Category:<br>
        <?php
        $cats = $conn->query("SELECT id, cat_name FROM categories ORDER BY id");
        while ($cat = $cats->fetch_assoc()) {
            $checked = ($cat['id'] == 2) ? 'checked' : '';   
            echo "<label class='cat-section'>
                    <input type='radio' name='category' value='{$cat['id']}' $checked required>
                    {$cat['cat_name']}
                  </label>";
        }
        ?><br>

        Content:<br>
        <textarea name="content" rows="8" required style="width:100%; padding:10px;"></textarea><br><br>

        Image (optional):<br>
        <input type="file" name="image"><br><br>

        <button class="sub-btn" type="submit">
            Upload Post
        </button>
    </form>

    <br><br>
    

    <h2>Your Posts</h2>
    <?php
    $sql = "SELECT p.*, c.cat_name 
            FROM OKGPOSTS p 
            JOIN categories c ON p.cat_id = c.id 
            ORDER BY p.createdAt DESC";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "<p>No posts yet. Create your first one above!</p>";
    } else {
        while ($post = $result->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";
            echo "<small>Posted on: " . $post['createdAt'] . 
                 " | Category: <span class='cat'>" . htmlspecialchars($post['cat_name']) . "</span></small><br><br>";
            echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
            if ($post['image']) {
                echo "<img src='{$post['image']}' alt='Post image'>";
            }
            echo "<a href='?delete={$post['id']}' class='delete-btn '
                        onclick='return confirm(\"Delete this post forever?\");'>
                        Delete
                  </a>";
            echo "</div>";
        }
    }
    ?>
</div>
</body>
</html>
