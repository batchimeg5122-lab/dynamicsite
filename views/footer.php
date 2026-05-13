<?php
// Footer тохиргоо авах
$footerText = getSetting('footer_text');
$facebook = getSetting('facebook');
$youtube = getSetting('youtube');
$instagram = getSetting('instagram');

// Сүүлийн мэдээ авах (Footer-т харуулах)
$recentNewsQuery = "SELECT * FROM news ORDER BY created_at DESC LIMIT 3";
$recentNews = $conn->query($recentNewsQuery);

// Үйлчилгээний тоо авах
$servicesCount = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
?>

    <!-- Footer -->
    <footer class="bg-dark text-white">
        <!-- Main Footer -->
        <div class="container py-5">
            <div class="row g-4">
                <!-- Компанийн мэдээлэл -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3 fw-bold">
                        <?php if($logo): ?>
                            <img src="<?php echo UPLOAD_URL . $logo; ?>" alt="<?php echo $siteName; ?>" style="height: 40px;" class="mb-2">
                        <?php else: ?>
                            <?php echo $siteName; ?>
                        <?php endif; ?>
                    </h5>
                    
                    <!-- Сошиал медиа -->
                    <?php if($facebook || $youtube || $instagram): ?>
                    <div class="mb-3">
                        <h6 class="mb-2">Биднийг дагаарай:</h6>
                        <div class="d-flex gap-2">
                            <?php if($facebook): ?>
                                <a href="<?php echo $facebook; ?>" 
                                   target="_blank" 
                                   class="btn btn-outline-light btn-sm rounded-circle"
                                   style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                   title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if($youtube): ?>
                                <a href="<?php echo $youtube; ?>" 
                                   target="_blank" 
                                   class="btn btn-outline-light btn-sm rounded-circle"
                                   style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                   title="YouTube">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if($instagram): ?>
                                <a href="<?php echo $instagram; ?>" 
                                   target="_blank" 
                                   class="btn btn-outline-light btn-sm rounded-circle"
                                   style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                   title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Хурдан холбоосууд -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="mb-3 fw-bold">Холбоосууд</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>" class="text-muted text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Нүүр
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>index.php?page=services" class="text-muted text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Үйлчилгээ
                                <?php if($servicesCount > 0): ?>
                                    <span class="badge bg-primary ms-1"><?php echo $servicesCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>index.php?page=news" class="text-muted text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Мэдээ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>index.php?page=contact" class="text-muted text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Холбоо барих
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Сүүлийн мэдээ -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-3 fw-bold">Сүүлийн мэдээ</h5>
                    <?php if($recentNews->num_rows > 0): ?>
                        <ul class="list-unstyled">
                            <?php while($newsItem = $recentNews->fetch_assoc()): ?>
                                <li class="mb-3">
                                    <a href="<?php echo BASE_URL; ?>index.php?page=news-detail&slug=<?php echo $newsItem['id']; ?>" 
                                       class="text-decoration-none">
                                        <div class="d-flex">
                                            <?php if($newsItem['image']): ?>
                                                <img src="<?php echo UPLOAD_URL . $newsItem['image']; ?>" 
                                                     alt="<?php echo $newsItem['title']; ?>"
                                                     class="rounded me-2"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="text-white mb-1 small">
                                                    <?php echo mb_substr($newsItem['title'], 0, 50, 'UTF-8'); ?>...
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i> 
                                                    <?php echo date('M d, Y', strtotime($newsItem['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small">Мэдээ байхгүй байна</p>
                    <?php endif; ?>
                </div>
                
                <!-- Холбоо барих мэдээлэл -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-3 fw-bold">Холбоо барих</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="bi bi-geo-alt-fill text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted">Хаяг</small>
                                    <p class="mb-0 text-white">
                                        Улаанбаатар хот<br>
                                        Сүхбаатар дүүрэг, 1-р хороо
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="bi bi-telephone-fill text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted">Утас</small>
                                    <p class="mb-0">
                                        <a href="tel:+97670000000" class="text-white text-decoration-none">
                                            +976 7000-0000
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="bi bi-envelope-fill text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted">И-мэйл</small>
                                    <p class="mb-0">
                                        <a href="mailto:info@company.mn" class="text-white text-decoration-none">
                                            info@company.mn
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-top border-secondary">
            <div class="container py-3">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <p class="mb-0 text-muted small">
                            <?php echo $footerText; ?>
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="<?php echo BASE_URL; ?>" class="text-muted small text-decoration-none">
                                    Үйлчилгээний нөхцөл
                                </a>
                            </li>
                            <li class="list-inline-item">|</li>
                            <li class="list-inline-item">
                                <a href="<?php echo BASE_URL; ?>" class="text-muted small text-decoration-none">
                                    Нууцлалын бодлого
                                </a>
                            </li>
                            <li class="list-inline-item">|</li>
                            <li class="list-inline-item">
                                <a href="<?php echo ADMIN_URL; ?>login.php" class="text-muted small text-decoration-none">
                                    <i class="bi bi-lock"></i> Админ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button (JavaScript-ээс үүсгэгдэнэ) -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>

<style>
/* Footer стайлууд */
footer {
    position: relative;
    margin-top: auto;
}

footer h5 {
    position: relative;
    padding-bottom: 10px;
}

footer h5::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.footer-links a {
    transition: all 0.3s ease;
    display: inline-block;
}

.footer-links a:hover {
    color: #fff !important;
    transform: translateX(5px);
}

.footer-links a:hover i {
    color: #667eea;
}

/* Сошиал товчнууд */
footer .btn-outline-light:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Холбоо барих icons */
footer .bi-geo-alt-fill,
footer .bi-telephone-fill,
footer .bi-envelope-fill {
    transition: transform 0.3s ease;
}

footer li:hover .bi-geo-alt-fill,
footer li:hover .bi-telephone-fill,
footer li:hover .bi-envelope-fill {
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    footer .col-md-6 {
        text-align: center !important;
    }
    
    footer h5::after {
        left: 50%;
        transform: translateX(-50%);
    }
}
footer a,
footer p,
footer small,
footer li,
footer .text-muted {
    color: #cfd3dd !important;
}

footer a:hover {
    color: #ffffff !important;
}

</style>

</body>
</html>