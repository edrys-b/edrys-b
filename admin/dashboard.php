<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Get statistics
try {
    $db = getDB();
    
    // Get contact messages count
    $stmt = $db->query("SELECT COUNT(*) as total FROM contact_messages");
    $total_messages = $stmt->fetch()['total'];
    
    $stmt = $db->query("SELECT COUNT(*) as unread FROM contact_messages WHERE status = 'unread'");
    $unread_messages = $stmt->fetch()['unread'];
    
    // Get media items count
    $stmt = $db->query("SELECT COUNT(*) as total FROM media_items WHERE is_active = 1");
    $total_media = $stmt->fetch()['total'];
    
    // Get recent messages
    $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $recent_messages = $stmt->fetchAll();
    
    // Get admin sessions count
    $stmt = $db->query("SELECT COUNT(*) as total FROM admin_sessions WHERE is_active = 1");
    $active_sessions = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    $total_messages = $unread_messages = $total_media = $active_sessions = 0;
    $recent_messages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - B-AIBUDA GLOBAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0056b3;
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, #004494 100%);
            color: white;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            border: none;
            background: none;
        }
        
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>Admin Panel
            </h5>
            <small>B-AIBUDA GLOBAL</small>
        </div>
        
        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-link active">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="content.php" class="nav-link">
                <i class="fas fa-edit me-2"></i>Content Management
            </a>
            <a href="messages.php" class="nav-link">
                <i class="fas fa-envelope me-2"></i>Messages
                <?php if ($unread_messages > 0): ?>
                <span class="badge bg-danger ms-2"><?php echo $unread_messages; ?></span>
                <?php endif; ?>
            </a>
            <a href="media.php" class="nav-link">
                <i class="fas fa-images me-2"></i>Media Gallery
            </a>
            <a href="services.php" class="nav-link">
                <i class="fas fa-cogs me-2"></i>Services
            </a>
            <div class="dropdown-divider my-2 mx-3" style="border-color: rgba(255,255,255,0.1);"></div>
            <a href="../index.php" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt me-2"></i>View Website
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0 text-dark">Dashboard</h4>
            </div>
            <div>
                <span class="text-muted">Welcome, Admin</span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </nav>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary me-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 class="mb-0"><?php echo $total_messages; ?></h3>
                                <p class="text-muted mb-0">Total Messages</p>
                                <?php if ($unread_messages > 0): ?>
                                <small class="text-danger"><?php echo $unread_messages; ?> unread</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success me-3">
                                <i class="fas fa-images"></i>
                            </div>
                            <div>
                                <h3 class="mb-0"><?php echo $total_media; ?></h3>
                                <p class="text-muted mb-0">Media Items</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info me-3">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">6</h3>
                                <p class="text-muted mb-0">Services</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning me-3">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="mb-0"><?php echo $active_sessions; ?></h3>
                                <p class="text-muted mb-0">Active Sessions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="content.php" class="btn btn-primary w-100">
                                        <i class="fas fa-edit me-2"></i>Edit Content
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="messages.php" class="btn btn-success w-100">
                                        <i class="fas fa-envelope me-2"></i>View Messages
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="media.php" class="btn btn-info w-100">
                                        <i class="fas fa-upload me-2"></i>Upload Media
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="../index.php" target="_blank" class="btn btn-warning w-100">
                                        <i class="fas fa-external-link-alt me-2"></i>View Website
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Messages -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Messages</h5>
                            <a href="messages.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recent_messages)): ?>
                            <p class="text-muted text-center py-3">No messages yet.</p>
                            <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_messages as $message): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <?php echo htmlspecialchars($message['subject']); ?>
                                                <?php if ($message['status'] === 'unread'): ?>
                                                <span class="badge bg-primary ms-2">New</span>
                                                <?php endif; ?>
                                            </h6>
                                            <p class="mb-1 text-muted">From: <?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</p>
                                            <small class="text-muted"><?php echo formatDate($message['created_at']); ?></small>
                                        </div>
                                        <a href="messages.php?view=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">PHP Version</small>
                                <div><?php echo phpversion(); ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Server Software</small>
                                <div><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Last Login</small>
                                <div><?php echo date('M d, Y H:i'); ?></div>
                            </div>
                            <div>
                                <small class="text-muted">Website Status</small>
                                <div class="text-success">
                                    <i class="fas fa-circle me-1"></i>Online
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
        
        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            // You can implement AJAX refresh here if needed
        }, 30000);
    </script>
</body>
</html>