<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'portfolio/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // delete image file
    $row = $conn->query("SELECT image FROM portfolio WHERE id = $id")->fetch_assoc();
    if ($row && !empty($row['image']) && file_exists(UPLOAD_PATH . $row['image'])) {
        @unlink(UPLOAD_PATH . $row['image']);
    }

    $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Portfolio item устгагдлаа.';
    } else {
        $_SESSION['error'] = 'Алдаа: ' . $conn->error;
    }
    redirect(ADMIN_URL . 'portfolio/');
}

$row = $conn->query("SELECT * FROM portfolio WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'portfolio/');
?>
<!doctype html>
<html lang="mn">
<head><meta charset="utf-8"><title>Portfolio устгах</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <h3>Portfolio устгах</h3>
  <div class="card p-3">
    <p>Та дараах зүйлийг устгах гэж байна:</p>
    <p><strong><?php echo htmlspecialchars($row['title']); ?></strong></p>
    <?php if($row['image']): ?><p><img src="<?php echo UPLOAD_URL . $row['image']; ?>" style="max-height:120px"></p><?php endif; ?>
    <form method="post">
      <button class="btn btn-danger" type="submit">Тийм, устгах</button>
      <a class="btn btn-secondary" href="index.php">Буцах</a>
    </form>
  </div>
</div>
</body>
</html>