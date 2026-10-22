# Nestly — University Hostel Complaint Management System

**Nestly** is a streamlined, web-based hostel complaint tracking and resolution platform built with **Laravel 11**, **PHP 8.2+**, and **MySQL**. It simplifies residential campus life by connecting students directly with hostel porters and campus administrators.

---

## Table of Contents

- [Overview](#overview)
- [System Actors & Workflow](#system-actors--workflow)
- [Design Decisions & Scope](#design-decisions--scope)
- [Complaint Lifecycle](#complaint-lifecycle)
- [Prerequisites](#prerequisites)
- [Step-by-Step Installation Guide](#step-by-step-installation-guide)
- [Default Seed Accounts](#default-seed-accounts)
- [Project Directory Structure](#project-directory-structure)
- [Routes & Role-Based Permissions](#routes--role-based-permissions)
- [Troubleshooting & FAQs](#troubleshooting--faqs)

---

## Overview

In traditional student hostels, lodging repairs or safety issues involves paper books, unlogged complaints, and endless delays. Nestly solves this with an audit-trailed, role-segregated portal where:
- Students file issues in seconds.
- Hostel Porters manage investigations and log incremental updates.
- Administrators supervise all campus residences, manage users, and inspect resolution statistics.

---

## System Actors & Workflow

The platform is strictly organized around **three actors**:

```
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
- Submits complaints by selecting their **hostel from a dropdown** and entering an **optional room number**.
- Follows ticket status with a real-time progress bar and timestamped update feed.
- Rates resolved complaints (1 to 5 stars) and submits feedback.

### 2. Hostel Porter (`PORTER`)
- Assigned to a specific hostel.
- Accesses a dedicated Porter Dashboard filtering complaints exclusive to their hostel.
- Moves complaints forward: `Pending` &rarr; `In progress` &rarr; `Resolved`.
- Adds audit-trailed update notes so students know what actions are being taken.
- Accounts are created via registration or admin, with admin approval required before initial sign-in.

### 3. Administrator (`ADMIN`)
- Master oversight across all hostels and complaints.
- User management: approve porters, activate/deactivate accounts, change user roles.
- Hostel management: register new hostels and toggle active statuses.
- Category management: maintain issue categories (e.g., Facility Issue, Theft, Bullying, Cult-related Issue).

---

## Design Decisions & Scope

To keep the application fast, reliable, and straightforward:

| Feature | Design Implementation |
|---|---|
| **Actors** | Exactly 3: Student, Porter, and Admin. (Separate maintenance staff and warden tiers have been intentionally removed). |
| **Room Allocation** | Eliminated. Students do not need room pre-allocations to lodge complaints. |
| **Hostel Selection** | Dynamic dropdown on complaint submission featuring all active hostels. |
| **Room Identification** | Optional string input (e.g. `Room 12`, `Block B`, or blank for common areas). |
| **Priority Levels** | Removed. All complaints enter a first-class, equal-priority queue. |
| **Photo Attachments** | Removed. Focus is kept on rapid, lightweight text submissions without complex media storage dependencies. |
| **Hostel Gender Classification** | Removed. Hostels are tracked purely by name and active status. |

---

## Complaint Lifecycle

Complaints follow a strict forward-only 3-step state machine:

```
[ 1. PENDING ] ──────> [ 2. IN_PROGRESS ] ──────> [ 3. RESOLVED ]
  Ticket submitted        Porter reviews &          Porter completes issue;
  by student              takes action              Student submits rating & review
```

- Every transition creates an immutable record in `complaint_updates` capturing the author, date, message, and previous/new status.
- Once marked `RESOLVED`, students can submit a 1–5 star rating and feedback note.

---

## Prerequisites

Before setting up the project, ensure your environment meets the following requirements:

1. **PHP**: `^8.2` or higher (verified compatible up to PHP 8.5)
2. **PHP Extensions**:
   - `pdo_mysql` (Database PDO driver)
   - `mysqli`
   - `openssl`
   - `mbstring`
   - `curl`
   - `fileinfo`
3. **Database**: **MySQL** or **MariaDB** Server (running on `127.0.0.1:3306`)
4. **Dependency Manager**: **Composer 2.x**
5. **Web Server**: Apache, Nginx, or PHP's built-in development server (`php artisan serve`)

---

## Step-by-Step Installation Guide

### 1. Configure PHP Extensions
Ensure `pdo_mysql` is enabled in your `php.ini` file:

```ini
extension_dir = "ext"
extension=pdo_mysql
extension=mysqli
extension=openssl
extension=mbstring
```

Verify that the extension is loaded:
```bash
php -m | findstr pdo_mysql   # Windows CMD
# or
php -m | grep pdo_mysql      # Linux / macOS
```

---

### 2. Prepare the MySQL Database
Start your MySQL server (via XAMPP, WAMP, Laragon, or standalone MySQL service), log into MySQL, and create the database:

```sql
CREATE DATABASE nestly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### 3. Environment Configuration (`.env`)
In the root directory, create or edit your `.env` file to configure your MySQL connection:

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

*(If you have a password for your local MySQL root user, add it to `DB_PASSWORD`)*.

---

### 4. Install Composer Dependencies
Run Composer to install all backend dependencies:

```bash
composer install
```

*(Note: If you are running PHP 8.3 and encounter composer platform restrictions, run `composer install --ignore-platform-req=php`).*

---

### 5. Generate Application Encryption Key
```bash
php artisan key:generate
```

---

### 6. Run Migrations & Database Seeders
Run the migration suite to build all database tables and populate them with reference hostels, categories, and default test accounts:

```bash
php artisan migrate:fresh --seed
```

---

### 7. Start the Development Server
```bash
php artisan serve
```

The application will now be live at:
👉 **`http://127.0.0.1:8000`**

---

## Default Seed Accounts

The seeder automatically provisions test accounts for each of the three actors:

| Role | Email | Password | Access & Purpose |
|---|---|---|---|
| **Administrator** | `admin@nestly.local` | `Admin@12345` | Global control of all hostels, users, categories, and complaints. |
| **Porter** | `porter@nestly.local` | `Porter@12345` | Manages complaints for assigned hostel (`Alvan Ikoku Hall`). |
| **Student** | `student@nestly.local` | `Student@12345` | Lodges and tracks complaint tickets. |

*You can also use the registration form (`/register`) to create brand new Student or Porter accounts.*

---

## Project Directory Structure

```
nestly-php/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php       # Admin dashboard, users, hostels, categories
│   │   │   ├── AuthController.php        # Register, login, logout, role router
│   │   │   ├── HomeController.php        # Public landing page with stats
│   │   │   ├── PorterController.php      # Porter dashboard & hostel complaint resolution
│   │   │   ├── ProfileController.php     # User profile & password management
│   │   │   └── StudentController.php     # Student dashboard, submission, details & ratings
│   │   └── Middleware/
│   │       └── RoleMiddleware.php        # Guard restricting routes to STUDENT, PORTER, or ADMIN
│   └── Models/
│       ├── Complaint.php                 # Complaint model (statuses, scopes, relationships)
│       ├── ComplaintCategory.php         # Categories (Facility, Theft, etc.)
│       ├── ComplaintUpdate.php           # Audit timeline history
│       ├── Hostel.php                    # Residence halls
│       └── User.php                      # Multi-role user model (STUDENT, PORTER, ADMIN)
├── database/
│   ├── migrations/                       # Database schema migrations
│   └── seeders/
│       ├── DatabaseSeeder.php            # Root seeder
│       ├── ReferenceDataSeeder.php       # 19 campus hostels and 5 standard categories
│       └── AdminUserSeeder.php           # Default seed credentials (Admin, Porter, Student)
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php             # Authenticated workspace layout (sidebar & navbar)
│       │   └── home.blade.php            # Public guest landing layout
│       ├── auth/
│       │   ├── login.blade.php           # Authentication login portal
│       │   └── register.blade.php        # 3-step dynamic student/porter registration
│       ├── student/
│       │   ├── dashboard.blade.php       # Student tickets and status badges
│       │   ├── submit_complaint.blade.php# Hostel dropdown & optional room number form
│       │   ├── complaint_detail.blade.php# Progress timeline & porter notes
│       │   └── rate_complaint.blade.php  # 5-star resolution feedback
│       ├── porter/
│       │   ├── dashboard.blade.php       # Filterable hostel complaints feed
│       │   └── complaint.blade.php       # Action portal to advance status & post notes
│       ├── admin/
│       │   ├── dashboard.blade.php       # Global triage feed & metrics
│       │   ├── complaint_manage.blade.php# Direct complaint updates & overrides
│       │   ├── users/                    # User listing, approvals, and form
│       │   ├── hostels/                  # Hostel catalog and activation toggle
│       │   └── categories/               # Category catalog and form
│       └── partials/
│           ├── _complaint_table.blade.php# Reusable table with dynamic role routes
│           ├── _filters.blade.php        # Search, hostel, category & status filters
│           └── _stats.blade.php          # Real-time counter cards
└── routes/
    └── web.php                           # Application HTTP route definitions
```

---

## Routes & Role-Based Permissions

All routes are protected by session authentication and the `role:ROLE` middleware:

### Public Routes
- `GET  /` &rarr; Landing page with live counter statistics.
- `GET|POST /register` &rarr; Multi-step account registration (Student or Porter).
- `GET|POST /login` &rarr; Sign in.
- `POST /logout` &rarr; Sign out.

### Shared Authenticated Routes
- `GET  /dashboard` &rarr; Smart router that dispatches users to their respective role dashboard.
- `GET  /profile` &rarr; View profile information.
- `POST /profile` &rarr; Update contact phone number.
- `POST /profile/password` &rarr; Change account password.

### Student Routes (`role:STUDENT`)
- `GET  /student/dashboard` &rarr; View personal tickets and filter by status.
- `GET  /student/submit-complaint` &rarr; Render complaint submission form with hostel select.
- `POST /student/submit-complaint` &rarr; Validate and store new complaint.
- `GET  /student/complaint/{id}` &rarr; Timeline progress tracker and porter notes.
- `GET|POST /student/complaint/{id}/rate` &rarr; 5-star rating & review on resolved complaints.

### Porter Routes (`role:PORTER`)
- `GET  /porter/dashboard` &rarr; View complaints submitted for the porter's assigned hostel.
- `GET|POST /porter/complaint/{id}` &rarr; Inspect complaint, append update note, and advance status (`Pending` &rarr; `In progress` &rarr; `Resolved`).

### Administrator Routes (`role:ADMIN`)
- `GET  /control/dashboard` &rarr; Overview of all complaints campus-wide.
- `GET|POST /control/complaint/{id}` &rarr; Administrative ticket management.
- `GET  /control/users` &rarr; User listing with filters.
- `GET|POST /control/users/add` &rarr; Manually provision a user.
- `GET|POST /control/users/{id}/edit` &rarr; Edit user details, change role, or assign hostel.
- `POST /control/users/{id}/action` &rarr; Approve, activate, or deactivate user.
- `GET  /control/hostels` &rarr; View and manage hostel directory.
- `GET|POST /control/hostels/add` &rarr; Create a hostel.
- `GET|POST /control/hostels/{id}/edit` &rarr; Edit a hostel.
- `POST /control/hostels/{id}/toggle` &rarr; Toggle hostel active/inactive.
- `GET  /control/categories` &rarr; Category catalog.
- `GET|POST /control/categories/add` &rarr; Create category.
- `GET|POST /control/categories/{id}/edit` &rarr; Edit category.
- `POST /control/categories/{id}/toggle` &rarr; Toggle category active/inactive.

---

## Troubleshooting & FAQs

### 1. `SQLSTATE[HY000] [2002] Connection refused`
- **Cause**: MySQL server is not running on `127.0.0.1:3306`.
- **Fix**: Open XAMPP / WAMP / MySQL service and click **Start** next to MySQL. Ensure port `3306` is open.

### 2. `could not find driver (Connection: mysql)`
- **Cause**: The `pdo_mysql` PHP extension is commented out in `php.ini`.
- **Fix**: Open your `php.ini` file, locate `;extension=pdo_mysql`, remove the semicolon `;`, save, and restart your terminal or web server.

### 3. "Porter account cannot log in"
- **Cause**: Porters require administrator approval upon registration.
- **Fix**: Log in with the Administrator account (`admin@nestly.local`), navigate to **Users**, find the porter, and click **Approve**.

### 4. How do I reset the database to a clean demo state?
- Run:
  ```bash
  php artisan migrate:fresh --seed
  ```
  *(This wipes existing test data, recreates tables, seeds reference hostels/categories, and reinstates the default Admin, Porter, and Student credentials).*
#   n e s t l y - p h p  
 