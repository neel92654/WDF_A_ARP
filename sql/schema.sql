CREATE DATABASE IF NOT EXISTS studenthub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub_db;

DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login DATETIME NULL,
    remember_token_hash CHAR(64) NULL,
    remember_token_expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    enrollment_no VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year VARCHAR(20) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_students_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    poster_path VARCHAR(255) NULL,
    status ENUM('upcoming', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'attended', 'cancelled') NOT NULL DEFAULT 'confirmed',
    CONSTRAINT fk_reg_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY uk_student_event (student_id, event_id)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action_name VARCHAR(100) NOT NULL,
    entity_type VARCHAR(60) NOT NULL,
    entity_id INT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO users (id, username, email, password_hash, role) VALUES
(1, 'admin', 'admin@charusat.edu.in', '$2y$12$TnUKTlWVmoswUgVo/nzw9OmGVV3tx0YhqXFL23MQIiLeF1Wa4jJMi', 'admin'),
(2, 'student', 'student@charusat.edu.in', '$2y$12$eqRsBgeEQ/JaAbInd5LmPedCsjkSaB1NcCPomWIzffFSRUW4Qlm4W', 'student');

INSERT INTO students (id, user_id, enrollment_no, full_name, email, mobile, course, year, gender) VALUES
(1, 2, 'STU2026001', 'Student 01', 'student@charusat.edu.in', '9800000001', 'B.Tech Computer Engineering', 'Second Year', 'Male'),
(2, NULL, 'STU2026002', 'Student 02', 'student02@charusat.edu.in', '9800000002', 'B.Tech Computer Engineering', 'Second Year', 'Female'),
(3, NULL, 'STU2026003', 'Student 03', 'student03@charusat.edu.in', '9800000003', 'B.Tech Computer Engineering', 'Second Year', 'Male'),
(4, NULL, 'STU2026004', 'Student 04', 'student04@charusat.edu.in', '9800000004', 'B.Tech Computer Engineering', 'Second Year', 'Female'),
(5, NULL, 'STU2026005', 'Student 05', 'student05@charusat.edu.in', '9800000005', 'B.Tech IT', 'First Year', 'Male'),
(6, NULL, 'STU2026006', 'Student 06', 'student06@charusat.edu.in', '9800000006', 'B.Tech IT', 'First Year', 'Female'),
(7, NULL, 'STU2026007', 'Student 07', 'student07@charusat.edu.in', '9800000007', 'B.Tech IT', 'Third Year', 'Male'),
(8, NULL, 'STU2026008', 'Student 08', 'student08@charusat.edu.in', '9800000008', 'BCA', 'First Year', 'Female'),
(9, NULL, 'STU2026009', 'Student 09', 'student09@charusat.edu.in', '9800000009', 'BCA', 'Second Year', 'Male'),
(10, NULL, 'STU2026010', 'Student 10', 'student10@charusat.edu.in', '9800000010', 'BCA', 'Third Year', 'Female'),
(11, NULL, 'STU2026011', 'Student 11', 'student11@charusat.edu.in', '9800000011', 'B.Tech Computer Engineering', 'Third Year', 'Male'),
(12, NULL, 'STU2026012', 'Student 12', 'student12@charusat.edu.in', '9800000012', 'B.Tech Computer Engineering', 'Fourth Year', 'Female'),
(13, NULL, 'STU2026013', 'Student 13', 'student13@charusat.edu.in', '9800000013', 'B.Tech IT', 'Second Year', 'Male'),
(14, NULL, 'STU2026014', 'Student 14', 'student14@charusat.edu.in', '9800000014', 'B.Tech IT', 'Fourth Year', 'Female'),
(15, NULL, 'STU2026015', 'Student 15', 'student15@charusat.edu.in', '9800000015', 'BCA', 'Second Year', 'Male'),
(16, NULL, 'STU2026016', 'Student 16', 'student16@charusat.edu.in', '9800000016', 'B.Tech Computer Engineering', 'First Year', 'Female'),
(17, NULL, 'STU2026017', 'Student 17', 'student17@charusat.edu.in', '9800000017', 'B.Tech Computer Engineering', 'Third Year', 'Male'),
(18, NULL, 'STU2026018', 'Student 18', 'student18@charusat.edu.in', '9800000018', 'B.Tech IT', 'Third Year', 'Female'),
(19, NULL, 'STU2026019', 'Student 19', 'student19@charusat.edu.in', '9800000019', 'BCA', 'Third Year', 'Male'),
(20, NULL, 'STU2026020', 'Student 20', 'student20@charusat.edu.in', '9800000020', 'B.Tech Computer Engineering', 'Second Year', 'Female'),
(21, NULL, 'STU2026021', 'Student 21', 'student21@charusat.edu.in', '9800000021', 'B.Tech IT', 'First Year', 'Male');

