# CHARUSAT Student Hub Portal

An integrated university student information and academic management portal developed for **Charotar University of Science and Technology (CHARUSAT)** as part of the Web Development Frameworks subject (ITUE203).

The portal is built using **HTML5, CSS3, Bootstrap 5, JavaScript, JSON, PHP, and MySQL** without external heavy frameworks or dependencies, designed to be clean, responsive, robust, and straightforward to explain during practical examinations and vivas.

---

## Practicals Overview (Practicals 1 to 14)

- **Practical 1: Project Initiation & Requirements**
  - Problem definition, user roles (Student and Admin), navigation flow, project directory structure, sitemap, wireframes, and GitHub-ready layout.
  - Documentation: `docs/requirement-analysis.md`, `docs/sitemap.md`, `docs/wireframes.md`.

- **Practical 2: Semantic HTML5 & Accessibility**
  - Semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`) across all pages.
  - Accessibility-friendly form labels, ARIA attributes, image alt descriptions, and visible keyboard focus.

- **Practical 3: Responsive UI Design with Bootstrap 5**
  - Mobile-first responsiveness tested across 1920px, 1440px, 1024px, 768px, 480px, and 375px viewports.
  - Unified primary blue palette, standard Bootstrap 5 components (cards, badges, modals, tables, navbars), and fluid `.table-responsive` containers.

- **Practical 4: JavaScript DOM Manipulation & Interactivity**
  - Collapsible FAQ accordion in `pages/about.html` (`js/about.js`).
  - Interactive highlights carousel slider in `pages/dashboard.html` (`js/dashboard.js`).
  - Quick Information modal popup in `pages/dashboard.html`.
  - Dismissible alert notification banner in `pages/dashboard.html`.
  - Light/Dark theme switcher with `localStorage` persistence and `☾` / `☀` icons (`js/common.js`).

- **Practical 5: Registration Form with Frontend Validation**
  - Student registration form with HTML5 types and JavaScript validation (`js/register.js`).
  - Regex validation for name, university email, and 10-digit mobile number.
  - Live dynamic password strength meter (Weak, Medium, Strong) and inline error messages.

- **Practical 6: Fetch API, External JSON, Search, Filter, Sort & Pagination**
  - Dynamic consumption of `data/events.json` using the Fetch API (`js/events.js`).
  - Real-time text search (name and venue), category filtering, multi-order sorting, and client-side pagination.
  - Also includes `data/students.json` and `data/faqs.json` with 15+ records each.

- **Practical 7: PHP Form Processing & CSV File Storage**
  - Contact support form in `pages/contact.html` submitting via POST to `php/contact.php`.
  - Server-side sanitation (`htmlspecialchars`, `trim`) and email validation.
  - Safe persistence of contact submissions to `storage/contacts.csv` using `fputcsv()`.

- **Practical 8: MySQL Schema Design, ER Model & PDO Connectivity**
  - Relational database schema `studenthub_db` with 4 normalized tables (`users`, `students`, `events`, `registrations`).
  - Complete ER Model documentation with Mermaid diagram (`docs/er-model.md`).
  - PHP PDO connection (`php/db_pdo.php`) and standalone prepared statement test (`php/test_pdo.php`).

- **Practical 9: Secure Registration with Database Insert & Password Hashing**
  - Upgraded student registration backend (`php/register.php`).
  - Duplicate email detection in MySQL using prepared statements.
  - Cryptographic password hashing using `password_hash($password, PASSWORD_DEFAULT)`.
  - Atomic database insert into `users` and `students` tables, simultaneously logging to `storage/registrations.csv`.

- **Practical 10: Secure Login/Logout, Sessions, RBAC & Inactivity Timeout**
  - Login processor (`php/login_process.php`) validating credentials against MySQL with `password_verify()`.
  - Session security using `session_regenerate_id(true)` to prevent session fixation.
  - 30-minute session inactivity timeout and role-based redirection:
    - Administrator &rarr; Admin Dashboard (`pages/admin_dashboard.php`).
    - Student &rarr; Student Dashboard (`pages/dashboard.php`).
  - Protected dashboard routes enforcing authentication via `php/auth.php`.
  - Complete session destruction on logout (`php/logout.php`).

- **Practical 11: Student Management CRUD with Search, Filter & CSV Export**
  - Admin module in `pages/manage_students.php`.
  - Full CRUD operations (Add, View, Edit, Delete with confirmation) using MySQLi prepared statements.
  - Real-time search by name, enrollment, or email, plus course dropdown filtering.
  - Seeded with 21 realistic student records.
  - One-click CSV export of student records (`manage_students.php?export=csv`).

- **Practical 12: Event Management CRUD with Poster Upload & File Validation**
  - Admin module in `pages/manage_events.php`.
  - Add, edit, and delete events with event status tracking.
  - Secure file upload for event posters:
    - Real binary MIME inspection via `finfo_file` (allowing only JPEG, PNG, and WebP).
    - File extension and size validation (&le; 2MB).
    - Safe unique filename generation and storage in `uploads/posters/`.
    - Automatic poster cleanup upon event deletion.

- **Practical 13: PHP JSON API & Fetch-Based CRUD Without Page Reload**
  - RESTful JSON API endpoints: `api/events.php` and `api/students.php`.
  - Asynchronous event management interface in `pages/events_api.php`.
  - Full Add, Edit, Delete, Search, and Filter operations powered by JavaScript `fetch()`.
  - Immediate DOM updates without full page reload.
  - Double-submission prevention (submit button disables and shows "Saving...").
  - Dynamic loading indicators and dismissible Bootstrap alert feedback.

- **Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu & Audit Log**
  - Unified administration control panel in `pages/admin_dashboard.php`.
  - Dynamic KPI cards computing real-time counts from MySQL: Total Students, Active Events, Event Bookings, and Portal Users.
  - Contextual role-based menus restricting administrative navigation strictly to admin accounts.
  - Server-enforced route authorization: unauthorized student attempts are blocked and redirected to `dashboard.php?msg=forbidden`.
  - Comprehensive Audit Trail logging: `audit_logs` table records user logins, student creation/updates/deletions, and event operations, displayed in a dedicated dashboard table.

---

## Practical Submission Reports (Practicals 1 to 14)

All 14 academic practical submission reports are available as individual PDF documents in the dedicated repository folder **`25DCE087_WDF_Practicals_PR1-PR14/`**:

| Practical | Topic / Scope | Submission PDF Report |
|---|---|---|
| **Practical 1** | Requirement Analysis, Portal Architecture, Sitemap & Low-Fi Wireframes | [`25DCE087_WDF_PR1.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR1.pdf) |
| **Practical 2** | Semantic HTML5 Page Structure, Accessibility & Keyboard Navigability | [`25DCE087_WDF_PR2.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR2.pdf) |
| **Practical 3** | Responsive UI Layouts with Bootstrap 5 & Multi-Viewport Breakpoints | [`25DCE087_WDF_PR3.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR3.pdf) |
| **Practical 4** | JavaScript DOM Manipulation: FAQ Accordion, Highlights Slider, Modal & Theme Switcher | [`25DCE087_WDF_PR4.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR4.pdf) |
| **Practical 5** | Student Registration Form with Regex Validation & Password Strength Meter | [`25DCE087_WDF_PR5.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR5.pdf) |
| **Practical 6** | Fetch API Dynamic Consumption of External JSON with Search, Filter & Pagination | [`25DCE087_WDF_PR6.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR6.pdf) |
| **Practical 7** | PHP Server-Side Form Handling, Input Sanitization & CSV File Storage | [`25DCE087_WDF_PR7.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR7.pdf) |
| **Practical 8** | Relational Database Schema Design, ER Modeling, PDO & Prepared Statements | [`25DCE087_WDF_PR8.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR8.pdf) |
| **Practical 9** | Secure Registration Backend with Duplicate Check, Hash & Atomic MySQL Insert | [`25DCE087_WDF_PR9.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR9.pdf) |
| **Practical 10** | Secure Authentication, Session Hijack Prevention, RBAC & Inactivity Timeout | [`25DCE087_WDF_PR10.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR10.pdf) |
| **Practical 11** | Student Records Management CRUD with Search, Course Filter & CSV Export | [`25DCE087_WDF_PR11.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR11.pdf) |
| **Practical 12** | Event Management CRUD with Secure Poster File Upload & Binary MIME Validation | [`25DCE087_WDF_PR12.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR12.pdf) |
| **Practical 13** | PHP RESTful JSON API & Asynchronous Fetch CRUD Without Page Reload | [`25DCE087_WDF_PR13.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR13.pdf) |
| **Practical 14** | Admin Dashboard, RBAC Route Authorization, Live Metrics & Audit Trail Logging | [`25DCE087_WDF_PR14.pdf`](25DCE087_WDF_Practicals_PR1-PR14/25DCE087_WDF_PR14.pdf) |

