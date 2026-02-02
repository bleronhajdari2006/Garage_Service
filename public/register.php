<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../auth/Auth.php';

use Models\User;
use Auth\Auth;

$auth = new Auth();
$userModel = new User();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf'] ?? null;

    if (!$auth->validateCsrf($csrf)) {
        $errors[] = 'Invalid CSRF token.';
    }
    if (strlen($username) < 3) $errors[] = 'Username too short.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
    if (strlen($password) < 6) $errors[] = 'Password too short.';

    if (empty($errors)) {
        try {
            $userModel->create($username, $email, $password, 'user');
            header('Location: /public/login.php');
            exit;
        } catch (\PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

$token = $auth->csrfToken();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register</title></head>
<body>
<h1>Register</h1>
<?php foreach ($errors as $e): ?>
    <div style="color:red"><?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>
<form method="post" action="">
    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
    <label>Username: <input name="username" required></label><br>
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Register</button>
 </form>
<p>Already have account? <a href="/public/login.php">Login</a></p>
</body>
</html>
