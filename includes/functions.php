<?php
// Utility functions for B-AIBUDA GLOBAL NIGERIA LIMITED Website

require_once 'config/database.php';

// Security Functions
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Content Management Functions
function getContent($section) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM content WHERE section = ?");
        $stmt->execute([$section]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Error fetching content: " . $e->getMessage());
        return false;
    }
}

function updateContent($section, $title, $content, $image_url = null) {
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE content SET title = ?, content = ?, image_url = ?, updated_at = NOW() WHERE section = ?");
        return $stmt->execute([$title, $content, $image_url, $section]);
    } catch(PDOException $e) {
        error_log("Error updating content: " . $e->getMessage());
        return false;
    }
}

function getAllServices() {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error fetching services: " . $e->getMessage());
        return [];
    }
}

function getMediaItems($category = null, $limit = null) {
    try {
        $db = getDB();
        $sql = "SELECT * FROM media_items WHERE is_active = 1";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY display_order, created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = (int)$limit;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error fetching media: " . $e->getMessage());
        return [];
    }
}

// Contact Form Functions
function saveContactMessage($name, $email, $phone, $subject, $message) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $subject, $message]);
    } catch(PDOException $e) {
        error_log("Error saving contact message: " . $e->getMessage());
        return false;
    }
}

function sendContactEmail($name, $email, $phone, $subject, $message) {
    $to = "idrisbala9@gmail.com";
    $email_subject = "New Contact Form Submission - " . $subject;
    
    $email_body = "New contact form submission from B-AIBUDA GLOBAL website:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $phone . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
    
    $headers = "From: noreply@baibudaglobal.org.ng\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return mail($to, $email_subject, $email_body, $headers);
}

// File Upload Functions
function uploadFile($file, $upload_dir = 'uploads/', $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error: ' . $file['error']];
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_types)];
    }
    
    // Check file size (10MB max)
    if ($file['size'] > 10 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large. Maximum size: 10MB'];
    }
    
    $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
    $filepath = $upload_dir . $filename;
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filepath' => $filepath, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}

// Admin Functions
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function adminLogin($password) {
    if ($password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';
        
        // Log session to database
        try {
            $db = getDB();
            $session_id = session_id();
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $db->prepare("INSERT INTO admin_sessions (session_id, username, ip_address, user_agent) VALUES (?, ?, ?, ?)");
            $stmt->execute([$session_id, 'admin', $ip_address, $user_agent]);
        } catch(PDOException $e) {
            error_log("Error logging admin session: " . $e->getMessage());
        }
        
        return true;
    }
    return false;
}

function adminLogout() {
    try {
        $db = getDB();
        $session_id = session_id();
        $stmt = $db->prepare("UPDATE admin_sessions SET is_active = 0 WHERE session_id = ?");
        $stmt->execute([$session_id]);
    } catch(PDOException $e) {
        error_log("Error updating admin session: " . $e->getMessage());
    }
    
    session_destroy();
}

// Utility Functions
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function generateSEOTitle($title) {
    return $title . ' - B-AIBUDA GLOBAL NIGERIA LIMITED';
}

function generateMetaDescription($content, $length = 160) {
    $clean_content = strip_tags($content);
    return truncateText($clean_content, $length);
}
?>