---

## Project Folder Structure

```
StudentHub/
│
├── 25DCE087_WDF_Practicals_PR1-PR14/   # Official Practical Submission PDF Reports (PR1 to PR14)
│   ├── 25DCE087_WDF_PR1.pdf            # Practical 1 Report
│   ├── 25DCE087_WDF_PR2.pdf            # Practical 2 Report
│   ├── 25DCE087_WDF_PR3.pdf            # Practical 3 Report
│   ├── 25DCE087_WDF_PR4.pdf            # Practical 4 Report
│   ├── 25DCE087_WDF_PR5.pdf            # Practical 5 Report
│   ├── 25DCE087_WDF_PR6.pdf            # Practical 6 Report
│   ├── 25DCE087_WDF_PR7.pdf            # Practical 7 Report
│   ├── 25DCE087_WDF_PR8.pdf            # Practical 8 Report
│   ├── 25DCE087_WDF_PR9.pdf            # Practical 9 Report
│   ├── 25DCE087_WDF_PR10.pdf           # Practical 10 Report
│   ├── 25DCE087_WDF_PR11.pdf           # Practical 11 Report
│   ├── 25DCE087_WDF_PR12.pdf           # Practical 12 Report
│   ├── 25DCE087_WDF_PR13.pdf           # Practical 13 Report
│   └── 25DCE087_WDF_PR14.pdf           # Practical 14 Report
│
├── api/                                # REST JSON API Endpoints (Practical 13)
│   ├── events.php                      # Event API (GET search/filter, POST create/update/delete)
│   └── students.php                    # Students list JSON API
│
├── css/                                # Page-Specific Stylesheets (Bootstrap 5 Overrides)
│   ├── about.css
│   ├── announcements.css
│   ├── assignments.css
│   ├── attendance.css
│   ├── contact.css
│   ├── courses.css
│   ├── dashboard.css
│   ├── events.css
│   ├── login.css
│   ├── profile.css
│   ├── register.css
│   ├── settings.css
│   └── timetable.css
│
├── data/                               # External JSON Datasets (Practical 6)
│   ├── events.json                     # 15 event records
│   ├── students.json                   # 15 student records
│   └── faqs.json                       # 15 FAQ records
│
├── docs/                               # Project Planning & System Specifications
│   ├── requirement-analysis.md         # Comprehensive functional & non-functional requirements
│   ├── sitemap.md                      # Complete portal sitemap
│   ├── wireframes.md                   # Low-fidelity wireframes
│   ├── er-model.md                     # Database ER diagram and entity specifications
│   ├── P1Wireframes.svg                # Practical 1 Wireframe graphic
│   └── SiteMap.png                     # Practical 1 Sitemap diagram
│
├── images/                             # Portal Logos & Graphics
│   ├── image.png                       # CHARUSAT Portal Brand Logo
│   └── profile.png                     # Student profile avatar
│
├── js/                                 # Client-Side JavaScript
│   ├── common.js                       # Theme switching (localStorage) & navigation
│   ├── dashboard.js                    # Content slider, modal, notification dismissal
│   ├── about.js                        # Collapsible FAQ accordion
│   ├── register.js                     # Client-side form validation & password strength
│   ├── login.js                        # Login validation & URL alert display
│   └── events.js                       # Practical 6 Fetch API, search, filter, pagination
│
├── pages/                              # HTML & PHP Views
│   ├── login.html                      # Portal entry / login screen
│   ├── register.html                   # Student registration form
│   ├── dashboard.php                   # Authenticated Student Dashboard (Protected)
│   ├── dashboard.html                  # Static Student Dashboard
│   ├── admin_dashboard.php             # Authenticated Administrator Control Panel (Protected)
│   ├── manage_students.php             # Student Management CRUD & Search (Practical 11)
│   ├── manage_events.php               # Event Management CRUD & Poster Upload (Practical 12)
│   ├── events_api.php                  # Real-time Fetch API CRUD without page reload (Practical 13)
│   ├── attendance.html                 # Lecture attendance metrics & 2x2 summary cards
│   ├── assignments.html                # Course assignment submissions & deadlines
│   ├── courses.html                    # Enrolled courses, credits, faculty mentors
│   ├── timetable.html                  # Weekly class schedule with lunch break
│   ├── announcements.html              # Campus notices & examination circulars
│   ├── events.html                     # Public events catalog (Practical 6 Fetch API)
│   ├── profile.html                    # Student profile details
│   ├── settings.html                   # Account security & portal preferences
│   ├── contact.html                    # Helpdesk contact form (Practical 7)
│   └── about.html                      # Portal overview & FAQ
│
├── php/                                # Backend Processing & Database Connections
│   ├── db.php                          # MySQLi database connection helper
│   ├── db_pdo.php                      # PDO database connection helper (Practical 8)
│   ├── test_pdo.php                    # Standalone PDO connection & prepared statement test
│   ├── auth.php                        # Session security, timeout & RBAC helper
│   ├── login_process.php               # Login verification & role redirection
│   ├── logout.php                      # Session destruction & logout handler
│   ├── register.php                    # Registration handler (MySQL insert + CSV logging)
│   └── contact.php                     # Contact form handler (CSV persistence)
│
├── practical_02_html/                  # Semantic HTML-Only Page Skeletons (Practical 2)
│   ├── index.html
│   ├── about.html
│   ├── contact.html
│   ├── courses.html
│   ├── attendance.html
│   ├── timetable.html
│   ├── events.html
│   ├── profile.html
│   ├── login.html
│   └── register.html
│
├── sql/                                # Database Schema & Seed Data
│   └── schema.sql                      # MySQL DDL schema and seed data
│
├── storage/                            # File Storage Directory (Practical 7)
│   ├── contacts.csv                    # Contact inquiries CSV
│   └── registrations.csv               # Registration records CSV
│
└── uploads/                            # File Upload Directory (Practical 12)
    └── posters/                        # Validated uploaded event poster graphics
```

