# TODOLIST — Online Quiz

Generated: 2025-10-11

This file lists the remaining work you may want to do later. Each item contains a short description and the next action to take.

---

## 1) Quick CSS check & polish
- Status: not started
- Description: Review `assets/css/quiz.css` and `assets/css/dashboard.css` to ensure consistent spacing, colors, responsiveness, and accessible contrast. Adjust styles for the join form and the history table.
- Next action: Open the UI in a browser, take screenshots of pages (login, dashboard, play, student history) and list visual issues. Then update CSS rules (small increments).

## 2) Duplicate answer submission guard
- Status: not started
- Description: Prevent students from submitting the same answer multiple times for a question (accidental double-click or retries). Implement server-side guard and optional client-side disable-on-submit.
- Next action: Update `pages/session/submit_answer_ajax.php` and `pages/session/submit_answer.php` to check `student_answers` before insert and return an error if already answered.

## 3) True/False question correctness & scoring
- Status: not started
- Description: Ensure True/False questions store which choice is correct (currently MCQ stored). Update question/choice models and scoring logic to evaluate TF.
- Next action: Add a `is_correct` flag to `choices` (if not present), update the question editor UI to mark correct choice(s), and ensure submission logic reads that flag to award points.

## 4) Pagination & history improvements
- Status: not started
- Description: Add pagination or limit rows on `pages/student/history.php` and admin reports to avoid long lists.
- Next action: Implement `LIMIT` + `OFFSET` with page links, or add simple server-side pagination helper.

## 5) Prevent replay/cheating & rate-limiting
- Status: not started
- Description: Add server-side rate-limiting per student/session and a small anti-cheat measure (e.g., token per question or time-window checks).
- Next action: Add a simple per-student rate-limit counter (in DB or temporary cache) and enforce min time between answer submissions.

## 6) Add "Review Attempt" page
- Status: not started
- Description: Allow students to view their submitted answers for a session and which were correct.
- Next action: Create `pages/student/review.php?session_id=...` that reads `student_answers` and shows questions + chosen answers with correctness.

## 7) Add tests / smoke tests
- Status: not started
- Description: Add small automated checks for core flows (register/login, create quiz, start session, student join+submit). Prefer simple PHP scripts or PHPUnit tests.
- Next action: Create a `tests/` folder and add a couple of PHP scripts that use cURL to hit endpoints or use PHP's CLI to test models.

## 8) Replace polling with WebSockets (optional)
- Status: not started
- Description: Migrate live session real-time updates from polling to websockets for instant updates and lower latency.
- Next action: Decide on approach (Ratchet in PHP, or run a small Node.js socket server). Implement a minimal socket server and change front-end to use websockets.

## 9) PDF import + question generation (optional)
- Status: not started
- Description: Allow teacher to upload a PDF and (optionally) auto-generate questions via simple heuristics or external AI.
- Next action: Add upload UI, parse PDF text server-side (e.g., with `pdftotext`), then add a simple parser to create question candidates for review.

## 10) Clean-up
- Status: not started
- Description: Remove one-off helpers (e.g., `tools/create_admin_johan.php`) after use, commit `DEV_NOTES.md`, and add CONTRIBUTING.md if needed.
- Next action: Run `git status` locally, commit files, and delete temporary scripts.

---

If you'd like, I can start working on any item above — tell me which one to prioritize and I'll create a more detailed plan and begin implementing it. You can also edit this file manually to re-order priorities or add estimates. 

*** End of list ***