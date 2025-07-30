<?php
// Database configuration for B-AIBUDA GLOBAL NIGERIA LIMITED Website
// Secure PDO connection with error handling

class Database {
    private $host = 'localhost';
    private $db_name = 'baibudaglobal_db';
    private $username = 'root'; // Change this in production
    private $password = ''; // Change this in production
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                )
            );
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("Database connection failed. Please try again later.");
        }
        
        return $this->conn;
    }
}

// Global database connection function
function getDB() {
    $database = new Database();
    return $database->getConnection();
}
?>