---

## Local Setup Instructions

### 1. Requirements
- **PHP**: Version 8.0 or newer (tested on PHP 8.5)
- **MySQL / MariaDB**: Version 5.7, 8.0, or 9.x
- **Web Server**: Apache (via XAMPP, WAMP, MAMP) or PHP's built-in development server

### 2. Database Configuration
1. Start your local MySQL service (e.g. through XAMPP control panel or Homebrew `brew services start mysql`).
2. Open terminal and import `sql/schema.sql`:
   ```bash
   mysql -u root < sql/schema.sql
   ```
   *(If your MySQL root account has a password, use `mysql -u root -p < sql/schema.sql`)*
3. Verify that `studenthub_db` has been created with all tables (`users`, `students`, `events`, `registrations`).

### 3. Database Credentials Configuration
If your MySQL credentials differ from the standard defaults (`host: 127.0.0.1`, `user: root`, `password: ''`), update the constants at the top of `php/db.php`:
```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'studenthub_db');
define('DB_PORT', 3306);
```

### 4. Running the Application

#### Option A: Using Built-in PHP Server (Simplest & Recommended)
Navigate to the `StudentHub` root directory in your terminal and run:
```bash
php -S localhost:8000
```
Then open your browser and navigate to:
```
http://localhost:8000/pages/login.html
```

