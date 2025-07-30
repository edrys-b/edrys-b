<?php
$page_title = 'Gallery - Our Projects & Work';
$meta_description = 'Explore our portfolio of completed projects including civil engineering, construction, import/export operations, and contracting work across Nigeria.';
include 'includes/header.php';

// Get media items from database
$all_media = getMediaItems();
$image_media = getMediaItems('general');
$project_media = getMediaItems('projects');
$team_media = getMediaItems('team');
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Gallery</h1>
                <p class="lead">Explore our portfolio of completed projects, team achievements, and company milestones through photos and videos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Filter -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="text-center">
            <div class="btn-group" role="group" id="galleryFilter">
                <input type="radio" class="btn-check" name="filter" id="filter-all" value="all" checked>
                <label class="btn btn-outline-primary" for="filter-all">All</label>
                
                <input type="radio" class="btn-check" name="filter" id="filter-projects" value="projects">
                <label class="btn btn-outline-primary" for="filter-projects">Projects</label>
                
                <input type="radio" class="btn-check" name="filter" id="filter-team" value="team">
                <label class="btn btn-outline-primary" for="filter-team">Team</label>
                
                <input type="radio" class="btn-check" name="filter" id="filter-general" value="general">
                <label class="btn btn-outline-primary" for="filter-general">Company</label>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="section-padding">
    <div class="container">
        <?php if (empty($all_media)): ?>
        <!-- Placeholder Gallery (when no media items exist) -->
        <div class="text-center mb-5">
            <h3 class="text-primary">Coming Soon</h3>
            <p class="text-muted">We're currently updating our gallery with the latest projects and achievements. Please check back soon!</p>
        </div>
        
        <!-- Sample Gallery Items -->
        <div class="row gallery-grid">
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="projects">
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/400x300?text=Civil+Engineering+Project" alt="Civil Engineering Project" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>Civil Engineering Project</h5>
                            <p>Road construction in Kebbi State</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('https://via.placeholder.com/800x600?text=Civil+Engineering+Project', 'Civil Engineering Project', 'Road construction in Kebbi State')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="projects">
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/400x300?text=Building+Construction" alt="Building Construction" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>Building Construction</h5>
                            <p>Commercial building project</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('https://via.placeholder.com/800x600?text=Building+Construction', 'Building Construction', 'Commercial building project')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="projects">
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/400x300?text=Bridge+Construction" alt="Bridge Construction" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>Bridge Construction</h5>
                            <p>Infrastructure development project</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('https://via.placeholder.com/800x600?text=Bridge+Construction', 'Bridge Construction', 'Infrastructure development project')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="team">
                <div class="gallery-item">
                    <img src="uploads/ceo.jpg" alt="CEO Murtala Adamu" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>CEO Murtala Adamu</h5>
                            <p>Chief Executive Officer</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('uploads/ceo.jpg', 'CEO Murtala Adamu', 'Chief Executive Officer')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="general">
                <div class="gallery-item">
                    <img src="uploads/logo.jpg" alt="Company Logo" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>B-AIBUDA GLOBAL</h5>
                            <p>Company Logo</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('uploads/logo.jpg', 'B-AIBUDA GLOBAL', 'Company Logo')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="projects">
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/400x300?text=Import+Export+Operations" alt="Import Export Operations" class="img-fluid">
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5>Import Export Operations</h5>
                            <p>International trade activities</p>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('https://via.placeholder.com/800x600?text=Import+Export+Operations', 'Import Export Operations', 'International trade activities')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <!-- Dynamic Gallery (when media items exist) -->
        <div class="row gallery-grid">
            <?php foreach ($all_media as $media): ?>
            <div class="col-lg-4 col-md-6 mb-4 gallery-item-wrapper" data-category="<?php echo htmlspecialchars($media['category']); ?>">
                <div class="gallery-item">
                    <?php if ($media['file_type'] === 'image'): ?>
                        <img src="<?php echo htmlspecialchars($media['file_path']); ?>" alt="<?php echo htmlspecialchars($media['title']); ?>" class="img-fluid">
                    <?php else: // video ?>
                        <video class="img-fluid" poster="<?php echo htmlspecialchars($media['file_path']); ?>">
                            <source src="<?php echo htmlspecialchars($media['file_path']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                    
                    <div class="gallery-overlay">
                        <div class="text-center">
                            <h5><?php echo htmlspecialchars($media['title']); ?></h5>
                            <?php if ($media['description']): ?>
                                <p><?php echo htmlspecialchars(truncateText($media['description'], 50)); ?></p>
                            <?php endif; ?>
                            <button class="btn btn-light btn-sm" onclick="openLightbox('<?php echo htmlspecialchars($media['file_path']); ?>', '<?php echo htmlspecialchars($media['title']); ?>', '<?php echo htmlspecialchars($media['description'] ?? ''); ?>')">
                                <i class="fas fa-expand-alt"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- No results message -->
        <div id="no-results" class="text-center d-none">
            <h4 class="text-muted">No items found</h4>
            <p class="text-muted">Try selecting a different category.</p>
        </div>
    </div>
</section>

<!-- Project Highlights -->
<section class="bg-light section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="text-primary">Project Highlights</h3>
            <p class="text-muted">Some of our notable achievements and successful project completions</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-road fa-2x"></i>
                        </div>
                        <h5>Road Infrastructure</h5>
                        <p class="text-muted">Over 50km of roads constructed across Kebbi State with modern engineering standards.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <h5>Commercial Buildings</h5>
                        <p class="text-muted">Multiple commercial and residential buildings completed with excellent quality standards.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-ship fa-2x"></i>
                        </div>
                        <h5>Import/Export Success</h5>
                        <p class="text-muted">Successful facilitation of international trade operations worth millions of dollars.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="lightboxTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="lightboxImage" src="" alt="" class="img-fluid w-100">
                <div id="lightboxDescription" class="p-3 text-white"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Gallery filtering
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="filter"]');
    const galleryItems = document.querySelectorAll('.gallery-item-wrapper');
    const noResults = document.getElementById('no-results');
    
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterValue = this.value;
            let visibleCount = 0;
            
            galleryItems.forEach(item => {
                const category = item.getAttribute('data-category');
                
                if (filterValue === 'all' || category === filterValue) {
                    item.style.display = 'block';
                    item.classList.add('fade-in-up');
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        });
    });
});

// Lightbox functionality
function openLightbox(imageSrc, title, description) {
    document.getElementById('lightboxImage').src = imageSrc;
    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxDescription').textContent = description;
    
    const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    lightboxModal.show();
}

// Keyboard navigation for lightbox
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const lightboxModal = bootstrap.Modal.getInstance(document.getElementById('lightboxModal'));
        if (lightboxModal) {
            lightboxModal.hide();
        }
    }
});

// Lazy loading for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});
</script>

<?php include 'includes/footer.php'; ?>