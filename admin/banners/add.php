<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $button_text = trim($_POST['button_text']);
    $button_link = trim($_POST['button_link']);
    
    // Зураг upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'banners');
        if (!$image) {
            $error = 'Зураг upload амжилтгүй!';
        }
    } else {
        $error = 'Баннер зураг оруулах шаардлагатай!';
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO banners (title, subtitle, image, button_text, button_link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $subtitle, $image, $button_text, $button_link);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Баннер амжилттай нэмэгдлээ!';
            redirect(ADMIN_URL . 'banners/');
        } else {
            $error = 'Алдаа гарлаа: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Баннер нэмэх - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0">Шинэ баннер нэмэх</h4>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <?php if($error): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <!-- Гарчиг -->
                                        <div class="col-12">
                                            <label for="title" class="form-label">
                                                Гарчиг <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="title" 
                                                   name="title" 
                                                   required
                                                   placeholder="Баннерын үндсэн гарчиг"
                                                   autofocus>
                                            <small class="text-muted">Том үсэг, товч, тод гарчиг</small>
                                        </div>
                                        
                                        <!-- Дэд гарчиг -->
                                        <div class="col-12">
                                            <label for="subtitle" class="form-label">
                                                Дэд гарчиг
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="subtitle" 
                                                   name="subtitle" 
                                                   placeholder="Нэмэлт тайлбар текст">
                                            <small class="text-muted">Гарчгийн доор харагдах текст</small>
                                        </div>
                                        
                                        <!-- Зураг -->
                                        <div class="col-12">
                                            <label for="image" class="form-label">
                                                Баннер зураг <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="image" 
                                                   name="image"
                                                   accept="image/*"
                                                   required
                                                   onchange="previewImage(this)">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle"></i> 
                                                Санал: 1920x600px (16:9 харьцаа). JPG, PNG форматаар. Max: 5MB
                                            </small>
                                            
                                            <!-- Image preview -->
                                            <div id="imagePreview" class="mt-3" style="display:none;">
                                                <img id="preview" src="" alt="Preview" class="img-thumbnail w-100" style="max-height: 300px; object-fit: cover;">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12"><hr></div>
                                        
                                        <!-- Товчны мэдээлэл -->
                                        <div class="col-12">
                                            <h5><i class="bi bi-cursor"></i> Товчны тохиргоо (сонголттой)</h5>
                                            <p class="text-muted small">
                                                Баннер дээр дарж болох товч нэмэх. Хэрэв хоосон орхивол товч харагдахгүй.
                                            </p>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="button_text" class="form-label">
                                                Товчны текст
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="button_text" 
                                                   name="button_text" 
                                                   placeholder="Дэлгэрэнгүй">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="button_link" class="form-label">
                                                Товчны холбоос
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="button_link" 
                                                   name="button_link" 
                                                   placeholder="index.php?page=services эсвэл https://...">
                                            <small class="text-muted">
                                                Дотоод хуудас: <code>index.php?page=about</code><br>
                                                Гадны холбоос: <code>https://example.com</code>
                                            </small>
                                        </div>
                                        
                                        <!-- Preview -->
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <i class="bi bi-eye"></i> Урьдчилан харах
                                                </div>
                                                <div class="card-body bg-dark text-white text-center" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                                    <div>
                                                        <h1 id="preview-title" class="display-4 fw-bold mb-3">Баннерын гарчиг</h1>
                                                        <p id="preview-subtitle" class="lead mb-4">Дэд гарчиг энд харагдана</p>
                                                        <div id="preview-button" style="display:none;">
                                                            <span class="btn btn-primary btn-lg" id="preview-btn-text">Товч</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Товчнууд -->
                                        <div class="col-12">
                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-check-circle"></i> Хадгалах
                                            </button>
                                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                                <i class="bi bi-x-circle"></i> Цуцлах
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Live preview
        document.getElementById('title').addEventListener('input', function() {
            document.getElementById('preview-title').textContent = this.value || 'Баннерын гарчиг';
        });
        
        document.getElementById('subtitle').addEventListener('input', function() {
            document.getElementById('preview-subtitle').textContent = this.value || 'Дэд гарчиг энд харагдана';
        });
        
        document.getElementById('button_text').addEventListener('input', function() {
            const btnDiv = document.getElementById('preview-button');
            const btnText = document.getElementById('preview-btn-text');
            
            if (this.value) {
                btnText.textContent = this.value;
                btnDiv.style.display = 'block';
            } else {
                btnDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>