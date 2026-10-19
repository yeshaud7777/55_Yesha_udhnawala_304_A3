<?php require_once 'config.php';
$error = '';
if (empty($_SESSION['captcha']))
    $_SESSION['captcha'] = (string) random_int(1000, 9999);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $captcha = trim($_POST['captcha'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6)
        $error = 'Enter name, valid email and password (minimum 6 characters).';
    elseif (!hash_equals((string) $_SESSION['captcha'], $captcha))
        $error = 'CAPTCHA is incorrect.';
    else {
        try {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $s = $conn->prepare('INSERT INTO users(name,email,password) VALUES(?,?,?)');
            $s->bind_param('sss', $name, $email, $hash);
            $s->execute();
            unset($_SESSION['captcha']);
            header('Location: login.php?registered=1');
            exit;
        } catch (Throwable $e) {
            $error = 'This email may already be registered.';
        }
    }
    $_SESSION['captcha'] = (string) random_int(1000, 9999);
}
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register</title>
</head>

<body>
    <h1>User Registration with CAPTCHA</h1>
    <p style="color:red"><?= e($error) ?></p>
    <form method="post">
        <p>Name <input name="name" required></p>
        <p>Email <input type="email" name="email" required></p>
        <p>Password <input type="password" name="password" minlength="6" required></p>
        <p>CAPTCHA: <b><?= e($_SESSION['captcha']) ?></b> <input name="captcha" placeholder="Enter number" required></p>
        <button>Register</button>
    </form><a href="index.php">Home</a>
</body>

</html>