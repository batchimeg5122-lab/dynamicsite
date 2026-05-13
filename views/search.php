// ===== views/search.php =====
<?php
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($keyword) {
    $kw = '%' . $keyword . '%';
    
    // Search Pages
    $stmt = $conn->prepare("SELECT 'page' as type, id, title, slug, content FROM pages WHERE title LIKE ? OR content LIKE ? LIMIT 5");
    $stmt->bind_param("ss", $kw, $kw);
    $stmt->execute();
    $pageResults = $stmt->get_result();
    while($r = $pageResults->fetch_assoc()) $results[] = $r;
    
    // Search Services
    $stmt = $conn->prepare("SELECT 'service' as type, id, title, NULL as slug, description as content FROM services WHERE title LIKE ? OR description LIKE ? LIMIT 5");
    $stmt->bind_param("ss", $kw, $kw);
    $stmt->execute();
    $serviceResults = $stmt->get_result();
    while($r = $serviceResults->fetch_assoc()) $results[] = $r;
    
    // Search News
    $stmt = $conn->prepare("SELECT 'news' as type, id, title, NULL as slug, content FROM news WHERE title LIKE ? OR content LIKE ? LIMIT 5");
    $stmt->bind_param("ss", $kw, $kw);
    $stmt->execute();
    $newsResults = $stmt->get_result();
    while($r = $newsResults->fetch_assoc()) $results[] = $r;
}
?>

<div class="page-header bg-dark text-white py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Хайлтын үр дүн</h1>
        <?php if($keyword): ?>
            <p class="lead">"<?=htmlspecialchars($keyword)?>" - <?=count($results)?> үр дүн олдлоо</p>
        <?php endif; ?>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Search Form -->
                <form method="GET" action="index.php" class="mb-4">
                    <input type="hidden" name="page" value="search">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control" placeholder="Хайх..." value="<?=htmlspecialchars($keyword)?>" required>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Хайх</button>
                    </div>
                </form>
                
                <!-- Results -->
                <?php if($keyword): ?>
                    <?php if(count($results) > 0): ?>
                        <div class="list-group">
                            <?php foreach($results as $r): ?>
                                <a href="<?php 
                                    if($r['type']=='page') echo BASE_URL.'index.php?page=page&slug='.$r['slug'];
                                    elseif($r['type']=='service') echo BASE_URL.'index.php?page=services';
                                    elseif($r['type']=='news') echo BASE_URL.'index.php?page=news-detail&slug='.$r['id'];
                                ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?=htmlspecialchars($r['title'])?></h5>
                                        <small><span class="badge bg-primary"><?=ucfirst($r['type'])?></span></small>
                                    </div>
                                    <p class="mb-1 text-muted"><?=mb_substr(strip_tags($r['content']), 0, 150, 'UTF-8')?>...</p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> "<?=htmlspecialchars($keyword)?>" хайлтад үр дүн олдсонгүй.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                        <p>Хайх үгээ оруулна уу</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
