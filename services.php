<?php
$page_title = 'Our Services - Comprehensive Solutions';
$meta_description = 'Explore our comprehensive range of services including civil engineering, construction, import/export, oil & gas, and general contracting solutions across Nigeria.';
include 'includes/header.php';

// Get services from database
$services = getAllServices();
$services_intro = getContent('services_intro');
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Services</h1>
                <p class="lead"><?php echo htmlspecialchars($services_intro['content'] ?? 'We offer comprehensive services across multiple sectors, delivering quality solutions that meet international standards.'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Comprehensive Business Solutions</h2>
            <p class="section-subtitle">From civil engineering to oil & gas, we provide end-to-end solutions for your business needs</p>
        </div>
        
        <div class="row">
            <?php foreach ($services as $index => $service): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card h-100" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="service-icon">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                    </div>
                    <h5 class="service-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                    <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                    <a href="#service-<?php echo $service['id']; ?>" class="btn btn-outline-primary btn-sm">Learn More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Detailed Services -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Service Details</h2>
            <p class="section-subtitle">Comprehensive information about each of our specialized services</p>
        </div>
        
        <!-- Civil Engineering -->
        <div class="service-detail mb-5" id="service-1">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <h3 class="text-primary">Civil Engineering</h3>
                    <p class="lead">Professional civil engineering services including roads, bridges, and building construction with adherence to international standards.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Structural Design</li>
                                <li><i class="fas fa-check text-success me-2"></i>Project Management</li>
                                <li><i class="fas fa-check text-success me-2"></i>Quality Assurance</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Safety Compliance</li>
                                <li><i class="fas fa-check text-success me-2"></i>Cost Optimization</li>
                                <li><i class="fas fa-check text-success me-2"></i>Environmental Impact</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-primary mb-3">Key Features</h5>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-drafting-compass text-primary me-2"></i>Advanced Design</h6>
                            <p class="text-muted mb-0">Using latest CAD technology and engineering software</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-hard-hat text-primary me-2"></i>Safety First</h6>
                            <p class="text-muted mb-0">Strict adherence to safety protocols and regulations</p>
                        </div>
                        <div class="feature-item">
                            <h6><i class="fas fa-certificate text-primary me-2"></i>Certified Engineers</h6>
                            <p class="text-muted mb-0">Licensed and experienced engineering professionals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roads & Bridges -->
        <div class="service-detail mb-5" id="service-2">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-road fa-2x"></i>
                    </div>
                    <h3 class="text-success">Roads & Bridges Construction</h3>
                    <p class="lead">Comprehensive road and bridge construction services, from planning to completion, ensuring durability and safety.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Highway Construction</li>
                                <li><i class="fas fa-check text-success me-2"></i>Bridge Engineering</li>
                                <li><i class="fas fa-check text-success me-2"></i>Drainage Systems</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Traffic Management</li>
                                <li><i class="fas fa-check text-success me-2"></i>Maintenance Services</li>
                                <li><i class="fas fa-check text-success me-2"></i>Quality Testing</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-success mb-3">Our Expertise</h5>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-map-marked-alt text-success me-2"></i>Site Survey</h6>
                            <p class="text-muted mb-0">Comprehensive topographical and geological surveys</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-tools text-success me-2"></i>Modern Equipment</h6>
                            <p class="text-muted mb-0">State-of-the-art construction machinery and tools</p>
                        </div>
                        <div class="feature-item">
                            <h6><i class="fas fa-clock text-success me-2"></i>Timely Completion</h6>
                            <p class="text-muted mb-0">Efficient project delivery within agreed timelines</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Building Construction -->
        <div class="service-detail mb-5" id="service-3">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <h3 class="text-warning">Building Construction</h3>
                    <p class="lead">Complete building construction services for residential, commercial, and industrial projects with modern techniques.</p>
                    
                    <div class="construction-types">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-home fa-2x text-warning mb-2"></i>
                                    <h6>Residential</h6>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-building fa-2x text-warning mb-2"></i>
                                    <h6>Commercial</h6>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <i class="fas fa-industry fa-2x text-warning mb-2"></i>
                                    <h6>Industrial</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-warning mb-3">Construction Process</h5>
                        <div class="timeline">
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 12px;">1</div>
                                    <div>
                                        <h6>Planning & Design</h6>
                                        <p class="text-muted mb-0">Architectural design and project planning</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 12px;">2</div>
                                    <div>
                                        <h6>Foundation & Structure</h6>
                                        <p class="text-muted mb-0">Foundation laying and structural work</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="d-flex">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 12px;">3</div>
                                    <div>
                                        <h6>Finishing & Handover</h6>
                                        <p class="text-muted mb-0">Interior finishing and project completion</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import and Export -->
        <div class="service-detail mb-5" id="service-4">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-ship fa-2x"></i>
                    </div>
                    <h3 class="text-info">Import and Export</h3>
                    <p class="lead">Efficient import and export services facilitating international trade with reliable logistics and documentation.</p>
                    
                    <div class="trade-services">
                        <h6 class="text-info">Import Services</h6>
                        <ul class="list-unstyled mb-3">
                            <li><i class="fas fa-arrow-down text-info me-2"></i>Customs Clearance</li>
                            <li><i class="fas fa-arrow-down text-info me-2"></i>Documentation</li>
                            <li><i class="fas fa-arrow-down text-info me-2"></i>Warehousing</li>
                        </ul>
                        
                        <h6 class="text-info">Export Services</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-arrow-up text-info me-2"></i>Market Research</li>
                            <li><i class="fas fa-arrow-up text-info me-2"></i>Logistics Coordination</li>
                            <li><i class="fas fa-arrow-up text-info me-2"></i>Quality Assurance</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-info mb-3">Trade Solutions</h5>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-globe text-info me-2"></i>Global Network</h6>
                            <p class="text-muted mb-0">Extensive international trade partnerships</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-file-contract text-info me-2"></i>Compliance</h6>
                            <p class="text-muted mb-0">Full regulatory compliance and documentation</p>
                        </div>
                        <div class="feature-item">
                            <h6><i class="fas fa-truck text-info me-2"></i>Logistics</h6>
                            <p class="text-muted mb-0">End-to-end supply chain management</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Oil and Gas -->
        <div class="service-detail mb-5" id="service-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-oil-well fa-2x"></i>
                    </div>
                    <h3 class="text-danger">Oil and Gas</h3>
                    <p class="lead">Specialized services in the oil and gas sector including equipment supply and technical support.</p>
                    
                    <div class="oil-gas-services">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Upstream Services</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-danger me-2"></i>Exploration Support</li>
                                    <li><i class="fas fa-check text-danger me-2"></i>Drilling Equipment</li>
                                    <li><i class="fas fa-check text-danger me-2"></i>Well Completion</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Downstream Services</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-danger me-2"></i>Refinery Support</li>
                                    <li><i class="fas fa-check text-danger me-2"></i>Pipeline Services</li>
                                    <li><i class="fas fa-check text-danger me-2"></i>Storage Solutions</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-danger mb-3">Industry Expertise</h5>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-hard-hat text-danger me-2"></i>Safety Standards</h6>
                            <p class="text-muted mb-0">Strict adherence to international safety protocols</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-cog text-danger me-2"></i>Technical Expertise</h6>
                            <p class="text-muted mb-0">Specialized knowledge in oil and gas operations</p>
                        </div>
                        <div class="feature-item">
                            <h6><i class="fas fa-leaf text-danger me-2"></i>Environmental Care</h6>
                            <p class="text-muted mb-0">Environmentally responsible practices</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Contracts & Supplies -->
        <div class="service-detail" id="service-6">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h3 class="text-secondary">General Contracts & Supplies</h3>
                    <p class="lead">Comprehensive contracting and supply solutions for various industries with quality assurance.</p>
                    
                    <div class="contract-types">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Contract Services</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-secondary me-2"></i>Project Management</li>
                                    <li><i class="fas fa-check text-secondary me-2"></i>Subcontractor Coordination</li>
                                    <li><i class="fas fa-check text-secondary me-2"></i>Quality Control</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Supply Services</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-secondary me-2"></i>Material Procurement</li>
                                    <li><i class="fas fa-check text-secondary me-2"></i>Equipment Rental</li>
                                    <li><i class="fas fa-check text-secondary me-2"></i>Inventory Management</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="bg-white rounded shadow-lg p-4">
                        <h5 class="text-secondary mb-3">Our Approach</h5>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-tasks text-secondary me-2"></i>Comprehensive Solutions</h6>
                            <p class="text-muted mb-0">End-to-end project delivery and support</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h6><i class="fas fa-award text-secondary me-2"></i>Quality Assurance</h6>
                            <p class="text-muted mb-0">Rigorous quality control at every stage</p>
                        </div>
                        <div class="feature-item">
                            <h6><i class="fas fa-handshake text-secondary me-2"></i>Partnership Approach</h6>
                            <p class="text-muted mb-0">Building long-term client relationships</p>
                        </div>
                    </div>
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
                <h2 class="mb-4">Need Our Services?</h2>
                <p class="lead mb-4">Contact us today to discuss your project requirements and get a customized solution.</p>
                <div>
                    <a href="contact.php" class="btn btn-warning btn-lg me-3">Get Quote</a>
                    <a href="tel:08035547894" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>