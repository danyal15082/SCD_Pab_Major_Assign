Classroom Resource Booking System
📋 Project Overview
A comprehensive full-stack web application designed for efficient management and booking of classroom resources including classrooms, laboratories, conference rooms, and equipment in educational institutions.

Course: Web Engineering Lab - CSC-314(L)
Session: Fall 2025
Technology Stack: PHP, MySQL, HTML5, CSS3, Bootstrap, JavaScript

✨ Features Implemented
🔐 Authentication System
✅ User Registration with validation
✅ User Login with PHP Sessions
✅ Role-based access control (Admin & User)
✅ Password encryption using PHP password_hash()
✅ Profile management
✅ Change password functionality
✅ Secure logout
👤 User Module
✅ User Dashboard with statistics
✅ Browse available resources with search and filters
✅ Create booking requests
✅ View booking history
✅ Cancel bookings
✅ View booking status and admin notes
👨‍💼 Admin Module
✅ Admin Dashboard with comprehensive analytics
✅ Manage Users (Add, Edit, Delete, Toggle Status)
✅ Manage Resources (Add, Edit, Delete)
✅ Manage Resource Categories
✅ Manage Bookings (Approve, Reject with notes)
✅ Reports & Analytics Dashboard
📊 Resource Management Module
✅ Multiple resource types (Classrooms, Labs, Equipment, etc.)
✅ Resource categorization
✅ Capacity tracking
✅ Location information
✅ Amenities listing
✅ Status management (Available, Maintenance, Unavailable)
📅 Booking Module
✅ Create booking requests
✅ Date and time validation
✅ Conflict detection (no double booking)
✅ Purpose and attendee tracking
✅ Booking status workflow (Pending → Approved/Rejected)
✅ Admin approval system with notes
✅ Booking cancellation
📈 Reports Module
✅ Booking statistics overview
✅ Most booked resources report
✅ Most active users report
✅ Monthly booking trends
✅ Category-wise utilization
✅ Approval rate analysis
🗄️ Database Architecture
Tables Structure
users - User accounts and authentication
resource_categories - Resource categorization
resources - All bookable resources
bookings - Booking requests and history
notifications - System notifications (ready for implementation)
system_settings - Application configuration
Entity Relationships
User → Bookings (One-to-Many)
Resource → Bookings (One-to-Many)
Category → Resources (One-to-Many)
🚀 Installation Instructions
Prerequisites
PHP 7.4 or higher
MySQL 5.7 or higher
Apache/Nginx web server
XAMPP/WAMP/LAMP stack (recommended)
Step 1: Database Setup
Open phpMyAdmin or MySQL command line
Import the database file:
source /path/to/classroom_booking_system.sql
Or import via phpMyAdmin interface
Step 2: Configure Database Connection
Open config/database.php
Update database credentials:
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'classroom_booking_system');
Step 3: Configure Application
Open config/config.php
Update SITE_URL constant:
define('SITE_URL', 'http://localhost/webapp');
Step 4: Deploy Files
Copy project folder to your web server document root:
XAMPP: C:/xampp/htdocs/webapp
WAMP: C:/wamp/www/webapp
Linux: /var/www/html/webapp
Step 5: Access Application
Open browser and navigate to: http://localhost/webapp
Use demo credentials to login
👥 Demo Credentials
Admin Account
Email: admin@classroom.com
Password: admin123
User Accounts
Email: john.doe@classroom.com

Password: admin123

Email: jane.smith@classroom.com

Password: admin123

