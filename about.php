<?php
$page_title = 'About Us - Company Profile & Leadership';
$meta_description = 'Learn about B-AIBUDA GLOBAL NIGERIA LIMITED - our company history, vision, mission, leadership team, and commitment to excellence in supply chain and contracting services.';
include 'includes/header.php';

// Get content from database
$about_content = getContent('about_content');
$vision = getContent('vision');
$mission = getContent('mission');
$core_values = getContent('core_values');
$ceo_name = getContent('ceo_name');
$ceo_bio = getContent('ceo_bio');
$company_info = getContent('company_title');
$board_directors = getContent('board_directors');
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">About Our Company</h1>
                <p class="lead">Discover the story behind B-AIBUDA GLOBAL NIGERIA LIMITED and our commitment to excellence in service delivery.</p>
            </div>
        </div>
    </div>
</section>

<!-- Company Overview -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-start">Our Company</h2>
                <p class="lead"><?php echo htmlspecialchars($about_content['content'] ?? 'B-AIBUDA GLOBAL NIGERIA LIMITED is a leading supply and contracting company committed to excellence in service delivery.'); ?></p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="border-start border-primary border-4 ps-3 mb-4">
                            <h6 class="text-primary mb-1">Company Registration</h6>
                            <p class="mb-0"><?php echo htmlspecialchars($company_info['content'] ?? 'RC: 8398525'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-start border-success border-4 ps-3 mb-4">
                            <h6 class="text-success mb-1">Years of Experience</h6>
                            <p class="mb-0">15+ Years in the Industry</p>
                        </div>
                    </div>
                </div>
                
                <div class="border-start border-warning border-4 ps-3">
                    <h6 class="text-warning mb-1">Board of Directors</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($board_directors['content'] ?? 'Murtala Adamu, Bashar Adamu and Muhammad Abubakar'); ?></p>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="uploads/logo.jpg" alt="B-AIBUDA GLOBAL Logo" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Vision & Mission</h2>
            <p class="section-subtitle">Guiding principles that drive our commitment to excellence and innovation</p>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="vision-mission-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-eye fa-lg"></i>
                        </div>
                        <h4 class="text-primary mb-0">Our Vision</h4>
                    </div>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($vision['content'] ?? 'To maintain and strengthen our core supply and general contracting business, to develop new innovations and technical ideas, and to respond to the changing need of our clients.')); ?></p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="vision-mission-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-bullseye fa-lg"></i>
                        </div>
                        <h4 class="text-success mb-0">Our Mission</h4>
                    </div>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($mission['content'] ?? 'To develop and expand the Nigeria supply and procurement industry through high level of professionalism and procurement skills.')); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Core Values</h2>
            <p class="section-subtitle">The fundamental beliefs that guide our actions and decisions</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="bg-light rounded-lg p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-heart text-primary fa-3x"></i>
                    </div>
                    <div class="core-values-content">
                        <?php 
                        $values_content = $core_values['content'] ?? 'Customers First: We exist to serve our customers. Innovation: We are intuitive, curious, inventive. Teamwork: Success requires teamwork.';
                        $values_paragraphs = explode("\n\n", $values_content);
                        foreach ($values_paragraphs as $paragraph) {
                            if (trim($paragraph)) {
                                echo '<p class="lead mb-3">' . nl2br(htmlspecialchars(trim($paragraph))) . '</p>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CEO Section -->
<section class="section-padding bg-light" id="ceo">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Meet Our Leadership</h2>
            <p class="section-subtitle">Experienced leadership driving innovation and excellence</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ceo-section bg-white">
                    <div class="row align-items-center">
                        <div class="col-lg-4 text-center">
                            <img src="uploads/ceo.jpg" alt="CEO Murtala Adamu" class="ceo-image mx-auto d-block mb-4">
                            <h4 class="text-primary"><?php echo htmlspecialchars($ceo_name['content'] ?? 'Murtala Adamu'); ?></h4>
                            <p class="text-muted">Chief Executive Officer</p>
                            
                            <div class="social-links mt-3">
                                <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="mailto:mcoinvestmentnigltd@gmail.com" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <h5 class="text-primary mb-3">Biography</h5>
                            <p class="lead text-muted">
                                <?php echo nl2br(htmlspecialchars($ceo_bio['content'] ?? 'Murtala Adamu is the Chief Executive Officer of B-AIBUDA GLOBAL NIGERIA LIMITED. With extensive experience in the supply and procurement industry, he leads the company with a vision for excellence and innovation in service delivery.')); ?>
                            </p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-graduation-cap text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Education</h6>
                                            <small class="text-muted">Advanced Business Management</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-briefcase text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Experience</h6>
                                            <small class="text-muted">15+ Years Industry Leadership</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Choose Us?</h2>
            <p class="section-subtitle">What sets us apart in the supply chain and contracting industry</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-award fa-2x"></i>
                    </div>
                    <h5>Quality Assurance</h5>
                    <p class="text-muted">We maintain the highest standards in all our projects and services, ensuring quality delivery every time.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h5>Timely Delivery</h5>
                    <p class="text-muted">We understand the importance of deadlines and consistently deliver projects on time.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h5>Expert Team</h5>
                    <p class="text-muted">Our team consists of experienced professionals with expertise across multiple industries.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h5>Customer Focus</h5>
                    <p class="text-muted">We prioritize our customers' needs and work closely with them to achieve their goals.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-lightbulb fa-2x"></i>
                    </div>
                    <h5>Innovation</h5>
                    <p class="text-muted">We continuously innovate and adapt to meet the evolving needs of our clients and industry.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5>Reliability</h5>
                    <p class="text-muted">You can count on us for consistent, reliable service and long-term partnership.</p>
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
                <h2 class="mb-4">Ready to Work with Us?</h2>
                <p class="lead mb-4">Join the many satisfied clients who have trusted us with their supply chain and contracting needs.</p>
                <div>
                    <a href="contact.php" class="btn btn-warning btn-lg me-3">Get Started Today</a>
                    <a href="services.php" class="btn btn-outline-light btn-lg">View Our Services</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>