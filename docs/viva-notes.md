# Student Hub Portal - Practical Viva Examination Notes (Practicals 1 to 14)

This revision guide is designed for the 2nd-year Web Development Frameworks (ITUE203) practical viva examination. It breaks down each practical implemented in the project.

---

## Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe & GitHub Setup

- **Requirement**: Initiate a semester-long project, define user roles, outline at least 10 pages, create sitemap, wireframes, folder structure, and README.
- **Implemented**: Created 13 clean portal pages with unified layout, requirement documentation (`docs/requirement-analysis.md`), visual tree sitemap (`docs/sitemap.md`), low-fidelity wireframes (`docs/wireframes.md`), and comprehensive setup guide (`README.md`).
- **Key Files**: `docs/requirement-analysis.md`, `docs/sitemap.md`, `docs/wireframes.md`, `README.md`.
- **Core Concepts**: Client-server architecture, URL structure (protocol, host, port, path, query parameters), sitemap planning, and maintainable project directory structure.
- **How it Works**: Organizes source code into dedicated folders (`pages/` for views, `css/` for stylesheets, `js/` for client scripts, `php/` for backend processors, `api/` for JSON endpoints, `sql/` for database schema, `storage/` for file data, and `docs/` for planning artifacts).
- **Viva Questions & Answers**:
  - *Q: What are the main components of a URL?*
    - *A: Protocol (e.g. `http://`), hostname (`localhost`), port (`8000`), resource path (`/pages/login.html`), and optional query string (`?error=invalid`).*
  - *Q: Why is folder structuring important in web development?*
    - *A: It separates concerns (frontend, backend, assets, data) making the application scalable, maintainable, and easier to debug.*
- **How to Demo**: Show `docs/sitemap.md` and walk through the project directory layout in VS Code.

---

## Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

- **Requirement**: Develop static HTML5 skeletons for at least 10 pages using semantic elements and accessibility attributes.
- **Implemented**: Structured all pages using semantic containers (`<nav>`, `<main>`, `<section>`, `<footer>`, `<form>`, `<table>`), descriptive labels (`<label for="...">`), image alt attributes, and keyboard-focusable controls.
- **Key Files**: `pages/dashboard.html`, `pages/attendance.html`, `pages/courses.html`, `pages/timetable.html`, `pages/about.html`, etc.
- **Core Concepts**: HTML5 semantic tags (`<header>`, `<nav>`, `<main>`, `<footer>`), ARIA attributes (`aria-label`, `aria-controls`, `aria-expanded`), form accessibility, and heading hierarchy (`h1` through `h6`).
- **How it Works**: Semantic tags tell browsers and assistive screen readers the exact purpose of each section rather than relying on generic `<div>` tags.
- **Viva Questions & Answers**:
  - *Q: Why use `<main>` and `<nav>` instead of `<div>`?*
    - *A: Semantic tags provide meaning to search engines and screen readers, improving SEO and web accessibility (WCAG compliance).*
  - *Q: What is the purpose of the `alt` attribute in `<img>`?*
    - *A: It provides descriptive fallback text when an image fails to load and allows screen readers to describe the graphic to visually impaired users.*
- **How to Demo**: Inspect any page (e.g. `pages/timetable.html`) in browser dev tools to show `<nav>`, `<main>`, and `<table>` elements with accessible labels.

---

## Practical 3: Responsive UI Design using CSS Grid, Flexbox, and Bootstrap

