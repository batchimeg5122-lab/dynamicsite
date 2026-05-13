<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Хуудас авах
$stmt = $conn->prepare("SELECT * FROM pages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();

if (!$page) {
    $_SESSION['error'] = 'Хуудас олдсонгүй!';
    redirect(ADMIN_URL . 'pages/');
}

// Шинэчлэх үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = trim($_POST['content']);
    $coverImage = $page['cover_image']; // Хуучин зураг
    
    // Шинэ зураг upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $newImage = uploadFile($_FILES['cover_image'], 'pages');
        if ($newImage) {
            // Хуучин зураг устгах
            if ($coverImage && file_exists(UPLOAD_PATH . $coverImage)) {
                unlink(UPLOAD_PATH . $coverImage);
            }
            $coverImage = $newImage;
        } else {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        // Slug давхардсан эсэх шалгах (өөрийнхөөс бусад)
        $checkSlug = $conn->prepare("SELECT id FROM pages WHERE slug = ? AND id != ?");
        $checkSlug->bind_param("si", $slug, $id);
        $checkSlug->execute();
        $checkResult = $checkSlug->get_result();
        
        if ($checkResult->num_rows > 0) {
            $error = 'Энэ slug аль хэдийн ашиглагдаж байна!';
        } else {
            // Шинэчлэх
            $stmt = $conn->prepare("UPDATE pages SET title = ?, slug = ?, content = ?, cover_image = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $title, $slug, $content, $coverImage, $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Хуудас амжилттай шинэчлэгдлээ!';
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
    <title>Хуудас засах - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Хуудас засах</h4>
                    </div>
                    <div>
                        <a href="<?php echo BASE_URL; ?>index.php?page=page&slug=<?php echo $page['slug']; ?>" 
                           target="_blank"
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> Үзэх
                        </a>
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
                                                   value="<?php echo htmlspecialchars($page['title']); ?>"
                                                   required>
                                        </div>
                                        
                                        <!-- Slug -->
                                        <div class="col-md-6">
                                            <label for="slug" class="form-label">
                                                Slug <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="slug" 
                                                   name="slug" 
                                                   value="<?php echo htmlspecialchars($page['slug']); ?>"
                                                   required>
                                            <small class="text-muted">
                                                URL: <?php echo BASE_URL; ?>page/<strong id="slug-preview"><?php echo $page['slug']; ?></strong>
                                            </small>
                                        </div>
                                        
                                        <!-- Cover зураг -->
                                        <div class="col-12">
                                            <label for="cover_image" class="form-label">
                                                Cover зураг
                                            </label>
                                            
                                            <?php if($page['cover_image']): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo UPLOAD_URL . $page['cover_image']; ?>" 
                                                         alt="Current cover" 
                                                         class="img-thumbnail"
                                                         style="max-height: 150px;">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Одоогийн зураг</small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="cover_image" 
                                                   name="cover_image"
                                                   accept="image/*">
                                            <small class="text-muted">
                                                Шинэ зураг оруулахгүй бол хуучин нь хадгалагдана
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
                                                      required><?php echo htmlspecialchars($page['content']); ?></textarea>
                                            <small class="text-muted">
                                                HTML код ашиглаж болно
                                            </small>
                                        </div>
                                        
                                        <!-- Мэдээлэл -->
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle"></i>
                                                <strong>Сүүлд шинэчлэгдсэн:</strong> 
                                                <?php echo date('Y-m-d H:i:s', strtotime($page['updated_at'])); ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Товчнууд -->
                                        <div class="col-12">
                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-check-circle"></i> Шинэчлэх
                                            </button>
                                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                                <i class="bi bi-x-circle"></i> Цуцлах
                                            </a>
                                            <a href="delete.php?id=<?php echo $page['id']; ?>" 
                                               class="btn btn-outline-danger btn-lg float-end"
                                               onclick="return confirm('Устгахдаа итгэлтэй байна уу?')">
                                                <i class="bi bi-trash"></i> Устгах
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
        // Slug preview шинэчлэх
        document.getElementById('slug').addEventListener('input', function() {
            document.getElementById('slug-preview').textContent = this.value || 'slug-here';
        });
    </script>
</body>
</html>