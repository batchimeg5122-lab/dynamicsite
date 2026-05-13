<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Багийн гишүүн авах
    $stmt = $conn->prepare("SELECT * FROM team WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    
    if ($member) {
        // Зураг устгах
        if ($member['photo'] && file_exists(UPLOAD_PATH . $member['photo'])) {
            unlink(UPLOAD_PATH . $member['photo']);
        }
        
        // Гишүүн устгах
        $deleteStmt = $conn->prepare("DELETE FROM team WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'Багийн гишүүн амжилттай устгагдлаа!';
        } else {
            $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
        }
    } else {
        $_SESSION['error'] = 'Багийн гишүүн олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'team/');
?>