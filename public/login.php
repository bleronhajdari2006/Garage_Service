<?php
require_once __DIR__ . '/../auth/Auth.php';

use Auth\Auth;

$auth = new Auth();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf'] ?? null;

    if (!$auth->validateCsrf($csrf)) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        if ($auth->login($username, $password)) {
            header('Location: /public/admin/dashboard.php');
            exit;
        }
        $errors[] = 'Invalid credentials.';
    }
}

$token = $auth->csrfToken();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login</title></head>
<body>
<h1>Login</h1>
<?php foreach ($errors as $e): ?>
    <div style="color:red"><?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>
<form method="post" action="">
    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
    <label>Username: <input name="username" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
 </form>
<p>Don't have an account? <a href="/public/register.php">Register</a></p>
</body>
</html>
