<?php
session_start();
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$success_message = '';
$error_message = '';

try { $db = getDB(); } catch (PDOException $e) { die('DB error'); }

function determineFileType($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $image_exts = ['jpg','jpeg','png','gif'];
    $video_exts = ['mp4','mov','avi','webm','mkv'];
    if (in_array($ext, $image_exts, true)) return 'image';
    if (in_array($ext, $video_exts, true)) return 'video';
    return 'image';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_media') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? 'general');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (!$title || !isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $error_message = 'Title and file are required.';
        } else {
            $upload = uploadFile($_FILES['file'], '../uploads/');
            if (!$upload['success']) {
                $error_message = $upload['message'];
            } else {
                $file_path = 'uploads/' . $upload['filename'];
                $file_type = determineFileType($upload['filename']);
                $stmt = $db->prepare('INSERT INTO media_items (title, description, file_path, file_type, category, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title, $description, $file_path, $file_type, $category, $display_order, $is_active]);
                $success_message = 'Media item added.';
            }
        }
    } elseif ($action === 'update_media') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? 'general');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        if ($id > 0) {
            $stmt = $db->prepare('UPDATE media_items SET title=?, description=?, category=?, display_order=?, is_active=? WHERE id=?');
            $stmt->execute([$title, $description, $category, $display_order, $is_active, $id]);
            $success_message = 'Media item updated.';
        }
    } elseif ($action === 'delete_media') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare('DELETE FROM media_items WHERE id = ?');
            $stmt->execute([$id]);
            $success_message = 'Media item deleted.';
        }
    }
}

$stmt = $db->query('SELECT * FROM media_items ORDER BY display_order, created_at DESC');
$media_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Gallery - Admin</title>
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
        .thumb { width: 80px; height: 60px; object-fit: cover; }
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
            <a href="content.php" class="nav-link"><i class="fas fa-edit me-2"></i>Content Management</a>
            <a href="messages.php" class="nav-link"><i class="fas fa-envelope me-2"></i>Messages</a>
            <a href="media.php" class="nav-link active"><i class="fas fa-images me-2"></i>Media Gallery</a>
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
                <h4 class="mb-0 text-dark">Media Gallery</h4>
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
                        <div class="card-header"><h5 class="mb-0">Add Media Item</h5></div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="add_media">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category">
                                        <option value="general">Company</option>
                                        <option value="projects">Projects</option>
                                        <option value="team">Team</option>
                                        <option value="logo">Logo</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" value="0">
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File</label>
                                    <input type="file" class="form-control" name="file" accept="image/*,video/*" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Media Items</h5></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Order</th>
                                            <th>Active</th>
                                            <th style="width: 180px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($media_items)): ?>
                                            <tr><td colspan="7" class="text-center text-muted p-4">No media items yet.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($media_items as $item): ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($item['file_type']==='image'): ?>
                                                            <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" class="thumb rounded" alt="thumb">
                                                        <?php else: ?>
                                                            <i class="fas fa-video text-muted"></i>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form method="POST" class="row gx-2 gy-1 align-items-center">
                                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                            <input type="hidden" name="action" value="update_media">
                                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                            <div class="col-12">
                                                                <input type="text" name="title" class="form-control form-control-sm" value="<?php echo htmlspecialchars($item['title']); ?>">
                                                            </div>
                                                    </td>
                                                    <td>
                                                            <select name="category" class="form-select form-select-sm">
                                                                <option value="general" <?php echo $item['category']==='general'?'selected':''; ?>>Company</option>
                                                                <option value="projects" <?php echo $item['category']==='projects'?'selected':''; ?>>Projects</option>
                                                                <option value="team" <?php echo $item['category']==='team'?'selected':''; ?>>Team</option>
                                                                <option value="logo" <?php echo $item['category']==='logo'?'selected':''; ?>>Logo</option>
                                                            </select>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($item['file_type']); ?></td>
                                                    <td>
                                                            <input type="number" name="display_order" class="form-control form-control-sm" value="<?php echo (int)$item['display_order']; ?>" style="width:90px;">
                                                    </td>
                                                    <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" <?php echo $item['is_active'] ? 'checked' : ''; ?>>
                                                            </div>
                                                    </td>
                                                    <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-save me-1"></i>Save</button>
                                                        </form>
                                                                <form method="POST" onsubmit="return confirm('Delete this item?');">
                                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                                    <input type="hidden" name="action" value="delete_media">
                                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                                                                </form>
                                                            </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="px-4 pb-3">
                                                        <form method="POST">
                                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                            <input type="hidden" name="action" value="update_media">
                                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                            <div class="row g-2">
                                                                <div class="col-12">
                                                                    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Description..."><?php echo htmlspecialchars($item['description']); ?></textarea>
                                                                </div>
                                                                <div class="col-12 text-end">
                                                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-save me-1"></i>Save Description</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
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