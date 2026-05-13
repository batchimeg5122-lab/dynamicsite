<?php
// Бүх үйлчилгээ авах
$servicesQuery = "SELECT * FROM services ORDER BY id DESC";
$services = $conn->query($servicesQuery);
?>

<!-- Page Header -->
<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Манай үйлчилгээ</h1>
                <p class="lead">Бид танд өндөр чанартай, мэргэжлийн үйлчилгээ үзүүлдэг</p>
            </div>
        </div>
    </div>
</div>

<!-- Үйлчилгээний жагсаалт -->
<section class="py-5">
    <div class="container">
        <?php if($services->num_rows > 0): ?>
            <div class="row g-4">
                <?php while($service = $services->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 service-card">
                            <?php if($service['image']): ?>
                                <div class="card-img-wrapper" style="overflow: hidden; height: 250px;">
                                    <img src="<?php echo UPLOAD_URL . $service['image']; ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo $service['title']; ?>" 
                                         style="object-fit: cover; height: 100%; width: 100%; transition: transform 0.3s;">
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="mb-3">
                                    <?php if($service['icon']): ?>
                                        <i class="<?php echo $service['icon']; ?> fs-1 text-primary"></i>
                                    <?php endif; ?>
                                </div>
                                
                                <h4 class="card-title mb-3"><?php echo $service['title']; ?></h4>
                                
                                <?php if($service['short_desc']): ?>
                                    <p class="card-text text-muted mb-3">
                                        <strong><?php echo $service['short_desc']; ?></strong>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if($service['description']): ?>
                                    <p class="card-text text-muted">
                                        <?php echo nl2br($service['description']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="<?php echo BASE_URL; ?>index.php?page=contact" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-envelope"></i> Холбоо барих
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-info-circle fs-3"></i>
                <p class="mb-0 mt-2">Одоогоор үйлчилгээ байхгүй байна.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-6 fw-bold mb-3">Та бидэнтэй хамтран ажиллахыг хүсч байна уу?</h2>
                <p class="lead text-muted mb-4">
                    Манай үйлчилгээний талаар дэлгэрэнгүй мэдээлэл авахыг хүсвэл бидэнтэй холбогдоно уу.
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?page=contact" class="btn btn-primary btn-lg">
                    <i class="bi bi-telephone"></i> Холбоо барих
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.service-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.service-card:hover .card-img-top {
    transform: scale(1.1);
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>