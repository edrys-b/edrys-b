-- B-AIBUDA GLOBAL NIGERIA LIMITED Website Database Schema
-- Created for www.baibudaglobal.org.ng

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: baibudaglobal_db

-- --------------------------------------------------------

-- Table structure for table `content`
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default content
INSERT INTO `content` (`section`, `title`, `content`, `image_url`) VALUES
('company_name', 'Company Name', 'B-AIBUDA GLOBAL NIGERIA LIMITED', NULL),
('company_title', 'Company Title', 'M & CO. INVESTMENT NIGERIA LTD. RC: 8398525', NULL),
('company_address', 'Address', 'No. 1 Near sheikh abubakar mahmud gumi, juma''at mosque argungu town, kebbi state, nigeria', NULL),
('company_email', 'Email', 'mcoinvestmentnigltd@gmail.com', NULL),
('company_phone', 'Phone', '08035547894, 08022070807, 08076104589', NULL),
('board_directors', 'Board of Directors', 'Murtala Adamu, Bashar Adamu and Muhammad Abubakar', NULL),
('vision', 'Our Vision', 'To maintain and strengthen our core supply and general contracting business, to develop new innovations and technical ideas, and to respond to the changing need of our clients. Our strategy for sustained growth is anchored in the development of world-class products.', NULL),
('mission', 'Our Mission', 'To develop and expand the Nigeria supply and procurement industry through high level of professionalism and procurement skills in order to meet up with the challenges of modern day supply and procurement techniques and innovations in the world market.', NULL),
('core_values', 'Our Core Values', 'Customers First: We exist to serve our customers. Our success will be determined by how well we perform for our customers.\n\nInnovation: We are intuitive, curious, inventive, practical and bold, which allows us to create new ideas for our customers, our business and employees. These ideas come from anywhere throughout our global operations.\n\nTeamwork: Success requires teamwork. We are collaborative and respect the contributions of each person to the team''s success.', NULL),
('about_content', 'About Us', 'B-AIBUDA GLOBAL NIGERIA LIMITED is a leading supply and general contracting company committed to excellence in service delivery. We specialize in civil engineering, import and export, oil and gas, and general contracts & supplies.', NULL),
('ceo_name', 'CEO Name', 'Murtala Adamu', NULL),
('ceo_bio', 'CEO Biography', 'Murtala Adamu is the Chief Executive Officer of B-AIBUDA GLOBAL NIGERIA LIMITED. With extensive experience in the supply and procurement industry, he leads the company with a vision for excellence and innovation in service delivery.', 'uploads/ceo.jpg'),
('services_intro', 'Services Introduction', 'We offer comprehensive services across multiple sectors, delivering quality solutions that meet international standards.', NULL);

-- --------------------------------------------------------

-- Table structure for table `contact_messages`
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `media_items`
CREATE TABLE `media_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('image','video') NOT NULL,
  `category` varchar(50) DEFAULT 'general',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample media items
INSERT INTO `media_items` (`title`, `description`, `file_path`, `file_type`, `category`, `display_order`) VALUES
('Company Logo', 'B-AIBUDA GLOBAL NIGERIA LIMITED Official Logo', 'uploads/logo.jpg', 'image', 'logo', 1),
('CEO Portrait', 'CEO Murtala Adamu', 'uploads/ceo.jpg', 'image', 'team', 2);

-- --------------------------------------------------------

-- Table structure for table `admin_sessions`
CREATE TABLE `admin_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table structure for table `services`
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert services
INSERT INTO `services` (`title`, `description`, `icon`, `display_order`) VALUES
('Civil Engineering', 'Professional civil engineering services including roads, bridges, and building construction with adherence to international standards.', 'fas fa-building', 1),
('Roads & Bridges', 'Comprehensive road and bridge construction services, from planning to completion, ensuring durability and safety.', 'fas fa-road', 2),
('Building Construction', 'Complete building construction services for residential, commercial, and industrial projects with modern techniques.', 'fas fa-hammer', 3),
('Import and Export', 'Efficient import and export services facilitating international trade with reliable logistics and documentation.', 'fas fa-ship', 4),
('Oil and Gas', 'Specialized services in the oil and gas sector including equipment supply and technical support.', 'fas fa-oil-well', 5),
('General Contracts & Supplies', 'Comprehensive contracting and supply solutions for various industries with quality assurance.', 'fas fa-handshake', 6);

COMMIT;