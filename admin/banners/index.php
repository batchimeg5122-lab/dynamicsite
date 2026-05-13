<?php
require_once '../../config.php';
checkAdminLogin();

$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

$bannersQuery = "SELECT * FROM banners ORDER BY id DESC";
$banners = $conn->query($bannersQuery);
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Баннер - Admin</title>
    
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
                        <a href="../dashboard.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0">Баннер / Hero Slider</h4>
                    </div>
                    <div>
                        <a href="add.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Шинэ баннер
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Messages -->
                        <?php if($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Table -->
                        <?php if($banners->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">ID</th>
                                            <th>Зураг</th>
                                            <th>Гарчиг</th>
                                            <th>Дэд гарчиг</th>
                                            <th>Товч</th>
                                            <th width="150" class="text-center">Үйлдэл</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($banner = $banners->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $banner['id']; ?></td>
                                                <td>
                                                    <?php if($banner['image']): ?>
                                                        <img src="<?php echo UPLOAD_URL . $banner['image']; ?>" 
                                                             alt="<?php echo $banner['title']; ?>"
                                                             class="img-thumbnail"
                                                             style="height: 60px; width: 120px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <span class="text-muted">Байхгүй</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo $banner['title']; ?></strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo $banner['subtitle'] ? mb_substr($banner['subtitle'], 0, 50, 'UTF-8') . '...' : '-'; ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if($banner['button_text']): ?>
                                                        <span class="badge bg-primary">
                                                            <?php echo $banner['button_text']; ?>
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-link-45deg"></i> 
                                                            <?php echo mb_substr($banner['button_link'], 0, 30, 'UTF-8'); ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="edit.php?id=<?php echo $banner['id']; ?>" 
                                                           class="btn btn-outline-primary"
                                                           title="Засах">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?php echo $banner['id']; ?>" 
                                                           class="btn btn-outline-danger"
                                                           onclick="return confirm('Устгахдаа итгэлтэй байна уу?')"
                                                           title="Устгах">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-image fs-1 text-muted d-block mb-3"></i>
                                <h5>Баннер байхгүй байна</h5>
                                <p class="text-muted">Нүүр хуудасны Hero Slider-т харагдах баннер нэмнэ үү</p>
                                <a href="add.php" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Баннер нэмэх
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Info card -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle text-primary"></i> Баннерын тухай</h6>
                        <ul class="mb-0 small text-muted">
                            <li>Баннер зураг: 1920x600px эсвэл 16:9 харьцаатай байхыг зөвлөж байна</li>
                            <li>Carousel slider: Нүүр хуудасны дээд хэсэгт автоматаар эргэдэг</li>
                            <li>Товчны холбоос: Хуудас эсвэл гадны холбоос байж болно</li>
                            <li>JPG, PNG форматаар хадгална</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>