# B-AIBUDA GLOBAL NIGERIA LIMITED Website

A comprehensive corporate website for B-AIBUDA GLOBAL NIGERIA LIMITED (M & CO. INVESTMENT NIGERIA LTD.), featuring supply chain management, contracting services, and engineering solutions across Nigeria.

**Domain:** www.baibudaglobal.org.ng

## 🚀 Features

### ✅ Complete Website Structure
- **Home Page:** Hero section, company overview, services preview, vision/mission, CEO section
- **About Page:** Company profile, leadership team, vision/mission, core values, why choose us
- **Services Page:** Detailed service descriptions for all 6 core services
- **Gallery Page:** Project portfolio with filtering and lightbox functionality
- **Contact Page:** Contact form, company information, map, FAQ section
- **Admin Panel:** Secure content management system

### ✅ Responsive Bootstrap 5 Design
- Professional corporate styling with modern UI/UX
- Mobile-first responsive design
- Custom CSS with CSS variables for easy theming
- Smooth animations and interactive elements
- Cross-browser compatibility

### ✅ Database Integration
- MySQL database with comprehensive schema
- Secure PDO connections with prepared statements
- Tables: content, contact_messages, media_items, admin_sessions, services
- Data integrity and proper indexing

### ✅ Contact Form with Email Notifications
- Form submissions automatically sent to **idrisbala9@gmail.com**
- All messages saved to database for admin review
- Built-in validation and security features
- CSRF protection and input sanitization

### ✅ Admin Panel (Password: admin123)
- **Dashboard:** Statistics overview and system information
- **Content Management:** Edit company information, vision, mission, CEO details
- **Message Management:** View and manage contact form submissions
- **Media Gallery:** Upload and manage photos/videos
- **Services Management:** Update service descriptions and features
- Secure session management and activity logging

### ✅ Security Features
- **SQL Injection Protection:** All database queries use prepared statements
- **XSS Prevention:** Input sanitization and output encoding
- **File Upload Security:** Type validation, size limits, secure storage
- **CSRF Protection:** Token-based form security
- **Admin Authentication:** Secure login with session management
- **Upload Directory:** Protected with .htaccess restrictions

## 📁 Project Structure

```
/
├── index.php              # Home page
├── about.php              # About us page
├── services.php           # Services overview
├── gallery.php            # Project gallery
├── contact.php            # Contact form and info
├── database.sql           # MySQL database schema
├── config/
│   └── database.php       # Database configuration
├── includes/
│   ├── header.php         # Common header template
│   ├── footer.php         # Common footer template
│   └── functions.php      # Utility functions
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styling
│   └── js/
│       └── script.js      # Custom JavaScript
├── uploads/
│   ├── .htaccess          # Security restrictions
│   ├── logo.jpg           # Company logo
│   └── ceo.jpg            # CEO portrait
└── admin/
    ├── index.php          # Admin login
    ├── dashboard.php      # Admin dashboard
    ├── logout.php         # Logout functionality
    └── [other admin pages]
```

## 🛠️ Quick Setup Instructions

### 1. Database Setup
```sql
-- Create database
CREATE DATABASE baibudaglobal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema
mysql -u your_username -p baibudaglobal_db < database.sql
```

### 2. Configuration
Edit `config/database.php` with your database credentials:
```php
private $host = 'localhost';
private $db_name = 'baibudaglobal_db';
private $username = 'your_db_username';
private $password = 'your_db_password';
```

### 3. File Permissions
```bash
chmod 755 uploads/
chmod 644 uploads/.htaccess
chmod 644 config/database.php
```

### 4. Upload Files
Replace placeholder files in `uploads/` directory:
- `logo.jpg` - Company logo (recommended: 400x400px)
- `ceo.jpg` - CEO portrait (recommended: 300x300px)

### 5. Email Configuration
For production email delivery:
- Configure SMTP settings in hosting control panel
- Or modify `sendContactEmail()` function in `includes/functions.php`
- Verify email delivery to idrisbala9@gmail.com

## 📧 Email Configuration Status

The contact form is configured to send notifications directly to **idrisbala9@gmail.com** using PHP's built-in `mail()` function. For production deployment:

1. **Shared Hosting:** Works automatically with most providers
2. **VPS/Dedicated:** Configure sendmail/postfix
3. **SMTP Alternative:** Modify the email function to use SMTP

