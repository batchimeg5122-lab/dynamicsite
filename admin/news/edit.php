<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if (!$news) {
    $_SESSION['error'] = 'Мэдээ олдсонгүй!';
    redirect(ADMIN_URL . 'news/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $news['image'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $newImage = uploadFile($_FILES['image'], 'news');
        if ($newImage) {
            if ($image && file_exists(UPLOAD_PATH . $image)) {
                unlink(UPLOAD_PATH . $image);
            }
            $image = $newImage;
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $image, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Мэдээ амжилттай шинэчлэгдлээ!';
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
    <title>Мэдээ засах - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0">Мэдээ засах</h4>
                    </div>
                </div>
            </div>
            
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
                                        <div class="col-12">
                                            <label for="title" class="form-label">Гарчиг <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                                   value="<?php echo htmlspecialchars($news['title']); ?>" required>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="image" class="form-label">Зураг</label>
                                            <?php if($news['image']): ?>
                                                <div class="mb-2">
                                                    <img src="<?php echo UPLOAD_URL . $news['image']; ?>" 
                                                         alt="Current" class="img-thumbnail" style="max-height: 150px;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                            <div id="imagePreview" class="mt-3" style="display:none;">
                                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="content" class="form-label">Агуулга <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="content" name="content" rows="20" required><?php echo htmlspecialchars($news['content']); ?></textarea>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle"></i> <strong>Үүсгэсэн:</strong> 
                                                <?php echo date('Y-m-d H:i:s', strtotime($news['created_at'])); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <hr>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="bi bi-check-circle"></i> Шинэчлэх
                                            </button>
                                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                                <i class="bi bi-x-circle"></i> Цуцлах
                                            </a>
                                            <a href="delete.php?id=<?php echo $news['id']; ?>" 
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
    </script>
</body>
</html>