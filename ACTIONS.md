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
36. Added project `README.md`.

## 2026-07-04 — Phase 9: Git push to GitHub

37. Local git repository complete with 3 commits:
    - `Phase 1-2: scaffold Laravel app + database layer`
    - `Phase 3-8: public portal, admin panel, workflow, reports, tests`
    - `Add project README`
38. Added remote `origin` = https://github.com/nathgunajit/grm-swift.git (provided by user).
39. Pushed `master` to GitHub — all commits confirmed on the remote. **V1 complete.**

---

# V2 — Phase 2 UI Enhancement (Tailwind CSS v4)

Implements `docs/Phase 2- GRM Portal Enhancement Requirements – SWIFT Project.docx`.

## 2026-07-04 — V2 Phase 1: Tailwind v4 build + design system

40. Fetched the latest Tailwind CSS v4 + Laravel Vite setup via the context7 MCP.
41. Installed `tailwindcss@4`, `@tailwindcss/vite`, `chart.js`, `alpinejs`; removed the Tailwind v3 deps and `postcss.config.js` / `tailwind.config.js`.
42. Configured `vite.config.js` (`@tailwindcss/vite` plugin), `resources/css/app.css` (`@import "tailwindcss"`, `@theme` brand palette + Poppins/Inter fonts, `@layer components` for `.btn`/`.card`/`.input`/`.table-grm`/`.sidebar-link`), `resources/js/app.js` (Alpine + Chart.js). `npm run build` succeeds.
43. Copied the three logos to `public/images/` (`swift-logo.png`, `arias-logo.png`, `assam-govt-logo.png`); added `<x-brand-bar>`, `<x-icon>` (inline-SVG set), and a Tailwind `<x-status-badge>`.

## 2026-07-04 — V2 Phase 2: Public portal on Tailwind

44. Rebuilt `layouts/public` (gradient header, three-logo brand strip, Alpine responsive nav, redesigned footer).
45. Redesigned Home (hero, stat cards, 3-tier GRC, sensitive-case notice) and converted submit, submitted, track, process, faq, resources, privacy, contact, and login to Tailwind.

## 2026-07-04 — V2 Phase 3: Registration change + demo OTP

46. Made Beel **optional**: `StoreGrievanceRequest` `beel_id` nullable; controllers handle null beel; forms drop the required marker.
47. Added `OtpController` (`/otp/send`, `/otp/verify`) — demo 6-digit OTP via cache + session verified flag (no SMS). Submit form gains an Alpine Send-OTP → Verify flow; `GrievanceController@store` blocks non-anonymous submits until the mobile is verified (anonymous skips OTP).

## 2026-07-04 — V2 Phase 4: Admin panel on Tailwind + dashboard charts

48. Rebuilt `layouts/admin` (Alpine collapsible sidebar, topbar dropdown). Converted all admin views (grievances index/show/create, reports, masters, users, committees).
49. `DashboardController` builds chart data; dashboard renders Chart.js doughnut (status), bar (level), and 6-month trend line, plus responsive stat cards.

## 2026-07-04 — V2 Phase 5: PDF branding + watermark

50. Added the three-logo header to the acknowledgment, resolution, and report PDFs, and a diagonal low-opacity **watermark** ("Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project.") on the acknowledgment and resolution PDFs (logos embedded via `public_path()`).

## 2026-07-04 — V2 Phase 6: Beel geo + Zone Management

51. Migration adds `latitude`/`longitude` to `beels` and a `zones` table + `zone_id` on `cpius`. `Zone` model; `Cpiu`/`Beel` updated. Beel admin form gains lat/long with a "view on map" Google Maps link (shown on track + grievance detail too).
52. `ZoneController` CRUD + `admin/masters/zones` view + sidebar link + routes (Super Admin / PMU Admin). `MasterSeeder` seeds 3 zones (Lower/Central/Upper Assam), assigns CPIUs, and adds beel coordinates.

## 2026-07-04 — V2 Phase 7: Tests, verification, docs, git

53. Updated feature tests for the OTP gate + optional Beel; added `OtpAndZoneTest`. `php artisan test` — **24 passed (68 assertions)**.
54. `php artisan migrate:fresh --seed` clean. Drove the live app: all public + admin pages 200 at desktop/mobile, OTP send/verify/submit gate works, Beel-optional submit works, PDFs show logos + watermark, Zone CRUD works, dashboard charts render. Confirmed no Bootstrap / `bi-` classes remain in views.
55. Updated `CLAUDE.md` (V2 stack) and this `ACTIONS.md`; committed V2 and pushed to GitHub. **V2 complete.**

---

# V3 — Admin Portal Enhancements & Module Visibility

Implements `docs/enhancement or user visbility of the modules.docx`.

## 2026-07-06 — V3

