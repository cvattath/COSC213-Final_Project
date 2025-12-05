<?php
session_start();

/* ============================
   ADMIN SECURITY CHECK
   - Only allow logged-in admins
============================ */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit;
}

/* ============================
   DATABASE CONNECTION
============================ */
$conn = new mysqli("localhost:3307", "root", "", "local_blog");

if ($conn->connect_error) {
    die("DB connection failed");
}

$status_message = "";

/* ============================
   HELPER FUNCTION
   - Redirect back to dashboard
============================ */
function redirect()
{
    header("Location: admin-dashboard.php");
    exit;
}

/* ============================
   DELETE POST
============================ */
if (isset($_GET["delete_post"])) {

    // get the post ID safely
    $post_id = (int) $_GET["delete_post"];

    // locate the image so we can delete the file
    $img = $conn->query("SELECT image FROM OKGPOSTS WHERE id=$post_id")->fetch_assoc();

    if ($img && !empty($img["image"]) && file_exists($img["image"])) {
        unlink($img["image"]);
    }

    // delete post record from database
    $conn->query("DELETE FROM OKGPOSTS WHERE id=$post_id");

    $status_message = "✅ Post deleted.";
}

/* ============================
   RESET USER PASSWORD
============================ */
if (isset($_GET["reset_user"])) {

    $uid = (int) $_GET["reset_user"];

    // hash the default reset password
    $newPass = password_hash("changeme123", PASSWORD_DEFAULT);

    // update password in database
    $conn->query("UPDATE users SET pass='$newPass' WHERE id=$uid");

    redirect();
}

/* ============================
   DELETE USER (ONE ACCOUNT ONLY)
============================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'])
    && $_POST['action'] === 'delete_user') {

    $uid = (int) $_POST["user_id"];  // comes from hidden input

    if ($uid > 0) {

        // Remove this user's posts
        $conn->query("DELETE FROM OKGPOSTS WHERE author_id = $uid");

        // Remove this user
        $conn->query("DELETE FROM users WHERE id = $uid");
    }

    // Redirect to avoid form re-submit on refresh
    redirect();
}


/* ============================
   DELETE CONTACT MESSAGE
============================ */
if (isset($_GET["delete_contact"])) {

    $mid = (int) $_GET["delete_contact"];

    // remove message from database
    $conn->query("DELETE FROM contacts WHERE id=$mid");

    $status_message = "✅ Message deleted.";
}

/* ============================
   POST SEARCH
============================ */
$search = isset($_GET["search"]) ? $_GET["search"] : "";
$safeSearch = $conn->real_escape_string($search);

/* ============================
   DASHBOARD STATISTICS
============================ */
$postCount    = $conn->query("SELECT COUNT(*) FROM OKGPOSTS")->fetch_row()[0];
$userCount    = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$messageCount = $conn->query("SELECT COUNT(*) FROM contacts")->fetch_row()[0];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="style.css" rel="stylesheet">
    <style>

/* =============================
   PAGE LAYOUT
============================= */

body{
    font-family: Arial, Helvetica, sans-serif;
    background:#f5f5f5;
}

.container{
    max-width:1100px;
    margin:30px auto;
    background:#ffffff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 5px rgba(0,0,0,0.1);
}

.section-title{
    margin:30px 0 15px;
}

/* =============================
   STATS
============================= */

.stats{
    display:flex;
    gap:20px;
    margin-bottom:15px;
}

.stats div{
    background:#f2f4ff;
    padding:10px 15px;
    border-radius:8px;
}

/* =============================
   SEARCH
============================= */

.search-box{
    margin:20px 0;
}

.search-box input{
    padding:7px;
    width: 100%;
}

.search-box button{
    padding:7px 14px;
}

/* =============================
   TABLES
============================= */

.owner-table,
.user-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:30px;
}

.owner-table th,
.owner-table td,
.user-table th,
.user-table td{
    border:1px solid #ccc;
    text-align:center;
    padding:10px;
}

.owner-table th,
.user-table th{
    background:#eee;
}

/* =============================
   BUTTONS
============================= */

.edit-btn,
.delete-btn,
.owner-delete-btn{
    display:inline-block;
    padding:6px 14px;
    margin:2px;
    border:none;
    border-radius:5px;
    color:white;
    font-size:13px;
    text-decoration:none;
    cursor:pointer;
    transition:.15s ease;
}

/* Edit button */

.edit-btn{
    background:#3575e6;
}

.edit-btn:hover{
    background:#2361cf;
}

/* Delete buttons */

.delete-btn,
.owner-delete-btn{
    background:#df3a3a;
}

.delete-btn:hover,
.owner-delete-btn:hover{
    background:#c42626;
}

/* =============================
   POSTS
============================= */

.post{
    background:#fafafa;
    border:1px solid #ddd;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
}

.post h3{
    margin:5px 0;
}

.post p{
    margin-top:10px;
}

.post-image{
    max-width:100%;
    margin-top:12px;
    border-radius:5px;
}

/* =============================
   CONTACT INBOX
============================= */

.contact-card{
    background:#fafafa;
    border:1px solid #ddd;
    padding:15px;
    border-radius:8px;
    margin:15px 0;
}

.contact-card p{
    margin-top:10px;
}

/* =============================
   HELPERS
============================= */

.msg{
    background:#e7ffe7;
    padding:10px;
    border-left:5px solid #42a142;
    margin:15px 0;
}

/* =============================
   DELETE POSITION SAFETY
============================= */

/* Prevent floating bug */
.user-table .delete-btn{
    position:static !important;
}

