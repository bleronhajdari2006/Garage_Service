<?php
require_once __DIR__ . '/../auth/Auth.php';
use Auth\Auth;
$auth = new Auth();
$auth->logout();
header('Location: /public/login.php');
exit;
