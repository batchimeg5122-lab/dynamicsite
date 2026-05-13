<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'testimonials/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $row = $conn->query("SELECT photo FROM testimonials WHERE id = $id")->fetch_assoc();
    if ($row && !empty($row['photo']) && file_exists(UPLOAD_PATH . $row['photo'])) {
        @unlink(UPLOAD_PATH . $row['photo']);
    }
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Testimonial устгагдлаа.';
    } else {
        $_SESSION['error'] = 'Алдаа: ' . $conn->error;
    }
    redirect(ADMIN_URL . 'testimonials/');
}

$row = $conn->query("SELECT * FROM testimonials WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'testimonials/');
?>
<!doctype html>
<html lang="mn">
<head><meta charset="utf-8"><title>Testimonial устгах</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <h3>Testimonial устгах</h3>
  <div class="card p-3">
    <p>Та дараах тестимонйлыг устгах гэж байна:</p>
    <p><strong><?php echo htmlspecialchars($row['author']); ?></strong></p>
    <?php if($row['photo']): ?><p><img src="<?php echo UPLOAD_URL . $row['photo']; ?>" style="max-height:120px"></p><?php endif; ?>
    <form method="post">
      <button class="btn btn-danger" type="submit">Тийм, устгах</button>
      <a class="btn btn-secondary" href="index.php">Буцах</a>
    </form>
  </div>
</div>
</body>
</html>