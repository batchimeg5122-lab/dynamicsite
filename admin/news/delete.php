<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();
    
    if ($news) {
        if ($news['image'] && file_exists(UPLOAD_PATH . $news['image'])) {
            unlink(UPLOAD_PATH . $news['image']);
        }
        
        $deleteStmt = $conn->prepare("DELETE FROM news WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'Мэдээ амжилттай устгагдлаа!';
        } else {
            $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
        }
    } else {
        $_SESSION['error'] = 'Мэдээ олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'news/');
?>