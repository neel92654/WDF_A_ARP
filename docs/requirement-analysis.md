# Requirement Analysis - CHARUSAT Student Hub Portal

## 1. Project Overview & Scope
CHARUSAT Student Hub Portal is an integrated student information and academic management portal developed for Charotar University of Science and Technology (CHARUSAT) as part of the Web Development Frameworks (ITUE203) subject.
The portal consolidates academic services including lecture attendance monitoring, assignment tracking, course enrollment, class timetables, campus circulars, events, student profiles, and administrative management.

## 2. Target Users & Roles
- **Student (End User)**: Accesses academic schedules, attendance summaries, pending coursework, timetable, campus announcements, event directories, and updates personal account settings.
- **Administrator (Academic Staff / Dept. Admin)**: Manages student records (CRUD), publishes events with validated posters, monitors portal usage, and triggers asynchronous event updates via JSON APIs.

## 3. Functional Requirements

### FR1: Authentication & Account Management
- Student registration with client-side and server-side validation.
- Secure login with hashed password verification (`password_verify`).
- Role-based redirection (Students to Student Dashboard, Admins to Admin Panel).
- Session tracking with 30-minute inactivity timeout.
- Secure logout with complete session destruction.

### FR2: Academic Information Services
- Attendance summary cards with uniform primary styling and subject-wise attendance metrics.
- Course catalog with faculty mentor allocations and credit breakdowns.
- Structured weekly lecture timetable with lunch break intervals.
- Course assignment tracker with deadlines and submission statuses.
- Official campus announcements and priority notices.

### FR3: Dynamic Event Directory
- External JSON data consumption via Fetch API (`data/events.json`).
- Live search by event title and venue.
- Category filtering (Academic, Cultural, Sports, Workshop, Seminar, Competition, Social, Club).
- Multi-criteria sorting (Date ascending, Date descending, Title A-Z).
- Client-side pagination with dynamic page controls.

### FR4: Inquiries & File Storage
- Contact inquiry form with server-side sanitation.
- Safe persistence of contact inquiries to CSV format (`storage/contacts.csv`).

### FR5: Database & Administrator Services
- Relational schema in MySQL (`users`, `students`, `events`, `registrations`).
- PDO prepared statement connectivity demonstration.
- Full Student Management CRUD with live search, course filters, and CSV export.
- Full Event Management CRUD with validated image poster upload (JPEG, PNG, WebP &le; 2MB).
- REST-style JSON API (`api/events.php`, `api/students.php`) supporting Fetch-based CRUD operations without page reload.

## 4. Non-Functional Requirements
- **Usability**: Clean university portal aesthetic using standard Bootstrap 5 components.
- **Responsiveness**: Mobile-first responsive layouts tested across 1920px, 1440px, 1024px, 768px, 480px, and 375px viewports.
- **Accessibility**: Semantic HTML5 elements (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`), descriptive labels, alt text, and visible keyboard focus.
- **Security**: Password hashing via `password_hash()`, MySQLi & PDO prepared statements against SQL injection, MIME-type and size validation against malicious uploads, session fixation prevention via `session_regenerate_id(true)`.
- **Maintainability**: Clear separation of concerns (presentation, stylesheets, scripts, API endpoints, database storage).

## 5. Navigation & Page Flow
```
Login (login.html)
  |-- Valid Student Credentials --> Student Dashboard (dashboard.php / dashboard.html)
  |     |-- Attendance (attendance.html)
  |     |-- Assignments (assignments.html)
  |     |-- Courses (courses.html)
  |     |-- Timetable (timetable.html)
  |     |-- Announcements (announcements.html)
  |     |-- Events Directory (events.html)
  |     |-- Profile (profile.html)
  |     |-- Settings (settings.html)
  |     |-- Contact Support (contact.html)
  |     |-- About Portal (about.html)
  |     `-- Logout (../php/logout.php)
  |
  |-- Valid Admin Credentials --> Admin Dashboard (admin_dashboard.php)
  |     |-- Student Management CRUD (manage_students.php)
  |     |-- Event Management CRUD + Upload (manage_events.php)
  |     |-- Real-Time Events API CRUD (events_api.php)
  |     |-- Student Portal View (dashboard.php)
  |     `-- Logout (../php/logout.php)
  |
  `-- New Student Registration --> Register (register.html -> php/register.php)
```
