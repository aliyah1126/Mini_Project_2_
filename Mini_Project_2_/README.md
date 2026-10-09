# Mini Project 2 - Student Portfolio & FYP Showcase

## Requirements
- Laragon
- PHP 8+
- MySQL
- Browser

## Setup
1. Copy this folder into `C:\laragon\www\mini_project_2`.
2. Start Apache and MySQL in Laragon.
3. Open phpMyAdmin.
4. Create/import `database.sql`.
5. Open `http://localhost/mini_project_2/register.php`.
6. Register a student.
7. To create an admin, register normally then run:
   `UPDATE users SET role='admin' WHERE email='your-email@example.com';`
8. Login as admin and create categories/assignments.
9. Login as student and submit a project.

## Default upload limit
5MB. Allowed: PDF, DOCX, TXT, ZIP.

## Features
- PHP + MySQL
- password_hash/password_verify
- session + role access
- POST forms
- JavaScript client validation
- PHP server validation
- AJAX live search
- file upload/download
- prepared statements
- SQL JOIN
- reusable header/footer/db