- **Requirement**: Build responsive mobile-first layouts using CSS Grid, Flexbox, and Bootstrap 5 without cluttered custom CSS.
- **Implemented**: Utilized Bootstrap 5 grid (`container`, `row`, `col-12 col-md-6 col-lg-3`), flexbox utilities (`d-flex`, `align-items-center`, `justify-content-between`), and unified primary blue cards across all screens (1920px down to 375px).
- **Key Files**: `pages/attendance.html`, `pages/timetable.html`, `css/dashboard.css`, `css/attendance.css`.
- **Core Concepts**: Media queries, CSS breakpoints (`sm`, `md`, `lg`, `xl`), Flexbox (`justify-content`, `align-items`), Grid columns, and `.table-responsive`.
- **How it Works**: Bootstrap's 12-column grid automatically wraps columns on smaller viewports. Tables wrapped in `.table-responsive` enable smooth horizontal scroll on mobile without breaking page layout.
- **Viva Questions & Answers**:
  - *Q: How does Bootstrap's responsive grid work?*
    - *A: It uses CSS Flexbox with media query breakpoints (e.g. `col-12` on mobile, `col-md-6` on tablets, `col-lg-3` on desktop).*
  - *Q: How did you ensure the timetable table doesn't break mobile screens?*
    - *A: Wrapped the `<table>` in a `<div class="table-responsive">` container, enabling fluid horizontal scroll.*
- **How to Demo**: Resize browser to 375px mobile view or open DevTools Device Toolbar to show 2x2 cards stacking cleanly into 1 column.

---

## Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

- **Requirement**: Implement collapsible FAQ, modal popup, content slider, notification banner, hamburger menu, and light/dark theme switcher stored in `localStorage`.
- **Implemented**:
  1. Collapsible FAQ in `pages/about.html` (`js/about.js`).
  2. Modal popup in `pages/dashboard.html` (`openModal()` / `closeModal()`).
  3. Highlights slider with Next/Prev buttons in `js/dashboard.js`.
  4. Dismissible alert notice banner in `pages/dashboard.html` (`closeNotification()`).
  5. Responsive mobile hamburger menu in Bootstrap navbar.
  6. Light/Dark theme switcher in `js/common.js` using `localStorage` and toggling `☾` / `☀`.
- **Key Files**: `js/common.js`, `js/dashboard.js`, `js/about.js`, `pages/dashboard.html`, `pages/about.html`.
- **Core Concepts**: DOM selection (`getElementById`), event listeners (`addEventListener`, `onclick`), CSS class toggling (`classList.toggle`), and `localStorage.setItem` / `getItem`.
- **How it Works**: When the theme button is clicked, `toggleTheme()` toggles the `.dark` class on `document.body`, sets `data-bs-theme="dark"` on `<html>`, updates the button icon to `☀`, and saves `'theme': 'dark'` to browser `localStorage`. On page load, `window.addEventListener("load")` reads `localStorage` and restores the theme instantly.
- **Viva Questions & Answers**:
  - *Q: What is the difference between `localStorage` and `sessionStorage`?*
    - *A: `localStorage` persists data permanently across browser restarts until cleared, whereas `sessionStorage` expires when the browser tab is closed.*
  - *Q: How does the content slider work?*
    - *A: An integer counter tracks `currentSlide`. `showSlide(index)` removes `.active` from all slides and adds `.active` to the slide at the target index with bounds wrapping.*
- **How to Demo**: Click the theme toggle button at top-right (observing instant dark mode), refresh the page (theme persists), and open the Quick Info modal on dashboard.

---

## Practical 5: Registration Form with Frontend Validation and Password Strength

- **Requirement**: Create a registration form with HTML5 types, JavaScript regex validation, user-friendly inline error messages, and a password strength meter.
- **Implemented**: Full student enrollment form validating name (`/^[A-Za-z ]{2,50}$/`), email format, 10-digit mobile (`/^[6-9][0-9]{9}$/`), password length & complexity, confirm password matching, course/year selection, and dynamic password strength indicator.
- **Key Files**: `pages/register.html`, `js/register.js`.
- **Core Concepts**: Form submit event intercept (`event.preventDefault()`), Regular Expressions (`test()`), live `input` event listeners, and DOM text updates.
- **How it Works**: On typing in the password field, an `input` event listener evaluates string length and character classes (uppercase letters + numbers) to display Weak, Medium, or Strong. On form submission, all fields are tested against regular expressions; if any check fails, `valid=false`, `event.preventDefault()` stops submission, and inline error text is displayed below the field.
- **Viva Questions & Answers**:
  - *Q: Why is client-side validation alone not sufficient?*
    - *A: Client-side JavaScript can be bypassed by disabling JS, modifying code in dev tools, or sending direct HTTP POST requests via curl. Backend validation is strictly required for security.*
  - *Q: Explain the regex `/^[6-9][0-9]{9}$/`.*
    - *A: `^` asserts start of string; `[6-9]` requires the first digit to be 6, 7, 8, or 9 (Indian mobile standard); `[0-9]{9}` requires exactly 9 more digits; `$` asserts end of string.*
