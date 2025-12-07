<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['logged_in'], $_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_pdo();

/* ------------------------
   Validate Post ID
-------------------------*/
$post_id = $_GET['id'] ?? null;

if (!is_numeric($post_id)) {
    die("Invalid post.");
}

/* ------------------------
   Get Post
-------------------------*/
$stmt = $pdo->prepare("
    SELECT *
    FROM okgposts
    WHERE id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

/* ------------------------
   Ownership check
-------------------------*/
if ($post['author_id'] != $_SESSION['user_id']) {
    die("You are not allowed to edit this post.");
}

/* ------------------------
   Load category list
-------------------------*/
$cats = $pdo->query("SELECT id, cat_name FROM categories")
            ->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------
   SAVE CHANGES
-------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {

    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $cat_id  = $_POST['cat_id'];

    /* ----- Image handling ----- */

    $imagePath = $post['image']; // existing path

    /* Remove image */
    if (!empty($_POST['remove_image'])) {
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
        $imagePath = null;
    }

    /* Upload new image */
    if (!empty($_FILES['image']['name'])) {

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . time() . "_" . $file;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    /* ----- Update DB ----- */

    $update = $pdo->prepare("
        UPDATE okgposts
        SET title = ?, content = ?, image = ?, cat_id = ?, updatedAt = NOW()
        WHERE id = ?
    ");

    $update->execute([
        $title,
        $content,
        $imagePath,
        $cat_id,
        $post_id
    ]);

    header("Location: post.php?id=".$post_id);
    exit;
}

/* ------------------------
   DELETE POST
-------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    /* Delete image file if exists */
    if ($post['image'] && file_exists($post['image'])) {
        unlink($post['image']);
    }

    $del = $pdo->prepare("DELETE FROM okgposts WHERE id = ?");
    $del->execute([$post_id]);

    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="editpost.css">
</head>
<body>

<h1>Edit Post</h1>

<form method="POST" enctype="multipart/form-data">

    <label>Title</label><br>
    <input
        type="text"
        name="title"
        value="<?= htmlspecialchars($post['title']) ?>"
        required
    >

    <br><br>

    <label>Category</label><br>
    <select name="cat_id">
        <?php foreach ($cats as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= $post['cat_id'] == $cat['id'] ? "selected" : "" ?>>
                <?= htmlspecialchars($cat['cat_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Content</label><br>
    <textarea
        name="content"
        rows="10"
        required
    ><?= htmlspecialchars($post['content']) ?></textarea>

    <br><br>

    <?php if (!empty($post['image'])): ?>
        <p>Current Image:</p>
        <img
            src="<?= htmlspecialchars($post['image']) ?>"
            class="post-image"
            style="max-width:300px;"
        >

        <br><br>

        <label>
            <input type="checkbox" name="remove_image" value="1">
            Remove current image
        </label>

        <br><br>
    <?php endif; ?>

    <label>Upload New Image</label><br>
    <input type="file" name="image">

    <br><br>

    <button type="submit" name="save">
        Save Changes
    </button>

    <button
        type="submit"
        name="delete"
        onclick="return confirm('Are you sure you want to delete this post?');"
        style="background:red;color:white;"
    >
        DELETE POST
    </button>

</form>

<br>
<a href="post.php?id=<?= $post_id ?>">← Cancel</a>

</body>
</html>
