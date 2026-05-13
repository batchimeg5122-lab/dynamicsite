<?php
// Success/Error мессеж
$successMsg = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$errorMsg = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success']);
unset($_SESSION['error']);

// Social media
$facebook = getSetting('facebook');
$youtube = getSetting('youtube');
$instagram = getSetting('instagram');
?>

<!-- Page Header -->
<div class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">Холбоо барих</h1>
                <p class="lead">Бидэнтэй холбогдох, санал хүсэлт илгээх</p>
            </div>
        </div>
    </div>
</div>

<!-- Холбоо барих хэсэг -->
<section class="py-5">
    <div class="container">
        <!-- Мессеж харуулах -->
        <?php if($successMsg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $successMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if($errorMsg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $errorMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-5">
            <!-- Холбоо барих форм -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Мессеж илгээх</h3>
                        
                        <form action="<?php echo BASE_URL; ?>send.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Нэр <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           required
                                           placeholder="Таны нэр">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">И-мэйл <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           required
                                           placeholder="example@email.com">
                                </div>
                                
                                <div class="col-12">
                                    <label for="phone" class="form-label">Утасны дугаар</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone"
                                           placeholder="99001122">
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Мессеж <span class="text-danger">*</span></label>
                                    <textarea class="form-control" 
                                              id="message" 
                                              name="message" 
                                              rows="5" 
                                              required
                                              placeholder="Таны мессеж..."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send"></i> Илгээх
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Холбоо барих мэдээлэл -->
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 100px;">
                    <h3 class="mb-4">Холбоо барих мэдээлэл</h3>
                    
                    <!-- Хаяг -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-geo-alt fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Хаяг</h5>
                            <p class="text-muted mb-0">
                                Улаанбаатар хот, Сүхбаатар дүүрэг<br>
                                1-р хороо, Энхтайваны өргөн чөлөө
                            </p>
                        </div>
                    </div>
                    
                    <!-- Утас -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-telephone fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Утас</h5>
                            <p class="text-muted mb-0">
                                +976 7000-0000<br>
                                +976 9900-0000
                            </p>
                        </div>
                    </div>
                    
                    <!-- И-мэйл -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-envelope fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">И-мэйл</h5>
                            <p class="text-muted mb-0">
                                info@company.mn<br>
                                contact@company.mn
                            </p>
                        </div>
                    </div>
                    
                    <!-- Цагийн хуваарь -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-clock fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Ажлын цаг</h5>
                            <p class="text-muted mb-0">
                                Даваа - Баасан: 09:00 - 18:00<br>
                                Бямба, Ням: Амарна
                            </p>
                        </div>
                    </div>
                    
                    <!-- Сошиал медиа -->
                    <?php if($facebook || $youtube || $instagram): ?>
                    <div class="mt-4 pt-4 border-top">
                        <h5 class="mb-3">Биднийг дагаарай</h5>
                        <div class="d-flex gap-3">
                            <?php if($facebook): ?>
                                <a href="<?php echo $facebook; ?>" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if($youtube): ?>
                                <a href="<?php echo $youtube; ?>" target="_blank" class="btn btn-outline-danger">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if($instagram): ?>
                                <a href="<?php echo $instagram; ?>" target="_blank" class="btn btn-outline-danger">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Газрын зураг -->
<section class="py-0">
    <div class="container-fluid p-0">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2674.0315917874596!2d106.91754931576656!3d47.91874657920431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5d9692428b8d6eb9%3A0x9c4b2c6f0fda3e77!2sUlaanbaatar%2C%20Mongolia!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy"></iframe>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>