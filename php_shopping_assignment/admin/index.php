<?php require_once __DIR__ . '/../config.php';
require_admin(); ?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin</title>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome <?= e($_SESSION['admin_name'] ?? 'Admin') ?></p>
    <ul>
        <li><a href="categories.php">Manage Categories (CRUD)</a></li>
        <li><a href="products.php">Manage Products</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="../index.php">View User Site</a></li>
    </ul>
</body>

</html>