## 🔐 Admin Access

- **URL:** `/admin/`
- **Password:** `admin123`
- **Features:**
  - Content management
  - Contact message review
  - Media upload system
  - Service management
  - System monitoring

## 🏢 Company Information

**B-AIBUDA GLOBAL NIGERIA LIMITED**
- **Registration:** M & CO. INVESTMENT NIGERIA LTD. RC: 8398525
- **Address:** No. 1 Near sheikh abubakar mahmud gumi, juma'at mosque argungu town, kebbi state, nigeria
- **Email:** mcoinvestmentnigltd@gmail.com
- **Phone:** 08035547894, 08022070807, 08076104589
- **Board of Directors:** Murtala Adamu, Bashar Adamu and Muhammad Abubakar
- **CEO:** Murtala Adamu

### Our Services
1. **Civil Engineering** - Professional engineering services with international standards
2. **Roads & Bridges** - Comprehensive infrastructure construction
3. **Building Construction** - Residential, commercial, and industrial projects
4. **Import and Export** - International trade facilitation and logistics
5. **Oil and Gas** - Specialized petroleum sector services
6. **General Contracts & Supplies** - Comprehensive contracting solutions

### Vision
To maintain and strengthen our core supply and general contracting business, to develop new innovations and technical ideas, and to respond to the changing need of our clients. Our strategy for sustained growth is anchored in the development of world-class products.

### Mission
To develop and expand the Nigeria supply and procurement industry through high level of professionalism and procurement skills in order to meet up with the challenges of modern day supply and procurement techniques and innovations in the world market.

## 🔧 Technical Specifications

- **PHP Version:** 7.4+ (Recommended: 8.0+)
- **MySQL Version:** 5.7+ (Recommended: 8.0+)
- **Web Server:** Apache with mod_rewrite
- **Dependencies:** 
  - Bootstrap 5.3.0 (CDN)
  - Font Awesome 6.4.0 (CDN)
  - Google Fonts (CDN)

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🚀 Deployment Checklist

### Pre-Deployment
- [ ] Update database credentials in `config/database.php`
- [ ] Replace placeholder images with actual company photos
- [ ] Test contact form email delivery
- [ ] Verify all links and navigation
- [ ] Test admin panel functionality
- [ ] Check responsive design on various devices

### Production Setup
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure email delivery (SMTP recommended)
- [ ] Set up regular database backups
- [ ] Configure error logging
- [ ] Set up monitoring and analytics
- [ ] Update DNS settings for www.baibudaglobal.org.ng

### Security Hardening
- [ ] Change default admin password
- [ ] Secure file permissions
- [ ] Configure firewall rules
- [ ] Enable security headers
- [ ] Set up automatic updates
- [ ] Regular security audits

## 🛡️ Security Features Implemented

1. **Database Security**
   - Prepared statements prevent SQL injection
   - Secure connection with error handling
   - Input validation and sanitization

2. **File Upload Security**
   - File type validation
   - Size limits enforced
   - Secure storage location
   - .htaccess protection

3. **Session Security**
   - Secure session management
   - Session fixation protection
   - Activity logging

4. **Input Security**
   - CSRF token protection
   - XSS prevention
   - Data validation and sanitization

5. **Admin Security**
   - Password-protected access
   - Session timeout
   - Activity monitoring

## 📱 Mobile Optimization

- Responsive design with mobile-first approach
- Touch-friendly navigation and buttons
- Optimized images for mobile bandwidth
- Fast loading times with minimal dependencies
- Mobile-optimized contact forms and galleries

## 🔍 SEO Optimization

- Semantic HTML structure
- Meta tags and descriptions for each page
- Open Graph tags for social sharing
- Structured data markup
- Mobile-friendly design
- Fast loading speeds
- Clean URL structure

## 🤝 Support & Maintenance

For technical support or customizations:
1. Check the admin panel for basic content updates
2. Review this README for common issues
3. Contact the development team for advanced modifications

## 📄 License

This website is proprietary software developed for B-AIBUDA GLOBAL NIGERIA LIMITED. All rights reserved.

---

**Developed for B-AIBUDA GLOBAL NIGERIA LIMITED**  
*Professional Supply & Contracting Services*  
www.baibudaglobal.org.ng
