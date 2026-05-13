<?php
// Хуудаслалт тохируулах
$limit = 9; // Нэг хуудсанд харуулах мэдээний тоо
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page_num - 1) * $limit;

// Нийт мэдээний тоо авах
$countQuery = "SELECT COUNT(*) as total FROM news";
$countResult = $conn->query($countQuery);
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Мэдээ авах
$newsQuery = "SELECT * FROM news ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$newsResult = $conn->query($newsQuery);
?>

<!-- Page Header -->
<div class="page-header bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Мэдээ ба мэдээлэл</h1>
                <p class="lead">Бидний сүүлийн үеийн мэдээ, мэдээллүүд</p>
            </div>
        </div>
    </div>
</div>

<!-- Мэдээний жагсаалт -->
<section class="py-5">
    <div class="container">
        <?php if($newsResult->num_rows > 0): ?>
            <div class="row g-4">
                <?php while($newsItem = $newsResult->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 border-0 shadow-sm news-card">
                            <?php if($newsItem['image']): ?>
                                <div class="card-img-wrapper" style="overflow: hidden; height: 250px;">
                                    <img src="<?php echo UPLOAD_URL . $newsItem['image']; ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo $newsItem['title']; ?>"
                                         style="object-fit: cover; height: 100%; width: 100%; transition: transform 0.3s;">
                                </div>
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="bi bi-newspaper fs-1 text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> 
                                        <?php echo date('Y оны m сарын d', strtotime($newsItem['created_at'])); ?>
                                    </small>
                                </div>
                                
                                <h5 class="card-title mb-3">
                                    <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $newsItem['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo $newsItem['title']; ?>
                                    </a>
                                </h5>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    <?php 
                                    $content = strip_tags($newsItem['content']);
                                    echo mb_substr($content, 0, 150, 'UTF-8') . '...'; 
                                    ?>
                                </p>
                                
                                <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $newsItem['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm mt-auto">
                                    Дэлгэрэнгүй <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Хуудаслалт -->
            <?php if($totalPages > 1): ?>
                <nav aria-label="Мэдээний хуудаслалт" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <!-- Өмнөх -->
                        <li class="page-item <?php echo $page_num <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL; ?>index.php?page=news&p=<?php echo $page_num - 1; ?>">
                                <i class="bi bi-chevron-left"></i> Өмнөх
                            </a>
                        </li>
                        
                        <!-- Хуудасны дугаарууд -->
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if($i == 1 || $i == $totalPages || ($i >= $page_num - 2 && $i <= $page_num + 2)): ?>
                                <li class="page-item <?php echo $i == $page_num ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>index.php?page=news&p=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif($i == $page_num - 3 || $i == $page_num + 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <!-- Дараах -->
                        <li class="page-item <?php echo $page_num >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL; ?>index.php?page=news&p=<?php echo $page_num + 1; ?>">
                                Дараах <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="alert alert-info text-center py-5" role="alert">
                <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                <h5>Мэдээ байхгүй байна</h5>
                <p class="mb-0">Удахгүй шинэ мэдээ нэмэгдэх болно.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.news-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.news-card:hover .card-img-top {
    transform: scale(1.05);
}

.news-card .card-title a:hover {
    color: #0d6efd !important;
}

.page-header {
    background: linear-gradient(135deg, #434343 0%, #000000 100%);
}
</style>
