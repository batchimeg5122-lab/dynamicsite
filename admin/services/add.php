<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

// Хадгалах үйлдэл
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_desc']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    
    // Зураг upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'services');
        if (!$image) {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        // Хадгалах
        $stmt = $conn->prepare("INSERT INTO services (title, short_desc, description, icon, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $short_desc, $description, $icon, $image);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Үйлчилгээ амжилттай нэмэгдлээ!';
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
    <title>Үйлчилгээ нэмэх - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Шинэ үйлчилгээ нэмэх</h4>
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
                                                   placeholder="Үйлчилгээний нэр">
                                        </div>
                                        
                                        <!-- Icon -->
                                        <div class="col-md-6">
                                            <label for="icon" class="form-label">
                                                Icon <small class="text-muted">(Bootstrap Icons)</small>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i id="icon-preview" class="bi bi-briefcase"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="icon" 
                                                       name="icon" 
                                                       placeholder="bi bi-briefcase"
                                                       value="bi bi-briefcase">
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
                                                   required
                                                   maxlength="255"
                                                   placeholder="Үйлчилгээний товч тайлбар (1-2 өгүүлбэр)">
                                        </div>
                                        
                                        <!-- Дэлгэрэнгүй тайлбар -->
                                        <div class="col-12">
                                            <label for="description" class="form-label">
                                                Дэлгэрэнгүй тайлбар
                                            </label>
                                            <textarea class="form-control" 
                                                      id="description" 
                                                      name="description" 
                                                      rows="6"
                                                      placeholder="Үйлчилгээний дэлгэрэнгүй мэдээлэл..."></textarea>
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
                                                   accept="image/*">
                                            <small class="text-muted">
                                                PNG, JPG, GIF форматаар байх ёстой. (Санал: 800x600px)
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
                                                        <i id="preview-icon" class="bi bi-briefcase fs-1 text-primary mb-3"></i>
                                                        <h5 id="preview-title">Үйлчилгээний нэр</h5>
                                                        <p id="preview-short" class="text-muted">Товч тайлбар энд харагдана</p>
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