- **How to Demo**: Submit an empty form on `pages/register.html` to observe red validation messages, then type a password to see live strength indicator change.

---

## Practical 6: Rendering External JSON Data using Fetch API, Search, Filter, Sort and Pagination

- **Requirement**: Display records from external JSON files using Fetch API with dynamic search, filter, sorting, and pagination.
- **Implemented**: `pages/events.html` consumes `data/events.json` (15 records) via `fetch()`, with live text search, category dropdown filtering, date/name sorting, and client-side pagination (5 items per page).
- **Key Files**: `pages/events.html`, `js/events.js`, `data/events.json`.
- **Core Concepts**: JavaScript Promises, `fetch()`, `response.json()`, array methods (`filter()`, `sort()`, `slice()`), and dynamic table row generation (`createElement`, `innerHTML`).
- **How it Works**: `loadEvents()` initiates an asynchronous HTTP request. Once parsed, `applyFilters()` filters the array by search string and category, sorts by selected order, calculates total pages (`Math.ceil(length / perPage)`), slices the current page slice (`slice(start, start + perPage)`), and renders rows dynamically into `<tbody>`.
- **Viva Questions & Answers**:
  - *Q: How does `fetch()` work in JavaScript?*
    - *A: `fetch()` returns a Promise that resolves to the Response object. Calling `.then(res => res.json())` parses the JSON body into a native JavaScript object/array asynchronously.*
  - *Q: How is pagination calculated in JavaScript?*
    - *A: Starting index is `(currentPage - 1) * perPage`. Slicing array with `array.slice(start, start + perPage)` extracts only the items for the current page.*
- **How to Demo**: Open `pages/events.html`, type "Hackathon" in the search box (instant filter), change category dropdown, and click pagination button "2".

---

## Practical 7: PHP Form Processing with Server-Side Validation and CSV File Storage

- **Requirement**: Process submitted form data via PHP POST, sanitize inputs on the server, and store records in CSV format with clear status messages.
- **Implemented**: `pages/contact.html` posts inquiry messages to `php/contact.php`. PHP validates inputs, sanitizes strings (`htmlspecialchars`, `trim`), checks email validity, and appends rows to `storage/contacts.csv` using `fputcsv()`.
- **Key Files**: `pages/contact.html`, `php/contact.php`, `storage/contacts.csv`.
- **Core Concepts**: HTTP POST method, `$_POST` superglobal, server validation (`filter_var`), XSS sanitization (`htmlspecialchars`), file handling (`fopen`, `fputcsv`, `fclose`).
- **How it Works**: `php/contact.php` verifies `$_SERVER['REQUEST_METHOD'] === 'POST'`. If validation passes, `fopen($file, "a")` opens the CSV in append mode. If the file is new or empty, header titles are written first, followed by sanitized submission fields and timestamp using `fputcsv()`.
- **Viva Questions & Answers**:
  - *Q: Why use POST instead of GET for form submissions?*
    - *A: POST sends data in the HTTP request body rather than appending to the URL query string, making it secure for sensitive data and free from URL length limitations.*
  - *Q: What does `fputcsv()` do in PHP?*
    - *A: It formats an array as a CSV line with appropriate comma delimiters and quotation enclosures, and writes it to an open file pointer.*
- **How to Demo**: Fill out the contact form at `pages/contact.html` and click "Send Message", then inspect `storage/contacts.csv` to verify the appended record.

---

## Practical 8: MySQL Schema Design, ER Model, PDO Connectivity & Prepared Statements

