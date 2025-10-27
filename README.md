# Bank UI — Complete Bootstrap PHP Site
**Default credentials:** username: `diana`  password: `123`

## What’s included
- PHP + MySQL sample site (procedural PHP, MySQLi)
- Bootstrap 5 frontend (responsive, professional)
- Pages: login, dashboard, accounts, transfer, transactions, users, logout
- `setup.php` to create required tables and a default user (run once)
- `install.sql` (for manual import)
- `db.php` centralizes DB config
- `assets/` folder with CSS and JS
- `export.zip` (this file)

## Setup (quick)
1. Place the `bank_ui` folder inside your PHP server webroot (e.g., XAMPP `htdocs`).
2. Edit `db.php` with your MySQL credentials.
3. Visit `http://localhost/bank_ui/setup.php` to create tables and the default user `diana` / `123`.
4. Then go to `http://localhost/bank_ui/` to login.

## Notes
- `setup.php` uses `password_hash()` to store the password securely.
- This is a starting point — review security before production.
