<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Үйлчилгээ авах
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    
    if ($service) {
        // Зураг устгах
        if ($service['image'] && file_exists(UPLOAD_PATH . $service['image'])) {
            unlink(UPLOAD_PATH . $service['image']);
        }
        
        // Үйлчилгээ устгах
        $deleteStmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'Үйлчилгээ амжилттай устгагдлаа!';
        } else {
            $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
        }
    } else {
        $_SESSION['error'] = 'Үйлчилгээ олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'services/');
?>