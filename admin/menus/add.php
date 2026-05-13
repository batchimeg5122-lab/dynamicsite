<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

// Parent цэсүүдийг авах
$parentMenus = $conn->query("SELECT * FROM menus WHERE parent_id IS NULL ORDER BY order_num ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $url = trim($_POST['url']);
    $order_num = (int)$_POST['order_num'];
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    
    if (empty($name)) {
        $error = 'Цэсний нэр оруулна уу!';
    } elseif (empty($url)) {
        $error = 'URL хаяг оруулна уу!';
    } else {
        $stmt = $conn->prepare("INSERT INTO menus (name, url, parent_id, order_num) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $name, $url, $parent_id, $order_num);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Цэс амжилттай нэмэгдлээ!';
            redirect(ADMIN_URL . 'menus/');
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
    <title>Цэс нэмэх - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Шинэ цэс нэмэх</h4>
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
                                
                                <form method="POST">
                                    <div class="row g-3">
                                        <!-- Цэсний нэр -->
                                        <div class="col-12">
                                            <label for="name" class="form-label">
                                                Цэсний нэр <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-lg" 
                                                   id="name" 
                                                   name="name" 
                                                   required
                                                   placeholder="Жишээ: Бидний тухай"
                                                   autofocus>
                                            <small class="text-muted">
                                                Навигацийн цэс дээр харагдах нэр
                                            </small>
                                        </div>
                                        
                                        <!-- URL хаяг -->
                                        <div class="col-12">
                                            <label for="url" class="form-label">
                                                URL хаяг <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="url" 
                                                   name="url" 
                                                   required
                                                   placeholder="index.php?page=about">
                                            <small class="text-muted">
                                                Дотоод хуудас эсвэл гадны холбоос
                                            </small>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="card bg-light">
                                                <div class="card-body py-2">
                                                    <p class="small mb-2"><strong>URL жишээнүүд:</strong></p>
                                                    <ul class="small mb-0">
                                                        <li>Нүүр хуудас: <code>./</code></li>
                                                        <li>Динамик хуудас: <code>index.php?page=page&slug=about-us</code></li>
                                                        <li>Үйлчилгээ: <code>index.php?page=services</code></li>
                                                        <li>Мэдээ: <code>index.php?page=news</code></li>
                                                        <li>Холбоо барих: <code>index.php?page=contact</code></li>
                                                        <li>Гадны холбоос: <code>https://example.com</code></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12"><hr></div>
                                        
                                        <!-- Эрэмбэ -->
                                        <div class="col-md-6">
                                            <label for="order_num" class="form-label">
                                                <i class="bi bi-sort-numeric-down"></i> Эрэмбэ
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="order_num" 
                                                   name="order_num" 
                                                   value="0"
                                                   min="0">
                                            <small class="text-muted">
                                                0 = эхэнд, их тоо = сүүлд харагдана
                                            </small>
                                        </div>
                                        
                                        <!-- Parent цэс (Дэд цэс үүсгэх) -->
                                        <div class="col-md-6">
                                            <label for="parent_id" class="form-label">
                                                Parent цэс (сонголттой)
                                            </label>
                                            <select class="form-select" id="parent_id" name="parent_id">
                                                <option value="">-- Үндсэн цэс --</option>
                                                <?php while($parent = $parentMenus->fetch_assoc()): ?>
                                                    <option value="<?php echo $parent['id']; ?>">
                                                        <?php echo $parent['name']; ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                            <small class="text-muted">
                                                Дэд цэс үүсгэх бол сонгоно
                                            </small>
                                        </div>
                                        
                                        <!-- Preview -->
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <i class="bi bi-eye"></i> Урьдчилан харах
                                                </div>
                                                <div class="card-body">
                                                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                                        <div class="container-fluid">
                                                            <span class="navbar-brand">Лого</span>
                                                            <ul class="navbar-nav">
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#">Нүүр</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" href="#" id="preview-menu">
                                                                        <strong id="preview-name">Цэсний нэр</strong>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#">Холбоо барих</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </nav>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="bi bi-link"></i> URL: 
                                                        <code id="preview-url">index.php?page=...</code>
                                                    </small>
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
        // Live preview
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value || 'Цэсний нэр';
        });
        
        document.getElementById('url').addEventListener('input', function() {
            document.getElementById('preview-url').textContent = this.value || 'index.php?page=...';
        });
    </script>
</body>
</html>