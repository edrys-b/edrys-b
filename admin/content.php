<?php
session_start();
require_once '../includes/functions.php';

// Redirect if not logged in
if (!isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$success_message = '';
$error_message = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_content') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error_message = 'Invalid CSRF token.';
    } else {
        $section = $_POST['section'] ?? '';
        $title = trim($_POST['title'] ?? '');
        $contentText = $_POST['content'] ?? '';
        $imageUrl = $_POST['current_image'] ?? null;

        // Optional image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadFile($_FILES['image'], '../uploads/');
            if ($uploadResult['success']) {
                $imageUrl = 'uploads/' . $uploadResult['filename'];
            } else {
                $error_message = $uploadResult['message'];
            }
        }

        if (!$error_message) {
            if (updateContent($section, $title, $contentText, $imageUrl)) {
                $success_message = 'Content updated successfully.';
            } else {
                $error_message = 'Failed to update content.';
            }
        }
    }
}

// Fetch all content sections
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM content ORDER BY section");
    $all_content = $stmt->fetchAll();
} catch (PDOException $e) {
    $all_content = [];
}

// Determine current section to edit
$current_section_key = $_GET['section'] ?? ($all_content[0]['section'] ?? '');
$current_content = $current_section_key ? getContent($current_section_key) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-color: #0056b3; --sidebar-width: 250px; }
        body { background-color: #f8f9fa; }
        .sidebar { position: fixed; top:0; left:0; height:100vh; width:var(--sidebar-width); background: linear-gradient(135deg, var(--primary-color) 0%, #004494 100%); color:white; z-index:1000; }
        .sidebar-header { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-nav { padding: 1rem 0; }
        .sidebar-nav .nav-link { color: rgba(255,255,255,0.8); padding: .75rem 1rem; border:none; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { color:#fff; background: rgba(255,255,255,0.1); }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .top-navbar { background:#fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 1rem 2rem; }
        .content-area { padding: 2rem; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.show { transform: translateX(0); } .main-content { margin-left:0; } }
        .list-group-item.active { background-color: #e9f2ff; color:#0d6efd; border-color: #b6d4fe; }
    </style>
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Admin Panel</h5>
            <small>B-AIBUDA GLOBAL</small>
        </div>
        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="content.php" class="nav-link active"><i class="fas fa-edit me-2"></i>Content Management</a>
            <a href="messages.php" class="nav-link"><i class="fas fa-envelope me-2"></i>Messages</a>
            <a href="media.php" class="nav-link"><i class="fas fa-images me-2"></i>Media Gallery</a>
            <a href="services.php" class="nav-link"><i class="fas fa-cogs me-2"></i>Services</a>
            <div class="dropdown-divider my-2 mx-3" style="border-color: rgba(255,255,255,0.1);"></div>
            <a href="../index.php" class="nav-link" target="_blank"><i class="fas fa-external-link-alt me-2"></i>View Website</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </nav>

    <div class="main-content">
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <h4 class="mb-0 text-dark">Content Management</h4>
            </div>
            <div>
                <span class="text-muted">Welcome, Admin</span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
            </div>
        </nav>

        <div class="content-area">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Content Sections</h5></div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php foreach ($all_content as $item): ?>
                                    <a class="list-group-item list-group-item-action <?php echo ($item['section'] === $current_section_key) ? 'active' : ''; ?>" href="?section=<?php echo urlencode($item['section']); ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-file-alt me-2"></i><?php echo htmlspecialchars($item['title'] ?: ucwords(str_replace('_',' ', $item['section']))); ?></span>
                                            <small class="text-muted"><?php echo htmlspecialchars($item['section']); ?></small>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Section</h5>
                            <?php if ($current_content): ?>
                                <small class="text-muted">Last updated: <?php echo htmlspecialchars($current_content['updated_at']); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if ($current_content): ?>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="update_content">
                                <input type="hidden" name="section" value="<?php echo htmlspecialchars($current_content['section']); ?>">
                                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_content['image_url']); ?>">

                                <div class="mb-3">
                                    <label class="form-label">Section Key</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($current_content['section']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($current_content['title'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea name="content" rows="8" class="form-control"><?php echo htmlspecialchars($current_content['content'] ?? ''); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Image (optional)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <?php if (!empty($current_content['image_url'])): ?>
                                        <div class="mt-2">
                                            <img src="../<?php echo htmlspecialchars($current_content['image_url']); ?>" alt="Current Image" style="max-height:120px;" class="img-thumbnail">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
                                </div>
                            </form>
                            <?php else: ?>
                                <p class="text-muted">No content found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() { document.getElementById('sidebar').classList.toggle('show'); });
    </script>
</body>
</html>