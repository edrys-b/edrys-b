<?php
$page_title = 'Home - Professional Supply & Contracting Services';
$meta_description = 'B-AIBUDA GLOBAL NIGERIA LIMITED - Leading supply and contracting company in Nigeria specializing in civil engineering, import/export, oil & gas, and general contracts & supplies.';
include 'includes/header.php';

// Get content from database
$vision = getContent('vision');
$mission = getContent('mission');
$about_content = getContent('about_content');
$ceo_info = getContent('ceo_name');
$services = getAllServices();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">B-AIBUDA GLOBAL<br><span class="text-warning">NIGERIA LIMITED</span></h1>
                    <p class="hero-subtitle">Your trusted partner in supply chain management, contracting services, and engineering solutions across Nigeria and beyond.</p>
                    <div class="hero-buttons">
                        <a href="services.php" class="btn btn-warning btn-lg me-3">Our Services</a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg">Get Quote</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="uploads/logo.jpg" alt="B-AIBUDA GLOBAL Logo" class="img-fluid" style="max-height: 400px; filter: brightness(1.2);">
            </div>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-start">About Our Company</h2>
                <p class="lead"><?php echo htmlspecialchars($about_content['content'] ?? 'We are a leading supply and contracting company in Nigeria.'); ?></p>
                
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Quality Service</h6>
                                <small class="text-muted">Professional Excellence</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Expert Team</h6>
                                <small class="text-muted">Experienced Professionals</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="about.php" class="btn btn-primary mt-3">Learn More About Us</a>
            </div>
            <div class="col-lg-6">
                <img src="uploads/ceo.jpg" alt="CEO Murtala Adamu" class="img-fluid about-image">
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Core Services</h2>
            <p class="section-subtitle">We offer comprehensive services across multiple sectors, delivering quality solutions that meet international standards.</p>
        </div>
        
        <div class="row">
            <?php 
            $featured_services = array_slice($services, 0, 6);
            foreach ($featured_services as $service): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card h-100">
                    <div class="service-icon">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                    </div>
                    <h5 class="service-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                    <p class="service-description"><?php echo htmlspecialchars(truncateText($service['description'], 120)); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-primary btn-lg">View All Services</a>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="vision-mission-card">
                    <h4><i class="fas fa-eye text-primary me-2"></i>Our Vision</h4>
                    <p><?php echo nl2br(htmlspecialchars($vision['content'] ?? 'To be the leading supply and contracting company in Nigeria.')); ?></p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="vision-mission-card">
                    <h4><i class="fas fa-bullseye text-primary me-2"></i>Our Mission</h4>
                    <p><?php echo nl2br(htmlspecialchars($mission['content'] ?? 'To provide exceptional supply and contracting services.')); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="15">0</span>
                    <span class="stat-label">Years Experience</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="500">0</span>
                    <span class="stat-label">Projects Completed</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="50">0</span>
                    <span class="stat-label">Expert Team</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <span class="stat-number" data-count="99">0</span>
                    <span class="stat-label">Success Rate %</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CEO Section -->
<section class="section-padding">
    <div class="container">
        <div class="ceo-section text-center">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <img src="uploads/ceo.jpg" alt="CEO Murtala Adamu" class="ceo-image mx-auto d-block">
                </div>
                <div class="col-lg-8">
                    <h3 class="text-primary">Meet Our CEO</h3>
                    <h4><?php echo htmlspecialchars($ceo_info['content'] ?? 'Murtala Adamu'); ?></h4>
                    <p class="lead text-muted">
                        <?php 
                        $ceo_bio = getContent('ceo_bio');
                        echo htmlspecialchars($ceo_bio['content'] ?? 'Leading the company with vision for excellence and innovation in service delivery.');
                        ?>
                    </p>
                    <a href="about.php#ceo" class="btn btn-outline-primary">Read Full Biography</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-primary text-white section-padding">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Ready to Start Your Project?</h2>
                <p class="lead mb-4">Get in touch with us today to discuss your requirements and receive a customized solution for your business needs.</p>
                <div>
                    <a href="contact.php" class="btn btn-warning btn-lg me-3">Contact Us Today</a>
                    <a href="tel:08035547894" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Counter Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200; // Animation speed

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-count'));
        const increment = target / speed;
        let current = 0;

        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 1);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    };

    // Intersection Observer for animation trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target.querySelector('.stat-number');
                if (counter && !counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    animateCounter(counter);
                }
            }
        });
    });

    // Observe stats section
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>

<?php include 'includes/footer.php'; ?>