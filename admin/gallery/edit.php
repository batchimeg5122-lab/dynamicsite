<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'gallery/');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $newImage = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $newImage = uploadFile($_FILES['image'], 'gallery');
        if (!$newImage) $error = 'Зураг upload амжилтгүй!';
    }

    if ($title === '') $error = 'Гарчиг оруулна уу.';

    if (empty($error)) {
        if ($newImage) {
            // get old image to delete
            $old = $conn->query("SELECT image FROM gallery WHERE id = $id")->fetch_assoc();
            if ($old && !empty($old['image']) && file_exists(UPLOAD_PATH . $old['image'])) {
                @unlink(UPLOAD_PATH . $old['image']);
            }
            $stmt = $conn->prepare("UPDATE gallery SET title=?, image=?, category=?, description=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $newImage, $category, $description, $id);
        } else {
            $stmt = $conn->prepare("UPDATE gallery SET title=?, category=?, description=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $category, $description, $id);
        }
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Gallery item амжилттай шинэчлэгдлээ.';
            redirect(ADMIN_URL . 'gallery/');
        } else {
            $error = 'Алдаа: ' . $conn->error;
        }
    }
}

$row = $conn->query("SELECT * FROM gallery WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'gallery/');
?>
<!doctype html>
<html lang="mn">
<head>
  <meta charset="utf-8">
  <title>Gallery засварлах - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">Буцах</a>
    <h3 class="d-inline ms-3">Gallery засварлах</h3>
  </div>

  <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Гарчиг <span class="text-danger">*</span></label>
          <input name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Одоогийн зураг</label><br>
          <?php if($row['image']): ?>
            <img src="<?php echo UPLOAD_URL . $row['image']; ?>" style="max-height:120px" alt=""><br><br>
          <?php endif; ?>
          <label class="form-label">Шинэ зураг (сонголттой)</label>
          <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Ангилал</label>
          <input name="category" class="form-control" value="<?php echo htmlspecialchars($row['category']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Тайлбар</label>
          <textarea name="description" rows="5" class="form-control"><?php echo htmlspecialchars($row['description']); ?></textarea>
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