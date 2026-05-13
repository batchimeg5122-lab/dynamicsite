<?php
require_once '../../config.php';
checkAdminLogin();

$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

// Шүүлтүүр
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Мессеж авах
$messagesQuery = "SELECT * FROM messages WHERE 1=1";

if ($search) {
    $messagesQuery .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR message LIKE '%$search%')";
}

if ($filter == 'today') {
    $messagesQuery .= " AND DATE(created_at) = CURDATE()";
} elseif ($filter == 'week') {
    $messagesQuery .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
}

$messagesQuery .= " ORDER BY created_at DESC";
$messages = $conn->query($messagesQuery);

// Статистик
$totalMessages = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$todayMessages = $conn->query("SELECT COUNT(*) as count FROM messages WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
$weekMessages = $conn->query("SELECT COUNT(*) as count FROM messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мессежүүд - Admin</title>
    
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
                        <a href="../dashboard.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0"><i class="bi bi-envelope"></i> Мессежүүд</h4>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="col-12 mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Нийт</h6>
                                        <h3 class="mb-0"><?php echo $totalMessages; ?></h3>
                                    </div>
                                    <i class="bi bi-envelope-fill fs-1 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Өнөөдөр</h6>
                                        <h3 class="mb-0"><?php echo $todayMessages; ?></h3>
                                    </div>
                                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">7 хоногт</h6>
                                        <h3 class="mb-0"><?php echo $weekMessages; ?></h3>
                                    </div>
                                    <i class="bi bi-calendar-week fs-1 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Messages -->
                        <?php if($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Filters & Search -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="btn-group" role="group">
                                    <a href="index.php?filter=all" 
                                       class="btn btn-sm <?php echo $filter == 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                        <i class="bi bi-list"></i> Бүгд
                                    </a>
                                    <a href="index.php?filter=today" 
                                       class="btn btn-sm <?php echo $filter == 'today' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                        <i class="bi bi-calendar-day"></i> Өнөөдөр
                                    </a>
                                    <a href="index.php?filter=week" 
                                       class="btn btn-sm <?php echo $filter == 'week' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                        <i class="bi bi-calendar-week"></i> 7 хоног
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form method="GET" class="d-flex">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control form-control-sm me-2" 
                                           placeholder="Хайх..."
                                           value="<?php echo htmlspecialchars($search); ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Table -->
                        <?php if($messages->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">ID</th>
                                            <th>Нэр</th>
                                            <th>И-мэйл / Утас</th>
                                            <th>Мессеж</th>
                                            <th width="150">Огноо</th>
                                            <th width="150" class="text-center">Үйлдэл</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($msg = $messages->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $msg['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <i class="bi bi-envelope"></i> 
                                                        <a href="mailto:<?php echo $msg['email']; ?>" class="text-decoration-none">
                                                            <?php echo htmlspecialchars($msg['email']); ?>
                                                        </a>
                                                    </div>
                                                    <?php if($msg['phone']): ?>
                                                        <div class="small text-muted">
                                                            <i class="bi bi-telephone"></i> 
                                                            <a href="tel:<?php echo $msg['phone']; ?>" class="text-decoration-none">
                                                                <?php echo htmlspecialchars($msg['phone']); ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo mb_substr(htmlspecialchars($msg['message']), 0, 80, 'UTF-8'); ?>
                                                        <?php if(mb_strlen($msg['message']) > 80): ?>...<?php endif; ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php echo date('Y-m-d', strtotime($msg['created_at'])); ?>
                                                        <br>
                                                        <span class="text-muted">
                                                            <?php echo date('H:i', strtotime($msg['created_at'])); ?>
                                                        </span>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="view.php?id=<?php echo $msg['id']; ?>" 
                                                           class="btn btn-outline-info"
                                                           title="Үзэх & Хариулах">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?php echo $msg['id']; ?>" 
                                                           class="btn btn-outline-danger"
                                                           onclick="return confirm('Устгахдаа итгэлтэй байна уу?')"
                                                           title="Устгах">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <h5>Мессеж байхгүй байна</h5>
                                <p class="text-muted">
                                    <?php if($search): ?>
                                        Хайлтын үр дүн олдсонгүй.
                                    <?php else: ?>
                                        Хэрэглэгчдээс ирсэн мессеж энд харагдана.
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>