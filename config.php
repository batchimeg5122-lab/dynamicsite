<?php
// Database тохиргоо
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dynamicsite');

// MySQL холболт үүсгэх
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Холболт шалгах
if ($conn->connect_error) {
    die("Холболт амжилтгүй: " . $conn->connect_error);
}

// UTF-8 тохируулах
$conn->set_charset("utf8mb4");

// Session эхлүүлэх
session_start();

// Үндсэн URL хаягууд
define('BASE_URL', 'http://localhost/dynamicsite/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Settings авах функц
function getSetting($key) {
    global $conn;
    $stmt = $conn->prepare("SELECT $key FROM settings WHERE id = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row[$key] ?? '';
}

// Redirect хийх функц
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Admin нэвтрэлт шалгах
function checkAdminLogin() {
    if (!isset($_SESSION['admin_id'])) {
        redirect(ADMIN_URL . 'login.php');
    }
}

// Файл upload хийх функц
function uploadFile($file, $folder = 'general') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return false;
    }
    
    $newname = uniqid() . '_' . time() . '.' . $ext;
    $uploadDir = UPLOAD_PATH . $folder . '/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newname)) {
        return $folder . '/' . $newname;
    }
    
    return false;
}
?>