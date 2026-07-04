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
