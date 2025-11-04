# Online Quiz System (MVP)

This is a simple Online Quiz System built with PHP, MySQL and plain HTML/CSS meant for local development with XAMPP.

What I added so far:
- DB migration file: `migrations.sql` (create `quiz_system` DB and initial tables)
- `classes/User.php` (basic user registration/login helper)
- Basic pages for register/login/logout
- Starter teacher dashboard and student join page

Quick setup
1. Start XAMPP and start Apache + MySQL.
2. Open phpMyAdmin or run mysql CLI and import `migrations.sql` to create the database and tables.
3. Update `database.php` to use the `quiz_system` database (change `$dbname` to `quiz_system`).
4. Place this project in your `htdocs` (already under `c:\xampp\htdocs\online_quiz`).
5. Open the browser: http://localhost/online_quiz/pages/register.php to create accounts and http://localhost/online_quiz/pages/login.php to log in.

Notes
- The application uses PHP sessions for auth and a small CSRF token on forms.
- Passwords are hashed with `password_hash()`.
- This is a starting point. Next steps: quiz CRUD, live session engine (websockets or polling), leaderboard, PDF import, admin UI.

If you want, I can now implement:
- Quiz CRUD (teacher) and question editor
- Live session creation + join flow and scoring
- Admin panel and reports
- PDF upload + question generation

Tell me which feature you want next and I'll implement it and run quick checks.

---

Current status (snapshot)
- Date: 2025-10-11
- Core features scaffolding completed: auth, role-aware dashboards (admin/teacher/student), live session plumbing (polling endpoints), leaderboard, and basic admin/report pages.
- UI: lightweight responsive auth pages and dashboard cards; play UI uses AJAX polling for current question and leaderboard.

Important files / recent additions
- `DEV_NOTES.md` — on-disk snapshot and running instructions (recommended to commit).
- `assets/css/auth.css`, `assets/js/auth.js` — redesigned login/register and show-password toggle.
- `pages/student/join.php` — fixed join flow, accepts GET from dashboard quick-join and POST from full form.
- `pages/student/history.php` — student quiz history (listing of past scores).
- `tools/create_admin_johan.php` — one-off helper to create an admin (delete after use).

Running the app locally (detailed)
1. Start XAMPP: open XAMPP Control Panel and start Apache and MySQL.
2. Create/import the database:
	- Open phpMyAdmin (http://localhost/phpmyadmin) and create the database `quiz_system`.
	- Import `migrations.sql` (and `migrations_update.sql` if present).
3. Verify `database.php` contains the correct credentials (default XAMPP: `root` / empty password) and `$dbname = 'quiz_system'`.
4. Visit the app pages in your browser:
	- Register: http://localhost/online_quiz/pages/register.php
	- Login:    http://localhost/online_quiz/pages/login.php
	- Dashboards: http://localhost/online_quiz/pages/dashboard.php

Create an admin account (two safe options)

Option A — one-off helper (recommended for local dev):
- Run the helper script that creates the admin user `johanadmin` (if not present):
```cmd
C:\xampp\php\php.exe c:\xampp\htdocs\online_quiz\tools\create_admin_johan.php
```
- After successful creation, delete the helper to avoid leaving a privileged shortcut in the repo:
```cmd
del c:\xampp\htdocs\online_quiz\tools\create_admin_johan.php
```

Option B — via phpMyAdmin (manual SQL):
- Generate a password hash using PHP on the CLI:
```cmd
C:\xampp\php\php.exe -r "echo password_hash('YourStrongPassword', PASSWORD_DEFAULT).PHP_EOL;"
```
- Copy the hash and run in phpMyAdmin SQL:
```sql
INSERT INTO users (name, email, password, role, created_at)
VALUES ('johanadmin', 'johanadmin@gmail.com', '<PASTE_HASH_HERE>', 'admin', NOW());
```

Security notes
- Remove helper scripts and do not expose the admin creation endpoint in production.
- This project is intended for local development and learning. Before deploying publicly, add proper input validation, rate-limiting, HTTPS, and session hardening.

Next recommended tasks
- Finish styling and layout for the student history page and link it from the student dashboard.
- Review `assets/css/quiz.css` and adjust contrast/spacing for consistent UI.
- Add server-side guard to prevent duplicate answer submissions and implement a small test harness for live session flows.
- (Optional) Replace polling with a websocket solution for real-time interaction.

If you'd like, I can commit `DEV_NOTES.md` or remove the admin helper now. Tell me what to do next.