<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM banners WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

if (!$banner) {
    $_SESSION['error'] = 'Баннер олдсонгүй!';
    redirect(ADMIN_URL . 'banners/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $button_text = trim($_POST['button_text']);
    $button_link = trim($_POST['button_link']);
    $image = $banner['image'];
    
    // Шинэ зураг upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $newImage = uploadFile($_FILES['image'], 'banners');
        if ($newImage) {
            if ($image && file_exists(UPLOAD_PATH . $image)) {
                unlink(UPLOAD_PATH . $image);
            }
            $image = $newImage;
        } else {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE banners SET title = ?, subtitle = ?, image = ?, button_text = ?, button_link = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $subtitle, $image, $button_text, $button_link, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Баннер амжилттай шинэчлэгдлээ!';
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
    <title>Баннер засах - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Баннер засах</h4>
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
                                                   value="<?php echo htmlspecialchars($banner['title']); ?>"
                                                   required>
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
                                                   value="<?php echo htmlspecialchars($banner['subtitle']); ?>">
                                        </div>
                                        
                                        <!-- Зураг -->
                                        <div class="col-12">
                                            <label for="image" class="form-label">
                                                Баннер зураг
                                            </label>
                                            
                                            <?php if($banner['image']): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo UPLOAD_URL . $banner['image']; ?>" 
                                                         alt="Current banner" 
                                                         class="img-thumbnail w-100"
                                                         style="max-height: 200px; object-fit: cover;">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Одоогийн зураг</small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="image" 
                                                   name="image"
                                                   accept="image/*"
                                                   onchange="previewImage(this)">
                                            <small class="text-muted">
                                                Шинэ зураг оруулахгүй бол хуучин нь хадгалагдана. Санал: 1920x600px
                                            </small>
                                            
                                            <!-- New image preview -->
                                            <div id="imagePreview" class="mt-3" style="display:none;">
                                                <p class="text-success small"><i class="bi bi-check-circle"></i> Шинэ зураг:</p>
                                                <img id="preview" src="" alt="New preview" class="img-thumbnail w-100" style="max-height: 200px; object-fit: cover;">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12"><hr></div>
                                        
                                        <!-- Товчны мэдээлэл -->
                                        <div class="col-12">
                                            <h5><i class="bi bi-cursor"></i> Товчны тохиргоо</h5>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="button_text" class="form-label">
                                                Товчны текст
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="button_text" 
                                                   name="button_text" 
                                                   value="<?php echo htmlspecialchars($banner['button_text']); ?>">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="button_link" class="form-label">
                                                Товчны холбоос
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="button_link" 
                                                   name="button_link" 
                                                   value="<?php echo htmlspecialchars($banner['button_link']); ?>">
                                        </div>
                                        
                                        <!-- Preview -->
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <i class="bi bi-eye"></i> Урьдчилан харах
                                                </div>
                                                <div class="card-body bg-dark text-white text-center" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                                    <div>
                                                        <h1 id="preview-title" class="display-4 fw-bold mb-3"><?php echo $banner['title']; ?></h1>
                                                        <p id="preview-subtitle" class="lead mb-4"><?php echo $banner['subtitle'] ?: 'Дэд гарчиг'; ?></p>
                                                        <div id="preview-button" style="display:<?php echo $banner['button_text'] ? 'block' : 'none'; ?>;">
                                                            <span class="btn btn-primary btn-lg" id="preview-btn-text"><?php echo $banner['button_text'] ?: 'Товч'; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
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
                                            <a href="delete.php?id=<?php echo $banner['id']; ?>" 
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
            document.getElementById('preview-subtitle').textContent = this.value || 'Дэд гарчиг';
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