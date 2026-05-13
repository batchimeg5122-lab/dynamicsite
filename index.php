<?php
require_once 'config.php';

// URL-аас хуудас авах
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Site тохиргоо авах
$siteName = getSetting('site_name');
$logo = getSetting('logo');

// Header оруулах
include 'views/header.php';

// Хуудас сонгох
switch($page) {
    case 'home':
        include 'views/home.php';
        break;
    
    case 'page':
        if ($slug) {
            include 'views/page.php';
        } else {
            include 'views/404.php';
        }
        break;
    
    case 'services':
        include 'views/services.php';
        break;
    
    case 'news':
        include 'views/news_list.php';
        break;
    
    case 'news-detail':
        if ($slug) {
            include 'views/news_single.php';
        } else {
            include 'views/404.php';
        }
        break;
    
    case 'contact':
        include 'views/contact.php';
        break;
    
    case 'gallery':
        include 'views/gallery.php';
        break;
    
    case 'testimonials':
        include 'views/testimonials.php';
        break;
    
    case 'faq':
        include 'views/faq.php';
        break;
    
    case 'portfolio':
        include 'views/portfolio.php';
        break;
    
    case 'search':
        include 'views/search.php';
        break;
    
    default:
        include 'views/404.php';
        break;
}

// Footer оруулах
include 'views/footer.php';
?>