INSERT INTO events (id, title, description, event_date, venue, category, poster_path, status, created_by) VALUES
(1, 'CHARUSAT TechFest 2026', 'Annual state-level technical symposium with coding battles, robotics, and project presentations.', '2026-09-15', 'Central Campus Auditorium', 'Competition', 'uploads/posters/techfest.png', 'upcoming', 1),
(2, 'AI and Cloud Computing Seminar', 'Expert session by industry leaders on modern generative AI, cloud scalability, and MLOps workflows.', '2026-08-30', 'Auditorium Hall B', 'Seminar', 'uploads/posters/ai_seminar.png', 'upcoming', 1),
(3, '24-Hour Code Hackathon', 'Collaborative sprint where student developer teams build innovative campus solutions.', '2026-08-07', 'CSPIT Computer Center', 'Competition', 'uploads/posters/hackathon.png', 'upcoming', 1),
(4, 'Web Development Workshop', 'Hands-on practical workshop covering responsive layouts, Bootstrap 5, and PHP backend APIs.', '2026-07-25', 'Lab 304, IT Building', 'Workshop', 'uploads/posters/web_workshop.png', 'upcoming', 1),
(5, 'Spoural Cultural Night', 'Annual university cultural celebration featuring music, classical dance, and theatre drama.', '2026-10-10', 'University Cricket Grounds', 'Cultural', 'uploads/posters/cultural.png', 'upcoming', 1),
(6, 'Inter-College Badminton Tournament', 'Annual inter-department sports championship across singles and doubles divisions.', '2026-09-02', 'Sports Complex', 'Sports', 'uploads/posters/sports.png', 'upcoming', 1),
(7, 'Cyber Security Awareness Session', 'Interactive awareness workshop on secure coding practices, password safety, and network hygiene.', '2026-08-18', 'Seminar Hall 1', 'Academic', 'uploads/posters/cyber.png', 'upcoming', 1),
(8, 'Social Outreach & Tree Plantation', 'NSS student-driven social initiative supporting campus ecology and tree plantation drive.', '2026-08-12', 'Campus Botanical Park', 'Social', 'uploads/posters/social.png', 'upcoming', 1);

INSERT INTO registrations (student_id, event_id, registered_at, status) VALUES
(1, 1, '2026-07-10 10:15:00', 'confirmed'),
(1, 2, '2026-07-11 11:20:00', 'confirmed'),
(1, 3, '2026-07-12 09:45:00', 'confirmed'),
(2, 1, '2026-07-10 14:00:00', 'confirmed'),
(3, 1, '2026-07-10 15:30:00', 'confirmed'),
(4, 3, '2026-07-12 12:10:00', 'confirmed'),
(5, 4, '2026-07-13 16:00:00', 'confirmed');

INSERT INTO audit_logs (user_id, action_name, entity_type, entity_id, details) VALUES
(1, 'SYSTEM_INIT', 'system', 1, 'Database schema and seed data initialized'),
(1, 'USER_LOGIN', 'users', 1, 'Admin user logged into system'),
(1, 'CREATE_EVENT', 'events', 1, 'Created CHARUSAT TechFest 2026 event'),
(1, 'ADD_STUDENT', 'students', 1, 'Enrolled initial student record STU2026001'),
(1, 'REGISTER_EVENT', 'registrations', 1, 'Confirmed registration for STU2026001 in TechFest');