- **Requirement**: Design normalized database with students, events, and registrations. Create PDO database connection with prepared statements.
- **Implemented**: Relational database `studenthub_db` with 4 tables (`users`, `students`, `events`, `registrations`), documented ER model (`docs/er-model.md`), PDO connection helper (`php/db_pdo.php`), and verification script (`php/test_pdo.php`).
- **Key Files**: `sql/schema.sql`, `docs/er-model.md`, `php/db_pdo.php`, `php/test_pdo.php`.
- **Core Concepts**: 3rd Normal Form (3NF), Primary Keys, Foreign Keys (`ON DELETE CASCADE`, `ON DELETE SET NULL`), PDO DSN, PDO error mode (`ERRMODE_EXCEPTION`), and prepared parameter binding (`:param`).
- **How it Works**: `php/db_pdo.php` creates a `new PDO("mysql:host=...;dbname=...", $user, $pass, $options)`. `php/test_pdo.php` prepares `SELECT * FROM users WHERE role = :role`, binds the parameter safely, and fetches results.
- **Viva Questions & Answers**:
  - *Q: What is the main difference between PDO and MySQLi?*
    - *A: PDO supports 12 different database drivers using a unified API and named parameters (`:name`), whereas MySQLi works only with MySQL databases.*
  - *Q: What is SQL Injection and how do prepared statements prevent it?*
    - *A: SQL Injection occurs when untrusted user input is directly concatenated into SQL queries. Prepared statements compile the query structure first and treat input data purely as literal values, preventing attackers from altering the query logic.*
- **How to Demo**: Run `php php/test_pdo.php` in terminal or open `http://localhost:8000/php/test_pdo.php` in browser to see the green PDO connection confirmation.

---

## Practical 9: Secure User Registration with Database Insert, Duplicate Check & Password Hashing

- **Requirement**: Insert user and student details into MySQL using MySQLi prepared statements, check for duplicate emails, and hash passwords with `password_hash()`.
- **Implemented**: Upgraded `php/register.php` to perform server-side regex checks, query `users` for duplicate email, hash passwords using `password_hash($pass, PASSWORD_DEFAULT)`, and insert records into `users` and `students` tables inside an atomic database transaction.
- **Key Files**: `pages/register.html`, `php/register.php`, `php/db.php`.
- **Core Concepts**: Password hashing (Bcrypt algorithm), salt generation, database transactions (`mysqli_begin_transaction`, `mysqli_commit`, `mysqli_rollback`), and MySQLi prepared statements.
- **How it Works**: If input is valid, PHP checks if email exists. If not, it hashes the password, begins a transaction, inserts user record to get `insert_id`, then inserts student profile with the linked `user_id`. If both succeed, `mysqli_commit()` commits the transaction and appends to `storage/registrations.csv`.
- **Viva Questions & Answers**:
  - *Q: Why should passwords never be hashed with plain MD5 or SHA1?*
    - *A: MD5 and SHA1 are fast hashing algorithms vulnerable to collision attacks and rainbow tables. `password_hash()` uses Bcrypt, which is intentionally slow and includes a unique cryptographic salt per password.*
  - *Q: What is a database transaction?*
    - *A: A sequence of operations executed as a single atomic unit (ACID properties). If any query fails, `mysqli_rollback()` undoes all partial changes, keeping data consistent.*
- **How to Demo**: Register a new student on `pages/register.html`. Try registering again with the same email to observe the duplicate email rejection message.

---

## Practical 10: Secure Login/Logout with Sessions, Role-Based Access, and Session Timeout

- **Requirement**: Implement secure login/logout with PHP sessions, `password_verify()`, session ID regeneration, protected dashboard access, role-based redirection, and inactivity timeout.
- **Implemented**:
  - `php/login_process.php` queries user by username/email, validates password using `password_verify()`, calls `session_regenerate_id(true)`, and redirects by role (`admin` -> `admin_dashboard.php`, `student` -> `dashboard.php`).
  - `php/auth.php` enforces `check_auth()` with a 30-minute inactivity timeout.
  - `php/logout.php` clears `$_SESSION`, expires session cookies, and destroys session.
