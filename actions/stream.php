<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) exit;

$file_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = getCurrentUser();

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$file_id, $user['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if ($file && file_exists($file['file_path'])) {
    header("Content-Type: " . $file['mime_type']);
    header("Content-Length: " . filesize($file['file_path']));
    readfile($file['file_path']);
    exit;
}
?>
