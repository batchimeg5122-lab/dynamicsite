<?php
// Мэдээний өгөгдөл авах
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $slug);
$stmt->execute();
$result = $stmt->get_result();
$newsItem = $result->fetch_assoc();

// Мэдээ олдсонгүй бол 404
if (!$newsItem) {
    include 'views/404.php';
    exit;
}

// Холбоотой мэдээ авах
$relatedQuery = "SELECT * FROM news WHERE id != ? ORDER BY created_at DESC LIMIT 3";
$relatedStmt = $conn->prepare($relatedQuery);
$relatedStmt->bind_param("i", $slug);
$relatedStmt->execute();
$relatedNews = $relatedStmt->get_result();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo BASE_URL; ?>">Нүүр</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo BASE_URL; ?>index.php?page=news">Мэдээ</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo mb_substr($newsItem['title'], 0, 50, 'UTF-8'); ?>...
            </li>
        </ol>
    </div>
</nav>

<!-- Мэдээний агуулга -->
<article class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Гарчиг -->
                <h1 class="display-5 fw-bold mb-3"><?php echo $newsItem['title']; ?></h1>
                
                <!-- Огноо -->
                <div class="mb-4 pb-3 border-bottom">
                    <small class="text-muted">
                        <i class="bi bi-calendar3"></i> 
                        <?php echo date('Y оны m сарын d, H:i', strtotime($newsItem['created_at'])); ?>
                    </small>
                </div>
                
                <!-- Зураг -->
                <?php if($newsItem['image']): ?>
                    <div class="mb-4">
                        <img src="<?php echo UPLOAD_URL . $newsItem['image']; ?>" 
                             alt="<?php echo $newsItem['title']; ?>" 
                             class="img-fluid rounded shadow-sm w-100"
                             style="max-height: 500px; object-fit: cover;">
                    </div>
                <?php endif; ?>
                
                <!-- Агуулга -->
                <div class="news-content">
                    <?php echo nl2br($newsItem['content']); ?>
                </div>
                
                <!-- Хуваалцах товчнууд -->
                <div class="mt-5 pt-4 border-top">
                    <h6 class="mb-3">Хуваалцах:</h6>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . 'index.php?page=news-detail&slug=' . $newsItem['id']); ?>" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(BASE_URL . 'index.php?page=news-detail&slug=' . $newsItem['id']); ?>&text=<?php echo urlencode($newsItem['title']); ?>" 
                           target="_blank" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                        <button onclick="navigator.clipboard.writeText('<?php echo BASE_URL . 'index.php?page=news-detail&slug=' . $newsItem['id']; ?>'); alert('Холбоос хуулагдлаа!');" 
                                class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-link-45deg"></i> Холбоос хуулах
                        </button>
                    </div>
                </div>
                
                <!-- Буцах товч -->
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>index.php?page=news" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Бүх мэдээ рүү буцах
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Холбоотой мэдээ -->
<?php if($relatedNews->num_rows > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Холбоотой мэдээ</h3>
        
        <div class="row g-4">
            <?php while($related = $relatedNews->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if($related['image']): ?>
                            <img src="<?php echo UPLOAD_URL . $related['image']; ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo $related['title']; ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> 
                                <?php echo date('Y-m-d', strtotime($related['created_at'])); ?>
                            </small>
                            <h6 class="card-title mt-2 mb-3">
                                <?php echo mb_substr($related['title'], 0, 60, 'UTF-8'); ?>...
                            </h6>
                            <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $related['id']; ?>" 
                               class="btn btn-outline-primary btn-sm">
                                Унших <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.news-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.news-content p {
    margin-bottom: 1.5rem;
}

.news-content h2,
.news-content h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.news-content ul,
.news-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.news-content li {
    margin-bottom: 0.5rem;
}

.news-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.news-content blockquote {
    border-left: 4px solid #0d6efd;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 4px;
}
</style>