- **Key Files**: `php/auth.php`, `php/login_process.php`, `php/logout.php`, `pages/login.html`, `pages/dashboard.php`, `pages/admin_dashboard.php`.
- **Core Concepts**: PHP `$_SESSION` superglobal, HTTP cookies, Session Fixation protection via `session_regenerate_id()`, Role-Based Access Control (RBAC), and session timeout calculation (`time() - $_SESSION['last_activity']`).
- **How it Works**: When credentials match, `login_user()` sets session variables and records current timestamp. Protected pages call `check_auth('admin')` or `check_auth('student')`. If no session exists or inactivity exceeds 1800s, the user is redirected to `login.html?msg=unauthorized` or `?msg=timeout`.
- **Viva Questions & Answers**:
  - *Q: Why is `session_regenerate_id(true)` called upon login?*
    - *A: It prevents Session Fixation attacks where an attacker tricks a user into authenticating using a known session ID.*
  - *Q: How does `password_verify()` work with `password_hash()`?*
    - *A: The hash string contains the algorithm, cost factor, salt, and hash. `password_verify()` extracts the salt and parameters from the stored hash, hashes the candidate password, and performs a timing-attack-safe comparison.*
- **How to Demo**:
  1. Login with `admin` / `Admin@123` -> redirected to Admin Control Panel.
  2. Logout -> redirected to login page.
  3. Login with `student` / `Student@123` -> redirected to Student Dashboard.
  4. Try accessing `pages/admin_dashboard.php` as student -> redirected with forbidden notice.

---

## Practical 11: Student Management CRUD with Search and Filter

- **Requirement**: Admin module to view, add, edit, delete, search, and filter student records using MySQLi prepared statements and clear feedback messages.
- **Implemented**: `pages/manage_students.php` provides a responsive management interface with live text search (name/enrollment/email), course dropdown filter, Add Student modal, Edit Student modal, Delete with confirmation, and one-click CSV export.
- **Key Files**: `pages/manage_students.php`, `php/db.php`.
- **Core Concepts**: Full CRUD (Create, Read, Update, Delete) operations, parameterized SQL queries, dynamically bound `mysqli_stmt_bind_param()`, modal data binding via JavaScript dataset attributes.
- **How it Works**: Form submissions send `action=create`, `update`, or `delete`. The page prepares the corresponding parameterized query, executes it safely, and sets `$message` and `$messageClass`. Search and filter options dynamically append `AND (full_name LIKE ? ...)` clauses using prepared statements.
- **Viva Questions & Answers**:
  - *Q: How does the edit modal populate a student's existing values?*
    - *A: Each "Edit" button carries `data-*` attributes (`data-id`, `data-name`, etc.). A JavaScript click listener reads these attributes and sets the values of the modal form inputs.*
  - *Q: How does the CSV export work in PHP?*
    - *A: It sets `Content-Type: text/csv` and `Content-Disposition: attachment; filename=...` headers, opens `php://output`, queries the database, and streams rows using `fputcsv()` directly to the client browser.*
- **How to Demo**: Log in as admin, open "Manage Students", search for "Alex", filter by course, click "Edit" on a student to change details, or click "Export to CSV".

---

## Practical 12: Event Management CRUD with Poster Upload and File Validation

- **Requirement**: Admin module to add, view, update, delete events, and upload event posters with file type and size validation.
- **Implemented**: `pages/manage_events.php` manages university events with poster upload. Validates file MIME types (`finfo_file` for `image/jpeg`, `image/png`, `image/webp`), checks extensions, restricts size (&le; 2MB), generates safe unique filenames, saves to `uploads/posters/`, and stores relative paths in MySQL.
- **Key Files**: `pages/manage_events.php`, `uploads/posters/`.
- **Core Concepts**: Multipart form encoding (`enctype="multipart/form-data"`), `$_FILES` superglobal, MIME inspection via `finfo_file(FILEINFO_MIME_TYPE)`, `move_uploaded_file()`, and file unlink upon deletion.
- **How it Works**: When an image is uploaded, PHP inspects `$_FILES['poster']['error']` and `$_FILES['poster']['size']`. Next, `finfo_file()` reads the actual binary header (magic bytes) to verify it is a genuine image, avoiding extension spoofing. A unique name `poster_TIMESTAMP_RAND.ext` is created and moved to `uploads/posters/`.
- **Viva Questions & Answers**:
  - *Q: Why is checking `$_FILES['file']['type']` insecure?*
    - *A: `$_FILES['file']['type']` is sent by the client browser in the HTTP header and can be easily forged by attackers. Using PHP's `finfo_file()` inspects the real binary signature of the file on the server.*
  - *Q: Why do we rename uploaded files before storing them?*
    - *A: Prevents file overwrites, directory traversal attacks, and removes special/executable characters from untrusted client filenames.*
