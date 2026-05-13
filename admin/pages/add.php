<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

// Хадгалах үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = trim($_POST['content']);
    
    // Slug автоматаар үүсгэх (хоосон бол)
    if (empty($slug)) {
        $slug = strtolower(str_replace(' ', '-', $title));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
    }
    
    // Зураг upload
    $coverImage = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $coverImage = uploadFile($_FILES['cover_image'], 'pages');
        if (!$coverImage) {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        // Slug давхардсан эсэх шалгах
        $checkSlug = $conn->prepare("SELECT id FROM pages WHERE slug = ?");
        $checkSlug->bind_param("s", $slug);
        $checkSlug->execute();
        $checkResult = $checkSlug->get_result();
        
        if ($checkResult->num_rows > 0) {
            $error = 'Энэ slug аль хэдийн ашиглагдаж байна!';
        } else {
            // Хадгалах
            $stmt = $conn->prepare("INSERT INTO pages (title, slug, content, cover_image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $slug, $content, $coverImage);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Хуудас амжилттай нэмэгдлээ!';
                redirect(ADMIN_URL . 'pages/');
            } else {
                $error = 'Алдаа гарлаа: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Хуудас нэмэх - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Шинэ хуудас нэмэх</h4>
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
                                        <div class="col-md-6">
                                            <label for="title" class="form-label">
                                                Гарчиг <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="title" 
                                                   name="title" 
                                                   required
                                                   placeholder="Хуудасны гарчиг">
                                        </div>
                                        
                                        <!-- Slug -->
                                        <div class="col-md-6">
                                            <label for="slug" class="form-label">
                                                Slug <small class="text-muted">(автоматаар үүснэ)</small>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="slug" 
                                                   name="slug" 
                                                   placeholder="about-us">
                                            <small class="text-muted">
                                                URL: <?php echo BASE_URL; ?>page/<strong id="slug-preview">about-us</strong>
                                            </small>
                                        </div>
                                        
                                        <!-- Cover зураг -->
                                        <div class="col-12">
                                            <label for="cover_image" class="form-label">
                                                Cover зураг
                                            </label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="cover_image" 
                                                   name="cover_image"
                                                   accept="image/*">
                                            <small class="text-muted">
                                                PNG, JPG, GIF форматаар байх ёстой
                                            </small>
                                        </div>
                                        
                                        <!-- Агуулга -->
                                        <div class="col-12">
                                            <label for="content" class="form-label">
                                                Агуулга <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control" 
                                                      id="content" 
                                                      name="content" 
                                                      rows="15"
                                                      required
                                                      placeholder="Хуудасны агуулга..."></textarea>
                                            <small class="text-muted">
                                                HTML код ашиглаж болно
                                            </small>
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
        // Slug автомат үүсгэх
        document.getElementById('title').addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            
            document.getElementById('slug').value = slug;
            document.getElementById('slug-preview').textContent = slug || 'slug-here';
        });
        
        // Slug manual засах
        document.getElementById('slug').addEventListener('input', function() {
            document.getElementById('slug-preview').textContent = this.value || 'slug-here';
        });
        
        // Image preview
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    alert('Зураг сонгогдлоо: ' + file.name);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>