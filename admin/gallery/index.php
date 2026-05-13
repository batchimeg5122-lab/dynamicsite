<?php
require_once '../../config.php';
checkAdminLogin();

$messages = [];
if (isset($_SESSION['success'])) { $messages['success'] = $_SESSION['success']; unset($_SESSION['success']); }
if (isset($_SESSION['error'])) { $messages['error'] = $_SESSION['error']; unset($_SESSION['error']); }

$query = "SELECT * FROM gallery ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!doctype html>
<html lang="mn">
<head>
  <meta charset="utf-8">
  <title>Gallery - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gallery</h3>
    <a href="add.php" class="btn btn-primary">Шинээр нэмэх</a>
  </div>

  <?php if(!empty($messages['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($messages['success']); ?></div>
  <?php endif; ?>
  <?php if(!empty($messages['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($messages['error']); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table mb-0 table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Гарчиг</th>
            <th>Ангилал</th>
            <th>Зураг</th>
            <th>Огноо</th>
            <th>Үйлдэл</th>
          </tr>
        </thead>
        <tbody>
          <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo (int)$row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>
                  <?php if($row['image']): ?>
                    <img src="<?php echo UPLOAD_URL . $row['image']; ?>" style="max-height:60px" alt="">
                  <?php endif; ?>
                </td>
                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-primary" href="edit.php?id=<?php echo $row['id']; ?>">Засах</a>
                  <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?php echo $row['id']; ?>">Устгах</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center py-4">Мэдээлэл олдсонгүй</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>