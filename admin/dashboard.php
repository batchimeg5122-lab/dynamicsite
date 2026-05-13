<?php
require_once '../config.php';
checkAdminLogin();

// Статистик авах
$pagesCount = $conn->query("SELECT COUNT(*) as count FROM pages")->fetch_assoc()['count'];
$servicesCount = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$teamCount = $conn->query("SELECT COUNT(*) as count FROM team")->fetch_assoc()['count'];
$newsCount = $conn->query("SELECT COUNT(*) as count FROM news")->fetch_assoc()['count'];
$messagesCount = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$bannersCount = $conn->query("SELECT COUNT(*) as count FROM banners")->fetch_assoc()['count'];
$galleryCount = $conn->query("SELECT COUNT(*) as count FROM gallery")->fetch_assoc()['count'];
$testimonialsCount = $conn->query("SELECT COUNT(*) as count FROM testimonials")->fetch_assoc()['count'];
$faqCount = $conn->query("SELECT COUNT(*) as count FROM faqs")->fetch_assoc()['count'];
$portfolioCount = $conn->query("SELECT COUNT(*) as count FROM portfolio")->fetch_assoc()['count'];
$subscribersCount = $conn->query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE is_active=1")->fetch_assoc()['count'];

// Сүүлийн мессежүүд
$recentMessages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");

// Сүүлийн мэдээ
$recentNews = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Удирдлагын самбар - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }
        
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }
        
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .topbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 250px;">
            <div class="p-4 border-bottom border-secondary">
                <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Админ</h4>
                <small class="text-muted">Удирдлагын самбар</small>
            </div>
            
            <nav class="py-3">
                <a href="dashboard.php" class="active">
                    <i class="bi bi-house"></i> Нүүр хуудас
                </a>
                <a href="pages/">
                    <i class="bi bi-file-text"></i> Хуудсууд
                </a>
                <a href="services/">
                    <i class="bi bi-briefcase"></i> Үйлчилгээ
                </a>
                <a href="team/">
                    <i class="bi bi-people"></i> Багийн гишүүд
                </a>
                <a href="news/">
                    <i class="bi bi-newspaper"></i> Мэдээ
                </a>
                <a href="banners/">
                    <i class="bi bi-image"></i> Баннер
                </a>
                <a href="menus/">
                    <i class="bi bi-list"></i> Цэс
                </a>
                <a href="settings/general.php">
                    <i class="bi bi-gear"></i> Тохиргоо
                </a>
<a href="testimonials/"><i class="bi bi-chat-quote"></i> Сэтгэгдэл</a>
<a href="faqs/"><i class="bi bi-question-circle"></i> FAQ</a>
<a href="portfolio/"><i class="bi bi-briefcase"></i> Төслүүд</a>
                <div class="border-top border-secondary mt-3 pt-3">
                    <a href="<?php echo BASE_URL; ?>" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i> Сайт үзэх
                    </a>
                    <a href="logout.php" class="text-danger">
                        <i class="bi bi-box-arrow-right"></i> Гарах
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Bar -->
            <div class="topbar p-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Удирдлагын самбар</h5>
                <div>
                    <span class="me-3">
                        <i class="bi bi-person-circle"></i> 
                        <?php echo $_SESSION['admin_username']; ?>
                    </span>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Гарах
                    </a>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-4">
                <!-- Welcome Message -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i> 
                    Сайн байна уу, <strong><?php echo $_SESSION['admin_username']; ?></strong>! 
                    Удирдлагын самбарт тавтай морил.
                </div>
                
                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card border-primary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Хуудсууд</h6>
                                        <h2 class="mb-0"><?php echo $pagesCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-file-text fs-1 text-primary"></i>
                                    </div>
                                </div>
                                <a href="pages/" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card border-success shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Үйлчилгээ</h6>
                                        <h2 class="mb-0"><?php echo $servicesCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-briefcase fs-1 text-success"></i>
                                    </div>
                                </div>
                                <a href="services/" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card border-warning shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Багийн гишүүд</h6>
                                        <h2 class="mb-0"><?php echo $teamCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-people fs-1 text-warning"></i>
                                    </div>
                                </div>
                                <a href="team/" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card border-info shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Мэдээ</h6>
                                        <h2 class="mb-0"><?php echo $newsCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-newspaper fs-1 text-info"></i>
                                    </div>
                                </div>
                                <a href="news/" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card border-danger shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Мессежүүд</h6>
                                        <h2 class="mb-0"><?php echo $messagesCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-envelope fs-1 text-danger"></i>
                                    </div>
                                    <a href="messages/" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card border-secondary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted mb-2">Баннер</h6>
                                        <h2 class="mb-0"><?php echo $bannersCount; ?></h2>
                                    </div>
                                    <div>
                                        <i class="bi bi-image fs-1 text-secondary"></i>
                                    </div>
                                </div>
                                <a href="banners/" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="row g-4">
                    <!-- Сүүлийн мессежүүд -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-envelope"></i> Сүүлийн мессежүүд</h6>
                            </div>
                            <div class="card-body p-0">
                                <?php if($recentMessages->num_rows > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php while($msg = $recentMessages->fetch_assoc()): ?>
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?php echo $msg['name']; ?></strong>
                                                    <small class="text-muted">
                                                        <?php echo date('m-d H:i', strtotime($msg['created_at'])); ?>
                                                    </small>
                                                </div>
                                                <small class="text-muted"><?php echo $msg['email']; ?></small>
                                                <p class="mb-0 small mt-1">
                                                    <?php echo mb_substr($msg['message'], 0, 80, 'UTF-8'); ?>...
                                                </p>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center py-4">Мессеж байхгүй</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Сүүлийн мэдээ -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-newspaper"></i> Сүүлийн мэдээ</h6>
                            </div>
                            <div class="card-body p-0">
                                <?php if($recentNews->num_rows > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php while($news = $recentNews->fetch_assoc()): ?>
                                            <a href="news/edit.php?id=<?php echo $news['id']; ?>" 
                                               class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?php echo $news['title']; ?></strong>
                                                    <small class="text-muted">
                                                        <?php echo date('m-d', strtotime($news['created_at'])); ?>
                                                    </small>
                                                </div>
                                            </a>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center py-4">Мэдээ байхгүй</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>