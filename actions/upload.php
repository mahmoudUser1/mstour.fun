<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) exit('Unauthorized');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $user = getCurrentUser();
    $folder_id = isset($_POST['folder_id']) && !empty($_POST['folder_id']) ? (int)$_POST['folder_id'] : null;
    
    $upload_dir = UPLOAD_DIR . $user['id'] . '/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    foreach ($_FILES['files']['name'] as $key => $name) {
        if ($_FILES['files']['error'][$key] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['files']['tmp_name'][$key];
            $size = $_FILES['files']['size'][$key];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $unique_name = uniqid() . '.' . $ext;
            
            if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
                $mime = $_FILES['files']['type'][$key];
                $stmt = $pdo->prepare("INSERT INTO files (name, original_name, file_path, file_size, mime_type, user_id, folder_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$unique_name, $name, $upload_dir . $unique_name, $size, $mime, $user['id'], $folder_id]);
            }
        }
    }
    header('Location: ../index.php' . ($folder_id ? "?folder=$folder_id" : ""));
}
?>
