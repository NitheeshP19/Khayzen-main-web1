# Khayzen Systems - Backend & Database Guide

Your website now features a "proper" backend that handles both **Email Notifications** and **Database Storage**.

## 1. How it Works
When a visitor submits a form (Contact or Newsletter):
1.  An **Email** is sent to `Khayzen@gmail.com`.
2.  The data is saved to a **Database** file on your server.

## 2. Accessing Your Database
The database is a simple CSV file that you can open in **Excel** or **Google Sheets**.
- **File Location**: `bat/submissions.csv`
- **How to Download**:
  1. Log in to your web hosting control panel (e.g., cPanel).
  2. Open "File Manager".
  3. Navigate to the `bat/` folder.
  4. Download `submissions.csv`.

## 3. Deployment Instructions
To make the backend active:
1.  Upload all files from the `Khayzen-main-web1` folder to your web server.
2.  Ensure your hosting supports **PHP** (almost all do).
3.  The backend will start working automatically.

## 4. Troubleshooting
- **No Emails?**: Check if your host allows standard PHP mail. If not, we can switch to SMTP (Gmail) in `bat/rd-mailform.config.json`.
- **No Database Entries?**: Ensure the `bat/` folder has "Write" permissions on your server.
