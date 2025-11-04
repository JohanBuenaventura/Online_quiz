# DEV_NOTES — Online Quiz (local snapshot)

Date: 2025-10-11
Summary: A lightweight Online Quiz system (teacher/student/admin) built in PHP + MySQL for local development (XAMPP). This file captures the current state, how to run locally, recent changes, and next steps.

---

## Current state (short)
- Language: PHP (PDO), DB: MySQL (quiz_system)
- Auth: register/login (roles: student, teacher, admin)
- Teacher: create/edit quizzes and questions; start live sessions (polling-based)
- Student: join session by code, play quiz (AJAX polling), leaderboard
- Admin: manage users/quizzes, export reports

## Files changed recently
- `pages/login.php`, `pages/register.php` — redesigned auth UI; CSRF preserved
- `assets/css/auth.css`, `assets/js/auth.js` — auth styles and show-password toggle
- `pages/student/dashboard.php` — inline join form added
- `pages/student/join.php` — fixed join flow (accepts GET/POST, redirects to session play)
- `pages/student/history.php` — (added) student history page
- `tools/create_admin_johan.php` — one-off helper to create admin (delete after use)
- Many other files created during the project (models, session APIs, etc.). See repo root for full list.

## How to run locally (quick)
1. Start XAMPP (Apache + MySQL) using XAMPP Control Panel.
2. Import DB schema if not yet done:
   - Open phpMyAdmin -> create database `quiz_system` (or change `database.php`).
   - Import `migrations.sql` (and `migrations_update.sql` if present).
3. Confirm DB credentials in `database.php` (defaults to `root` with empty password on XAMPP).
4. Visit the app in browser:
   - Login: http://localhost/online_quiz/pages/login.php
   - Register: http://localhost/online_quiz/pages/register.php
   - Teacher dashboard: http://localhost/online_quiz/pages/teacher/dashboard.php
   - Student dashboard: http://localhost/online_quiz/pages/student/dashboard.php

## Create admin user (if needed)
Option A (recommended): run the one-off helper (CLI):
```cmd
C:\xampp\php\php.exe c:\xampp\htdocs\online_quiz\tools\create_admin_johan.php
```
Option B (phpMyAdmin SQL): run this SQL (replace password hash from PHP):
```sql
INSERT INTO users (name, email, password, role, created_at)
VALUES ('johanadmin', 'johanadmin@gmail.com', '<BCRYPT_HASH>', 'admin', NOW());
```
After creating admin, delete `tools/create_admin_johan.php` to avoid leaving shortcuts in the repo.

## Notes & security
- CSRF tokens are used on forms. Passwords are hashed with `password_hash()`.
- The app uses polling for live sessions (AJAX). For production consider websockets.
- Remove helper scripts and don't expose debug info or DB credentials publicly.

## Next steps / TODOs (local priorities)
- Finish student history UI and link it from student dashboard (partially done).
- Quick CSS polish: review `assets/css/quiz.css` and `assets/css/dashboard.css` for visual consistency.
- Add server-side prevention for duplicate answer submissions.
- (Optional) Implement PDF import + auto-question generation.
- Replace polling with websocket-based real-time updates (optional/advanced).

## How to preserve this chat & resume later
- Copilot Chat usually persists conversations in the extension; however, for safety this file contains the important bits.
- Commit this file to git so it's tracked:
```cmd
git add DEV_NOTES.md
git commit -m "Add DEV_NOTES snapshot (2025-10-11)"
```

## Quick troubleshooting
- If a page shows a PHP error, check Apache error log (XAMPP) and PHP error settings. Useful paths:
  - Apache logs: C:\xampp\apache\logs\error.log
  - PHP errors: see `php.ini` settings or display errors in dev only.

---

If you want, I can also:
- Create `CHAT_SUMMARY.md` with a full copy of selected chat messages.
- Delete the admin helper script now (I can remove `tools/create_admin_johan.php`).
- Commit the DEV_NOTES.md for you (I cannot run git on your machine, but I can provide the commands).

Tell me which of the above you'd like next.