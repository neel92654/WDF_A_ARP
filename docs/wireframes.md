# Low-Fidelity Wireframes - CHARUSAT Student Hub

## 1. Login Page Wireframe (Desktop & Mobile)

```
+-------------------------------------------------------------+
| [LOGO] CHARUSAT STUDENT HUB                      [Theme: ☾] |
+-------------------------------------------------------------+
|                                                             |
|                 +-------------------------+                 |
|                 |   [LOGO] Student Login  |                 |
|                 |   Access your dashboard |                 |
|                 | ----------------------- |                 |
|                 | Username / Enrollment:  |                 |
|                 | [_____________________] |                 |
|                 | Password:               |                 |
|                 | [_____________________] |                 |
|                 |                         |                 |
|                 | [     Sign In Button   ]|                 |
|                 |                         |                 |
|                 | New student? Register   |                 |
|                 +-------------------------+                 |
|                                                             |
+-------------------------------------------------------------+
| (c) 2026 CHARUSAT Student Hub - Charotar University         |
+-------------------------------------------------------------+
```

## 2. Student Dashboard Wireframe (Desktop)

```
+-------------------------------------------------------------+
| [LOGO] CHARUSAT STUDENT HUB  [Home|Attn|Assign|Courses...]  |
+-------------------------------------------------------------+
| [Notice Alert Banner: Mid Sem Exam Begins 7 Sept]       [X] |
+-------------------------------------------------------------+
| Welcome Back, Demo Student                                  |
| Enrollment: STU2026001 | Program: B.Tech CE | Sem: 3        |
|                                      [View Quick Info Modal]|
+-------------------------------------------------------------+
| Student Hub Highlights Slider            [Previous] [Next]  |
| [Active Slide Card: Current Attendance / Assignments / Evt] |
+-------------------------------------------------------------+
| [CARD 1: Attn 92%]   [CARD 2: Assign 3]                     |
| [CARD 3: Courses 4]  [CARD 4: Next Event Hackathon]         |
+-------------------------------------------------------------+
| Academic Modules Table          | Latest Announcement       |
| - Attendance : 92%              | Mid Sem Exam circular...  |
| - Assignments: 3 Pending        | Date: 7 September 2026    |
| - Courses    : 4 Enrolled       | [View all announcements]  |
+-------------------------------------------------------------+
| About | Contact | Logout                                    |
+-------------------------------------------------------------+
```

## 3. Attendance Summary 2x2 Wireframe (Desktop)

```
+-------------------------------------------------------------+
| Attendance Overview                                         |
| Student Name: Demo Student | Enrollment: STU2026001 | Sem: 3|
+------------------------------+------------------------------+
| [PRIMARY BORDER]             | [PRIMARY BORDER]             |
| OVERALL AVERAGE              | TOTAL LECTURES               |
| 94.75%                       | 117                          |
| [Good Standing]              | [Conducted]                  |
+------------------------------+------------------------------+
| [PRIMARY BORDER]             | [PRIMARY BORDER]             |
| LECTURES ATTENDED            | MINIMUM REQUIRED             |
| 111                          | 75%                          |
| [Present]                    | [University Rule]            |
+------------------------------+------------------------------+
| Subject-wise Lecture Attendance Table                       |
| - WDF  : 28 total, 27 present -> 96% [Good]                 |
| - OOP  : 30 total, 28 present -> 93% [Good]                 |
| - FDSA : 29 total, 27 present -> 93% [Good]                 |
| - DM   : 30 total, 29 present -> 97% [Good]                 |
+-------------------------------------------------------------+
```

## 4. Student Management CRUD Wireframe (Admin Panel)

```
+-------------------------------------------------------------+
| [LOGO] CHARUSAT STUDENT HUB [Admin]   [Overview|Students|Ev]|
+-------------------------------------------------------------+
| Student Management                     [Export CSV] [+ Add] |
+-------------------------------------------------------------+
| [Search by name/email...     ] [Course Filter v] [Filter Btn]|
+-------------------------------------------------------------+
| Enrollment | Full Name  | Email      | Course | Year | Action|
| STU2026001 | Demo Stud  | student@.. | CE     | 2nd  | [E][D]|
| STU2026002 | Alex M     | alex@...   | CE     | 2nd  | [E][D]|
| STU2026003 | Jordan L   | jordan@..  | CE     | 2nd  | [E][D]|
+-------------------------------------------------------------+
| Modal: Add Student / Edit Student                           |
| Inputs: Enrollment, Name, Email, Mobile, Course, Year, Gend |
| [Cancel] [Save / Update Button]                             |
+-------------------------------------------------------------+
```

## 5. Event Management CRUD & Poster Upload Wireframe

```
+-------------------------------------------------------------+
| Event Management & Poster Upload                   [+ Add]  |
+-------------------------------------------------------------+
| Poster | Title       | Date       | Venue   | Cat   | Action|
| [Img]  | TechFest    | 2026-09-15 | Aud A   | Comp  | [E][D]|
| [Img]  | AI Seminar  | 2026-08-30 | Aud B   | Sem   | [E][D]|
| [Img]  | Hackathon   | 2026-08-07 | Lab 304 | Comp  | [E][D]|
+-------------------------------------------------------------+
| Modal: Add / Edit Event                                     |
| Title: [_____________________]                              |
| Date: [YYYY-MM-DD]    Category: [Select Dropdown v]         |
| Venue: [Campus Hall ] Status:   [Upcoming v]                |
| Poster File: [Choose File: JPG/PNG/WebP <= 2MB]             |
| [Cancel] [Save Event]                                       |
+-------------------------------------------------------------+
```
