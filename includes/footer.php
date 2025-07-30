    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-primary mb-3">
                        <img src="uploads/logo.jpg" alt="Logo" height="30" class="me-2">
                        <?php echo htmlspecialchars($company_name); ?>
                    </h5>
                    <p class="mb-3"><?php echo htmlspecialchars(getContent('about_content')['content'] ?? 'Leading supply and contracting company in Nigeria.'); ?></p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-primary mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="about.php" class="text-white-50 text-decoration-none">About</a></li>
                        <li><a href="services.php" class="text-white-50 text-decoration-none">Services</a></li>
                        <li><a href="gallery.php" class="text-white-50 text-decoration-none">Gallery</a></li>
                        <li><a href="contact.php" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-primary mb-3">Our Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="services.php#civil" class="text-white-50 text-decoration-none">Civil Engineering</a></li>
                        <li><a href="services.php#construction" class="text-white-50 text-decoration-none">Construction</a></li>
                        <li><a href="services.php#import" class="text-white-50 text-decoration-none">Import & Export</a></li>
                        <li><a href="services.php#oil" class="text-white-50 text-decoration-none">Oil & Gas</a></li>
                        <li><a href="services.php#contracts" class="text-white-50 text-decoration-none">General Contracts</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-primary mb-3">Contact Info</h5>
                    <div class="contact-info">
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <?php echo htmlspecialchars(getContent('company_address')['content'] ?? 'Argungu Town, Kebbi State, Nigeria'); ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <?php echo htmlspecialchars($company_phone); ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <?php echo htmlspecialchars($company_email); ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-globe text-primary me-2"></i>
                            www.baibudaglobal.org.ng
                        </p>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($company_name); ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small class="text-white-50">
                            RC: <?php echo htmlspecialchars(getContent('company_title')['content'] ?? '8398525'); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="btn btn-primary btn-floating" id="backToTop" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <!-- Back to Top Script -->
    <script>
        // Back to top button
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>