<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'faqs/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'FAQ устгагдлаа.';
    } else {
        $_SESSION['error'] = 'Алдаа: ' . $conn->error;
    }
    redirect(ADMIN_URL . 'faqs/');
}

$row = $conn->query("SELECT * FROM faqs WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'faqs/');
?>
<!doctype html>
<html lang="mn">
<head><meta charset="utf-8"><title>FAQ устгах</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-4">
  <h3>FAQ устгах</h3>
  <div class="card p-3">
    <p>Та дараах FAQ-г устгах гэж байна:</p>
    <p><strong><?php echo htmlspecialchars($row['question']); ?></strong></p>
    <form method="post">
      <button class="btn btn-danger" type="submit">Тийм, устгах</button>
      <a class="btn btn-secondary" href="index.php">Буцах</a>
    </form>
  </div>
</div>
</body>
</html>