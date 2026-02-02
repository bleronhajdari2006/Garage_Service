<?php
require_once __DIR__ . '/../../auth/Auth.php';
require_once __DIR__ . '/../../models/User.php';

use Auth\Auth;
use Models\User;

$auth = new Auth();
if (!$auth->check()) {
    header('Location: /public/login.php');
    exit;
}

if (!$auth->isAdmin()) {
    echo '<h1>403 - Forbidden</h1><p>Admin access required.</p>';
    exit;
}

$userModel = new User();
$users = $userModel->getAll(100, 0);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title></head>
<body>
<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($auth->user()['username']); ?> — <a href="/public/logout.php">Logout</a></p>

<nav>
  <a href="/public/admin/users.php">Manage Users</a> |
  <a href="/public/admin/pages.php">Manage Pages</a> |
  <a href="/public/admin/news.php">Manage News</a> |
  <a href="/public/admin/products.php">Manage Products</a> |
  <a href="/public/admin/contacts.php">View Contacts</a>
</nav>

<h2>Users</h2>
<table border="1" cellpadding="6">
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['role']); ?></td>
            <td><?php echo htmlspecialchars($u['created_at']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
