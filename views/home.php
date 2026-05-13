<?php
// Banner авах
$bannersQuery = "SELECT * FROM banners ORDER BY id DESC LIMIT 5";
$banners = $conn->query($bannersQuery);

// Үйлчилгээ авах
$servicesQuery = "SELECT * FROM services LIMIT 6";
$services = $conn->query($servicesQuery);

// Багийн гишүүд авах
$teamQuery = "SELECT * FROM team LIMIT 4";
$team = $conn->query($teamQuery);

// Сүүлийн мэдээ авах
$newsQuery = "SELECT * FROM news ORDER BY created_at DESC LIMIT 3";
$news = $conn->query($newsQuery);

// Түншүүд авах
$partnersQuery = "SELECT * FROM partners ORDER BY id ASC";
$partners = $conn->query($partnersQuery);
?>

<!-- Hero Slider -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php 
        $i = 0;
        $banners->data_seek(0);
        while($banner = $banners->fetch_assoc()): 
        ?>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>" 
                    class="<?php echo $i == 0 ? 'active' : ''; ?>"></button>
        <?php 
        $i++;
        endwhile; 
        ?>
    </div>
    
    <div class="carousel-inner">
        <?php 
        $i = 0;
        $banners->data_seek(0);
        while($banner = $banners->fetch_assoc()): 
        ?>
            <div class="carousel-item <?php echo $i == 0 ? 'active' : ''; ?>">
                <img src="<?php echo UPLOAD_URL . $banner['image']; ?>" class="d-block w-100" alt="<?php echo $banner['title']; ?>" style="height: 500px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-4 rounded">
                    <h1><?php echo $banner['title']; ?></h1>
                    <p class="lead"><?php echo $banner['subtitle']; ?></p>
                    <?php if($banner['button_text'] && $banner['button_link']): ?>
                        <a href="<?php echo $banner['button_link']; ?>" class="btn btn-primary btn-lg">
                            <?php echo $banner['button_text']; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
        $i++;
        endwhile; 
        ?>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Үйлчилгээ хэсэг -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Манай үйлчилгээ</h2>
            <p class="text-muted">Бид та бүхэнд дараах үйлчилгээг санал болгож байна</p>
        </div>
        
        <div class="row g-4">
            <?php while($service = $services->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if($service['image']): ?>
                            <img src="<?php echo UPLOAD_URL . $service['image']; ?>" class="card-img-top" alt="<?php echo $service['title']; ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <?php if($service['icon']): ?>
                                <i class="<?php echo $service['icon']; ?> fs-1 text-primary mb-3"></i>
                            <?php endif; ?>
                            <h5 class="card-title"><?php echo $service['title']; ?></h5>
                            <p class="card-text text-muted"><?php echo $service['short_desc']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Багийн гишүүд хэсэг -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Манай баг</h2>
            <p class="text-muted">Мэргэжлийн багийн гишүүд</p>
        </div>
        
        <div class="row g-4">
            <?php while($member = $team->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <?php if($member['photo']): ?>
                            <img src="<?php echo UPLOAD_URL . $member['photo']; ?>" class="card-img-top" alt="<?php echo $member['name']; ?>" style="height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1"><?php echo $member['name']; ?></h5>
                            <p class="text-muted small mb-3"><?php echo $member['position']; ?></p>
                            <div class="d-flex justify-content-center gap-2">
                                <?php if($member['facebook']): ?>
                                    <a href="<?php echo $member['facebook']; ?>" target="_blank" class="text-primary">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if($member['linkedin']): ?>
                                    <a href="<?php echo $member['linkedin']; ?>" target="_blank" class="text-primary">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Сүүлийн мэдээ хэсэг -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Сүүлийн мэдээ</h2>
            <p class="text-muted">Шинэ мэдээ болон мэдээллүүд</p>
        </div>
        
        <div class="row g-4">
            <?php while($newsItem = $news->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if($newsItem['image']): ?>
                            <img src="<?php echo UPLOAD_URL . $newsItem['image']; ?>" class="card-img-top" alt="<?php echo $newsItem['title']; ?>" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar"></i> <?php echo date('Y-m-d', strtotime($newsItem['created_at'])); ?>
                            </p>
                            <h5 class="card-title"><?php echo $newsItem['title']; ?></h5>
                            <p class="card-text text-muted"><?php echo substr(strip_tags($newsItem['content']), 0, 100); ?>...</p>
                            <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $newsItem['id']; ?>" class="btn btn-outline-primary btn-sm">
                                Дэлгэрэнгүй <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>index.php?page=news" class="btn btn-primary">
                Бүх мэдээ үзэх
            </a>
        </div>
    </div>
</section>

<!-- Түншүүд хэсэг -->
<?php if(isset($partners) && $partners && $partners->num_rows > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="display-6 fw-bold">Манай түншүүд</h2>
        </div>
        
        <div class="row g-4 align-items-center">
            <?php while($partner = $partners->fetch_assoc()): ?>
                <div class="col-6 col-md-3 text-center">
                    <?php if($partner['url']): ?>
                        <a href="<?php echo $partner['url']; ?>" target="_blank">
                            <img src="<?php echo UPLOAD_URL . $partner['logo']; ?>" alt="<?php echo $partner['name']; ?>" class="img-fluid" style="max-height: 80px; filter: grayscale(100%); transition: 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                        </a>
                    <?php else: ?>
                        <img src="<?php echo UPLOAD_URL . $partner['logo']; ?>" alt="<?php echo $partner['name']; ?>" class="img-fluid" style="max-height: 80px;">
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>