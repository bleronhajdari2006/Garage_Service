# Testing Guide — Garage_Service Phase 2

## Prerequisites
- PHP 7.4+ installed
- MySQL Server running (local or remote)
- Git (optional, for version control)

## Quick Start (Local Testing)

### 1. Create Database

Open MySQL command line or MySQL Workbench and run:

```sql
CREATE DATABASE garage_service CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dbuser'@'localhost' IDENTIFIED BY 'dbpass';
GRANT ALL PRIVILEGES ON garage_service.* TO 'dbuser'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Import Database Schema

```bash
# Navigate to project directory
cd c:\Users\Bleron Hajdari\Documents\Garage_Service

# Import schema
mysql -u root -p garage_service < migrations/schema.sql

# Import sample data
mysql -u root -p garage_service < migrations/sample-data.sql
```

When prompted for password, enter your MySQL root password.

### 3. Verify Database

```bash
mysql -u dbuser -p garage_service
# Enter password: dbpass

# In MySQL prompt, run:
SHOW TABLES;
SELECT * FROM users;
SELECT * FROM pages;
```

Should see:
- 5 tables: users, pages, products, news, contacts
- 1 admin user
- 4 sample pages (home, about, services, news)

### 4. Create Uploads Directory

```bash
# From project root
mkdir public\uploads
```

### 5. Start PHP Server

```bash
# From project root directory
php -S localhost:8000 -t public
```

You should see:
```
Development Server started at http://localhost:8000
Press Ctrl-C to quit.
```

## Testing Workflow

### Test 1: Public Pages (No Auth Required)

1. **Visit Home Page**
   - URL: `http://localhost:8000/?page=home`
   - ✓ Should display "Welcome to Garage Service" from database
   - ✓ Should show sample News and Products

2. **Visit About Page**
   - URL: `http://localhost:8000/?page=about`
   - ✓ Should display "About Our Garage" content

3. **Visit Services Page**
   - URL: `http://localhost:8000/?page=services`
   - ✓ Should display services content with products list

4. **Visit News Page**
   - URL: `http://localhost:8000/?page=news`
   - ✓ Should display news items from database

5. **Visit Contact Page**
   - URL: `http://localhost:8000/public/contact.php`
   - ✓ Should show contact form
   - Fill in: Name, Email, Message
   - Click "Send Message"
   - ✓ Should see success message
   - ✓ Check MySQL: `SELECT * FROM contacts;` should show your submission

### Test 2: Registration & Login

1. **Register New User**
   - URL: `http://localhost:8000/public/register.php`
   - Username: `testuser`
   - Email: `test@example.com`
   - Password: `password123`
   - Click "Register"
   - ✓ Should redirect to login page
   - ✓ Check MySQL: `SELECT * FROM users;` should show new user with role='user'

2. **Login as Regular User**
   - URL: `http://localhost:8000/public/login.php`
   - Username: `testuser`
   - Password: `password123`
   - Click "Login"
   - ✓ Should redirect to `/public/admin/dashboard.php`
   - ✓ Should see "403 - Forbidden" message (user role, not admin)

3. **Logout**
   - Click "Logout" link
   - ✓ Should redirect to login page
   - ✓ Session should be cleared

### Test 3: Admin Authentication & Authorization

1. **Login as Admin**
   - URL: `http://localhost:8000/public/login.php`
   - Username: `admin`
   - Password: `admin`
   - Click "Login"
   - ✓ Should see Admin Dashboard
   - ✓ Should display Users table with admin and testuser

2. **Verify Admin Access**
   - Check navigation links visible:
     - Manage Users
     - Manage Pages
     - Manage News
     - Manage Products
     - View Contacts

### Test 4: Admin Pages Management

1. **Manage Pages**
   - URL: `http://localhost:8000/public/admin/pages.php`
   - ✓ Should list 4 pages: home, about, services, news

2. **Create New Page**
   - Scroll to "Create Page" section
   - Slug: `blog`
   - Title: `Blog`
   - Content: `This is our blog page.`
   - Click "Create"
   - ✓ Should refresh and show "blog" in the pages list
   - ✓ Verify: `http://localhost:8000/?page=blog` shows new page

3. **Edit Page**
   - In existing pages table, click "Edit" on any page
   - Modify title or content
   - Click "Save"
   - ✓ Should update in database
   - ✓ Verify change on public page

4. **Delete Page**
   - Click "Delete" on a non-core page (e.g., the blog page you created)
   - Confirm deletion
   - ✓ Should be removed from list

### Test 5: Admin News Management

1. **Manage News**
   - URL: `http://localhost:8000/public/admin/news.php`
   - ✓ Should list 2 sample news items

2. **Create News Item**
   - Title: `New Tire Brand Available`
   - Body: `We now stock premium brand tires for all vehicle types.`
   - Media: (optional - upload an image or skip)
   - Click "Create"
   - ✓ Should appear in list
   - ✓ Verify on Home page: `http://localhost:8000/?page=home` (bottom shows "Latest News")

3. **Upload Media to News**
   - Create another news item
   - Title: `Service Special`
   - Body: `50% off alignments this week.`
   - Media: Select any image file from your computer
   - Click "Create"
   - ✓ Should upload successfully
   - ✓ File should appear in `public/uploads/` with name like `news_xxxxx.jpg`
   - ✓ News list should show "View" link for media

### Test 6: Admin Products Management

1. **Manage Products**
   - URL: `http://localhost:8000/public/admin/products.php`
   - ✓ Should list 3 sample products/services

