<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM team WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    $_SESSION['error'] = 'Багийн гишүүн олдсонгүй!';
    redirect(ADMIN_URL . 'team/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $bio = trim($_POST['bio']);
    $facebook = trim($_POST['facebook']);
    $linkedin = trim($_POST['linkedin']);
    $photo = $member['photo'];
    
    // Шинэ зураг upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $newPhoto = uploadFile($_FILES['photo'], 'team');
        if ($newPhoto) {
            if ($photo && file_exists(UPLOAD_PATH . $photo)) {
                unlink(UPLOAD_PATH . $photo);
            }
            $photo = $newPhoto;
        } else {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE team SET name = ?, position = ?, bio = ?, photo = ?, facebook = ?, linkedin = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $position, $bio, $photo, $facebook, $linkedin, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Мэдээлэл амжилттай шинэчлэгдлээ!';
            redirect(ADMIN_URL . 'team/');
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
    <title>Багийн гишүүн засах - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Багийн гишүүн засах</h4>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <?php if($error): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row g-3">
                                        <!-- Нэр -->
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">
                                                Нэр <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="name" 
                                                   name="name" 
                                                   value="<?php echo htmlspecialchars($member['name']); ?>"
                                                   required>
                                        </div>
                                        
                                        <!-- Албан тушаал -->
                                        <div class="col-md-6">
                                            <label for="position" class="form-label">
                                                Албан тушаал <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="position" 
                                                   name="position" 
                                                   value="<?php echo htmlspecialchars($member['position']); ?>"
                                                   required>
                                        </div>
                                        
                                        <!-- Зураг -->
                                        <div class="col-12">
                                            <label for="photo" class="form-label">
                                                Зураг
                                            </label>
                                            
                                            <?php if($member['photo']): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo UPLOAD_URL . $member['photo']; ?>" 
                                                         alt="<?php echo $member['name']; ?>" 
                                                         class="img-thumbnail"
                                                         style="height: 200px; width: 200px; object-fit: cover;">
                                                    <div class="mt-1">
                                                        <small class="text-muted">Одоогийн зураг</small>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="photo" 
                                                   name="photo"
                                                   accept="image/*"
                                                   onchange="previewImage(this)">
                                            <small class="text-muted">
                                                Шинэ зураг оруулахгүй бол хуучин нь хадгалагдана. Санал: 400x400px (square)
                                            </small>
                                            
                                            <!-- New image preview -->
                                            <div id="imagePreview" class="mt-3" style="display:none;">
                                                <p class="text-success small">
                                                    <i class="bi bi-check-circle"></i> Шинэ зураг:
                                                </p>
                                                <img id="preview" 
                                                     src="" 
                                                     alt="New preview" 
                                                     class="img-thumbnail" 
                                                     style="height: 200px; width: 200px; object-fit: cover;">
                                            </div>
                                        </div>
                                        
                                        <!-- Танилцуулга -->
                                        <div class="col-12">
                                            <label for="bio" class="form-label">
                                                Товч танилцуулга
                                            </label>
                                            <textarea class="form-control" 
                                                      id="bio" 
                                                      name="bio" 
                                                      rows="4"
                                                      placeholder="Ажлын туршлага, мэргэжил, боловсрол..."><?php echo htmlspecialchars($member['bio']); ?></textarea>
                                            <small class="text-muted">
                                                Гишүүний товч танилцуулга, туршлага
                                            </small>
                                        </div>
                                        
                                        <div class="col-12"><hr></div>
                                        
                                        <!-- Сошиал холбоосууд -->
                                        <div class="col-12">
                                            <h5><i class="bi bi-share"></i> Сошиал холбоосууд</h5>
                                            <p class="text-muted small">Сонголттой. Хоосон орхиж болно.</p>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="facebook" class="form-label">
                                                <i class="bi bi-facebook text-primary"></i> Facebook
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="facebook" 
                                                   name="facebook" 
                                                   value="<?php echo htmlspecialchars($member['facebook']); ?>"
                                                   placeholder="https://facebook.com/username">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="linkedin" class="form-label">
                                                <i class="bi bi-linkedin text-primary"></i> LinkedIn
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="linkedin" 
                                                   name="linkedin" 
                                                   value="<?php echo htmlspecialchars($member['linkedin']); ?>"
                                                   placeholder="https://linkedin.com/in/username">
                                        </div>
                                        
                                        <!-- Preview Card -->
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <i class="bi bi-eye"></i> Урьдчилан харах
                                                </div>
                                                <div class="card-body text-center">
                                                    <div class="mb-3">
                                                        <?php if($member['photo']): ?>
                                                            <img src="<?php echo UPLOAD_URL . $member['photo']; ?>" 
                                                                 id="preview-img"
                                                                 class="rounded-circle mb-2" 
                                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div id="preview-img" 
                                                                 class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2" 
                                                                 style="width: 120px; height: 120px;">
                                                                <i class="bi bi-person fs-1 text-white"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h5 id="preview-name" class="mb-1"><?php echo $member['name']; ?></h5>
                                                    <p id="preview-position" class="text-muted small mb-3"><?php echo $member['position']; ?></p>
                                                    <p id="preview-bio" class="small text-muted">
                                                        <?php echo $member['bio'] ? mb_substr($member['bio'], 0, 100, 'UTF-8') . '...' : 'Танилцуулга...'; ?>
                                                    </p>
                                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                                        <span class="text-primary"><i class="bi bi-facebook"></i></span>
                                                        <span class="text-primary"><i class="bi bi-linkedin"></i></span>
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
                                            <a href="delete.php?id=<?php echo $member['id']; ?>" 
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
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value || 'Нэр';
        });
        
        document.getElementById('position').addEventListener('input', function() {
            document.getElementById('preview-position').textContent = this.value || 'Албан тушаал';
        });
        
        document.getElementById('bio').addEventListener('input', function() {
            const text = this.value || 'Танилцуулга...';
            document.getElementById('preview-bio').textContent = text.substring(0, 100) + (text.length > 100 ? '...' : '');
        });
    </script>
</body>
</html>