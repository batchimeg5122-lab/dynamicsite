<?php
// Хуудсын өгөгдөл авах
$stmt = $conn->prepare("SELECT * FROM pages WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$pageData = $result->fetch_assoc();

// Хуудас олдсонгүй бол 404
if (!$pageData) {
    include 'views/404.php';
    exit;
}
?>

<!-- Cover зураг -->
<?php if($pageData['cover_image']): ?>
<div class="page-header" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo UPLOAD_URL . $pageData['cover_image']; ?>') center/cover; height: 300px;">
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <h1 class="display-4 text-white fw-bold"><?php echo $pageData['title']; ?></h1>
    </div>
</div>
<?php endif; ?>

<!-- Хуудасны агуулга -->
<section class="py-5">
    <div class="container">
        <?php if(!$pageData['cover_image']): ?>
        <div class="mb-4">
            <h1 class="display-5 fw-bold"><?php echo $pageData['title']; ?></h1>
            <hr>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="content">
                    <?php echo $pageData['content']; ?>
                </div>
                
                <?php if($pageData['updated_at']): ?>
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted small">
                        <i class="bi bi-clock"></i> Сүүлд шинэчлэгдсэн: 
                        <?php echo date('Y оны m сарын d', strtotime($pageData['updated_at'])); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.content h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.content h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.content p {
    margin-bottom: 1.25rem;
}

.content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.content ul, .content ol {
    margin-bottom: 1.25rem;
    padding-left: 2rem;
}

.content li {
    margin-bottom: 0.5rem;
}

.content blockquote {
    border-left: 4px solid #0d6efd;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}
</style>