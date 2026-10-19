<?php
$dir = realpath(__DIR__ . '/../uploads');
$files = $dir ? scandir($dir) : [];
?><!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Directory Files</title>
</head>

<body>
    <h1>Files in uploads directory</h1>
    <ul>
        <?php if ($files)
            foreach ($files as $f) {
                if ($f === '.' || $f === '..')
                    continue;
                echo '<li>' . htmlspecialchars($f, ENT_QUOTES, 'UTF-8') . '</li>';
            } ?>
    </ul><a href="../index.php">Home</a>
</body>

</html>