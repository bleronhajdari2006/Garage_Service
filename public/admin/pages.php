<?php
require_once __DIR__ . '/../../auth/Auth.php';
require_once __DIR__ . '/../../models/Pages.php';

use Auth\Auth;
use Models\Pages;

$auth = new Auth();
if (!$auth->check() || !$auth->isAdmin()) {
    header('Location: /public/login.php');
    exit;
}

$cfg = require __DIR__ . '/../../config.php';
$uploadsDir = $cfg['uploads_dir'];

$pagesModel = new Pages();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf'] ?? null;
    if (!$auth->validateCsrf($token)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        if ($action === 'create') {
            $slug = trim($_POST['slug'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $mediaPath = null;
            if (!empty($_FILES['media']['name'])) {
                $f = $_FILES['media'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg','jpeg','png','gif','pdf'];
                if (!in_array(strtolower($ext), $allowed)) {
                    $errors[] = 'Invalid file type.';
                } else {
                    $name = uniqid('page_') . '.' . $ext;
                    move_uploaded_file($f['tmp_name'], $uploadsDir . DIRECTORY_SEPARATOR . $name);
                    $mediaPath = 'public/uploads/' . $name;
                }
            }
            if (empty($errors)) {
                $pagesModel->create($slug, $title, $content, $mediaPath, $auth->user()['id']);
            }
        } elseif ($action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            $slug = trim($_POST['slug'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $mediaPath = null;
            if (!empty($_FILES['media']['name'])) {
                $f = $_FILES['media'];
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg','jpeg','png','gif','pdf'];
                if (!in_array(strtolower($ext), $allowed)) {
                    $errors[] = 'Invalid file type.';
                } else {
                    $name = uniqid('page_') . '.' . $ext;
                    move_uploaded_file($f['tmp_name'], $uploadsDir . DIRECTORY_SEPARATOR . $name);
                    $mediaPath = 'public/uploads/' . $name;
                }
            }
            if (empty($errors)) {
                $pagesModel->update($id, $slug, $title, $content, $mediaPath);
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $pagesModel->delete($id);
        }
    }
}

$items = $pagesModel->listAll(200, 0);
$token = $auth->csrfToken();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Manage Pages</title></head>
<body>
<h1>Manage Pages</h1>
<p><a href="/public/admin/dashboard.php">Back to Dashboard</a> | <a href="/public/logout.php">Logout</a></p>

<?php foreach ($errors as $e): ?>
    <div style="color:red"><?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>

<h2>Create Page</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
    <input type="hidden" name="action" value="create">
    <label>Slug (URL-friendly, e.g. "home", "about"): <input name="slug" required></label><br>
    <label>Title: <input name="title" required></label><br>
    <label>Content: <textarea name="content"></textarea></label><br>
    <label>Media (image/pdf): <input name="media" type="file" accept="image/*,application/pdf"></label><br>
    <button type="submit">Create</button>
 </form>

<h2>Existing Pages</h2>
<table border="1" cellpadding="6">
    <tr><th>ID</th><th>Slug</th><th>Title</th><th>Media</th><th>Created</th><th>Actions</th></tr>
    <?php foreach ($items as $it): ?>
        <tr>
            <td><?php echo $it['id']; ?></td>
            <td><?php echo htmlspecialchars($it['slug']); ?></td>
            <td><?php echo htmlspecialchars($it['title']); ?></td>
            <td><?php echo $it['media_path'] ? '<a href="/' . htmlspecialchars($it['media_path']) . '" target="_blank">View</a>' : '-'; ?></td>
            <td><?php echo htmlspecialchars($it['created_at']); ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $it['id']; ?>">
                    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                </form>
                <button onclick="editPage(<?php echo $it['id']; ?>,'<?php echo addslashes(htmlspecialchars($it['slug'])); ?>','<?php echo addslashes(htmlspecialchars($it['title'])); ?>','<?php echo addslashes(htmlspecialchars($it['content'] ?? '')); ?>')">Edit</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function editPage(id, slug, title, content) {
    var form = document.createElement('form');
    form.method = 'post';
    form.enctype = 'multipart/form-data';
    form.innerHTML = '<input type="hidden" name="csrf" value="'+<?php echo json_encode($token); ?>+'">'
        + '<input type="hidden" name="action" value="edit">'
        + '<input type="hidden" name="id" value="'+id+'">'
        + '<label>Slug: <input name="slug" required value="'+slug+'"></label><br>'
        + '<label>Title: <input name="title" required value="'+title+'"></label><br>'
        + '<label>Content: <textarea name="content">'+content+'</textarea></label><br>'
        + '<label>Media (optional): <input name="media" type="file" accept="image/*,application/pdf"></label><br>'
        + '<button type="submit">Save</button>';
    var win = window.open('', '_blank', 'width=700,height=500');
    win.document.body.appendChild(form);
}
</script>

</body>
</html>
