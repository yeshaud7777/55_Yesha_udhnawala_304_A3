<?php require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $s = $conn->prepare('SELECT id,name,password FROM users WHERE email=?');
    $s->bind_param('s', $email);
    $s->execute();
    $u = $s->get_result()->fetch_assoc();
    if ($u && password_verify($password, $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        header('Location: index.php');
        exit;
    }
    $error = 'Invalid email or password.';
}
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>User Login</title>
</head>

<body>
    <h1>User Login</h1>
    <?php if (isset($_GET['registered']))
        echo '<p>Registration successful. Please login.</p>'; ?>
    <p style="color:red"><?= e($error) ?></p>
    <form method="post">
        <p>Email <input type="email" name="email" required></p>
        <p>Password <input type="password" name="password" required></p><button>Login</button>
    </form>
    <a href="register.php">Register</a> | <a href="index.php">Home</a>
</body>

</html>