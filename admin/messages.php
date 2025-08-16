<?php
session_start();
require_once '../includes/functions.php';

if (!isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$success_message = '';
$error_message = '';

try {
    $db = getDB();
} catch (PDOException $e) {
    die('Database connection failed');
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && verifyCSRFToken($_POST['csrf_token'])) {
    $action = $_POST['action'] ?? '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($action === 'update_status' && in_array($_POST['status'] ?? '', ['unread','read','replied'], true)) {
        $status = $_POST['status'];
        $stmt = $db->prepare('UPDATE contact_messages SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
        $success_message = 'Message status updated.';
    } elseif ($action === 'delete') {
        $stmt = $db->prepare('DELETE FROM contact_messages WHERE id = ?');
        $stmt->execute([$id]);
        $success_message = 'Message deleted.';
    } elseif ($action === 'send_reply') {
        $reply_to = $_POST['reply_to'] ?? '';
        $subject = 'Re: ' . ($_POST['original_subject'] ?? 'Your inquiry');
        $body = trim($_POST['reply_body'] ?? '');
        if (!$reply_to || !$body) {
            $error_message = 'Reply email and body are required.';
        } else {
            $headers = "From: noreply@baibudaglobal.org.ng\r\nReply-To: noreply@baibudaglobal.org.ng\r\nX-Mailer: PHP/" . phpversion();
            if (@mail($reply_to, $subject, $body, $headers)) {
                $stmt = $db->prepare("UPDATE contact_messages SET status = 'replied' WHERE id = ?");
                $stmt->execute([$id]);
                $success_message = 'Reply sent and status updated to replied.';
            } else {
                $error_message = 'Failed to send email reply.';
            }
        }
    }
}

// View single message if requested
$view_id = isset($_GET['view']) ? (int)$_GET['view'] : 0;
$view_message = null;
if ($view_id > 0) {
    $stmt = $db->prepare('SELECT * FROM contact_messages WHERE id = ?');
    $stmt->execute([$view_id]);
    $view_message = $stmt->fetch();
    if ($view_message && $view_message['status'] === 'unread') {
        $db->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$view_id]);
        $view_message['status'] = 'read';
    }
}

// Filters
$status_filter = $_GET['status'] ?? 'all';
$where = '';
$params = [];
if (in_array($status_filter, ['unread','read','replied'], true)) {
    $where = 'WHERE status = ?';
    $params[] = $status_filter;
}

$stmt = $db->prepare("SELECT * FROM contact_messages $where ORDER BY created_at DESC");
$stmt->execute($params);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin</title>
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
        pre.message { white-space: pre-wrap; }
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
            <a href="messages.php" class="nav-link active"><i class="fas fa-envelope me-2"></i>Messages</a>
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
                <h4 class="mb-0 text-dark">Messages</h4>
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
                <div class="col-lg-5 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Inbox</h5>
                            <div>
                                <a href="?status=all" class="btn btn-sm btn-outline-secondary <?php echo ($status_filter==='all')?'active':''; ?>">All</a>
                                <a href="?status=unread" class="btn btn-sm btn-outline-primary <?php echo ($status_filter==='unread')?'active':''; ?>">Unread</a>
                                <a href="?status=read" class="btn btn-sm btn-outline-success <?php echo ($status_filter==='read')?'active':''; ?>">Read</a>
                                <a href="?status=replied" class="btn btn-sm btn-outline-info <?php echo ($status_filter==='replied')?'active':''; ?>">Replied</a>
                            </div>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 70vh; overflow:auto;">
                            <?php if (empty($messages)): ?>
                                <div class="p-3 text-muted">No messages found.</div>
                            <?php else: ?>
                                <?php foreach ($messages as $m): ?>
                                    <a class="list-group-item list-group-item-action <?php echo ($view_id===$m['id'])?'active':''; ?>" href="?status=<?php echo urlencode($status_filter); ?>&view=<?php echo $m['id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($m['subject']); ?></strong>
                                            <small><?php echo htmlspecialchars(formatDate($m['created_at'])); ?></small>
                                        </div>
                                        <div class="small text-muted">From: <?php echo htmlspecialchars($m['name']); ?> (<?php echo htmlspecialchars($m['email']); ?>)</div>
                                        <?php if ($m['status']==='unread'): ?><span class="badge bg-primary mt-1">Unread</span><?php endif; ?>
                                        <?php if ($m['status']==='replied'): ?><span class="badge bg-info mt-1">Replied</span><?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Message Details</h5>
                            <?php if ($view_message): ?>
                                <div>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="id" value="<?php echo $view_message['id']; ?>">
                                        <input type="hidden" name="status" value="<?php echo $view_message['status']==='unread' ? 'read' : 'unread'; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-<?php echo $view_message['status']==='unread'?'success':'secondary'; ?>">
                                            <i class="fas fa-eye me-1"></i><?php echo $view_message['status']==='unread'?'Mark Read':'Mark Unread'; ?>
                                        </button>
                                    </form>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $view_message['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if ($view_message): ?>
                                <div class="mb-3">
                                    <div class="mb-1"><strong>From:</strong> <?php echo htmlspecialchars($view_message['name']); ?> (<?php echo htmlspecialchars($view_message['email']); ?>)<?php if ($view_message['phone']): ?>, <?php echo htmlspecialchars($view_message['phone']); ?><?php endif; ?></div>
                                    <div class="mb-1"><strong>Subject:</strong> <?php echo htmlspecialchars($view_message['subject']); ?></div>
                                    <div class="mb-3"><strong>Received:</strong> <?php echo htmlspecialchars($view_message['created_at']); ?></div>
                                    <pre class="message border rounded p-3 bg-light"><?php echo htmlspecialchars($view_message['message']); ?></pre>
                                </div>
                                <hr>
                                <h6>Quick Reply</h6>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="send_reply">
                                    <input type="hidden" name="id" value="<?php echo $view_message['id']; ?>">
                                    <input type="hidden" name="reply_to" value="<?php echo htmlspecialchars($view_message['email']); ?>">
                                    <input type="hidden" name="original_subject" value="<?php echo htmlspecialchars($view_message['subject']); ?>">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="reply_body" rows="5" placeholder="Type your reply..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Send Reply</button>
                                </form>
                            <?php else: ?>
                                <p class="text-muted mb-0">Select a message from the inbox to view details.</p>
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