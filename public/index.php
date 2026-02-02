<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../models/Pages.php';

use DB\Database;
use Models\Pages;

$pdo = Database::getInstance();
$pagesModel = new Pages();

// Parse slug from query or default to 'home'
$slug = $_GET['page'] ?? $_GET['p'] ?? 'home';
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));

$page = $pagesModel->findBySlug($slug);

if (!$page) {
    // Try loading from static files for backward compatibility
    if (file_exists(__DIR__ . "/../{$slug}.html")) {
        include __DIR__ . "/../{$slug}.html";
    } else {
        header("HTTP/1.0 404 Not Found");
        echo '<h1>404 - Page not found</h1>';
    }
    exit;
}

// Fetch related content (news, products) for display
$news = [];
$products = [];
if (in_array($slug, ['home', 'news'])) {
    $stmt = $pdo->query('SELECT * FROM news ORDER BY created_at DESC LIMIT 5');
    $news = $stmt->fetchAll();
}
if (in_array($slug, ['home', 'products', 'services'])) {
    $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 5');
    $products = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($page['title']); ?> — Garage Service</title>
    <link rel="stylesheet" href="/style.css">
    <script src="/script.js" defer></script>
</head>
<body>
    <header class="topbar">
        <div class="container">
            <div class="logo">🚗 Garage Service</div>
            <div class="contact">
                <a href="/public/contact.php">Contact</a>
                <a href="/public/register.php" class="book">Register</a>
                <a href="/public/login.php" class="book">Login</a>
            </div>
        </div>
    </header>

    <nav class="nav-wrap">
        <div class="container main-nav">
            <div class="nav-logo">GS</div>
            <ul>
                <li><a href="/?page=home">Home</a></li>
                <li><a href="/?page=about">About Us</a></li>
                <li><a href="/?page=services">Services</a></li>
                <li><a href="/?page=news">News</a></li>
                <li><a href="/public/contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <main class="container" style="padding: 32px 0; flex: 1;">
        <h1><?php echo htmlspecialchars($page['title']); ?></h1>
        
        <div style="max-width: 800px;">
            <?php if (!empty($page['media_path'])): ?>
                <div style="margin-bottom: 20px;">
                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $page['media_path'])): ?>
                        <img src="/<?php echo htmlspecialchars($page['media_path']); ?>" alt="<?php echo htmlspecialchars($page['title']); ?>" style="max-width: 100%; height: auto;">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div style="line-height: 1.6; color: #333;">
                <?php echo nl2br(htmlspecialchars($page['content'])); ?>
            </div>
        </div>

        <?php if (!empty($news) && in_array($slug, ['home', 'news'])): ?>
            <h2 style="margin-top: 40px;">Latest News</h2>
            <div style="display: grid; gap: 20px; margin-top: 20px;">
                <?php foreach ($news as $item): ?>
                    <div style="border: 1px solid #eee; padding: 16px; border-radius: 8px;">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p style="color: #666; margin: 8px 0;"><?php echo substr(htmlspecialchars($item['body'] ?? ''), 0, 150) . '...'; ?></p>
                        <?php if ($item['media_path']): ?>
                            <small><a href="/<?php echo htmlspecialchars($item['media_path']); ?>" target="_blank">View media</a></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($products) && in_array($slug, ['home', 'services', 'products'])): ?>
            <h2 style="margin-top: 40px;">Our Services & Products</h2>
            <div style="display: grid; gap: 20px; margin-top: 20px;">
                <?php foreach ($products as $item): ?>
                    <div style="border: 1px solid #eee; padding: 16px; border-radius: 8px;">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p style="color: #666; margin: 8px 0;"><?php echo substr(htmlspecialchars($item['description'] ?? ''), 0, 150) . '...'; ?></p>
                        <?php if ($item['media_path']): ?>
                            <small><a href="/<?php echo htmlspecialchars($item['media_path']); ?>" target="_blank">View media</a></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 Garage Service. All rights reserved.</p>
            <p><a href="/public/login.php">Admin</a></p>
        </div>
    </footer>
</body>
</html>
