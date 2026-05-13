// ===== Document Ready =====
document.addEventListener('DOMContentLoaded', function() {
    
    // Navbar scroll эффект
    initNavbarScroll();
    
    // Smooth scrolling
    initSmoothScroll();
    
    // Back to top товч
    initBackToTop();
    
    // Form validation
    initFormValidation();
    
    // Image lazy loading
    initLazyLoading();
    
    // Animations on scroll
    initScrollAnimations();
    
    // Auto dismiss alerts
    autoDismissAlerts();
});

// ===== Navbar Scroll Effect =====
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    
    if (!navbar) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow');
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
        } else {
            navbar.classList.remove('shadow');
            navbar.style.backgroundColor = '';
        }
    });
}

// ===== Smooth Scrolling =====
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Хоосон эсвэл зөвхөн # бол үл хар
            if (!href || href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ===== Back to Top Button =====
function initBackToTop() {
    // Back to top товч үүсгэх
    const backToTop = document.createElement('button');
    backToTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
    backToTop.className = 'btn btn-primary btn-back-to-top';
    backToTop.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        z-index: 999;
        border: none;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(backToTop);
    
    // Scroll хийхэд харуулах
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });
    
    // Click хийхэд дээш явах
    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Hover эффект
    backToTop.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    backToTop.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
}

// ===== Form Validation =====
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// ===== Image Lazy Loading =====
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// ===== Scroll Animations =====
function initScrollAnimations() {
    const elements = document.querySelectorAll('.fade-in-up');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    elements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
}

// ===== Auto Dismiss Alerts =====
function autoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5 секундын дараа хаах
    });
}

// ===== Image Preview =====
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// ===== Loading Spinner =====
function showLoading() {
    const spinner = document.createElement('div');
    spinner.id = 'globalSpinner';
    spinner.className = 'position-fixed top-50 start-50 translate-middle';
    spinner.style.zIndex = '9999';
    spinner.innerHTML = `
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Ачааллаж байна...</span>
        </div>
    `;
    
    document.body.appendChild(spinner);
    
    // Overlay нэмэх
    const overlay = document.createElement('div');
    overlay.id = 'globalOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    `;
    
    document.body.appendChild(overlay);
}

function hideLoading() {
    const spinner = document.getElementById('globalSpinner');
    const overlay = document.getElementById('globalOverlay');
    
    if (spinner) spinner.remove();
    if (overlay) overlay.remove();
}

// ===== Confirm Dialog =====
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// ===== Toast Notification =====
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Автоматаар устгах
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// ===== Copy to Clipboard =====
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Хуулагдлаа!', 'success');
    }).catch(() => {
        showToast('Хуулахад алдаа гарлаа!', 'danger');
    });
}

// ===== Print Page =====
function printPage() {
    window.print();
}

// ===== Share Page =====
function sharePage(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    
    const shareUrls = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
        linkedin: `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`
    };
    
    if (shareUrls[platform]) {
        window.open(shareUrls[platform], '_blank', 'width=600,height=400');
    }
}

// ===== Global Error Handler =====
window.addEventListener('error', function(e) {
    console.error('JavaScript алдаа:', e.error);
});

// Export functions for use in other scripts
window.siteJS = {
    previewImage,
    showLoading,
    hideLoading,
    confirmAction,
    showToast,
    copyToClipboard,
    printPage,
    sharePage
};