56. Read the enhancement document; confirmed decisions with user (CPIU covers many districts → `districts.cpiu_id`; resolve = DFDO/CPIU/PIU/Super Admin; manual entry = Beel Animator/SSGC/PIU Officer/Super Admin).
57. Migration `refactor_cpiu_district_remove_zones`: added `districts.cpiu_id`, dropped `cpius.zone_id`, dropped `zones` table. Deleted `Zone` model; updated `Cpiu` (districts hasMany) and `District` (cpiu belongsTo); reseeded districts→CPIU in `MasterSeeder`.
58. Removed the Zone module surface: deleted `ZoneController` + `admin/masters/zones.blade.php`, removed the zones route and sidebar link.
59. Role-based grievance actions: added `User::ROLE_GRIEVANCE_ACTIONS` + `canGrievance()`; guarded every action in `GrievanceAdminController`; restricted manual-entry routes/sidebar to the manual-entry roles; rewrote the Take-Action panel in `show.blade.php` to render only permitted tabs (PMU Admin = monitoring note).
60. Escalation: `GrievanceService::escalate()` takes a target level + team note; the escalate form now selects the target Level and Team (committee) and shows the District, with a per-district GRC team list (`escalationTeams` from the controller).
61. Masters — User Types: description → textarea, "Users" → "Users (Nos)"; Beel: Block exposed as a visible select (all fields visible); CPIU: district-assignment checkboxes where districts owned by another CPIU are disabled (`CpiuController::syncDistricts`).
62. Committees: new `EmployeeController@search` (+ `admin/employees/search` route) and an Alpine autocomplete on the member-name field that fills name + designation.
63. Grievance list: colour-coded Due pill (`Grievance::dueBadge()` — green >3d, amber ≤3d, red overdue, slate done) with a legend. Reports: on-time-vs-delayed breakdown + delayed-grievances table in `ReportController`, the reports page, CSV and PDF.
64. Tests: replaced zone tests with a CPIU-district assignment test + `zone routes are gone`; added `RoleActionTest` (animator can't resolve/can escalate, DFDO resolves, PMU view-only, manual-entry gating); fixed the V1 resolve test to use DFDO. `php artisan test` — **31 passed (80 assertions)**.
65. `migrate:fresh --seed` clean; `npm run build`; drove the live app per role: animator sees Comment/Escalate but no Resolve (resolve endpoint 403), PMU Admin is view-only, escalate shows Level+Team+District, `/admin/zones` 404s, employee autocomplete returns name+designation, CPIU disables taken districts, Beel shows Block, reports show on-time-vs-delayed (CSV/PDF included), due column colour-coded.
66. Updated `CLAUDE.md` and this `ACTIONS.md`; committed V3 and pushed to GitHub. **V3 complete.**

---

# V4 — Notifications (Email + Demo SMS + Admin Bell)

Complainant gets email + SMS with their Tracking/Ack ID on submit and on each action; officers get an in-app bell alert. Demo SMS gateway (no real SMS/email dispatched).

## 2026-07-07 — V4

67. Infra: `notifications` table (Laravel database channel) + `sms_logs` table/model migrations. `SmsService` demo gateway (`config/services.php` → `sms.driver`, default `log`) that persists to `sms_logs` + app log, with an MSG91 live stub. Custom `App\Notifications\Channels\SmsChannel` routes a notification's `toSms()` through `SmsService`.
68. Notification classes: `GrievanceRegistered` + `GrievanceUpdated` (complainant — mail + SMS, carrying Tracking ID / Ack No / status / track link), `GrievanceAdminAlert` (officers — database channel, with title/body/icon/url). `via()` picks channels from the AnonymousNotifiable's available mail/sms routes.
69. `GrievanceNotifier` service: `registered()` and `actionTaken()` fan each event out to the complainant (skipped when anonymous or no contact) and to jurisdiction-scoped officers via `officersFor()` (mirrors `Grievance::scopeVisibleTo`: full-visibility roles always + district/beel/CPIU matches, guarded against null jurisdiction).
70. Wired the notifier into `GrievanceController` (online submit, feedback→close, reopen) and `GrievanceAdminController` (manual store, review, comment, escalate, resolve). Complainant is emailed/SMSed on register, escalate, resolve; officers get a bell alert on every event.
71. Admin bell: `Admin\NotificationController` (index/read/readAll) + routes; bell dropdown in `layouts/admin.blade.php` (unread badge, recent 8, mark-all-read) + sidebar link with unread count; added a `bell` icon. `admin/notifications` page with an Inbox tab and a demo-SMS-log tab (Super Admin/PMU Admin).
72. Tests: `NotificationTest` — online submit creates an SMS log with the tracking ID + an officer database notification; resolve SMSes the complainant + alerts the officer; bell page loads. `php artisan test` — **35 passed (91 assertions)**. `migrate` + `npm run build` clean.
73. Updated `CLAUDE.md` (notifications in stack/modules/DB/status → V4) and this `ACTIONS.md`. **V4 complete.**
