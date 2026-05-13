<?php
require_once '../../config.php';
checkAdminLogin();

$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

$menusQuery = "SELECT * FROM menus ORDER BY order_num ASC, id ASC";
$menus = $conn->query($menusQuery);
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Цэс - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Навигацийн цэс</h4>
                    </div>
                    <div>
                        <a href="add.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Шинэ цэс
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
                        <?php if($menus->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">ID</th>
                                            <th width="50" class="text-center">
                                                <i class="bi bi-sort-numeric-down"></i> Эрэмбэ
                                            </th>
                                            <th>Цэсний нэр</th>
                                            <th>URL хаяг</th>
                                            <th width="100" class="text-center">Төлөв</th>
                                            <th width="150" class="text-center">Үйлдэл</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($menu = $menus->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $menu['id']; ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?php echo $menu['order_num']; ?></span>
                                                </td>
                                                <td>
                                                    <strong>
                                                        <i class="bi bi-list"></i> <?php echo $menu['name']; ?>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <code><?php echo $menu['url']; ?></code>
                                                    <br>
                                                    <a href="<?php echo BASE_URL . $menu['url']; ?>" 
                                                       target="_blank" 
                                                       class="small text-decoration-none">
                                                        <i class="bi bi-box-arrow-up-right"></i> Холбоос үзэх
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($menu['parent_id']): ?>
                                                        <span class="badge bg-info">Дэд цэс</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary">Үндсэн</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="edit.php?id=<?php echo $menu['id']; ?>" 
                                                           class="btn btn-outline-primary"
                                                           title="Засах">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?php echo $menu['id']; ?>" 
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
                                <i class="bi bi-list-ul fs-1 text-muted d-block mb-3"></i>
                                <h5>Цэс байхгүй байна</h5>
                                <p class="text-muted">Навигацийн цэсний эхний элементийг нэмнэ үү</p>
                                <a href="add.php" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Цэс нэмэх
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Info card -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle text-primary"></i> Цэсний тухай</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="small text-muted mb-2"><strong>Эрэмбэ (Order):</strong></p>
                                <ul class="small text-muted mb-3">
                                    <li>0, 1, 2... гэх мэтээр дугаарлана</li>
                                    <li>Бага тоо өмнө харагдана</li>
                                    <li>Ижил тоо байвал ID-гаар эрэмбэлэгдэнэ</li>
                                </ul>
                                
                                <p class="small text-muted mb-2"><strong>URL хаяг:</strong></p>
                                <ul class="small text-muted mb-0">
                                    <li>Дотоод хуудас: <code>index.php?page=about</code></li>
                                    <li>Гадны холбоос: <code>https://example.com</code></li>
                                    <li>Хуудасны slug: <code>index.php?page=page&slug=about-us</code></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <p class="small text-muted mb-2"><strong>Жишээ цэсүүд:</strong></p>
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <table class="table table-sm mb-0 small">
                                            <tr>
                                                <td>Нүүр</td>
                                                <td><code>./</code></td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>Бидний тухай</td>
                                                <td><code>index.php?page=page&slug=about</code></td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>Үйлчилгээ</td>
                                                <td><code>index.php?page=services</code></td>
                                                <td>2</td>
                                            </tr>
                                            <tr>
                                                <td>Холбоо барих</td>
                                                <td><code>index.php?page=contact</code></td>
                                                <td>5</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>