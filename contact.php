<?php
$page_title = 'Contact Us - Get In Touch';
$meta_description = 'Contact B-AIBUDA GLOBAL NIGERIA LIMITED for inquiries about our services. We provide supply chain, contracting, and engineering solutions across Nigeria.';
include 'includes/header.php';

// Get company contact information
$company_address = getContent('company_address');
$company_email = getContent('company_email');
$company_phone = getContent('company_phone');

$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error_message = 'Security token mismatch. Please try again.';
    } else {
        // Sanitize input
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $subject = sanitizeInput($_POST['subject'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        
        // Validate input
        $errors = [];
        
        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Name must be at least 2 characters long.';
        }
        
        if (empty($email) || !validateEmail($email)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (empty($subject) || strlen($subject) < 5) {
            $errors[] = 'Subject must be at least 5 characters long.';
        }
        
        if (empty($message) || strlen($message) < 10) {
            $errors[] = 'Message must be at least 10 characters long.';
        }
        
        if (empty($errors)) {
            // Save to database
            if (saveContactMessage($name, $email, $phone, $subject, $message)) {
                // Send email notification
                if (sendContactEmail($name, $email, $phone, $subject, $message)) {
                    $success_message = 'Thank you for your message! We will get back to you soon.';
                } else {
                    $success_message = 'Your message has been saved. We will contact you soon.';
                }
                
                // Clear form data
                $name = $email = $phone = $subject = $message = '';
            } else {
                $error_message = 'There was an error sending your message. Please try again.';
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                <p class="lead">Get in touch with us for inquiries about our services or to discuss your project requirements.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8 mb-5">
                <div class="contact-form">
                    <h3 class="text-primary mb-4">Send us a Message</h3>
                    
                    <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" id="contactForm">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <select class="form-control" id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry" <?php echo (isset($subject) && $subject === 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="Civil Engineering" <?php echo (isset($subject) && $subject === 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                                    <option value="Construction Services" <?php echo (isset($subject) && $subject === 'Construction Services') ? 'selected' : ''; ?>>Construction Services</option>
                                    <option value="Import/Export" <?php echo (isset($subject) && $subject === 'Import/Export') ? 'selected' : ''; ?>>Import/Export</option>
                                    <option value="Oil and Gas" <?php echo (isset($subject) && $subject === 'Oil and Gas') ? 'selected' : ''; ?>>Oil and Gas</option>
                                    <option value="General Contracts" <?php echo (isset($subject) && $subject === 'General Contracts') ? 'selected' : ''; ?>>General Contracts</option>
                                    <option value="Quote Request" <?php echo (isset($subject) && $subject === 'Quote Request') ? 'selected' : ''; ?>>Quote Request</option>
                                    <option value="Partnership" <?php echo (isset($subject) && $subject === 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="6" placeholder="Please provide details about your inquiry or project requirements..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="privacy" required>
                            <label class="form-check-label" for="privacy">
                                I agree to the processing of my personal data for the purpose of responding to my inquiry.
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h4 class="mb-4">Contact Information</h4>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6>Office Address</h6>
                            <p><?php echo nl2br(htmlspecialchars($company_address['content'] ?? 'No. 1 Near sheikh abubakar mahmud gumi, juma\'at mosque argungu town, kebbi state, nigeria')); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h6>Phone Numbers</h6>
                            <p><?php echo htmlspecialchars($company_phone['content'] ?? '08035547894, 08022070807, 08076104589'); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6>Email Address</h6>
                            <p><a href="mailto:<?php echo htmlspecialchars($company_email['content'] ?? 'mcoinvestmentnigltd@gmail.com'); ?>" class="text-white"><?php echo htmlspecialchars($company_email['content'] ?? 'mcoinvestmentnigltd@gmail.com'); ?></a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <h6>Website</h6>
                            <p><a href="https://www.baibudaglobal.org.ng" class="text-white">www.baibudaglobal.org.ng</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6>Business Hours</h6>
                            <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 4:00 PM<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="text-primary">Find Us</h3>
            <p class="text-muted">Locate our office in Argungu Town, Kebbi State, Nigeria</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9 rounded shadow">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3873.123456789!2d4.5167!3d12.7500!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTLCsDQ1JzAwLjAiTiA0wrAzMScwMC4wIkU!5e0!3m2!1sen!2sng!4v1234567890"
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="text-primary">Need Immediate Assistance?</h3>
            <p class="text-muted">Contact us directly for urgent inquiries</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-phone fa-2x"></i>
                    </div>
                    <h5>Call Us</h5>
                    <p class="text-muted mb-3">Speak directly with our team</p>
                    <a href="tel:08035547894" class="btn btn-outline-primary">Call Now</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <h5>Email Us</h5>
                    <p class="text-muted mb-3">Send us your detailed inquiry</p>
                    <a href="mailto:<?php echo htmlspecialchars($company_email['content'] ?? 'mcoinvestmentnigltd@gmail.com'); ?>" class="btn btn-outline-success">Send Email</a>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <h5>Visit Us</h5>
                    <p class="text-muted mb-3">Come to our office in Argungu</p>
                    <button type="button" class="btn btn-outline-info" onclick="scrollToMap()">View Location</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-light section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="text-primary">Frequently Asked Questions</h3>
            <p class="text-muted">Common questions about our services and processes</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                What services do you offer?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer comprehensive services including civil engineering, roads & bridges construction, building construction, import/export services, oil & gas solutions, and general contracting & supplies.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                How can I get a quote for my project?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can request a quote by filling out our contact form above, calling us directly, or sending an email with your project details. We'll respond within 24 hours with a detailed quotation.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Do you work on projects outside Kebbi State?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we work on projects across Nigeria and internationally. Our team is equipped to handle projects of various scales and locations.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                What is your typical project timeline?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Project timelines vary depending on scope and complexity. We provide detailed timelines during the quotation phase and maintain regular communication throughout the project duration.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Form validation
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value.trim();
    const privacy = document.getElementById('privacy').checked;
    
    let errors = [];
    
    if (name.length < 2) {
        errors.push('Name must be at least 2 characters long.');
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errors.push('Please enter a valid email address.');
    }
    
    if (!subject) {
        errors.push('Please select a subject.');
    }
    
    if (message.length < 10) {
        errors.push('Message must be at least 10 characters long.');
    }
    
    if (!privacy) {
        errors.push('Please accept the privacy policy.');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Please fix the following errors:\n\n' + errors.join('\n'));
    }
});

// Scroll to map function
function scrollToMap() {
    document.querySelector('.ratio').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<?php include 'includes/footer.php'; ?>