<?php
// Цэс авах
$menuQuery = "SELECT * FROM menus WHERE parent_id IS NULL ORDER BY order_num ASC";
$menuResult = $conn->query($menuQuery);
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm" >
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <?php if($logo): ?>
                    <img src="<?php echo UPLOAD_URL . $logo; ?>" alt="<?php echo $siteName; ?>" height="50">
                <?php else: ?>
                    <strong><?php echo $siteName; ?></strong>
                <?php endif; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php while($menu = $menuResult->fetch_assoc()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . $menu['url']; ?>">
                                <?php echo $menu['name']; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    <form class="d-flex ms-3" action="<?=BASE_URL?>index.php" method="GET">
    <input type="hidden" name="page" value="search">
    <input type="search" name="q" class="form-control form-control-sm me-2" placeholder="Хайх..." style="width:200px">
    <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
</form>
                </ul>
            </div>
        </div>
    </nav>