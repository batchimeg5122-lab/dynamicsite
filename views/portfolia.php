<?php
$category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$portfolioQuery = "SELECT * FROM portfolio WHERE 1=1";
if($category) $portfolioQuery .= " AND category='$category'";
$portfolioQuery .= " ORDER BY is_featured DESC, id DESC";
$portfolio = $conn->query($portfolioQuery);
$categories = $conn->query("SELECT DISTINCT category FROM portfolio WHERE category IS NOT NULL");
?>
<div class="page-header bg-dark text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Төслүүд</h1>
        <p class="lead">Манай хийсэн ажлууд</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <!-- Category Filter -->
        <div class="text-center mb-4">
            <div class="btn-group">
                <a href="?page=portfolio" class="btn <?=!$category?'btn-dark':'btn-outline-dark'?>">Бүгд</a>
                <?php while($cat=$categories->fetch_assoc()): ?>
                    <a href="?page=portfolio&cat=<?=urlencode($cat['category'])?>" 
                       class="btn <?=$category==$cat['category']?'btn-dark':'btn-outline-dark'?>">
                        <?=htmlspecialchars($cat['category'])?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        
        <?php if($portfolio->num_rows>0): ?>
            <div class="row g-4">
                <?php while($p=$portfolio->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 portfolio-card">
                            <?php if($p['image']): ?>
                                <div style="overflow:hidden;height:250px">
                                    <img src="<?=UPLOAD_URL.$p['image']?>" class="card-img-top" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s">
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?=htmlspecialchars($p['title'])?></h5>
                                    <?php if($p['is_featured']): ?>
                                        <span class="badge bg-warning">Featured</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag"></i> <?=$p['category']?>
                                    <?php if($p['client_name']): ?> | <i class="bi bi-building"></i> <?=$p['client_name']?><?php endif; ?>
                                </p>
                                <p class="card-text"><?=nl2br(htmlspecialchars($p['description']))?></p>
                                <?php if($p['project_url']): ?>
                                    <a href="<?=$p['project_url']?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right"></i> Үзэх
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5"><p class="text-muted">Төсөл байхгүй</p></div>
        <?php endif; ?>
    </div>
</section>

<style>
.portfolio-card:hover img { transform: scale(1.1); }
.portfolio-card { transition: transform 0.3s; }
.portfolio-card:hover { transform: translateY(-5px); }
</style>