- **How to Demo**: Open "Manage Events", click "+ Add Event", fill details, choose a poster image (&le; 2MB), save, and verify the new event appears in the table with its thumbnail image.

---

## Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload

- **Requirement**: Build simple PHP JSON API endpoints for events and consume them using Fetch API to perform list, add, update, and delete operations asynchronously without a full page reload.
- **Implemented**:
  - API endpoint `api/events.php`: handles GET (listing, search, category filter, sorting) and POST (`create`, `update`, `delete`) returning structured JSON with HTTP status codes.
  - Interactive page `pages/events_api.php`: fully asynchronous interface using native `fetch()` for all operations. Includes live search, real-time table re-render, modal forms, button disabling on submit (prevents double submission), and dynamic alerts without page reload.
- **Key Files**: `api/events.php`, `api/students.php`, `pages/events_api.php`.
- **Core Concepts**: RESTful JSON API, `header('Content-Type: application/json')`, HTTP status codes (200, 400, 403, 405, 500), `fetch()` POST with JSON body (`JSON.stringify()`), asynchronous DOM manipulation.
- **How it Works**: The page fires `fetch('../api/events.php')` on load and renders rows using DOM methods. When adding or deleting, `fetch()` sends a JSON POST payload to `api/events.php`. The PHP endpoint validates input, executes prepared queries, and returns `{"success": true, "message": "..."}`. The JavaScript Promise handler catches the response and updates the UI immediately.
- **Viva Questions & Answers**:
  - *Q: What are the advantages of AJAX/Fetch over traditional form submissions?*
    - *A: Traditional submissions reload the entire page, causing screen flicker and redundant asset reloading. Fetch API exchanges only small JSON payloads in the background, making user interactions instant and responsive.*
  - *Q: How do you prevent double submission in asynchronous forms?*
    - *A: Disable the submit button (`btn.disabled = true`) and update its label (e.g. "Saving...") immediately upon form submission, re-enabling it only after the Promise resolves or rejects.*
- **How to Demo**: Open `pages/events_api.php` in browser. Click "+ Add Event via API", fill details, and submit. Notice the event appears instantly in the table with a green success message without the browser tab reloading!

---

## Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log

- **Requirement**: Develop an admin dashboard displaying user statistics, student count, event count, latest registrations, and audit logs. Restrict access to admin users only and show dynamic menu options based on role.
- **Implemented**:
  - `pages/admin_dashboard.php`: Live KPI aggregation cards (Total Students, Active Events, Event Bookings, Portal Users) loaded dynamically via SQL `COUNT(*)`.
  - Contextual Navigation: Administrators see full management controls (Overview, Manage Students, Manage Events, Events API CRUD, Student View), while students see only student modules.
  - Role-Based Access Control (RBAC): `check_auth('admin')` validates session identity on every request. Unauthorized student attempts are halted and redirected to `dashboard.php?msg=forbidden`.
  - Audit Trail Logging: `audit_logs` table records user logins, student creation/updates/deletions, and event operations via `php/audit.php` (`record_audit_action()`). The latest activity logs are displayed directly in a dedicated dashboard table.
