# ACTIONS LOG — GRM Portal (SWIFT Project)

Every step and action taken to build this project is recorded here, newest at the bottom.

---

## 2026-07-04 — Phase 0: Requirements & Planning

1. **Read requirement documents** in the project folder:
   - `FINAL GRM Manual.pdf` — official GRM manual (ARIAS Society / SWIFT Project): 3-tier GRC system (Level I Field 7 days, Level II CPIU 15 days, Level III PIU 15 days), grievance handling protocol, annexure formats I–VII.
   - `GRM.docx` — application spec: user types, grievance form fields, module list, master tables, login, dashboards, reports.
   - `GRM Pages.xlsx` — page list for web portal and admin panel.
2. **Verified local environment**: PHP 8.2.12, Composer 2.7.4, Node 20, MariaDB 10.4 (XAMPP), git 2.43 — all compatible with Laravel 11.
3. **Decisions confirmed with user**: push to GitHub after completion; skip mobile OTP (format validation only); frontend = Blade + Bootstrap 5.
4. **Plan written and approved** (see `CLAUDE.md` for the summary).

## 2026-07-04 — Phase 1: Project Scaffold

5. Moved the three requirement documents into `docs/`.
6. Created Laravel 11 project via `composer create-project laravel/laravel:^11.0` (scaffolded in temp dir, moved to project root).
7. Created `ACTIONS.md` (this file) and `CLAUDE.md` (plan summary / project guide).
8. Configured `.env`: app name "SWIFT GRM Portal", timezone Asia/Kolkata, MySQL connection to `grm_swift`.
9. Created MySQL database `grm_swift` (utf8mb4).
10. Installed `barryvdh/laravel-dompdf` for PDF generation.
11. Ran `php artisan storage:link`.

## 2026-07-04 — Phase 2: Database (migrations, models, seeders)

12. Extended the users table migration with GRM fields (empid, mobile, designation, office_address, user_type_id, cpiu/district/beel FKs, is_active).
13. Added migrations: `create_master_tables` (districts, cpius, blocks, revenue_circles, beels, user_types, user_assignments), `create_grievance_tables` (grievance_categories, grievances, grievance_documents, grievance_actions, grievance_feedback), `create_committee_tables` (committees, committee_members).
14. Created Eloquent models for all 15 tables with relationships; `User` has `role()`/`hasRole()` helpers via user_type slug; `Grievance` holds SLA constants (7/15/15) and `isOverdue()`/`levelLabel()` helpers.
15. Added `App\Services\GrievanceService` — tracking-ID generation (`GRM-YYYY-NNNNNN`), acknowledgment numbers, SLA due-dates, action logging, escalate() and resolve(). Reused by seeder, public submit, and admin workflow.
16. Wrote seeders: `MasterSeeder` (9 roles, 9 grievance categories, 5 districts, 3 CPIUs, blocks/circles, 5 beels, 3 GRC committees with members), `UserSeeder` (Super Admin + 7 role users), `GrievanceSeeder` (6 demo grievances across statuses/levels with timeline + a feedback record).
17. Ran `php artisan migrate:fresh --seed` — clean. Verified counts: 9 user_types, 8 users, 9 categories, 5 beels, 6 grievances, 19 actions, 3 committees.
18. `git init` and first commit.

## 2026-07-04 — Phase 3: Public portal

19. Built `layouts/public` (Bootstrap 5 via CDN, GRM green theme, nav + footer), `status-badge` Blade component.
20. `HomeController` + views: home (hero, live status counters, 3-tier overview), GRM process, FAQ (accordion), resources (with doc download route), privacy policy, contact.
21. `GrievanceController` + `StoreGrievanceRequest`: submit form (all GRM.docx fields, anonymous toggle, mobile format validation — no OTP, file uploads), tracking-ID generation, acknowledgment PDF (Annexure III), confirmation page.
22. Track page: search by tracking/acknowledgment ID or mobile; status timeline from `grievance_actions`; resolution PDF; post-resolution feedback form (Annexure V) that closes the grievance; reopen (escalates to next level).

## 2026-07-04 — Phase 4: Auth, role middleware, dashboards

23. `LoginController` (login with email OR mobile + password, active-account check), login view with demo credentials, logout.
24. `EnsureUserHasRole` middleware registered as `role` alias in `bootstrap/app.php`.
25. `layouts/admin` with role-aware sidebar; `DashboardController` with per-role KPI cards + recent grievances.
26. Added `Grievance::scopeVisibleTo($user)` — jurisdiction scoping (beel / district / CPIU / full) enforced on all queues and detail views.

## 2026-07-04 — Phase 5: Grievance workflow

27. `GrievanceAdminController`: queue with filters, detail view, manual entry, review/comment/escalate/resolve — each writing a `grievance_actions` row; sensitive cases flagged for ICC referral; resolution generates the resolution PDF; SLA-overdue highlighting.

## 2026-07-04 — Phase 6: Admin masters, users, committees

28. CRUD controllers + views for districts, blocks, revenue circles, CPIUs, beels (HTML5 `form`-attribute inline editing), user types.
29. `UserController`: official registration (EMPID etc.), edit, and assignment history with assign/relieve dates.
30. `CommitteeController`: GRC committees per level with members and a ≥30%-women indicator.

## 2026-07-04 — Phase 7: Reports

31. `ReportController`: counts by status/category/level/district, SLA resolution rate, average resolution time, escalations, feedback satisfaction; CSV and PDF (`pdf/report`) export.

## 2026-07-04 — Phase 8: Tests & end-to-end verification

32. Created `grm_swift_test` DB; pointed phpunit at it.
33. Wrote feature tests: `PublicPortalTest`, `AuthTest`, `GrievanceWorkflowTest`.
34. `php artisan test` — **18 passed (53 assertions)**.
35. Drove the live app on `php artisan serve`: submitted a grievance (GRM-2026-000007) → acknowledgment PDF → logged in as SSGC → reviewed → escalated L1→L2 → resolved → resolution PDF; verified jurisdiction 403s, role 403s, all admin pages 200, reports CSV/PDF export.
