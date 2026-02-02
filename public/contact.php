<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db/Database.php';

use DB\Database;

$pdo = Database::getInstance();
$cfg = require __DIR__ . '/../config.php';
$uploadsDir = $cfg['uploads_dir'];
$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
        $errors[] = 'Please complete all fields correctly.';
    }

    $filePath = null;
    if (!empty($_FILES['file']['name'])) {
        $f = $_FILES['file'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif','pdf'];
        if (!in_array(strtolower($ext), $allowed)) {
            $errors[] = 'Invalid file type.';
        } else {
            $namef = uniqid('contact_') . '.' . $ext;
            move_uploaded_file($f['tmp_name'], $uploadsDir . DIRECTORY_SEPARATOR . $namef);
            $filePath = 'public/uploads/' . $namef;
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO contacts (name, email, message, file_path, submitted_at) VALUES (:n, :e, :m, :f, NOW())');
        $stmt->execute([':n' => $name, ':e' => $email, ':m' => $message, ':f' => $filePath]);
        $success = 'Thanks — your message was received.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contact</title>
<link rel="stylesheet" href="/style.css">
<script src="/script.js" defer></script>
</head>
<body>
<main class="container contact-section">
  <div class="contact-wrap">
    <div class="contact-left">
      <h2>Contact Us</h2>
      <p class="lead">Send us a message and we'll get back to you.</p>
    </div>
    <div class="contact-card">
      <?php if ($success): ?><div style="color:green"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
      <?php foreach ($errors as $e): ?><div style="color:red"><?php echo htmlspecialchars($e); ?></div><?php endforeach; ?>
      <form id="contactForm" method="post" enctype="multipart/form-data">
        <label>Name <input name="name" required></label>
        <label>Email <input name="email" type="email" required></label>
        <label>Message <textarea name="message" required></textarea></label>
        <label>Attachment (optional) <input name="file" type="file" accept="image/*,application/pdf"></label>
        <button type="submit">Send Message</button>
      </form>
    </div>
  </div>
</main>
</body>
</html>
