# Sitemap - CHARUSAT Student Hub Portal

## 1. Hierarchy Tree

```
Student Hub Portal
├── Authentication
│   ├── Login Page (pages/login.html)
│   ├── Registration Form (pages/register.html)
│   ├── Registration Processor (php/register.php)
│   ├── Login Processor (php/login_process.php)
│   └── Logout Handler (php/logout.php)
│
├── Student Portal (Public & Protected)
│   ├── Student Dashboard (pages/dashboard.php & pages/dashboard.html)
│   ├── Attendance Overview (pages/attendance.html)
│   ├── Assignments Tracker (pages/assignments.html)
│   ├── Courses Catalog (pages/courses.html)
│   ├── Weekly Timetable (pages/timetable.html)
│   ├── Campus Announcements (pages/announcements.html)
│   ├── Live Events Directory (pages/events.html)
│   ├── Student Profile (pages/profile.html)
│   ├── Account Settings (pages/settings.html)
│   ├── Contact & Support (pages/contact.html)
│   └── About Portal (pages/about.html)
│
├── Administrator Control Panel (Protected: Admin Only)
│   ├── Admin Dashboard (pages/admin_dashboard.php)
│   ├── Student Management CRUD (pages/manage_students.php)
│   ├── Event Management CRUD & Upload (pages/manage_events.php)
│   └── Asynchronous Events API CRUD (pages/events_api.php)
│
├── REST API Endpoints
│   ├── Events JSON API (api/events.php)
│   └── Students JSON API (api/students.php)
│
└── Database & Tests
    ├── MySQL Schema & Seed Data (sql/schema.sql)
    ├── MySQLi Connection (php/db.php)
    ├── PDO Connection (php/db_pdo.php)
    └── PDO Connectivity Test (php/test_pdo.php)
```

## 2. Page & Endpoint Directory

| Page / Endpoint | Purpose | Access Level | Primary Technologies |
|---|---|---|---|
| `pages/login.html` | Portal entry, credential inputs, status alerts | Public | HTML5, Bootstrap 5, JS |
| `pages/register.html` | Student registration with client validation & password meter | Public | HTML5, Bootstrap 5, JS |
| `pages/dashboard.php` | Authenticated student landing page with metrics & highlights | Student / Admin | PHP, Sessions, Bootstrap 5, JS |
| `pages/attendance.html` | Subject-wise attendance breakdown and 2x2 summary cards | Public / Student | HTML5, Bootstrap 5 |
| `pages/assignments.html` | Coursework deadlines, pending submissions, and status badges | Public / Student | HTML5, Bootstrap 5 |
| `pages/courses.html` | Semester course directory, credits, faculty mentors | Public / Student | HTML5, Bootstrap 5 |
| `pages/timetable.html` | Structured weekly schedule with lunch-break intervals | Public / Student | HTML5, Bootstrap 5 |
| `pages/announcements.html`| Institutional notices, academic circulars, exam dates | Public / Student | HTML5, Bootstrap 5 |
| `pages/events.html` | Dynamic event catalog with search, filter, sort, and pagination | Public / Student | Fetch API, JSON, JS |
| `pages/profile.html` | Student identity card and verified academic records | Public / Student | HTML5, Bootstrap 5 |
| `pages/settings.html` | Security preferences, theme mode, notification options | Public / Student | HTML5, Bootstrap 5 |
| `pages/contact.html` | Support desk inquiry form with server-side CSV storage | Public | HTML5, PHP, CSV |
| `pages/about.html` | Portal purpose, core features, and collapsible FAQ | Public | HTML5, Bootstrap 5, JS |
| `pages/admin_dashboard.php`| Admin KPI statistics, quick module links, recent student list | Admin Only | PHP, MySQL, Sessions |
| `pages/manage_students.php`| Full student CRUD, search, course filter, and CSV export | Admin Only | PHP, MySQLi, Bootstrap 5 |
| `pages/manage_events.php` | Full event CRUD with validated poster uploads | Admin Only | PHP, File Upload, MySQLi |
| `pages/events_api.php` | Live event management via Fetch API without full page reload | Admin Only | Fetch API, JSON, JS |
| `api/events.php` | REST JSON API endpoint for event retrieval & mutations | Protected (Mutations) | PHP, JSON, MySQLi |
| `api/students.php` | REST JSON API endpoint for student lists | Public / Admin | PHP, JSON, MySQLi |
| `php/test_pdo.php` | Standalone demonstration script for PDO connectivity | Admin / Viva | PHP PDO, MySQL |
