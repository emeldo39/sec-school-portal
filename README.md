# Divine Royal Int'l College Nkpor — School Portal

A full-featured secondary school management web application built with Laravel for **Divine Royal Int'l College Nkpor, Anambra State**. The portal serves administrators, teachers, students/parents, and the general public through a unified platform.

---

## Features

### Admin Panel
- **Dashboard** — school-wide overview and statistics
- **Student Management** — enrollment, profiles, class assignment
- **Class & Subject Management** — JSS and SSS levels
- **Academic Terms** — term/session configuration
- **Score & Result Management** — approve/publish termly results
- **Teacher Assignments** — assign teachers to subjects and classes
- **Attendance Tracking** — student and staff attendance records
- **Grading System** — configurable grading scales per school level (JSS/SSS)
- **Score Weights** — custom weight configuration for score components
- **Announcements** — school-wide notice board
- **Gallery** — school photo gallery management
- **News & Publications** — school news posts and publications management
- **Hero Slides & Popup Notices** — homepage content management
- **Contact Messages** — view and manage enquiries from the public
- **Reports** — generate academic and administrative reports
- **Activity Logs** — audit trail of all admin actions
- **Backup** — database backup management
- **School Settings** — configure school name, logo, contact info, etc.

### Teacher Portal
- **Dashboard** — class and subject overview
- **Score Entry** — enter and manage student scores per subject/term
- **Attendance** — mark class attendance
- **Results** — view and print student results
- **Announcements** — view school announcements
- **Profile** — manage personal profile and signature

### Public Website
- Home page with hero slideshow and popup notices
- About, Academics, Admissions, Staff, and Gallery pages
- News & blog section
- Contact form
- **Public Result Checker** — parents/students can check published results online

### Authentication
- Role-based access control: `admin`, `principal`, `teacher`
- Forgot password / password reset
- Secure session management

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP) |
| Database | MySQL |
| Frontend | Blade templates, Bootstrap, Vite |
| Auth | Laravel built-in auth |
| Server | Apache (XAMPP) |

---

## Getting Started

### Requirements
- PHP >= 8.2
- Composer
- MySQL
- Node.js & npm

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/emeldo39/sec-school-portal.git
cd sec-school-portal

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file and configure
cp .env.example .env
# Edit .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Generate app key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Seed initial data (grading scales, settings)
php artisan db:seed

# 8. Build frontend assets
npm run build

# 9. Start the server
php artisan serve
```

The application will be available at `http://localhost:8000`.

---

## Environment Variables

Key variables to configure in `.env`:

```env
APP_NAME="Divine Royal Int'l College"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_portal
DB_USERNAME=root
DB_PASSWORD=
```

---

## User Roles

| Role | Access |
|---|---|
| `admin` | Full system access |
| `principal` | Admin-level access with principal designation |
| `teacher` | Score entry, attendance, results for assigned subjects |

---

## Score Components

**JSS (Junior Secondary School)**
- 1st Weekly Exercise
- Take Home Assignment
- College Quiz
- Project
- 2nd Weekly Exercise
- Take Home Assignment 2
- Summary of Continuous Assessment
- End of Term Examination

**SSS (Senior Secondary School)**
- Same components as JSS plus additional SSS-specific score fields

---

## License

Proprietary — developed for Divine Royal Int'l College Nkpor, Anambra State.
