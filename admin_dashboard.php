<?php
session_start();

/* ========================================================
   ADMIN SECURITY CHECK
======================================================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit;
}

/* ========================================================
   DATABASE CONNECTION
======================================================== */
$conn = new mysqli("localhost:3307", "root", "", "local_blog");

if ($conn->connect_error) {
    die("Database connection failed");
}

$status_message = "";

/* ========================================================
   HELPER
======================================================== */
function redirect(){
    header("Location: admin-dashboard.php");
    exit;
}

/* ========================================================
   HANDLE ACTIONS
======================================================== */

/* DELETE POST */
if (isset($_GET['delete_post'])) {

    $post_id = (int) $_GET['delete_post'];

    $img = $conn->query("
        SELECT image FROM OKGPOSTS WHERE id=$post_id
    ")->fetch_assoc();

    if ($img && !empty($img['image']) && file_exists($img['image'])) {
        unlink($img['image']);
    }

    $conn->query("
        DELETE FROM OKGPOSTS WHERE id=$post_id
    ");

    $status_message = "✅ Post deleted.";
}

/* DELETE USER */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'delete_user') {

    $uid = (int) $_POST['user_id'];

    if ($uid > 0) {

        // Delete user's posts
        $conn->query("DELETE FROM OKGPOSTS WHERE author_id=$uid");

        // Delete the user
        $conn->query("DELETE FROM users WHERE id=$uid");
    }

    redirect();
}

/* DELETE CONTACT MESSAGE */
if (isset($_GET['delete_contact'])) {

    $mid = (int) $_GET['delete_contact'];

    $conn->query("
        DELETE FROM contacts WHERE id=$mid
    ");

    $status_message = "✅ Message deleted.";
}

/* ========================================================
   SEARCH INPUT
======================================================== */
$search     = $_GET['search'] ?? '';
$safeSearch = $conn->real_escape_string($search);

/* ========================================================
   DASHBOARD STATS
======================================================== */
$postCount    = $conn->query("SELECT COUNT(*) FROM OKGPOSTS")->fetch_row()[0];
$userCount    = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$messageCount = $conn->query("SELECT COUNT(*) FROM contacts")->fetch_row()[0];

/* ========================================================
   FETCH DATA
======================================================== */

/* USERS */
$users = $conn->query("
    SELECT id, u_name, name, age, createdAt
    FROM users
    WHERE u_name LIKE '%$safeSearch%'
       OR name   LIKE '%$safeSearch%'
    ORDER BY id ASC
");

/* POSTS */
$posts = $conn->query("
    SELECT p.*, u.u_name
    FROM OKGPOSTS p
    JOIN users u ON p.author_id = u.id
    WHERE p.title LIKE '%$safeSearch%'
       OR p.content LIKE '%$safeSearch%'
    ORDER BY p.createdAt DESC
");

/* CONTACTS */
$msgs = $conn->query("
    SELECT *
    FROM contacts
    WHERE subject LIKE '%$safeSearch%'
       OR name    LIKE '%$safeSearch%'
       OR email   LIKE '%$safeSearch%'
       OR message LIKE '%$safeSearch%'
    ORDER BY createdAt DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

<style>

/* =====================
   LAYOUT
===================== */

body{
    font-family:Arial, Helvetica, sans-serif;
    background:#f5f5f5;
}

.container{
    max-width:1100px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
}

/* =====================
   STATS
===================== */

.stats{
    display:flex;
    gap:15px;
}

.stats div{
    background:#f2f4ff;
    padding:10px 15px;
    border-radius:8px;
}

/* =====================
   SEARCH
===================== */

.search-box{
    margin:20px 0;
}

.search-box input{
    width:100%;
    padding:7px;
}

/* =====================
   TABLES
===================== */

table{
    width:100%;
    border-collapse:collapse;
    margin:25px 0;
}

th, td{
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

th{
    background:#eee;
}

/* =====================
   BUTTONS
===================== */

.edit-btn,
.owner-delete-btn,
.delete-btn{
    padding:6px 14px;
    border-radius:5px;
    background:#df3a3a;
    color:#fff;
    border:none;
    cursor:pointer;
    text-decoration:none;
}

.edit-btn{
    background:#3575e6;
}

/* =====================
   POSTS + CONTACTS
===================== */

.post,
.contact-card{
    border:1px solid #ddd;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
}

.post-image{
    max-width:100%;
    margin-top:10px;
}

/* =====================
   STATUS MESSAGE
===================== */

.msg{
    background:#e7ffe7;
    border-left:5px solid #42a142;
    padding:10px;
    margin:15px 0;
}

/* CLICKABLE POSTS */
.clickable{
    cursor:pointer;
}

/* COLLAPSIBLE CONTENT */
.post-content{
    overflow:hidden;
    max-height:0;
    transition:max-height 0.3s ease;
}

/* EXPANDED STATE */
.post-content.expanded{
    max-height:2000px; /* big number so images fit */
}


</style>

</head>
<body>

<div class="container">

<h1>Admin Dashboard</h1>
<p>Welcome, Administrator</p>

<a href="home.php">← Back to site</a>

<div class="stats">
    <div>📄 Posts: <b><?= $postCount ?></b></div>
    <div>👤 Users: <b><?= $userCount ?></b></div>
    <div>📬 Messages: <b><?= $messageCount ?></b></div>
</div>

<?php if($status_message): ?>
<div class="msg">
    <?= htmlspecialchars($status_message) ?>
</div>
<?php endif; ?>

<!-- SEARCH -->
<form class="search-box" method="GET">
    <input type="text"
           name="search"
           placeholder="Search posts, user, or contact messages..."
           value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>


<!-- USERS -->
<h2>Users</h2>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Name</th>
    <th>Age</th>
    <th>Registered</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php if($users->num_rows > 0): ?>
<?php while($u = $users->fetch_assoc()): ?>

<tr>
    <td><?= htmlspecialchars($u['id']) ?></td>
    <td><?= htmlspecialchars($u['u_name']) ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['age']) ?></td>
    <td><?= htmlspecialchars($u['createdAt']) ?></td>

    <td>
        <a class="edit-btn" href="admin-edit-user.php?id=<?= $u['id'] ?>">
            Edit
        </a>
    </td>

    <td>
        <form method="POST"
              onsubmit="return confirm('Delete this user and all posts?')">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
            <button class="owner-delete-btn">Delete</button>
        </form>
    </td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="7">No users found.</td></tr>
<?php endif; ?>

</table>


<!-- POSTS -->
<h2>Blog Posts</h2>

<?php if ($posts->num_rows > 0): ?>
<?php while ($post = $posts->fetch_assoc()): ?>

<div class="post clickable" onclick="togglePost(<?= $post['id'] ?>)">

    <h3><?= htmlspecialchars($post['title']) ?></h3>

    <small>
        By <?= htmlspecialchars($post['u_name']) ?>
        • <?= htmlspecialchars($post['createdAt']) ?>
    </small>

    <!-- COLLAPSIBLE CONTENT -->
    <div class="post-content" id="post-<?= $post['id'] ?>">

        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

        <?php if ($post['image']): ?>
            <img 
                src="<?= htmlspecialchars($post['image']) ?>" 
                class="post-image" 
                alt="Post image"
            >
        <?php endif; ?>

        <a class="delete-btn"
           href="?delete_post=<?= $post['id'] ?>"
           onclick="return confirm('Delete this post?')">
            Delete Post
        </a>

    </div>

</div>

<?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>



<!-- CONTACTS -->
<h2>Contact Messages</h2>

<?php if($msgs->num_rows > 0): ?>
<?php while($m = $msgs->fetch_assoc()): ?>

<div class="contact-card">

<h3><?= htmlspecialchars($m['subject'] ?: 'No Subject') ?></h3>

<small>
<?= htmlspecialchars($m['name']) ?> •
<?= htmlspecialchars($m['email']) ?> •
<?= htmlspecialchars($m['createdAt']) ?>
</small>

<p><?= nl2br(htmlspecialchars($m['message'])) ?></p>

<a class="delete-btn"
   href="?delete_contact=<?= $m['id'] ?>"
   onclick="return confirm('Delete this message?')">
   Delete Message
</a>

</div>

<?php endwhile; ?>
<?php else: ?>
<p>No messages found.</p>
<?php endif; ?>

</div>
    <script>
function togglePost(id){
    const box = document.getElementById("post-" + id);

    box.classList.toggle("expanded");
}
</script>

</body>
</html>
