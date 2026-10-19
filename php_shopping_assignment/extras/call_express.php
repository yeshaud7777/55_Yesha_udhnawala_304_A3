<?php
$url = 'http://127.0.0.1:3000/api/students';
$context = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 5, 'header' => "Accept: application/json\r\n"]]);
$result = @file_get_contents($url, false, $context);
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>calls Express</title>
</head>

<body>
    <h1>calling Express REST API</h1>
    <?php if ($result === false): ?>
        <p>Could not connect. Start Node server with npm start in node_api folder.</p><?php else: ?>
        <pre><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></pre><?php endif; ?>
    <a href="../index.php">Home</a>
</body>

</html>