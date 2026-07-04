# SWIFT GRM Portal

A **Grievance Redressal Mechanism (GRM)** web application for the ADB-assisted **Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project**, managed by ARIAS Society, Government of Assam.

Built with **Laravel 11**, **MySQL/MariaDB**, and **Tailwind CSS v4** (Alpine.js + Chart.js), with branded PDF generation via dompdf.

## Features

**Public portal (no login)**
- Register a grievance online (or anonymously/confidentially) with document uploads — mobile is format-validated (no OTP)
- Instant **Tracking ID** (`GRM-YYYY-NNNNNN`) and downloadable **Acknowledgment PDF**
- Track status by tracking/acknowledgment ID or mobile, with a full status timeline
- Download the resolution letter, submit a satisfaction feedback form, or reopen if not satisfied
- GRM process, FAQ, resources, privacy policy, contact pages

**Admin panel (official login by email or mobile)**
- Per-role dashboards scoped by jurisdiction (beel / district / CPIU / level)
- Manual grievance entry for offline complaints
- Review, comment, **escalate** through the 3-tier GRC, and **resolve** with a generated resolution PDF
- Sensitive cases (GBV/SEA-SH, misconduct) flagged for confidential priority handling
- Masters CRUD (districts, blocks, revenue circles, CPIUs, beels), user management with assignment history, GRC committees (with a ≥30%-women indicator)
- Reports: counts by status/category/level/district, SLA resolution rate, average time, feedback summary — exportable to CSV and PDF

## Three-tier Grievance Redressal Committee

| Level | Committee | SLA |
|-------|-----------|-----|
| I | Field / Beel GRC | 7 days |
| II | Cluster / CPIU GRC | 15 days |
| III | PIU GRC | 15 days |

## Roles

Public User · Beel Animator · BDC Facilitator · SSGC · DFDO · CPIU Officer · PIU Officer · PMU Admin · Super Admin

## Setup

Requires PHP 8.2+, Composer, and MySQL/MariaDB (e.g. XAMPP).

```bash
composer install
npm install && npm run build   # compiles Tailwind v4 + Alpine + Chart.js
cp .env.example .env           # then set DB_DATABASE=grm_swift, DB_USERNAME=root, DB_PASSWORD=
php artisan key:generate
mysql -u root -e "CREATE DATABASE grm_swift CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve              # http://127.0.0.1:8000
```

> The compiled front-end assets in `public/build` are committed, so the app renders on XAMPP without a build step. Re-run `npm run build` after changing any Blade/CSS/JS.

### Seeded logins

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@grmswift.local | Admin@123 |
| SSGC (and other officials) | ssgc@grmswift.local | Password@123 |

Other seeded officials: `animator@`, `bdc@`, `dfdo@`, `cpiu@`, `piu@`, `pmu@grmswift.local` (all `Password@123`).

## Tests

```bash
mysql -u root -e "CREATE DATABASE grm_swift_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan test
```

## Documentation

- `CLAUDE.md` — project/architecture guide
- `ACTIONS.md` — step-by-step build log
- `docs/` — source requirement documents (GRM Manual, spec, page list)
