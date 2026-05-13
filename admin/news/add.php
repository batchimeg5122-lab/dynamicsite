<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

// Хадгалах үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Зураг upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'news');
        if (!$image) {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        // Хадгалах
        $stmt = $conn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Мэдээ амжилттай нэмэгдлээ!';
            redirect(ADMIN_URL . 'news/');
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
    <title>Мэдээ нэмэх - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Шинэ мэдээ нэмэх</h4>
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
                                                   placeholder="Мэдээний гарчиг"
                                                   autofocus>
                                        </div>
                                        
                                        <!-- Зураг -->
                                        <div class="col-12">
                                            <label for="image" class="form-label">
                                                Зураг
                                            </label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="image" 
                                                   name="image"
                                                   accept="image/*"
                                                   onchange="previewImage(this)">
                                            <small class="text-muted">
                                                PNG, JPG, GIF форматаар байх ёстой. Санал: 1200x600px
                                            </small>
                                            
                                            <!-- Image preview -->
                                            <div id="imagePreview" class="mt-3" style="display:none;">
                                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                            </div>
                                        </div>
                                        
                                        <!-- Агуулга -->
                                        <div class="col-12">
                                            <label for="content" class="form-label">
                                                Агуулга <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" 
                                                      id="content" 
                                                      name="content" 
                                                      rows="20"
                                                      required
                                                      placeholder="Мэдээний агуулга..."></textarea>
                                            <small class="text-muted">
                                                HTML код ашиглаж болно. Мөр шилжихдээ Enter дарна уу.
                                            </small>
                                        </div>
                                        
                                        <!-- Зөвлөмж -->
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle"></i>
                                                <strong>Зөвлөмж:</strong> Мэдээний агуулга дээр зураг нэмэхийг хүсвэл HTML код ашиглана уу:
                                                <code>&lt;img src="URL" alt="Тайлбар" class="img-fluid"&gt;</code>
                                            </div>
                                        </div>
                                        
                                        <!-- Товчнууд -->
                                        <div class="col-12">
                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-check-circle"></i> Нийтлэх
                                            </button>
                                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                                <i class="bi bi-x-circle"></i> Цуцлах
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Форматчлалын зөвлөмж -->
                        <div class="card shadow-sm mt-3">
                            <div class="card-header bg-primary text-white">
                                <i class="bi bi-book"></i> HTML форматчлалын зөвлөмж
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Гарчиг:</h6>
                                        <code>&lt;h2&gt;Гарчиг&lt;/h2&gt;</code>
                                        
                                        <h6 class="mt-3">Өгүүлбэр:</h6>
                                        <code>&lt;p&gt;Текст&lt;/p&gt;</code>
                                        
                                        <h6 class="mt-3">Жагсаалт:</h6>
                                        <code>
                                            &lt;ul&gt;<br>
                                            &nbsp;&nbsp;&lt;li&gt;Нэг&lt;/li&gt;<br>
                                            &nbsp;&nbsp;&lt;li&gt;Хоёр&lt;/li&gt;<br>
                                            &lt;/ul&gt;
                                        </code>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Тод бичвэр:</h6>
                                        <code>&lt;strong&gt;Текст&lt;/strong&gt;</code>
                                        
                                        <h6 class="mt-3">Налуу бичвэр:</h6>
                                        <code>&lt;em&gt;Текст&lt;/em&gt;</code>
                                        
                                        <h6 class="mt-3">Холбоос:</h6>
                                        <code>&lt;a href="URL"&gt;Текст&lt;/a&gt;</code>
                                    </div>
                                </div>
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
        
        // Character counter
        const content = document.getElementById('content');
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        content.parentElement.appendChild(counter);
        
        function updateCounter() {
            const length = content.value.length;
            counter.textContent = `${length} тэмдэгт`;
        }
        
        content.addEventListener('input', updateCounter);
        updateCounter();
    </script>
</body>
</html>