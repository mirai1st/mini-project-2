# Campus Service Hub

A student skills & services platform built with PHP, MySQL, Bootstrap & AJAX.

---

## Setup Instructions (XAMPP)

1. Copy the `campus_hub` folder into `C:/xampp/htdocs/`
2. Open **phpMyAdmin** → http://localhost/phpmyadmin
3. Click **New** → Create database named `campus_hub`
4. Click **Import** → select `database.sql` → click **Go**
5. Open browser → go to http://localhost/campus_hub/

---

## Default Admin Login

- **Email:** admin@campus.edu  
- **Password:** admin123

---

## Project Structure

```
campus_hub/
├── database.sql          ← Import this first
├── index.php             ← Home page
├── login.php             ← Login (Task 1)
├── register.php          ← Register (Task 1)
├── logout.php            ← Logout (Task 1)
├── dashboard.php         ← Dashboard (Task 2)
├── search.php            ← Search + AJAX (Task 2 & 5)
├── ajax_search.php       ← AJAX endpoint (Task 5)
├── includes/
│   ├── db.php            ← DB connection + helper functions
│   ├── header.php        ← Shared header/navbar
│   └── footer.php        ← Shared footer
├── services/
│   ├── add.php           ← Add service (Task 3)
│   ├── edit.php          ← Edit service (Task 3)
│   ├── view.php          ← View service
│   └── delete.php        ← Delete service (Task 3)
├── admin/
│   ├── users.php         ← Manage users (Task 1)
│   └── delete_user.php
├── uploads/              ← Uploaded images stored here
└── assets/
    ├── css/style.css
    └── js/main.js        ← Client validation + AJAX
```

---

## Tasks Covered

| Task | Description |
|------|-------------|
| Task 1 | Registration, login, password_hash/verify, roles (admin/user), session, restrict access |
| Task 2 | 5 pages: Home, Login/Register, Dashboard, Add/Edit Service, Search |
| Task 3 | CRUD services, image upload (JPG/PNG, max 2MB), owner-only edit/delete |
| Task 4 | Client-side JS validation + server-side PHP validation on all forms |
| Task 5 | AJAX live search using fetch() — no page reload |
| Task 6 | MySQL JOIN, WHERE, ORDER BY for dynamic content display |
| Task 7 | htmlspecialchars(), prepared statements, secure sessions |
| Task 8 | Push to GitHub (see below) |

---

## Task 8 — GitHub Deployment

```bash
# Inside campus_hub folder
git init
git add .
git commit -m "first commit - Campus Service Hub"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/campus-hub.git
git push -u origin main
```

> **Note:** Do not commit the `uploads/` folder with real images.  
> Add a `.gitignore` file with: `uploads/*` and `!uploads/.gitkeep`

---

## Security Features (Task 7)

- `htmlspecialchars()` on all output — prevents XSS
- Prepared statements (`bind_param`) — prevents SQL injection
- `password_hash()` / `password_verify()` — secure passwords
- `session_start()` + role checks — secure session handling
- File type checked with `mime_content_type()` — safe file uploads
