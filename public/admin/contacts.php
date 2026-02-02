<?php
require_once __DIR__ . '/../../auth/Auth.php';
require_once __DIR__ . '/../../db/Database.php';

use Auth\Auth;
use DB\Database;

$auth = new Auth();
if (!$auth->check() || !$auth->isAdmin()) {
    header('Location: /public/login.php');
    exit;
}

$pdo = Database::getInstance();
$stmt = $pdo->query('SELECT * FROM contacts ORDER BY submitted_at DESC LIMIT 500');
$items = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contact Submissions</title></head>
<body>
<h1>Contact Submissions</h1>
<p><a href="/public/admin/dashboard.php">Back to Dashboard</a></p>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>File</th><th>Submitted</th></tr>
  <?php foreach ($items as $it): ?>
    <tr>
      <td><?php echo $it['id']; ?></td>
      <td><?php echo htmlspecialchars($it['name']); ?></td>
      <td><?php echo htmlspecialchars($it['email']); ?></td>
      <td><?php echo nl2br(htmlspecialchars($it['message'])); ?></td>
      <td><?php echo $it['file_path'] ? '<a href="/' . htmlspecialchars($it['file_path']) . '" target="_blank">Open</a>' : '-'; ?></td>
      <td><?php echo htmlspecialchars($it['submitted_at']); ?></td>
    </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
