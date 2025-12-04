<?php
session_start();

// send login page if user is not owner
if (empty($_SESSION['is_owner']) || $_SESSION['is_owner'] !== true) {
    header('Location: owner_login.php');
    exit;
}

// logout for owner
if (isset($_GET['logout'])) {
    unset($_SESSION['is_owner'], $_SESSION['owner_username']);
    header('Location: owner_login.php');
    exit;
}

require_once 'db.php';

$pdo = get_pdo();

$postMessage = '';
$userMessage = '';
$errorMessage = '';

// post deletion process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_post') {
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    if ($postId) {
        try {
            // get photo pass
            $stmt = $pdo->prepare('SELECT image FROM OKGPOSTS WHERE id = ?');
            $stmt->execute([$postId]);
            $post = $stmt->fetch();

            if ($post) {
                // if images are attached, delete them
                if (!empty($post['image'])) {
                    $imagePath = 'uploads/' . $post['image'];
                    if (is_file($imagePath)) {
                        @unlink($imagePath);
                    }
                }

                // delete post record
                $stmt = $pdo->prepare('DELETE FROM OKGPOSTS WHERE id = ?');
                $stmt->execute([$postId]);
                $postMessage = '投稿(ID: ' . $postId . ')を削除しました。';
            }
        } catch (Exception $e) {
            $errorMessage = '投稿の削除中にエラーが発生しました。';
        }
    }
}

// user deletion process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_user') {
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    if ($userId) {
        try {
            $pdo->beginTransaction();

            // delete all the posts deleted user posted
            $stmt = $pdo->prepare('SELECT image FROM OKGPOSTS WHERE author_id = ?');
            $stmt->execute([$userId]);
            $posts = $stmt->fetchAll();

            // delete images attached teleted posts
            foreach ($posts as $p) {
                if (!empty($p['image'])) {
                    $imagePath = 'uploads/' . $p['image'];
                    if (is_file($imagePath)) {
                        @unlink($imagePath);
                    }
                }
            }

            // delete posts record
            $stmt = $pdo->prepare('DELETE FROM OKGPOSTS WHERE author_id = ?');
            $stmt->execute([$userId]);

            // delete user record
            $stmt = $pdo->prepare('DELETE FROM USERS WHERE id = ?');
            $stmt->execute([$userId]);

            $pdo->commit();
            $userMessage = 'User(ID: ' . $userId . ') and posts has been deleted.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $errorMessage = 'Error occured during deleting user';
        }
    }
}

// get all posts
$sqlPosts = '
    SELECT p.id, p.title, p.createdAt, 
           u.u_name AS author_name,
           c.cat_name AS category_name
    FROM OKGPOSTS p
    INNER JOIN USERS u ON p.author_id = u.id
    INNER JOIN CATEGORIES c ON p.cat_id = c.id
    ORDER BY p.createdAt DESC
';
$postsStmt = $pdo->query($sqlPosts);
$posts = $postsStmt->fetchAll();

// get all users
$sqlUsers = '
    SELECT id, u_name, name, age, createdAt
    FROM USERS
    ORDER BY createdAt DESC
';
$usersStmt = $pdo->query($sqlUsers);
$users = $usersStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .owner-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 10px;
        }
        .owner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .owner-header h1 {
            margin: 0;
        }
        .owner-messages {
            margin-bottom: 15px;
        }
        .owner-success {
            color: green;
        }
        .owner-error {
            color: red;
        }
        table.owner-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.owner-table th,
        table.owner-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            font-size: 14px;
        }
        table.owner-table th {
            background-color: #f0f0f0;
        }
        .owner-delete-btn {
            padding: 4px 8px;
            font-size: 13px;
            cursor: pointer;
        }
        .section-title {
            margin: 20px 0 8px;
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
    </style>
    <script>
        // popup for confirming deletion
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    const msg = form.dataset.type === 'post'
                        ? 'Are you sure to delete this post?'
                        : 'Are you sure to delete this user and all the posts?';
                    if (!confirm(msg)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</head>
<body>

<div class="owner-container">
    <div class="owner-header">
        <h1>Owner Dashboard</h1>
        <div>
            <?php if (!empty($_SESSION['owner_username'])): ?>
                <span>Logged in as: <strong><?= htmlspecialchars($_SESSION['owner_username'], ENT_QUOTES, 'UTF-8') ?></strong></span>
            <?php endif; ?>
            &nbsp;|&nbsp;
            <a href="home.php">HOME</a>
            &nbsp;|&nbsp;
            <a href="owner_dashboard.php?logout=1">Logout</a>
        </div>
    </div>

    <div class="owner-messages">
        <?php if ($postMessage): ?>
            <div class="owner-success"><?= htmlspecialchars($postMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($userMessage): ?>
            <div class="owner-success"><?= htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="owner-error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </div>

    <!-- All the posts -->
    <h2 class="section-title">Posts</h2>
    <table class="owner-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Categorie</th>
            <th>Author</th>
            <th>Created Date</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($posts): ?>
            <?php foreach ($posts as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['author_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($p['createdAt'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form method="post" class="delete-form" data-type="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete_post">
                            <input type="hidden" name="post_id" value="<?= (int)$p['id'] ?>">
                            <input type="submit" class="owner-delete-btn" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">There is no posts</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- all users -->
    <h2 class="section-title">Users</h2>
    <table class="owner-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Name</th>
            <th>Age</th>
            <th>Registered Date</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($users): ?>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['u_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['age'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['createdAt'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form method="post" class="delete-form" data-type="user" style="display:inline;">
                            <input type="hidden" name="action" value="delete_user">
                            <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                            <input type="submit" class="owner-delete-btn" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">There is no user</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
