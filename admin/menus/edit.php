<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();

if (!$menu) {
    $_SESSION['error'] = 'Цэс олдсонгүй!';
    redirect(ADMIN_URL . 'menus/');
}

// Parent цэсүүдийг авах (өөрөөсөө бусад)
$parentMenus = $conn->query("SELECT * FROM menus WHERE parent_id IS NULL AND id != $id ORDER BY order_num ASC");

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
        $stmt = $conn->prepare("UPDATE menus SET name = ?, url = ?, parent_id = ?, order_num = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $name, $url, $parent_id, $order_num, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Цэс амжилттай шинэчлэгдлээ!';
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
    <title>Цэс засах - Admin</title>
    
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
                        <h4 class="d-inline mb-0">Цэс засах</h4>
                    </div>
                    <div>
                        <a href="<?php echo BASE_URL . $menu['url']; ?>" 
                           target="_blank"
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-box-arrow-up-right"></i> Холбоос үзэх
                        </a>
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
                                                   value="<?php echo htmlspecialchars($menu['name']); ?>"
                                                   required>
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
                                                   value="<?php echo htmlspecialchars($menu['url']); ?>"
                                                   required>
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
                                                   value="<?php echo $menu['order_num']; ?>"
                                                   min="0">
                                        </div>
                                        
                                        <!-- Parent цэс -->
                                        <div class="col-md-6">
                                            <label for="parent_id" class="form-label">
                                                Parent цэс (сонголттой)
                                            </label>
                                            <select class="form-select" id="parent_id" name="parent_id">
                                                <option value="">-- Үндсэн цэс --</option>
                                                <?php while($parent = $parentMenus->fetch_assoc()): ?>
                                                    <option value="<?php echo $parent['id']; ?>"
                                                            <?php echo ($menu['parent_id'] == $parent['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $parent['name']; ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
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
                                                                        <strong id="preview-name"><?php echo $menu['name']; ?></strong>
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
                                                        <code id="preview-url"><?php echo $menu['url']; ?></code>
                                                    </small>
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
                                            <a href="delete.php?id=<?php echo $menu['id']; ?>" 
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