/* Kill any orphan floating delete buttons */
body > .delete-btn{
    display:none !important;
}


/* =============================
   MODAL PREVIEW (future)
============================= */

.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.6);
}

.modal-content{
    background:#fff;
    padding:20px;
    border-radius:10px;
    width:70%;
    margin:80px auto;
}

.modal-img{
    max-width:100%;
    max-height:400px;
    object-fit:contain;
    margin-top:12px;
}

#closeModal{
    float:right;
    font-size:20px;
    cursor:pointer;
}

</style>

</head>

<body class="body-dash">

<div class="container">

    <h1>Admin Dashboard</h1>
    <p>Welcome, Administrator</p>

    <a href="home.php">← Back to site</a>

    <br><br>

    <!-------------------------
        DASHBOARD STATS
    -------------------------->
    <div class="stats">
        <div>📄 Posts: <b><?php echo $postCount; ?></b></div>
        <div>👤 Users: <b><?php echo $userCount; ?></b></div>
        <div>📬 Messages: <b><?php echo $messageCount; ?></b></div>
    </div>

    <!-------------------------
        STATUS MESSAGE
    -------------------------->
    <?php if ($status_message != "") { ?>
        <div class="msg">
            <?php echo htmlspecialchars($status_message); ?>
        </div>
    <?php } ?>

    <!-------------------------
        SEARCH POSTS
    -------------------------->
    <form method="GET" class="search-box">
        <input type="text"
               name="search"
               placeholder="Search posts, user, or contact messages..."
               value="<?php echo htmlspecialchars($search); ?>">

        <button>Search</button>
    </form>

    <!-------------------------
        USER MANAGEMENT
    -------------------------->
<!-------------------------
    USER MANAGEMENT
-------------------------->
<h2 class="section-title">Users</h2>

<table class="owner-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Name</th>
            <th>Age</th>
            <th>Registered Date</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>

    <tbody>

<?php

$users = $conn->query("
    SELECT id, u_name, name, age, createdAt
    FROM users
    ORDER BY id ASC
");

if ($users->num_rows > 0):
    while ($u = $users->fetch_assoc()):
?>

        <tr>
            <td><?= htmlspecialchars($u['id']) ?></td>
            <td><?= htmlspecialchars($u['u_name']) ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['age']) ?></td>
            <td><?= htmlspecialchars($u['createdAt']) ?></td>

            <!-- EDIT USER -->
            <td>
                <a class="edit-btn"
                   href="admin-edit-user.php?id=<?= (int)$u['id'] ?>">
                    Edit
                </a>
            </td>

            <!-- DELETE USER -->
            <td>
                <form method="post"
                      class="delete-form"
                      style="display:inline"
                      onsubmit="return confirm('Delete this user and all posts?');">
                      
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                    
                    <input type="submit"
                           class="owner-delete-btn"
                           value="Delete">
                </form>
            </td>
        </tr>

<?php
    endwhile;
else:
?>
    <tr>
        <td colspan="7">There are no users yet.</td>
    </tr>

<?php endif; ?>

    </tbody>
</table>



    <!-------------------------
        BLOG POSTS
    -------------------------->
    <h2>All Blog Posts</h2>

    <?php

    $posts = $conn->query("
        SELECT p.*, u.u_name
        FROM OKGPOSTS p
        JOIN users u ON p.author_id = u.id
        WHERE p.title LIKE '%$safeSearch%'
           OR p.content LIKE '%$safeSearch%'
        ORDER BY p.createdAt DESC
    ");

    if ($posts->num_rows === 0) {

        echo "<p>No posts found.</p>";

    } else {

        while ($post = $posts->fetch_assoc()) {
    ?>

            <div class="post">

                <h3><?php echo htmlspecialchars($post['title']); ?></h3>

                <small>
                    By <?php echo htmlspecialchars($post['u_name']); ?>
                    • <?php echo $post['createdAt']; ?>
                </small>

                <p>
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </p>

                <?php if (!empty($post['image'])) { ?>
                    <img class="post-image"
                         src="<?php echo htmlspecialchars($post['image']); ?>">
                <?php } ?>

                <a class="delete-btn"
                   onclick="return confirm('Delete this post?')"
                   href="?delete_post=<?php echo $post['id']; ?>">
                   Delete Post
                </a>

            </div>

    <?php
        }
    }
    ?>

    <!-------------------------
        CONTACT INBOX
    -------------------------->
    <h2>Contact Messages</h2>

    <?php

    $msgs = $conn->query("
        SELECT *
        FROM contacts
        ORDER BY createdAt DESC
    ");

    if ($msgs->num_rows === 0) {

        echo "<p>No messages.</p>";

    } else {

        while ($m = $msgs->fetch_assoc()) {
    ?>

            <div class="contact-card">

                <h3>
                    <?php echo htmlspecialchars($m['subject'] ?: "No Subject"); ?>
                </h3>

                <small>
                    <?php echo htmlspecialchars($m['name']); ?> •
                    <?php echo htmlspecialchars($m['email']); ?>

                    <?php if (!empty($m['phone'])) { ?>
                        • <?php echo htmlspecialchars($m['phone']); ?>
                    <?php } ?>

                    • <?php echo $m['createdAt']; ?>
                </small>

                <p>
                    <?php echo nl2br(htmlspecialchars($m['message'])); ?>
                </p>

                <a class="delete-btn"
                   onclick="return confirm('Delete this message?')"
                   href="?delete_contact=<?php echo $m['id']; ?>">
                   Delete Message
                </a>

            </div>

    <?php
        }
    }
    ?>

</div>

</body>
</html>