#### Option B: Using XAMPP / Apache
1. Copy the `StudentHub` folder into your XAMPP web directory (`htdocs/`).
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open your browser and navigate to:
```
http://localhost/StudentHub/pages/login.html
```

---

## Demonstration Login Credentials

Use the following pre-seeded credentials for testing and viva demonstration:

| Role | Username | Email | Password | Target Destination |
|---|---|---|---|---|
| **Administrator** | `admin` | `admin@charusat.edu.in` | `Admin@123` | `pages/admin_dashboard.php` |
| **Student** | `student` | `student@charusat.edu.in` | `Student@123` | `pages/dashboard.php` |

---

## Step-by-Step Testing Guide

### 1. Public Pages & Interactivity
- Open `http://localhost:8000/pages/dashboard.html`.
- Test the **Light/Dark theme toggle** at the top right &mdash; verify it switches to dark mode and persists upon page refresh.
- Test the **Notice banner** close button.
- Test the **Highlights slider** using Next/Previous buttons.
- Click **"View Quick Information"** to open and close the modal popup.
- Visit `pages/about.html` and click the FAQ items to test the collapsible accordion.

### 2. Client & Server Form Validation (Practicals 5, 7, 9)
- Open `pages/register.html`. Attempt to submit an empty form &mdash; observe client-side regex error messages and password strength meter.
- Fill valid details and submit &mdash; verify user and student records are inserted into MySQL, and a row is appended to `storage/registrations.csv`.
- Open `pages/contact.html`, fill out the form, and submit &mdash; verify entry is appended to `storage/contacts.csv`.

