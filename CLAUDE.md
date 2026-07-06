# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Grievance Redressal Mechanism (GRM) web portal for the ADB-assisted **Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project**, managed by ARIAS Society (Govt. of Assam). Source requirement documents are in `docs/` (GRM Manual PDF, spec DOCX, page-list XLSX).

## Tech stack

- **Laravel 11** (PHP 8.2, XAMPP on Windows)
- **MySQL/MariaDB** — database `grm_swift` (local: root, no password)
- **Blade + Tailwind CSS v4** (V2), built via Vite (`@tailwindcss/vite`). Design tokens in `resources/css/app.css` (`@theme` brand palette, Poppins/Inter fonts, `@layer components` for `.btn`, `.card`, `.input`, etc.). **Bootstrap was removed in V2.**
- **Alpine.js** (nav/sidebar/tabs/OTP UI) and **Chart.js** (dashboard charts) — bundled through Vite (`resources/js/app.js`).
- **barryvdh/laravel-dompdf** — acknowledgment slips, resolution letters, PDF reports (branded with the three logos + a diagonal SWIFT watermark).
- **Mobile OTP verification (demo mode)** — `OtpController` generates a 6-digit code (shown on screen / logged, no SMS); a non-anonymous grievance must verify its mobile before submitting. Swap the send path for a real SMS gateway later.
- **Icons:** inline SVG via `<x-icon name="…">` (`resources/views/components/icon.blade.php`). Logos via `<x-brand-bar>`; PNGs live in `public/images/`.

> **Build note:** run `npm install && npm run build` after cloning (or `npm run dev` while developing). Blade uses `@vite([...])`; the compiled manifest lives in `public/build`.

## How it works (domain)

Citizens submit grievances online (or officials enter offline complaints manually). Each grievance gets a **tracking ID** (`GRM-YYYY-NNNNNN`) and an **acknowledgment PDF**. Grievances pass through a **3-tier Grievance Redressal Committee (GRC)**:

| Level | Who | SLA |
|---|---|---|
| I — Field/Beel | DFDO-constituted field GRC | 7 days |
| II — Cluster/CPIU | Zonal CPIU GRC | 15 days |
| III — PIU | Deputy Project Director GRC | 15 days |

Status flow: `registered → under_review → escalated → resolved → closed`, with `reopened` if the complainant is not satisfied. Sensitive cases (GBV/SEA-SH, corruption) are flagged for confidential priority handling. After resolution the complainant submits a feedback form (Annexure V).

## Roles

Public User (no login), Beel Animator, BDC Facilitator, SSGC, DFDO, CPIU Officer, PIU Officer, PMU Admin, Super Admin. Officials log in with email/mobile + password; a `role` middleware and jurisdiction scoping (beel/district/CPIU/level) control what each sees.

## Modules

- **Public portal:** Home, Submit Grievance, Track Complaint (timeline, PDFs, feedback, reopen), GRM Process, Help & FAQ, Resources, Privacy Policy, Contact.
- **Admin panel:** per-role dashboards, manual grievance entry, review/comment/escalate/resolve with resolution PDF, SLA-overdue flags, masters CRUD (districts, blocks, revenue circles, CPIUs, beels), user types + official registration + assignments, GRC committees with members, reports (counts, SLA resolution rate, average time, escalations, feedback summary) with CSV/PDF export.

## Database (main tables)

Masters: `districts` (V3: `cpiu_id` — each district belongs to one CPIU), `blocks`, `revenue_circles`, `cpius`, `beels` (`latitude`/`longitude`). **Zones were removed in V3.** Users: `user_types`, `users`, `user_assignments`. Grievances: `grievance_categories` (9 codes from the manual), `grievances`, `grievance_documents`, `grievance_actions` (audit timeline — every state change writes a row), `grievance_feedback`. Committees: `committees`, `committee_members`.

**Role → grievance actions (V3)** — enforced in `GrievanceAdminController` and rendered in `admin/grievances/show.blade.php` via `User::canGrievance($action)` / `User::ROLE_GRIEVANCE_ACTIONS`:
- Beel Animator: manual_entry, review, comment, escalate · BDC Facilitator: review, comment, escalate · SSGC: manual_entry, review, comment, escalate · DFDO / CPIU Officer: review, comment, escalate, resolve · PIU Officer: manual_entry, review, comment, resolve · PMU Admin: view/monitor only · Super Admin: all.
- On escalate, the officer picks the target Level and Team (committee for that level & district). CPIUs own districts (assign in the CPIU master; taken districts are disabled). Committee member entry autocompletes employees via `admin/employees/search` and fills designation. Reports include an on-time-vs-delayed resolution breakdown.

## Run locally

```bash
# 1. create database (XAMPP MariaDB)
D:/xampp/mysql/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS grm_swift"
# 2. install PHP + JS deps
composer install
npm install && npm run build        # compiles Tailwind v4 + Alpine + Chart.js into public/build
# 3. migrate + seed
php artisan migrate:fresh --seed
php artisan storage:link
# 4. serve
php artisan serve   # http://127.0.0.1:8000
```

Tests: `php artisan test` — single test class/method: `php artisan test --filter=GrievanceSubmitTest` (or `--filter=test_method_name`).

Seeded Super Admin: `admin@grmswift.local` / `Admin@123` (see ACTIONS.md; sample users for each role are seeded — password `Password@123`).

## Current status (2026-07-04)

**V1, V2 and V3 are complete** and pushed to GitHub (`github.com/nathgunajit/grm-swift`). V1 delivered the full GRM portal (Blade + Bootstrap). V2 redesigned the UI on **Tailwind CSS v4**, added dashboard charts, three-logo PDF branding + watermark, demo mobile-OTP, optional Beel on registration, and Beel lat/long. V3 (module-visibility enhancement) added **role-based grievance actions** (per-role Take-Action + escalate with Level/Team/District), **removed Zone management** (CPIUs now own districts via `districts.cpiu_id`), made all Beel fields visible, added an **employee autocomplete** to committee member entry, colour-coded the grievance-list Due column, added an **on-time-vs-delayed reports** section, and changed User Types (description textarea, "Users (Nos)"). See `ACTIONS.md` for the full step-by-step log.

## Conventions

- **Every step/action is logged in `ACTIONS.md`** — keep appending as work progresses.
- One git commit per implementation phase.
- All grievance state changes must write a `grievance_actions` row (the public timeline and audit trail depend on it).
