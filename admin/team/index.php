<?php
require_once '../../config.php';
checkAdminLogin();

$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

$teamQuery = "SELECT * FROM team ORDER BY id DESC";
$team = $conn->query($teamQuery);
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Багийн гишүүд - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="../dashboard.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0">Багийн гишүүд</h4>
                    </div>
                    <div>
                        <a href="add.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Шинэ гишүүн
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
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
                        
                        <?php if($team->num_rows > 0): ?>
                            <div class="row g-4">
                                <?php while($member = $team->fetch_assoc()): ?>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="card h-100">
                                            <?php if($member['photo']): ?>
                                                <img src="<?php echo UPLOAD_URL . $member['photo']; ?>" 
                                                     class="card-img-top" 
                                                     alt="<?php echo $member['name']; ?>"
                                                     style="height: 250px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><?php echo $member['name']; ?></h5>
                                                <p class="text-muted small"><?php echo $member['position']; ?></p>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="edit.php?id=<?php echo $member['id']; ?>" 
                                                       class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="delete.php?id=<?php echo $member['id']; ?>" 
                                                       class="btn btn-outline-danger"
                                                       onclick="return confirm('Устгахдаа итгэлтэй байна уу?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
                                <h5>Багийн гишүүн байхгүй байна</h5>
                                <a href="add.php" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Гишүүн нэмэх
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