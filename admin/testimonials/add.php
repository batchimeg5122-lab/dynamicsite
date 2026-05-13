<?php
require_once '../../config.php';
checkAdminLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $photo = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = uploadFile($_FILES['photo'], 'testimonials');
        if (!$photo) $error = 'Зураг upload амжилтгүй!';
    }

    if ($author === '') $error = 'Нэр оруулна уу.';

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO testimonials (author, content, photo, is_featured) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $author, $content, $photo, $is_featured);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Testimonial амжилттай нэмэгдлээ.';
            redirect(ADMIN_URL . 'testimonials/');
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
  <title>Testimonial нэмэх - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">Буцах</a>
    <h3 class="d-inline ms-3">Testimonial нэмэх</h3>
  </div>

  <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Нэр <span class="text-danger">*</span></label>
          <input name="author" class="form-control" required value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Агуулга</label>
          <textarea name="content" rows="6" class="form-control"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Зураг</label>
          <input type="file" name="photo" class="form-control">
          <small class="text-muted">PNG,JPG,GIF,WEBP</small>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
          <label class="form-check-label" for="is_featured">Онцлох</label>
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