2. **Create Product**
   - Title: `Battery Replacement`
   - Description: `Professional battery installation with warranty.`
   - Media: (optional)
   - Click "Create"
   - ✓ Should appear in list
   - ✓ Verify on Services page: `http://localhost:8000/?page=services`

### Test 7: Admin Contact Submissions

1. **View Contacts**
   - URL: `http://localhost:8000/public/admin/contacts.php`
   - ✓ Should show your earlier contact form submission
   - ✓ Columns: ID, Name, Email, Message, File, Submitted

2. **Contact with File Upload**
   - Go to `http://localhost:8000/public/contact.php`
   - Fill form with:
     - Name: `John Doe`
     - Email: `john@example.com`
     - Message: `Please quote for brake service.`
     - Attachment: Select a PDF or image
   - Click "Send Message"
   - ✓ Should see success message
   - ✓ Go back to Contacts admin page
   - ✓ New submission should appear with file link

### Test 8: Validation & Security

1. **Front-End Validation**
   - Go to `http://localhost:8000/public/register.php`
   - Try to register with:
     - Short username (< 3 chars)
     - ✓ Should show error: "Username too short"
     - Invalid email
     - ✓ Should show error: "Invalid email"
     - Short password (< 6 chars)
     - ✓ Should show error: "Password too short"

2. **File Upload Validation**
   - Go to `http://localhost:8000/public/contact.php`
   - Try to upload an executable file (.exe, .bat, etc.)
   - ✓ JavaScript should show: "Invalid file type"
   - Try to upload a very large file (> 5MB)
   - ✓ JavaScript should show: "File too large. Max 5MB"

3. **CSRF Protection**
   - Go to `http://localhost:8000/public/login.php`
   - Inspect the HTML (right-click → Inspect)
   - Look for hidden input: `<input type="hidden" name="csrf" value="...">`
   - ✓ Should have a CSRF token
   - Try to bypass it by removing token from form request
   - ✓ Should fail with "Invalid CSRF token" message

4. **Role-Based Access**
   - Login as `testuser` (regular user)
   - Try to visit `http://localhost:8000/public/admin/pages.php`
   - ✓ Should redirect to login or show 403 error
   - Login as `admin`
   - ✓ Should grant access

### Test 9: Responsive Design

1. **Desktop View**
   - Open DevTools (F12)
   - View pages at full width
   - ✓ Layout should look clean and organized

2. **Tablet View**
   - Press F12, click device toggle (mobile icon)
   - Select iPad or Tablet preset
   - Navigate through pages
   - ✓ Tables should be readable
   - ✓ Forms should be usable

3. **Mobile View**
   - Select iPhone or mobile preset
   - Navigate through pages
   - ✓ Single column layout
   - ✓ Touch-friendly buttons
   - ✓ Readable text

### Test 10: Database Verification

Open MySQL and verify data:

```sql
-- Check users
SELECT id, username, email, role FROM users;

-- Check pages (should show home, about, services, news, blog)
SELECT id, slug, title FROM pages;

-- Check news
SELECT id, title, created_at FROM news;

-- Check products
SELECT id, title, created_at FROM products;

-- Check contacts
SELECT id, name, email, submitted_at FROM contacts;

-- Verify foreign keys work
SELECT c.id, c.name, p.title FROM products p 
JOIN contacts c ON c.submitted_by = p.created_by 
LIMIT 5;
```

## Troubleshooting

### PHP Server Not Starting
```
Error: "Failed to listen on localhost:8000"
Solution: Port 8000 is in use. Try:
php -S localhost:8001 -t public
```

### "Connection refused" Error
```
Error: "PDO Connection failed"
Solution: 
1. Check MySQL is running
2. Verify credentials in config.php
3. Run: mysql -u dbuser -p (use 'dbpass' as password)
```

### Pages Not Loading from Database
```
Solution:
1. Run: SELECT COUNT(*) FROM pages;
2. If 0 rows, re-import sample data:
   mysql -u root -p garage_service < migrations/sample-data.sql
3. Check error logs: php_error.log in project root
```

### File Upload Not Working
```
Solution:
1. Verify public/uploads/ directory exists
2. Check folder permissions: chmod 755 public/uploads
3. Check PHP upload size in php.ini:
   upload_max_filesize = 20M
   post_max_size = 20M
```

### Slider Not Auto-Rotating
```
Solution:
1. Check script.js is loaded (browser DevTools → Network tab)
2. Pages need multiple news/product items to show slider
3. Check browser console for JS errors (F12 → Console)
```

## Success Criteria

✓ All 10 tests pass  
✓ Public pages load from database (no static HTML)  
✓ Auth system works (register, login, logout, roles)  
✓ Admin CRUD works (create, read, update, delete)  
✓ File uploads work (images, PDFs)  
✓ Contact form stores data in database  
✓ Validation works (front-end and back-end)  
✓ CSRF tokens present on all forms  
✓ Responsive design works on mobile/tablet/desktop  
✓ Slider auto-rotates on home page  

## Next Steps

1. **Git Setup** (Phase 2 requirement)
   - Initialize repo: `git init`
   - Add all files: `git add .`
   - Commit: `git commit -m "Phase 2 initial commit"`

2. **Team Collaboration**
   - Push to GitHub/Bitbucket
   - Create branches for each team member
   - Each member makes contributions to different areas

3. **Production Deployment**
   - Deploy to web server (Apache, Nginx)
   - Use real domain name
   - Set up HTTPS/SSL
   - Configure email for contact form
   - Regular backups

---

**Estimated testing time**: 30-45 minutes for full workflow
