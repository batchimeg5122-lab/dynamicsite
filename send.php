<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Өгөгдөл авах
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    
    // Шалгалт хийх
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Нэрээ оруулна уу";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "И-мэйл хаягаа зөв оруулна уу";
    }
    
    if (empty($message)) {
        $errors[] = "Мессежээ оруулна уу";
    }
    
    // Алдаагүй бол хадгалах
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Таны мессеж амжилттай илгээгдлээ!";
        } else {
            $_SESSION['error'] = "Алдаа гарлаа. Дахин оролдоно уу.";
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
    
    // Буцаах
    redirect(BASE_URL . 'index.php?page=contact');
} else {
    redirect(BASE_URL);
}
?>