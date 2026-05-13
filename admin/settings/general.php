<?php
require_once '../../config.php';
checkAdminLogin();

$settings = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = trim($_POST['site_name']);
    $footer_text = trim($_POST['footer_text']);
    $facebook = trim($_POST['facebook']);
    $youtube = trim($_POST['youtube']);
    $instagram = trim($_POST['instagram']);
    $logo = $settings['logo'];
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $newLogo = uploadFile($_FILES['logo'], 'general');
        if ($newLogo) {
            if ($logo) unlink(UPLOAD_PATH . $logo);
            $logo = $newLogo;
        }
    }
    
    $stmt = $conn->prepare("UPDATE settings SET site_name=?, logo=?, footer_text=?, facebook=?, youtube=?, instagram=? WHERE id=1");
    $stmt->bind_param("ssssss", $site_name, $logo, $footer_text, $facebook, $youtube, $instagram);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Тохиргоо хадгалагдлаа!';
        redirect(ADMIN_URL . 'settings/general.php');
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <title>Ерөнхий тохиргоо - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <a href="../dashboard.php" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Буцах
                </a>
                <h4 class="d-inline">Ерөнхий тохиргоо</h4>
            </div>
            
            <div class="col-lg-8 mx-auto">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Сайтын нэр <span class="text-danger">*</span></label>
                                    <input type="text" name="site_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Лого</label>
                                    <?php if($settings['logo']): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo UPLOAD_URL . $settings['logo']; ?>" alt="Logo" style="height: 60px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">PNG форматаар тунгалаг өнгөтэй байх нь дээр. Санал: 200x60px</small>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Footer текст</label>
                                    <input type="text" name="footer_text" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['footer_text']); ?>">
                                </div>
                                
                                <div class="col-12"><hr><h5>Сошиал холбоосууд</h5></div>
                                
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook</label>
                                    <input type="url" name="facebook" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['facebook']); ?>" 
                                           placeholder="https://facebook.com/...">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-youtube text-danger"></i> YouTube</label>
                                    <input type="url" name="youtube" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['youtube']); ?>" 
                                           placeholder="https://youtube.com/...">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram</label>
                                    <input type="url" name="instagram" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['instagram']); ?>" 
                                           placeholder="https://instagram.com/...">
                                </div>
                                
                                <div class="col-12">
                                    <hr>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Хадгалах
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>