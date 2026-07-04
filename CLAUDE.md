# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Grievance Redressal Mechanism (GRM) web portal for the ADB-assisted **Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project**, managed by ARIAS Society (Govt. of Assam). Source requirement documents are in `docs/` (GRM Manual PDF, spec DOCX, page-list XLSX).

## Tech stack

- **Laravel 11** (PHP 8.2, XAMPP on Windows)
- **MySQL/MariaDB** — database `grm_swift` (local: root, no password)
- **Blade + Bootstrap 5** (CDN, no Vite build required)
- **barryvdh/laravel-dompdf** — acknowledgment slips, resolution letters, PDF reports
- No mobile OTP (format validation only, by decision)

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

Masters: `districts`, `blocks`, `revenue_circles`, `cpius`, `beels`. Users: `user_types`, `users`, `user_assignments`. Grievances: `grievance_categories` (9 codes from the manual), `grievances`, `grievance_documents`, `grievance_actions` (audit timeline — every state change writes a row), `grievance_feedback`. Committees: `committees`, `committee_members`.

## Run locally

```bash
# 1. create database (XAMPP MariaDB)
D:/xampp/mysql/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS grm_swift"
# 2. install + migrate + seed
composer install
php artisan migrate:fresh --seed
# 3. serve
php artisan serve   # http://127.0.0.1:8000
```

Tests: `php artisan test` — single test class/method: `php artisan test --filter=GrievanceSubmitTest` (or `--filter=test_method_name`).

Seeded Super Admin: `admin@grmswift.local` / `Admin@123` (see ACTIONS.md; sample users for each role are seeded — password `Password@123`).

## Current status (2026-07-04)

The application is **under construction** — see `ACTIONS.md` for the step-by-step build log. Done so far: requirements analyzed (`docs/`), plan approved, Laravel 11 scaffolded into `grm-tmp/` (still to be moved to the project root). Not yet done: `grm_swift` database creation, migrations/models/seeders, portal pages, auth/dashboards, workflow, reports, git init + GitHub push.

## Conventions

- **Every step/action is logged in `ACTIONS.md`** — keep appending as work progresses.
- One git commit per implementation phase.
- All grievance state changes must write a `grievance_actions` row (the public timeline and audit trail depend on it).