### 3. Authentication & Role-Based Access (Practical 10)
- Try accessing `http://localhost:8000/pages/admin_dashboard.php` without logging in &mdash; observe automatic redirection to login with `?msg=unauthorized`.
- Log in with `student` / `Student@123` &mdash; verify redirection to `pages/dashboard.php` displaying the student's personal details.
- Attempt to navigate to `pages/admin_dashboard.php` as a student &mdash; observe access is blocked.
- Click "Logout" &mdash; verify session is destroyed and you are returned to login with `?msg=logged_out`.

### 4. Admin Student Management CRUD (Practical 11)
- Log in with `admin` / `Admin@123` &mdash; redirected to `pages/admin_dashboard.php`.
- Click **"Manage Students"** (`pages/manage_students.php`).
- Test search (type student name) and filter by Course.
- Click **"+ Add Student"**, enter details, and save.
- Click **"Edit"** on a student row, modify information, and save.
- Click **"Export to CSV"** &mdash; verify student CSV download.

### 5. Admin Event Management & Poster Upload (Practical 12)
- From Admin Dashboard, click **"Manage Events"** (`pages/manage_events.php`).
- Click **"+ Add Event"**, fill details, choose a poster image (JPEG, PNG, or WebP &le; 2MB), and submit.
- Verify new event appears in the table with its thumbnail image.
- Click **"Edit"** to update details or click **"Delete"** (verifying file cleanup).

### 6. Real-Time Fetch API CRUD Without Page Reload (Practical 13)
- From Admin Dashboard, click **"Events API CRUD"** (`pages/events_api.php`).
- Notice the table loads data asynchronously via `fetch('../api/events.php')`.
- Test live search in the search bar &mdash; observe table updates instantly.
- Click **"+ Add Event via API"**, enter event details, and submit.
- Verify the event appears in the list immediately with a green success alert, without the browser tab reloading!

---
