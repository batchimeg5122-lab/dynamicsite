<!-- 404 Error Page -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="error-404">
                    <!-- 404 дугаар -->
                    <h1 class="display-1 fw-bold text-primary mb-0" style="font-size: 10rem;">404</h1>
                    
                    <!-- Icon -->
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
                    </div>
                    
                    <!-- Гарчиг -->
                    <h2 class="display-6 fw-bold mb-3">Уучлаарай, хуудас олдсонгүй!</h2>
                    
                    <!-- Тайлбар -->
                    <p class="lead text-muted mb-4">
                        Таны хайсан хуудас олдсонгүй эсвэл устгагдсан байж магадгүй юм.
                        <br>
                        Та доорх товчийг дарж нүүр хуудас руугаа буцаарай.
                    </p>
                    
                    <!-- Товчнууд -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-house"></i> Нүүр хуудас
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </button>
                    </div>
                    
                    <!-- Хайлтын form -->
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="mb-3">Эсвэл та хайлт хийж үзээрэй</h5>
                        <form action="<?php echo BASE_URL; ?>index.php" method="GET" class="mx-auto" style="max-width: 500px;">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Хайх үгээ оруулна уу..."
                                       aria-label="Search">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Хайх
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Холбоосууд -->
                    <div class="mt-5">
                        <h6 class="mb-3 text-muted">Эдгээр хуудсууд танд тус болж магадгүй:</h6>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="<?php echo BASE_URL; ?>index.php?page=services" class="text-decoration-none">
                                <i class="bi bi-briefcase"></i> Үйлчилгээ
                            </a>
                            <span class="text-muted">|</span>
                            <a href="<?php echo BASE_URL; ?>index.php?page=news" class="text-decoration-none">
                                <i class="bi bi-newspaper"></i> Мэдээ
                            </a>
                            <span class="text-muted">|</span>
                            <a href="<?php echo BASE_URL; ?>index.php?page=contact" class="text-decoration-none">
                                <i class="bi bi-envelope"></i> Холбоо барих
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.error-404 {
    padding: 60px 20px;
}

.error-404 .display-1 {
    line-height: 1;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

.error-404 i.bi-exclamation-triangle {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.error-404 a:hover {
    text-decoration: underline !important;
}
</style>