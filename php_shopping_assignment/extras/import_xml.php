<?php require_once __DIR__ . '/../config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlfile'])) {
  $tmp = $_FILES['xmlfile']['tmp_name'];
  $xml = @simplexml_load_file($tmp);
  if (!$xml)
    $message = 'Invalid XML file.';
  else {
    $count = 0;
    foreach ($xml->student as $st) {
      $name = trim((string) $st->name);
      $email = trim((string) $st->email);
      if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
        continue;
      try {
        $s = $conn->prepare('INSERT INTO students(name,email) VALUES(?,?)');
        $s->bind_param('ss', $name, $email);
        $s->execute();
        $count++;
      } catch (Throwable $e) {
      }
    }
    $message = "Imported $count student records into students table.";
  }
}
?><!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>XML Import</title>
</head>

<body>
  <h1>Import students.xml into MySQL</h1>
  <p><?= e($message) ?></p>
  <form method="post" enctype="multipart/form-data"><input type="file" name="xmlfile" accept=".xml"
      required><button>Import XML</button></form>
  <p>Expected XML format:</p>
  <pre>&lt;students&gt;
  &lt;student&gt;&lt;name&gt;Asha Patel&lt;/name&gt;&lt;email&gt;asha@example.com&lt;/email&gt;&lt;/student&gt;
&lt;/students&gt;</pre><a href="../index.php">Home</a>
</body>

</html>