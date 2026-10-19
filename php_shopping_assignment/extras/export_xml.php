<?php require_once __DIR__ . '/../config.php';
header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="students.xml"');
$rows = $conn->query('SELECT id,name,email,created_at FROM students ORDER BY id')->fetch_all(MYSQLI_ASSOC);
$xml = new XMLWriter();
$xml->openURI('php://output');
$xml->startDocument('1.0', 'UTF-8');
$xml->setIndent(true);
$xml->startElement('students');
foreach ($rows as $r) {
    $xml->startElement('student');
    foreach ($r as $key => $value) {
        $xml->writeElement($key, (string) $value);
    }
    $xml->endElement();
}
$xml->endElement();
$xml->endDocument();
$xml->flush();
