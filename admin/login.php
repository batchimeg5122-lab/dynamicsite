<?php
require_once '../config.php';

// Админ нэвтэрсэн бол dashboard руу шилжүүлэх
if (isset($_SESSION['admin_id'])) {
    redirect(ADMIN_URL . 'dashboard.php');
}

$error = '';

// Нэвтрэх үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Нэвтрэх нэр болон нууц үгээ оруулна уу!';
    } else {
        // Админ шалгах
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect(ADMIN_URL . 'dashboard.php');
        } else {
            $error = 'Нэвтрэх нэр эсвэл нууц үг буруу байна!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ нэвтрэх - <?php echo getSetting('site_name'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            max-width: 450px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        
        .login-body {
            padding: 40px;
            background: white;
            border-radius: 0 0 15px 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <i class="bi bi-shield-lock fs-1 mb-3 d-block"></i>
            <h3 class="mb-0">Админ нэвтрэх</h3>
            <p class="mb-0 mt-2 opacity-75">Удирдлагын хэсэгт нэвтрэх</p>
        </div>
        
        <!-- Body -->
        <div class="login-body">
            <?php if($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="form-label">
                        <i class="bi bi-person"></i> Нэвтрэх нэр
                    </label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="username" 
                           name="username" 
                           placeholder="Нэвтрэх нэрээ оруулна уу"
                           required
                           autofocus>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Нууц үг
                    </label>
                    <input type="password" 
                           class="form-control form-control-lg" 
                           id="password" 
                           name="password" 
                           placeholder="Нууц үгээ оруулна уу"
                           required>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">
                        Намайг санах
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-login w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Нэвтрэх
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Сайт руу буцах
                </a>
            </div>
            
            <div class="mt-4 pt-3 border-top text-center text-muted small">
                <p class="mb-0">
                    <i class="bi bi-info-circle"></i> Анхдагч нэвтрэх:<br>
                    <strong>admin</strong> / <strong>123456</strong>
                </p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>