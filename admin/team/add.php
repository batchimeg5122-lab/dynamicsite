<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $bio = trim($_POST['bio']);
    $facebook = trim($_POST['facebook']);
    $linkedin = trim($_POST['linkedin']);
    
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = uploadFile($_FILES['photo'], 'team');
        if (!$photo) {
            $error = 'Зураг upload амжилтгүй!';
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO team (name, position, bio, photo, facebook, linkedin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $position, $bio, $photo, $facebook, $linkedin);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Багийн гишүүн амжилттай нэмэгдлээ!';
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
    <title>Багийн гишүүн нэмэх - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-left"></i> Буцах
                </a>
                <h4 class="d-inline mb-0">Шинэ гишүүн нэмэх</h4>
            </div>
            
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
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Нэр <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Овог нэр">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="position" class="form-label">Албан тушаал <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="position" name="position" required placeholder="Жишээ: CEO">
                                </div>
                                
                                <div class="col-12">
                                    <label for="photo" class="form-label">Зураг</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <small class="text-muted">Санал: 400x400px, portrait зураг</small>
                                </div>
                                
                                <div class="col-12">
                                    <label for="bio" class="form-label">Товч танилцуулга</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Ажлын туршлага, мэргэжил..."></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/...">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/...">
                                </div>
                                
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>