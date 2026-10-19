<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'php_shopping_assignment');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    die('Database connection failed. Check config.php host/port/user/password and import database.sql. Details: ' . htmlspecialchars($e->getMessage()));
}
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
function require_admin()
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}
function require_user()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