📁 Project Structure
webapp/
├── admin/                      # Admin module pages
│   ├── dashboard.php          # Admin dashboard
│   ├── manage_users.php       # User management
│   ├── manage_resources.php   # Resource management
│   ├── manage_bookings.php    # Booking management
│   └── reports.php            # Reports & analytics
├── user/                       # User module pages
│   ├── dashboard.php          # User dashboard
│   ├── browse_resources.php   # Browse available resources
│   ├── create_booking.php     # Create new booking
│   └── my_bookings.php        # View booking history
├── assets/                     # Static assets
│   ├── css/                   # Stylesheets
│   │   └── style.css         # Custom styles
│   ├── js/                    # JavaScript files
│   │   └── main.js           # Custom scripts
│   └── images/                # Image assets
├── config/                     # Configuration files
│   ├── config.php             # Application configuration
│   └── database.php           # Database connection
├── database/                   # Database files
│   └── classroom_booking_system.sql  # Database schema
├── includes/                   # Reusable components
│   ├── header.php             # Common header
│   └── footer.php             # Common footer
├── index.php                   # Home page
├── login.php                   # Login page
├── register.php                # Registration page
├── logout.php                  # Logout handler
├── profile.php                 # User profile
├── change_password.php         # Change password
├── .gitignore                  # Git ignore rules
└── README.md                   # Documentation
🎨 User Interface Features
Design Elements
✅ Responsive Bootstrap 4.6 layout
✅ Font Awesome icons
✅ Custom CSS animations
✅ Modal dialogs for confirmations
✅ Alert messages with auto-dismiss
✅ Progress bars for statistics
✅ Badge indicators for status
✅ Card-based layout design
Navigation
✅ Role-based navigation menus
✅ User dropdown with profile options
✅ Breadcrumb navigation
✅ Quick action buttons
✔️ Validation Implemented
Client-Side Validation (JavaScript)
✅ Required field validation
✅ Email format validation
✅ Phone number format validation
✅ Password strength check
✅ Password confirmation match
✅ Date validation (no past dates)
✅ Time validation (end time > start time)
✅ Real-time error feedback
Server-Side Validation (PHP)
✅ Input sanitization
✅ SQL injection prevention
✅ XSS protection
✅ CSRF protection via sessions
✅ Data type validation
✅ Business logic validation
✅ Duplicate entry prevention
📊 Functional Entry URIs
Public Pages
GET / - Home page
GET /login.php - Login page
POST /login.php - Process login
GET /register.php - Registration page
POST /register.php - Process registration
GET /logout.php - Logout
User Pages
GET /user/dashboard.php - User dashboard
GET /user/browse_resources.php - Browse resources
GET /user/create_booking.php - Create booking form
POST /user/create_booking.php - Submit booking
GET /user/my_bookings.php - View bookings
Admin Pages
GET /admin/dashboard.php - Admin dashboard
GET /admin/manage_users.php - Manage users
GET /admin/manage_resources.php - Manage resources
GET /admin/manage_bookings.php - Manage bookings
POST /admin/manage_bookings.php - Approve/reject bookings
GET /admin/reports.php - View reports
Common Pages
GET /profile.php - View/edit profile
POST /profile.php - Update profile
GET /change_password.php - Change password form
POST /change_password.php - Update password
🚧 Features Not Yet Implemented
Planned Enhancements
⏳ Email notifications for booking status
⏳ Calendar view for bookings
⏳ Resource availability calendar
⏳ Recurring bookings
⏳ File upload for resource images
⏳ Advanced search with multiple filters
⏳ Export reports to PDF/Excel
⏳ User notification center
⏳ Booking reminders
⏳ Resource maintenance scheduling
🔧 Technical Implementation
Backend (PHP)
Pure PHP without frameworks
Object-Oriented programming principles
Prepared statements for SQL queries
Session management for authentication
Error handling and logging
Input validation and sanitization
Frontend
HTML5 semantic markup
CSS3 with custom styles
Bootstrap 4.6 responsive framework
jQuery for DOM manipulation
JavaScript form validation
AJAX for asynchronous operations (ready)
Database
MySQL relational database
Normalized schema design
Foreign key constraints
Indexes for performance
Views for complex queries
Transactions for data integrity
🎯 Assignment Compliance
✅ Technical Requirements Met
✅ Frontend: HTML5, CSS3, Bootstrap
✅ Responsive layout
✅ JavaScript validation
✅ Backend: Core PHP (no frameworks)
✅ CRUD operations
✅ MySQL database
✅ User authentication with sessions
✅ Two user roles (Admin + User)
✅ 3+ functional modules
📚 Documentation Deliverables
✅ Complete project folder
✅ Database SQL file with sample data
✅ README documentation (this file)
✅ Inline code comments
⏳ SRS Document (to be submitted separately)
⏳ Project Thesis (to be submitted separately)
⏳ PowerPoint Presentation (to be prepared)
🛠️ Development Guidelines
Adding New Features
Follow existing code structure
Add validation (client + server side)
Update database schema if needed
Test thoroughly before deployment
Update this README
Security Best Practices
Always use prepared statements
Sanitize all user inputs
Use password_hash() for passwords
Implement CSRF tokens
Validate on both client and server
Use HTTPS in production
📝 Notes for Evaluator
Key Highlights
Complete CRUD Implementation: All modules have full Create, Read, Update, Delete operations
Dual Validation: Every form has both client-side (JavaScript) and server-side (PHP) validation
Security: Prepared statements, password hashing, input sanitization implemented
User Experience: Responsive design, intuitive navigation, helpful error messages
Code Quality: Well-organized, commented, follows best practices
Database Design: Normalized schema with proper relationships and constraints
Testing the Application
Test user registration and login
Test resource browsing and booking as user
Test booking approval workflow as admin
Check validation by submitting invalid data
Test responsive design on different screen sizes
📞 Support & Contact
For questions or issues regarding this project:

Check code comments for implementation details
Review database schema for data structure
Refer to assignment guidelines for requirements
📄 License
This project is developed as an academic assignment for Web Engineering Lab (CSC-314L) - Fall 2025.

Last Updated: November 2025
Version: 1.0.0
Status: ✅ Ready for Submission