# Nestly: University Hostel Complaint Management System

**Nestly** is a web-based hostel complaint tracking and resolution platform built with **Laravel 11**, **PHP 8.2+**, and **MySQL**. It connects students directly with hostel porters and campus administrators.

---

## Table of Contents

- [Overview](#overview)
- [System Actors & Workflow](#system-actors--workflow)
- [Design Decisions & Scope](#design-decisions--scope)
- [Complaint Lifecycle](#complaint-lifecycle)
- [Prerequisites](#prerequisites)
- [Installation Guide](#installation-guide)
- [Default Seed Accounts](#default-seed-accounts)
- [Project Directory Structure](#project-directory-structure)
- [Routes & Role-Based Permissions](#routes--role-based-permissions)
- [Troubleshooting & FAQs](#troubleshooting--faqs)

---

## Overview

In traditional student hostels, lodging repairs or safety issues involves paper books, unlogged complaints, and endless delays. Nestly replaces this with an audit-trailed, role-segregated portal:

- **Students** file issues in seconds.
- **Hostel Porters** manage investigations and log incremental updates.
- **Administrators** supervise all campus residences, manage users, and inspect resolution statistics.

---

## System Actors & Workflow

The platform is organized around **three actors**:

```text
 ┌────────────────┐         lodge complaint         ┌───────────────┐
 │    STUDENT     │ ──────────────────────────────> │    PORTER     │
 └────────────────┘                                 └───────────────┘
         │                                                  │
         │ rate & feedback upon resolution                  │ review & update
         ▼                                                  ▼
 ┌──────────────────────────────────────────────────────────────────┐
 │                          ADMINISTRATOR                           │
 │        (System oversight, users, hostels & categories)           │
 └──────────────────────────────────────────────────────────────────┘
```

### 1. Student (`STUDENT`)

- Registers an account (immediate login, no manual approval required).
- Submits complaints by selecting a **hostel from a dropdown** and entering an **optional room number**.
- Follows ticket status with a real-time progress bar and timestamped update feed.
- Rates resolved complaints (1 to 5 stars) and submits feedback.

### 2. Hostel Porter (`PORTER`)

- Assigned to a specific hostel.
- Accesses a dedicated dashboard showing only complaints for their hostel.
- Moves complaints forward: `Pending` → `In progress` → `Resolved`.
- Adds audit-trailed update notes so students know what action is being taken.
- Accounts are created via registration or by an admin; admin approval is required before first sign-in.

### 3. Administrator (`ADMIN`)

- Master oversight across all hostels and complaints.
- **User management:** approve porters, activate/deactivate accounts, change user roles.
- **Hostel management:** register new hostels and toggle active status.
- **Category management:** maintain issue categories (e.g. Facility Issue, Theft, Bullying, Cult-related Issue).

---

## Design Decisions & Scope

| Feature | Design Implementation |
|---|---|
| **Actors** | Exactly 3: Student, Porter, and Admin. Separate maintenance staff and warden tiers are intentionally removed. |
| **Room Allocation** | Eliminated. Students do not need room pre-allocations to lodge complaints. |
| **Hostel Selection** | Dynamic dropdown on complaint submission listing all active hostels. |
| **Room Identification** | Optional string input (e.g. `Room 12`, `Block B`, or blank for common areas). |
| **Priority Levels** | Removed. All complaints enter a single equal-priority queue. |
| **Photo Attachments** | Removed. Submissions stay lightweight and text-only. |
| **Hostel Gender Classification** | Removed. Hostels are tracked by name and active status only. |

---

## Complaint Lifecycle

Complaints follow a forward-only, 3-step state machine:

```text
[ 1. PENDING ] ──────> [ 2. IN_PROGRESS ] ──────> [ 3. RESOLVED ]
  Ticket submitted        Porter reviews &          Porter completes issue;
  by student              takes action              student submits rating & review
```

- Every transition creates an immutable record in `complaint_updates` capturing the author, date, message, and previous/new status.
- Once marked `RESOLVED`, students can submit a 1–5 star rating and a feedback note.

---

## Prerequisites

1. **PHP** `^8.2` or higher (verified up to PHP 8.5)
2. **PHP extensions:** `pdo_mysql`, `mysqli`, `openssl`, `mbstring`, `curl`, `fileinfo`
3. **Database:** MySQL or MariaDB running on `127.0.0.1:3306`
4. **Dependency manager:** Composer 2.x
5. **Web server:** Apache, Nginx, or PHP's built-in server (`php artisan serve`)

---

## Installation Guide

### 1. Configure PHP Extensions

Enable the required extensions in `php.ini`:

```ini
extension_dir = "ext"
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=mbstring
```

Verify `pdo_mysql` is loaded:

```bash
# Windows CMD
php -m | findstr pdo_mysql

# Linux / macOS
php -m | grep pdo_mysql
```

### 2. Prepare the MySQL Database

Start MySQL (via XAMPP, WAMP, Laragon, or a standalone service), then create the database:

```sql
CREATE DATABASE nestly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configure `.env`

In the project root, create or edit `.env`:

```env
APP_NAME=Nestly
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nestly
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

> If your local MySQL root user has a password, set it in `DB_PASSWORD`.

### 4. Install Dependencies

```bash
composer install
```

> On PHP 8.3 with platform restrictions, run `composer install --ignore-platform-req=php`.

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations & Seeders

Builds all tables and seeds reference hostels, categories, and default test accounts:

```bash
php artisan migrate:fresh --seed
```

### 7. Start the Development Server

```bash
php artisan serve
```

The app is now live at **http://127.0.0.1:8000**.

---

## Default Seed Accounts

| Role | Email | Password | Purpose |
|---|---|---|---|
| **Administrator** | `admin@nestly.local` | `Admin@12345` | Global control of hostels, users, categories, and complaints. |
| **Porter** | `porter@nestly.local` | `Porter@12345` | Manages complaints for the assigned hostel (`Alvan Ikoku Hall`). |
| **Student** | `student@nestly.local` | `Student@12345` | Lodges and tracks complaint tickets. |

New Student or Porter accounts can also be created at `/register`.

> ⚠️ Change or remove these credentials before deploying to production.

---

## Project Directory Structure

```text
nestly-php/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php        # Admin dashboard, users, hostels, categories
│   │   │   ├── AuthController.php         # Register, login, logout, role router
│   │   │   ├── HomeController.php         # Public landing page with stats
│   │   │   ├── PorterController.php       # Porter dashboard & complaint resolution
│   │   │   ├── ProfileController.php      # Profile & password management
│   │   │   └── StudentController.php      # Student dashboard, submission, details, ratings
│   │   └── Middleware/
│   │       └── RoleMiddleware.php         # Restricts routes to STUDENT, PORTER, or ADMIN
│   └── Models/
│       ├── Complaint.php                  # Statuses, scopes, relationships
│       ├── ComplaintCategory.php          # Categories (Facility, Theft, etc.)
│       ├── ComplaintUpdate.php            # Audit timeline history
│       ├── Hostel.php                     # Residence halls
│       └── User.php                       # Multi-role user model
├── database/
│   ├── migrations/                        # Schema migrations
│   └── seeders/
│       ├── DatabaseSeeder.php             # Root seeder
│       ├── ReferenceDataSeeder.php        # 19 campus hostels, 5 standard categories
│       └── AdminUserSeeder.php            # Default Admin, Porter, Student accounts
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php              # Authenticated layout (sidebar & navbar)
│       │   └── home.blade.php             # Public guest layout
│       ├── auth/
│       │   ├── login.blade.php            # Login portal
│       │   └── register.blade.php         # 3-step student/porter registration
│       ├── student/
│       │   ├── dashboard.blade.php        # Tickets and status badges
│       │   ├── submit_complaint.blade.php # Hostel dropdown & optional room number
│       │   ├── complaint_detail.blade.php # Progress timeline & porter notes
│       │   └── rate_complaint.blade.php   # 5-star resolution feedback
│       ├── porter/
│       │   ├── dashboard.blade.php        # Filterable hostel complaints feed
│       │   └── complaint.blade.php        # Advance status & post notes
│       ├── admin/
│       │   ├── dashboard.blade.php        # Global triage feed & metrics
│       │   ├── complaint_manage.blade.php # Complaint updates & overrides
│       │   ├── users/                     # User listing, approvals, form
│       │   ├── hostels/                   # Hostel catalog & activation toggle
│       │   └── categories/                # Category catalog & form
│       └── partials/
│           ├── _complaint_table.blade.php # Reusable table with role-based routes
│           ├── _filters.blade.php         # Search, hostel, category & status filters
│           └── _stats.blade.php           # Real-time counter cards
└── routes/
    └── web.php                            # HTTP route definitions
```

---

## Routes & Role-Based Permissions

All authenticated routes use session auth and the `role:ROLE` middleware.

### Public Routes

| Method | Route | Description |
|---|---|---|
| `GET` | `/` | Landing page with live counter statistics |
| `GET` `POST` | `/register` | Multi-step registration (Student or Porter) |
| `GET` `POST` | `/login` | Sign in |
| `POST` | `/logout` | Sign out |

### Shared Authenticated Routes

| Method | Route | Description |
|---|---|---|
| `GET` | `/dashboard` | Redirects users to their role dashboard |
| `GET` | `/profile` | View profile information |
| `POST` | `/profile` | Update contact phone number |
| `POST` | `/profile/password` | Change account password |

### Student Routes (`role:STUDENT`)

| Method | Route | Description |
|---|---|---|
| `GET` | `/student/dashboard` | View personal tickets, filter by status |
| `GET` | `/student/submit-complaint` | Complaint form with hostel select |
| `POST` | `/student/submit-complaint` | Validate and store a new complaint |
| `GET` | `/student/complaint/{id}` | Timeline progress tracker and porter notes |
| `GET` `POST` | `/student/complaint/{id}/rate` | 5-star rating & review on resolved complaints |

### Porter Routes (`role:PORTER`)

| Method | Route | Description |
|---|---|---|
| `GET` | `/porter/dashboard` | Complaints for the porter's assigned hostel |
| `GET` `POST` | `/porter/complaint/{id}` | Inspect, add update note, advance status (`Pending` → `In progress` → `Resolved`) |

### Administrator Routes (`role:ADMIN`)

| Method | Route | Description |
|---|---|---|
| `GET` | `/control/dashboard` | Campus-wide complaint overview |
| `GET` `POST` | `/control/complaint/{id}` | Administrative ticket management |
| `GET` | `/control/users` | User listing with filters |
| `GET` `POST` | `/control/users/add` | Manually provision a user |
| `GET` `POST` | `/control/users/{id}/edit` | Edit user, change role, assign hostel |
| `POST` | `/control/users/{id}/action` | Approve, activate, or deactivate user |
| `GET` | `/control/hostels` | View and manage hostel directory |
| `GET` `POST` | `/control/hostels/add` | Create a hostel |
| `GET` `POST` | `/control/hostels/{id}/edit` | Edit a hostel |
| `POST` | `/control/hostels/{id}/toggle` | Toggle hostel active/inactive |
| `GET` | `/control/categories` | Category catalog |
| `GET` `POST` | `/control/categories/add` | Create a category |
| `GET` `POST` | `/control/categories/{id}/edit` | Edit a category |
| `POST` | `/control/categories/{id}/toggle` | Toggle category active/inactive |

---

## Troubleshooting & FAQs

### 1. `SQLSTATE[HY000] [2002] Connection refused`

- **Cause:** MySQL is not running on `127.0.0.1:3306`.
- **Fix:** Start MySQL from XAMPP / WAMP / your service manager and make sure port `3306` is open.

### 2. `could not find driver (Connection: mysql)`

- **Cause:** The `pdo_mysql` extension is commented out in `php.ini`.
- **Fix:** Change `;extension=pdo_mysql` to `extension=pdo_mysql`, save, then restart your terminal or web server.

### 3. Porter account cannot log in

- **Cause:** Porters need administrator approval after registering.
- **Fix:** Log in as `admin@nestly.local`, open **Users**, find the porter, and click **Approve**.

### 4. How do I reset the database to a clean demo state?

```bash
php artisan migrate:fresh --seed
```

This wipes existing data, recreates all tables, reseeds hostels and categories, and restores the default Admin, Porter, and Student accounts.
