<?php
require_once '../../config.php';
checkAdminLogin();

// Success/Error мессеж
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success']);
unset($_SESSION['error']);

// Мэдээ авах
$newsQuery = "SELECT * FROM news ORDER BY created_at DESC";
$newsResult = $conn->query($newsQuery);
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мэдээ - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Мэдээ</h4>
                    </div>
                    <div>
                        <a href="add.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Шинэ мэдээ
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
                        <?php if($newsResult->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">ID</th>
                                            <th>Гарчиг</th>
                                            <th width="150">Зураг</th>
                                            <th width="150">Огноо</th>
                                            <th width="150" class="text-center">Үйлдэл</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($news = $newsResult->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $news['id']; ?></td>
                                                <td>
                                                    <strong><?php echo $news['title']; ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo mb_substr(strip_tags($news['content']), 0, 80, 'UTF-8'); ?>...
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if($news['image']): ?>
                                                        <img src="<?php echo UPLOAD_URL . $news['image']; ?>" 
                                                             alt="<?php echo $news['title']; ?>"
                                                             class="img-thumbnail"
                                                             style="height: 50px; width: 80px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <span class="text-muted">Байхгүй</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('Y-m-d', strtotime($news['created_at'])); ?>
                                                        <br>
                                                        <span class="text-muted">
                                                            <?php echo date('H:i', strtotime($news['created_at'])); ?>
                                                        </span>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $news['id']; ?>" 
                                                           target="_blank"
                                                           class="btn btn-outline-info"
                                                           title="Үзэх">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="edit.php?id=<?php echo $news['id']; ?>" 
                                                           class="btn btn-outline-primary"
                                                           title="Засах">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?php echo $news['id']; ?>" 
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
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <h5>Мэдээ байхгүй байна</h5>
                                <p class="text-muted">Эхний мэдээгээ нэмнэ үү</p>
                                <a href="add.php" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Мэдээ нэмэх
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>