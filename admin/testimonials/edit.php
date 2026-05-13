<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'testimonials/');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $newPhoto = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $newPhoto = uploadFile($_FILES['photo'], 'testimonials');
        if (!$newPhoto) $error = 'Зураг upload амжилтгүй!';
    }

    if ($author === '') $error = 'Нэр оруулна уу.';

    if (empty($error)) {
        if ($newPhoto) {
            $old = $conn->query("SELECT photo FROM testimonials WHERE id = $id")->fetch_assoc();
            if ($old && !empty($old['photo']) && file_exists(UPLOAD_PATH . $old['photo'])) {
                @unlink(UPLOAD_PATH . $old['photo']);
            }
            $stmt = $conn->prepare("UPDATE testimonials SET author=?, content=?, photo=?, is_featured=? WHERE id=?");
            $stmt->bind_param("sssii", $author, $content, $newPhoto, $is_featured, $id);
        } else {
            $stmt = $conn->prepare("UPDATE testimonials SET author=?, content=?, is_featured=? WHERE id=?");
            $stmt->bind_param("ssii", $author, $content, $is_featured, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Testimonial амжилттай шинэчлэгдлээ.';
            redirect(ADMIN_URL . 'testimonials/');
        } else {
            $error = 'Алдаа: ' . $conn->error;
        }
    }
}

$row = $conn->query("SELECT * FROM testimonials WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'testimonials/');
?>
<!doctype html>
<html lang="mn">
<head>
  <meta charset="utf-8">
  <title>Testimonial засварлах - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">Буцах</a>
    <h3 class="d-inline ms-3">Testimonial засварлах</h3>
  </div>

  <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Нэр <span class="text-danger">*</span></label>
          <input name="author" class="form-control" required value="<?php echo htmlspecialchars($row['author']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Агуулга</label>
          <textarea name="content" rows="6" class="form-control"><?php echo htmlspecialchars($row['content']); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Одоогийн зураг</label><br>
          <?php if($row['photo']): ?>
            <img src="<?php echo UPLOAD_URL . $row['photo']; ?>" style="max-height:120px" alt=""><br><br>
          <?php endif; ?>
          <label class="form-label">Шинэ зураг (сонголттой)</label>
          <input type="file" name="photo" class="form-control">
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" <?php echo $row['is_featured'] ? 'checked' : ''; ?>>
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