- **Key Files**: `pages/admin_dashboard.php`, `php/audit.php`, `php/auth.php`, `sql/schema.sql`.
- **Core Concepts**: Relational aggregations (`COUNT(*)`), Server-enforced RBAC, Audit Trail logging for traceability and non-repudiation, role-based contextual navigation.
- **How it Works**: On page load, `check_auth('admin')` checks `$_SESSION['role']`. The script executes SQL aggregation queries for live counts and queries the `audit_logs` table joined with `users` (`SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.id DESC LIMIT 6`). Every administrative action across the portal automatically inserts an entry into `audit_logs`.
- **Viva Questions & Answers**:
  - *Q: Are dashboard metrics loaded dynamically or hardcoded?*
    - *A: Fully dynamically. SQL aggregation queries `SELECT COUNT(*) FROM students`, `events`, `registrations`, and `users` calculate real-time counts from the database on every page load.*
  - *Q: How is access restricted to administrators?*
    - *A: Server-side middleware `check_auth('admin')` verifies the session role before rendering markup. If a student tries to navigate to `admin_dashboard.php`, they are immediately redirected to `dashboard.php?msg=forbidden`.*
  - *Q: What is the purpose of an audit log in web applications?*
    - *A: Audit logs provide accountability and traceability by recording who performed an action, what entity was modified, when it occurred, and the operation details.*
- **How to Demo**:
  1. Log in as `student` and try visiting `http://localhost:8000/pages/admin_dashboard.php` -> observe redirect to student dashboard with forbidden message.
  2. Log in as `admin` -> see live KPI cards, recent students, and the Audit Trail table displaying recent operations.

---

## Core Concept Comparative Revision for Viva

| Concept Comparison | Primary Distinctions & Viva Summary |
|---|---|
| **Frontend vs Backend Validation** | **Frontend (JS)** provides immediate user feedback and UX convenience, but can be bypassed. **Backend (PHP)** is mandatory for security, verifying all types, lengths, and constraints before database operations. |
| **CSV/JSON Storage vs MySQL Storage** | **CSV/JSON** is simple file storage suitable for small logs or exports without a database server. **MySQL** is a relational database providing ACID transactions, foreign keys, indexing, concurrent access, and structured SQL queries. |
| **PDO vs MySQLi** | **MySQLi** is specialized for MySQL/MariaDB with both procedural and OOP APIs. **PDO (PHP Data Objects)** is an abstraction layer supporting 12+ database drivers with consistent exception handling and named parameters. |
| **Password Hashing vs Verification** | **Hashing (`password_hash()`)** uses one-way cryptographic algorithms (bcrypt/Argon2) with automatic salting to store irreversible digests. **Verification (`password_verify()`)** compares a plaintext candidate against the hash using constant-time comparison to prevent timing attacks. |
| **Sessions vs Remember-Me Tokens** | **Sessions** store server-side state identified by a temporary session cookie that expires on browser close or inactivity timeout. **Remember-Me Tokens** use long-lived cryptographic random tokens whose SHA-256 hashes are stored in the database to re-authenticate users across browser restarts. |
| **Traditional CRUD vs Fetch-based CRUD** | **Traditional CRUD** submits full HTML forms causing page reloads and screen flicker. **Fetch-based CRUD (AJAX)** exchanges JSON payloads asynchronously in the background, updating DOM elements dynamically without reloading the page. |
| **Authentication vs Role-Based Authorization** | **Authentication** answers *\"Who are you?\"* by verifying username and password. **Authorization (RBAC)** answers *\"What are you allowed to do?\"* by checking the authenticated user's role (`admin` vs `student`) against protected resources. |
| **Foreign Keys vs Unique Constraints** | **Foreign Keys** enforce referential integrity between related tables (e.g. `student_id` in registrations must exist in `students`). **Unique Constraints** prevent duplicate values in a column or composite columns (e.g. unique pair of `student_id` and `event_id`). |
| **JSON API Endpoints vs HTML Forms** | **JSON APIs** return structured, language-agnostic data payloads (`Content-Type: application/json`) intended for programmatic consumption by JavaScript clients or mobile apps. **HTML Forms** submit URL-encoded data intended to render or reload an HTML document. |

---

## Summary of Credentials for Practical Examination

| Role | Username | Email | Password | Target Dashboard |
|---|---|---|---|---|
| **Admin** | `admin` | `admin@charusat.edu.in` | `Admin@123` | `pages/admin_dashboard.php` |
| **Student** | `student` | `student@charusat.edu.in` | `Student@123` | `pages/dashboard.php` |
