# StudentDesk — Student Management System

A beginner-friendly PHP and MySQL student management system built for local development with XAMPP and Visual Studio Code.

## Features

- Administrator login with hashed passwords
- Dashboard summary cards
- Register, list, search, edit, and delete students
- Prepared SQL statements, HTML output escaping, CSRF tokens, and server-side validation
- Responsive interface

## Requirements

- Windows, macOS, or Linux
- XAMPP (Apache, PHP, and MySQL/MariaDB)
- Visual Studio Code
- A modern web browser

## Local installation (XAMPP)

1. Install XAMPP from https://www.apachefriends.org/.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Copy the `student-management-system` folder into XAMPP's `htdocs` folder. On Windows, this is usually `C:\xampp\htdocs\student-management-system`.
4. Open `http://localhost/phpmyadmin`.
5. Choose **Import**, select this project's `database.sql`, and click **Import** or **Go**.
6. Open `config.php` and check the database settings. Default XAMPP settings commonly use user `root` and an empty password on a local development machine.
7. Visit `http://localhost/student-management-system/install.php` and create the first admin account with a strong password of at least 12 characters.
8. Delete `install.php` from the project folder after creating the admin.
9. Visit `http://localhost/student-management-system/login.php` and sign in.

## Upload to GitHub

Install Git, create an empty GitHub repository, then open this folder in VS Code and use Terminal:

```bash
git init
git add .
git commit -m "Initial student management system"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/student-management-system.git
git push -u origin main
```

Replace `YOUR-USERNAME` with your GitHub username. If Git asks you to authenticate, follow its browser-based sign-in flow or GitHub's current instructions. Never commit passwords, production credentials, or real student data.

## Deploy online

GitHub is source-code hosting, not a PHP runtime. Choose a web host that supports the PHP version your project uses and a MySQL-compatible database.

1. Create a PHP hosting account and database.
2. Create the database tables by importing `database.sql` into the host database. If the host does not permit `CREATE DATABASE`, remove the first two SQL lines and select the database in the host's database tool first.
3. Update `config.php` with the host's database hostname, database name, username, and password. Do not publish production credentials to GitHub; ideally read them from environment variables.
4. Upload the application files to the host's document root using its file manager or SFTP. Do not leave `install.php` online after setup.
5. Run the one-time setup only if needed and then delete `install.php`.
6. Enable HTTPS, test login and student operations, and set up database backups.

## Important security notes

- This is an educational starter project, not a security audit or a finished production system.
- Use HTTPS online and a unique strong admin password.
- Do not expose phpMyAdmin or XAMPP directly to the public internet.
- Restrict access to student records and comply with your institution's privacy requirements.
- For production, move database credentials outside the web root/environment configuration and configure secure session cookie settings.
- Do not use real student data in a public GitHub repository.
