<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Цэс олдсон эсэхийг шалгах
    $stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $menu = $result->fetch_assoc();
    
    if ($menu) {
        // Дэд цэсүүд байгаа эсэхийг шалгах
        $childCheck = $conn->prepare("SELECT COUNT(*) as count FROM menus WHERE parent_id = ?");
        $childCheck->bind_param("i", $id);
        $childCheck->execute();
        $childResult = $childCheck->get_result();
        $childCount = $childResult->fetch_assoc()['count'];
        
        if ($childCount > 0) {
            $_SESSION['error'] = 'Энэ цэсэнд дэд цэсүүд байна! Эхлээд дэд цэсүүдийг устгана уу эсвэл parent-ийг солино уу.';
        } else {
            // Цэс устгах
            $deleteStmt = $conn->prepare("DELETE FROM menus WHERE id = ?");
            $deleteStmt->bind_param("i", $id);
            
            if ($deleteStmt->execute()) {
                $_SESSION['success'] = 'Цэс амжилттай устгагдлаа!';
            } else {
                $_SESSION['error'] = 'Устгахад алдаа гарлаа!';
            }
        }
    } else {
        $_SESSION['error'] = 'Цэс олдсонгүй!';
    }
} else {
    $_SESSION['error'] = 'Буруу хүсэлт!';
}

redirect(ADMIN_URL . 'menus/');
?>