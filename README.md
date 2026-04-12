# Task Planner — Laravel MVC Web Application

## Project Overview

Task Planner is a web application that helps users organise their daily tasks and manage deadlines in one place. It is designed primarily for students and busy professionals who need a simple, centralised system for managing assignments, personal tasks, and work responsibilities.

The application is built using PHP with the Laravel framework following MVC architecture, and stores task data in a MySQL database.

**Developed by:** Maryna Hordiienko & Aleksy Cieslak  
**Module:** Server-Side Development — CA2  
**Institution:** Dundalk Institute of Technology (DkIT)

---

## Problem Statement

Many students and professionals struggle to track tasks and deadlines because their tasks are scattered across notebooks, phone notes, and calendars. This leads to missed deadlines, stress, and poor time management.

Task Planner solves this by providing a single platform where users can create, edit, complete, and delete tasks — keeping everything organised in one place.

---

## User Personas

### Daniel Murphy (Maryna's Persona)
- Age 21, Computer Science student at Trinity College Dublin
- Goals: organise study tasks, track college deadlines, reduce stress
- Frustrations: forgets assignment deadlines, tasks written in different places
- Uses laptop and smartphone daily

### Ciaran O'Brien (Aleksy's Persona)
- Age 34, Digital Marketing Manager in Galway
- Goals: centralise work, personal, and GAA club tasks in one place
- Frustrations: tasks scattered across WhatsApp, sticky notes, and memory
- Technically comfortable but needs something simple

---

## Features Implemented

### In Scope
- User registration
- Login and logout
- View personal task list (dashboard)
- Add task (title, description, due date, due time)
- Edit task
- Delete task
- Mark task as completed
- Client-side and server-side validation
- User authorisation (users only see their own tasks)

### Out of Scope
- Team collaboration / shared tasks
- Email notifications
- Calendar sync
- Task priorities
- Admin analytics

---

## User Flows

Two user journeys are documented in the Project Planning and UX Artefacts PDF:

**New User:** Opens app → Register → Dashboard (empty) → Create first task → Redirected to dashboard (task visible)

**Returning User:** Opens app → Login → Dashboard → Edit existing task → Save changes → Redirected to dashboard (updated task visible)

---

## Technology Stack

| Technology | Purpose |
|---|---|
| Laravel (PHP) | Backend framework, MVC architecture |
| MySQL | Relational database |
| Blade Templates | Server-side HTML views |
| Tailwind CSS | UI styling |
| Vite | Frontend asset bundling |
| Laravel Breeze | Authentication scaffolding |

---

## Project Structure

```
app/
  Http/Controllers/
    TaskController.php      — CRUD logic for tasks
    Auth/                   — Authentication controllers (Breeze)
  Models/
    Task.php                — Task model with user relationship
    User.php                — User model with tasks relationship
database/
  migrations/               — Database schema definitions
resources/
  views/
    tasks/                  — Blade views: index, create, edit, show
    layouts/                — App layout templates
routes/
  web.php                   — Application routes
```

---

## Setup and Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/ms-Maryna/task-planner.git
cd task-planner

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
DB_DATABASE=task_planner
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Build frontend assets
npm run dev

# 8. Start the server
php artisan serve
```

---

## Git Workflow

The project uses feature branches following the structure:

- `main` — stable, reviewed code
- `planning` — UX and planning documents (Stage 1)
- `implement-authentication` — authentication feature
- `implement-crud-tasks` — task CRUD feature
- `home_page` — Home Page with focus tips

---

## Known Limitations

- No email verification enforced
- No task priority levels (out of scope)
- Tested locally with SQLite and MySQL; not deployed to production

---

## Authors

| Name | Contributions |
|---|---|
| Maryna Hordiienko | Project concept, user personas, database migrations, authentication, README, project scope |
| Aleksy Cieslak | Wireframes, Task model, CRUD controllers, Blade views, routing |
