<?php require_once __DIR__ . '/../config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $s = $conn->prepare('SELECT id,username,password FROM admins WHERE username=?');
    $s->bind_param('s', $u);
    $s->execute();
    $a = $s->get_result()->fetch_assoc();
    if ($a && password_verify($p, $a['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_name'] = $a['username'];
        header('Location: index.php');
        exit;
    }
    $error = 'Invalid admin username/password.';
}
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
</head>

<body>
    <h1>Admin Login</h1>
    <p style="color:red"><?= e($error) ?></p>
    <form method="post">
        <p>Username <input name="username" required></p>
        <p>Password <input type="password" name="password" required></p><button>Login</button>
    </form>
    <p>Demo: admin / password</p>
</body>

</html>