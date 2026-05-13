<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) redirect(ADMIN_URL . 'faqs/');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $order_num = (int)($_POST['order_num'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($question === '') {
        $error = 'Асуулт оруулна уу.';
    } else {
        $stmt = $conn->prepare("UPDATE faqs SET question=?, answer=?, category=?, order_num=?, is_active=? WHERE id=?");
        $stmt->bind_param("sssiii", $question, $answer, $category, $order_num, $is_active, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'FAQ амжилттай шинэчлэгдлээ.';
            redirect(ADMIN_URL . 'faqs/');
        } else {
            $error = 'Алдаа: ' . $conn->error;
        }
    }
}

// load current
$row = $conn->query("SELECT * FROM faqs WHERE id = $id")->fetch_assoc();
if (!$row) redirect(ADMIN_URL . 'faqs/');
?>
<!doctype html>
<html lang="mn">
<head>
  <meta charset="utf-8">
  <title>FAQ засварлах - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">Буцах</a>
    <h3 class="d-inline ms-3">FAQ засварлах</h3>
  </div>

  <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Асуулт <span class="text-danger">*</span></label>
          <input name="question" class="form-control" required value="<?php echo htmlspecialchars($row['question']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Хариулт</label>
          <textarea name="answer" rows="6" class="form-control"><?php echo htmlspecialchars($row['answer']); ?></textarea>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Ангилал</label>
            <input name="category" class="form-control" value="<?php echo htmlspecialchars($row['category']); ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Эрэмбэ</label>
            <input type="number" name="order_num" class="form-control" value="<?php echo (int)$row['order_num']; ?>">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?php echo $row['is_active'] ? 'checked' : ''; ?>>
              <label class="form-check-label" for="is_active">Идэвхтэй</label>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Хадгалах</button>
          <a class="btn btn-secondary" href="index.php">Буцах</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>