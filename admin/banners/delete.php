<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Баннер авах
    $stmt = $conn->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banner = $result->fetch_assoc();
    
    if ($banner) {
        // Зураг устгах
        if ($banner['image'] && file_exists(UPLOAD_PATH . $banner['image'])) {
            unlink(UPLOAD_PATH . $banner['image']);
        }
        
        // Баннер устгах
        $deleteStmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'Баннер амжилттай устгагдлаа!';
        } else {
            $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
        }
    } else {
        $_SESSION['error'] = 'Баннер олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'banners/');
?>