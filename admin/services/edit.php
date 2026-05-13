<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Үйлчилгээ авах
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    $_SESSION['error'] = 'Үйлчилгээ олдсонгүй!';
    redirect(ADMIN_URL . 'services/');
}

// Шинэчлэх үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_desc']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    $image = $service['image']; // Хуучин зураг
    
    // Шинэ зураг upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $newImage = uploadFile($_FILES['image'], 'services');
        if ($newImage) {
            // Хуучин зураг устгах
            if ($image && file_exists(UPLOAD_PATH . $image)) {
                unlink(UPLOAD_PATH . $image);
            }
            $image = $newImage;
        } else {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        // Шинэчлэх
        $stmt = $conn->prepare("UPDATE services SET title = ?, short_desc = ?, description = ?, icon = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $short_desc, $description, $icon, $image, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Үйлчилгээ амжилттай шинэчлэгдлээ!';
            redirect(ADMIN_URL . 'services/');
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
    <title>Үйлчилгээ засах - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Үйлчилгээ засах</h4>
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
                                                   value="<?php echo htmlspecialchars($service['title']); ?>"
                                                   required>
                                        </div>
                                        
                                        <!-- Icon -->
                                        <div class="col-md-6">
                                            <label for="icon" class="form-label">
                                                Icon <small class="text-muted">(Bootstrap Icons)</small>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i id="icon-preview" class="<?php echo $service['icon'] ?: 'bi bi-briefcase'; ?>"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="icon" 
                                                       name="icon" 
                                                       value="<?php echo htmlspecialchars($service['icon']); ?>">
                                            </div>
                                            <small class="text-muted">
                                                <a href="https://icons.getbootstrap.com/" target="_blank">
                                                    Icon жагсаалт үзэх
                                                </a>
                                            </small>
                                        </div>
                                        
                                        <!-- Товч тайлбар -->
                                        <div class="col-12">
                                            <label for="short_desc" class="form-label">
                                                Товч тайлбар <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="short_desc" 
                                                   name="short_desc" 
                                                   value="<?php echo htmlspecialchars($service['short_desc']); ?>"
                                                   required
                                                   maxlength="255">
                                        </div>
                                        
                                        <!-- Дэлгэрэнгүй тайлбар -->
                                        <div class="col-12">
                                            <label for="description" class="form-label">
                                                Дэлгэрэнгүй тайлбар
                                            </label>
                                            <textarea class="form-control" 
                                                      id="description" 
                                                      name="description" 
                                                      rows="6"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                        </div>
                                        
                                        <!-- Зураг -->
                                        <div class="col-12">
                                            <label for="image" class="form-label">
                                                Зураг
                                            </label>
                                            
                                            <?php if($service['image']): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo UPLOAD_URL . $service['image']; ?>" 
                                                         alt="Current image" 
                                                         class="img-thumbnail"
                                                         style="max-height: 150px;">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Одоогийн зураг</small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="image" 
                                                   name="image"
                                                   accept="image/*">
                                            <small class="text-muted">
                                                Шинэ зураг оруулахгүй бол хуучин нь хадгалагдана
                                            </small>
                                        </div>
                                        
                                        <!-- Preview -->
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <i class="bi bi-eye"></i> Урьдчилан харах
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <i id="preview-icon" class="<?php echo $service['icon'] ?: 'bi bi-briefcase'; ?> fs-1 text-primary mb-3"></i>
                                                        <h5 id="preview-title"><?php echo $service['title']; ?></h5>
                                                        <p id="preview-short" class="text-muted"><?php echo $service['short_desc']; ?></p>
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
                                            <a href="delete.php?id=<?php echo $service['id']; ?>" 
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
        // Icon preview
        document.getElementById('icon').addEventListener('input', function() {
            const iconClass = this.value || 'bi bi-briefcase';
            document.getElementById('icon-preview').className = iconClass;
            document.getElementById('preview-icon').className = iconClass + ' fs-1 text-primary mb-3';
        });
        
        // Title preview
        document.getElementById('title').addEventListener('input', function() {
            document.getElementById('preview-title').textContent = this.value || 'Үйлчилгээний нэр';
        });
        
        // Short desc preview
        document.getElementById('short_desc').addEventListener('input', function() {
            document.getElementById('preview-short').textContent = this.value || 'Товч тайлбар энд харагдана';
        });
    </script>
</body>
</html>