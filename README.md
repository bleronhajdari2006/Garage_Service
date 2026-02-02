# Garage_Service — Phase 2 Full-Stack Implementation

## Setup Instructions

### Prerequisites
- PHP 7.4+
- MySQL 5.7+ (with InnoDB)
- Apache/Nginx with mod_rewrite (or use PHP built-in server)

### Step 1: Create Database and User

```sql
CREATE DATABASE garage_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dbuser'@'localhost' IDENTIFIED BY 'dbpass';
GRANT ALL PRIVILEGES ON garage_service.* TO 'dbuser'@'localhost';
FLUSH PRIVILEGES;
```

### Step 2: Import Schema and Sample Data

```bash
mysql -u root -p garage_service < migrations/schema.sql
mysql -u root -p garage_service < migrations/sample-data.sql
```

### Step 3: Configure Database Connection

Edit `config.php` with your MySQL credentials:

```php
'db' => [
    'host' => '127.0.0.1',
    'dbname' => 'garage_service',
    'user' => 'dbuser',
    'pass' => 'dbpass',
    'charset' => 'utf8mb4',
],
```

### Step 4: Create Uploads Directory

```bash
mkdir -p public/uploads
chmod 755 public/uploads
```

### Step 5: Run Local Development Server

From the project root:

```bash
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser.

## Default Credentials

- **Username**: admin
- **Password**: admin

(Change these immediately after first login!)

## Project Structure

```
Garage_Service/
├── config.php              # Database and app configuration
├── db/
│   └── Database.php        # PDO wrapper singleton
├── models/
│   ├── User.php            # User CRUD
│   ├── Pages.php           # Dynamic pages CRUD
│   ├── News.php            # News CRUD
│   ├── Product.php         # Products/Services CRUD
├── auth/
│   └── Auth.php            # Session, login, role checks, CSRF
├── public/
│   ├── index.php           # Main dynamic router (home, about, news, services)
│   ├── register.php        # User registration
│   ├── login.php           # User login
│   ├── logout.php          # User logout
│   ├── contact.php         # Contact form (stores to DB)
│   ├── admin/
│   │   ├── dashboard.php   # Admin panel (users, links to CMS)
│   │   ├── users.php       # Manage users
│   │   ├── pages.php       # Manage pages (home, about, services, news)
│   │   ├── news.php        # Manage news items
│   │   ├── products.php    # Manage products/services
│   │   └── contacts.php    # View contact submissions
│   ├── uploads/            # User-uploaded images/PDFs
│   ├── style.css           # Responsive CSS + slider styles
│   └── script.js           # Front-end validation + slider
├── migrations/
│   ├── schema.sql          # Database schema
│   └── sample-data.sql     # Sample users, pages, news, products

```

## Features Implemented

### Phase 2 Requirements ✓

1. **Multi-Page Website**
   - ✓ Home, About, Services, News, Contact (all database-driven)
   - ✓ Dynamic page loading from Pages table
   - ✓ Navigation wired to dynamic pages

2. **Authentication & Roles**
   - ✓ Login/Register pages with CSRF protection
   - ✓ Session-based auth with admin/user roles
   - ✓ Password hashing (bcrypt)
   - ✓ Admin-only access to dashboard

3. **Database-Driven Content**
   - ✓ Pages, News, Products in MySQL with foreign keys
   - ✓ Creator tracking (created_by user IDs)
   - ✓ Contact form submissions stored in DB
   - ✓ No static text files—all content is dynamic

4. **Admin Panel**
   - ✓ Dashboard with user list and quick links
   - ✓ Pages manager (CRUD for home, about, services, news)
   - ✓ News manager (CRUD with image/PDF uploads)
   - ✓ Products manager (CRUD with image/PDF uploads)
   - ✓ Contact submissions viewer

5. **File Uploads**
   - ✓ Image and PDF support for all content
   - ✓ Server-side file type validation
   - ✓ Files stored in `public/uploads/`

6. **Validation**
   - ✓ Back-end: PDO prepared statements, CSRF tokens
   - ✓ Front-end: File type/size validation in JS
   - ✓ Email validation, required fields
   - ✓ Back-end validation on forms

7. **Responsive Design**
   - ✓ Mobile-first CSS (tablets, mobile, desktop)
   - ✓ Responsive tables, forms
   - ✓ Auto-rotating image slider (4-second intervals)

8. **OOP PHP Code**
   - ✓ PDO Database class (singleton pattern)
   - ✓ Model classes: User, Pages, News, Product
   - ✓ Auth class with session/CSRF helpers
   - ✓ No procedural PHP—fully OOP compliant

## Usage

### Public Pages
- Home: `http://localhost:8000/?page=home`
- About: `http://localhost:8000/?page=about`
- Services: `http://localhost:8000/?page=services`
- News: `http://localhost:8000/?page=news`
- Contact: `http://localhost:8000/public/contact.php`

### Admin Area
- Login: `http://localhost:8000/public/login.php` (admin/admin)
- Dashboard: `http://localhost:8000/public/admin/dashboard.php`
- Manage Pages: `http://localhost:8000/public/admin/pages.php`
- Manage News: `http://localhost:8000/public/admin/news.php`
- Manage Products: `http://localhost:8000/public/admin/products.php`
- View Contacts: `http://localhost:8000/public/admin/contacts.php`

## Next Steps

1. **Git Setup** (Phase 2 requirement)
   ```bash
   git init
   git config user.name "Your Name"
   git config user.email "your.email@example.com"
   git add .
   git commit -m "Initial Phase 2 commit"
   ```

2. **Team Collaboration**
   - Create branches per team member (e.g., `feature/user-[name]`)
   - Each team member makes changes and commits
   - Use pull requests for code review

3. **Production Deployment**
   - Update credentials in `config.php`
   - Create strong admin password
   - Set up HTTPS/SSL
   - Configure email sending for contact form
   - Set up automated backups

## Security Notes

- All form inputs are validated and sanitized
- SQL queries use prepared statements (PDO)
- CSRF tokens on all state-changing operations
- Passwords hashed with bcrypt (PASSWORD_DEFAULT)
- Session data contains only user ID, username, and role
- Upload directory should not be in version control

---

**Status**: Phase 2 full-stack complete—ready for testing, Git integration, and team collaboration.
