<?php
$category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$galleryQuery = "SELECT * FROM gallery WHERE 1=1";
if($category) $galleryQuery .= " AND category='$category'";
$galleryQuery .= " ORDER BY id DESC";
$gallery = $conn->query($galleryQuery);
$categories = $conn->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL");
?>
<div class="page-header bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Зургийн цомог</h1>
        <p class="lead">Манай үйл ажиллагааны зургууд</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <!-- Category Filter -->
        <div class="text-center mb-4">
            <div class="btn-group">
                <a href="?page=gallery" class="btn <?=!$category?'btn-primary':'btn-outline-primary'?>">Бүгд</a>
                <?php while($cat=$categories->fetch_assoc()): ?>
                    <a href="?page=gallery&cat=<?=urlencode($cat['category'])?>" 
                       class="btn <?=$category==$cat['category']?'btn-primary':'btn-outline-primary'?>">
                        <?=htmlspecialchars($cat['category'])?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        
        <?php if($gallery->num_rows>0): ?>
            <div class="row g-3">
                <?php while($item=$gallery->fetch_assoc()): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="<?=UPLOAD_URL.$item['image']?>" 
                                 class="card-img-top" 
                                 style="height:250px;object-fit:cover;cursor:pointer"
                                 onclick="openLightbox('<?=UPLOAD_URL.$item['image']?>','<?=htmlspecialchars($item['title'])?>')">
                            <div class="card-body p-2">
                                <h6 class="small mb-0"><?=htmlspecialchars($item['title'])?></h6>
                                <small class="text-muted"><?=htmlspecialchars($item['category'])?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-image fs-1 text-muted"></i>
                <h5>Зураг байхгүй байна</h5>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lightboxTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="lightboxImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function openLightbox(src, title) {
    document.getElementById('lightboxImage').src = src;
    document.getElementById('lightboxTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>