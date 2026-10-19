<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $rows = $conn->query('SELECT id,name,email FROM students ORDER BY id')->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'name and valid email required']);
        exit;
    }
    try {
        $s = $conn->prepare('INSERT INTO students(name,email) VALUES(?,?)');
        $s->bind_param('ss', $name, $email);
        $s->execute();
        http_response_code(201);
        echo json_encode(['id' => $conn->insert_id, 'name' => $name, 'email' => $email]);
    } catch (Throwable $e) {
        http_response_code(409);
        echo json_encode(['error' => 'Could not create student/user; email may exist']);
    }
    exit;
}
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
