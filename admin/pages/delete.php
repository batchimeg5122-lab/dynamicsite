<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Хуудас авах
    $stmt = $conn->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    
    if ($page) {
        // Зураг устгах
        if ($page['cover_image'] && file_exists(UPLOAD_PATH . $page['cover_image'])) {
            unlink(UPLOAD_PATH . $page['cover_image']);
        }
        
        // Хуудас устгах
        $deleteStmt = $conn->prepare("DELETE FROM pages WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'Хуудас амжилттай устгагдлаа!';
        } else {
            $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
        }
    } else {
        $_SESSION['error'] = 'Хуудас олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'pages/');
?>