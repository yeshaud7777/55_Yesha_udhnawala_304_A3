<?php require_once __DIR__ . '/../config.php';
require_admin();
$msg = '';
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $s = $conn->prepare('DELETE FROM categories WHERE id=?');
    $s->bind_param('i', $id);
    $s->execute();
    header('Location: categories.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    if ($name !== '') {
        try {
            if ($id) {
                $s = $conn->prepare('UPDATE categories SET name=? WHERE id=?');
                $s->bind_param('si', $name, $id);
            } else {
                $s = $conn->prepare('INSERT INTO categories(name) VALUES(?)');
                $s->bind_param('s', $name);
            }
            $s->execute();
            header('Location: categories.php');
            exit;
        } catch (Throwable $e) {
            $msg = 'Category name already exists or could not be saved.';
        }
    }
}
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $s = $conn->prepare('SELECT * FROM categories WHERE id=?');
    $s->bind_param('i', $id);
    $s->execute();
    $edit = $s->get_result()->fetch_assoc();
}
$rows = $conn->query('SELECT * FROM categories ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Categories</title>
</head>

<body>
    <h1>Manage Categories</h1><a href="index.php">Dashboard</a>
    <p style="color:red"><?= e($msg) ?></p>
    <form method="post"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>"><input name="name"
            placeholder="Category name" value="<?= e($edit['name'] ?? '') ?>" required><button><?= $edit ? 'Update' : 'Add' ?>
            Category</button></form>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr><?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= e($r['name']) ?></td>
                <td><a href="?edit=<?= $r['id'] ?>">Edit</a> | <a href="?delete=<?= $r['id'] ?>"
                        onclick="return confirm('Delete category and its products?')">Delete</a></td>
            </tr><?php endforeach; ?>
    </table>
</body>

</html>