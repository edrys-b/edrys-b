<?php
session_start();
require_once __DIR__ . '/functions.php';

// Get company information
$company_name = getContent('company_name')['content'] ?? 'B-AIBUDA GLOBAL NIGERIA LIMITED';
$company_email = getContent('company_email')['content'] ?? 'mcoinvestmentnigltd@gmail.com';
$company_phone = getContent('company_phone')['content'] ?? '08035547894';

// Set default page title and description if not set
$page_title = $page_title ?? 'Professional Supply & Contracting Services';
$meta_description = $meta_description ?? 'B-AIBUDA GLOBAL NIGERIA LIMITED - Leading supply and contracting company specializing in civil engineering, import/export, oil & gas, and general contracts & supplies.';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo generateSEOTitle($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="keywords" content="Nigeria supply, contracting services, civil engineering, import export, oil gas, construction, B-AIBUDA GLOBAL">
    <meta name="author" content="B-AIBUDA GLOBAL NIGERIA LIMITED">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo generateSEOTitle($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:url" content="https://www.baibudaglobal.org.ng">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://www.baibudaglobal.org.ng/uploads/logo.jpg">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="uploads/favicon.ico">
</head>
<body>
    <!-- Top Bar -->
    <div class="bg-primary text-white py-2 d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <small><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($company_email); ?></small>
                </div>
                <div class="col-md-6 text-end">
                    <small><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($company_phone); ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="uploads/logo.jpg" alt="<?php echo htmlspecialchars($company_name); ?>" height="50" class="me-2">
                <span class="fw-bold text-primary d-none d-md-inline"><?php echo htmlspecialchars($company_name); ?></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'index' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'about' ? 'active' : ''; ?>" href="about.php">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'services' ? 'active' : ''; ?>" href="services.php">
                            <i class="fas fa-cogs me-1"></i>Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'gallery' ? 'active' : ''; ?>" href="gallery.php">
                            <i class="fas fa-images me-1"></i>Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'contact' ? 'active' : ''; ?>" href="contact.php">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="admin/">
                            <i class="fas fa-user-shield me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>