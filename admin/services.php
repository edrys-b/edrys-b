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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_service') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fas fa-cog');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        if ($title && $description) {
            $stmt = $db->prepare('INSERT INTO services (title, description, icon, display_order, is_active) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$title, $description, $icon, $display_order, $is_active]);
            $success_message = 'Service added.';
        } else {
            $error_message = 'Title and description are required.';
        }
    } elseif ($action === 'update_service') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fas fa-cog');
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        if ($id > 0) {
            $stmt = $db->prepare('UPDATE services SET title=?, description=?, icon=?, display_order=?, is_active=? WHERE id=?');
            $stmt->execute([$title, $description, $icon, $display_order, $is_active, $id]);
            $success_message = 'Service updated.';
        }
    } elseif ($action === 'delete_service') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
            $stmt->execute([$id]);
            $success_message = 'Service deleted.';
        }
    }
}

$stmt = $db->query('SELECT * FROM services ORDER BY display_order, created_at DESC');
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Admin</title>
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
        .icon-preview { font-size: 1.5rem; }
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
            <a href="media.php" class="nav-link"><i class="fas fa-images me-2"></i>Media Gallery</a>
            <a href="services.php" class="nav-link active"><i class="fas fa-cogs me-2"></i>Services</a>
            <div class="dropdown-divider my-2 mx-3" style="border-color: rgba(255,255,255,0.1);"></div>
            <a href="../index.php" class="nav-link" target="_blank"><i class="fas fa-external-link-alt me-2"></i>View Website</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </nav>

    <div class="main-content">
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <h4 class="mb-0 text-dark">Services</h4>
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
                        <div class="card-header"><h5 class="mb-0">Add Service</h5></div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="action" value="add_service">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon (Font Awesome)</label>
                                    <input type="text" class="form-control" name="icon" value="fas fa-cog">
                                    <small class="text-muted">Example: fas fa-building, fas fa-road, fas fa-hammer</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" value="0">
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Service</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Services List</h5></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Icon</th>
                                            <th>Title</th>
                                            <th>Order</th>
                                            <th>Active</th>
                                            <th style="width: 220px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($services)): ?>
                                            <tr><td colspan="5" class="text-center text-muted p-4">No services defined.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($services as $s): ?>
                                                <tr>
                                                    <td class="icon-preview"><i class="<?php echo htmlspecialchars($s['icon']); ?>"></i></td>
                                                    <td>
                                                        <form method="POST" class="row gx-2 gy-1 align-items-center">
                                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                            <input type="hidden" name="action" value="update_service">
                                                            <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                                            <div class="col-12">
                                                                <input type="text" name="title" class="form-control form-control-sm mb-1" value="<?php echo htmlspecialchars($s['title']); ?>">
                                                                <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Description..."><?php echo htmlspecialchars($s['description']); ?></textarea>
                                                                <input type="text" name="icon" class="form-control form-control-sm mt-1" value="<?php echo htmlspecialchars($s['icon']); ?>">
                                                            </div>
                                                    </td>
                                                    <td style="width:120px;">
                                                            <input type="number" name="display_order" class="form-control form-control-sm" value="<?php echo (int)$s['display_order']; ?>">
                                                    </td>
                                                    <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" <?php echo $s['is_active'] ? 'checked' : ''; ?>>
                                                            </div>
                                                    </td>
                                                    <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-save me-1"></i>Save</button>
                                                        </form>
                                                                <form method="POST" onsubmit="return confirm('Delete this service?');">
                                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                                    <input type="hidden" name="action" value="delete_service">
                                                                    <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                                                                </form>
                                                            </div>
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