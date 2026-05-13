<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uploadFile($_FILES['image'], 'portfolio');
        if (!$image) $error = 'Зураг upload амжилтгүй!';
    }

    if ($title === '') $error = 'Гарчиг оруулна уу.';

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO portfolio (title, description, image, category, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $image, $category, $link);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Portfolio item амжилттай нэмэгдлээ.';
            redirect(ADMIN_URL . 'portfolio/');
        } else {
            $error = 'Алдаа: ' . $conn->error;
        }
    }
}
?>
<!doctype html>
<html lang="mn">
<head>
  <meta charset="utf-8">
  <title>Portfolio нэмэх - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">Буцах</a>
    <h3 class="d-inline ms-3">Portfolio нэмэх</h3>
  </div>

  <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Гарчиг <span class="text-danger">*</span></label>
          <input name="title" class="form-control" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Зураг</label>
          <input type="file" name="image" class="form-control">
          <small class="text-muted">PNG,JPG,GIF,WEBP</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Ангилал</label>
          <input name="category" class="form-control" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Холбоос (URL)</label>
          <input name="link" class="form-control" value="<?php echo htmlspecialchars($_POST['link'] ?? ''); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Тайлбар</label>
          <textarea name="description" rows="6" class="form-control"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Хадгалах</button>
          <a class="btn btn-secondary" href="index.php">Цуцлах</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>