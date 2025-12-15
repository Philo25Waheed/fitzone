
# FitZone Gym - Setup Instructions

## Overview
FitZone is a fitness tracking web application built with Native PHP and MySQL.

---

## Prerequisites

1. **XAMPP** installed with:
   - Apache (running)
   - MySQL (running)

2. **Project Location**:
   - Place project folder in: `C:\xampp\htdocs\`
   - Access via: `http://localhost/fitzone_gym_test/`

---

## Quick Setup

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL**

### Step 2: Run Database Setup
1. Open browser and navigate to:
   ```
   http://localhost/fitzone_gym_test/setup.php
   ```
2. The script will automatically:
   - Create `fitzone_db` database
   - Create all required tables
   - Insert default data (meals, exercises, training programs)

### Step 3: Access the Site
1. Go to: `http://localhost/fitzone_gym_test/`
2. Click "Login" → "Sign Up" to create an account
3. Start using the app!

---

## Project Structure

```
fitzone_gym_test/
│
├── config/
│   └── db.php              # Database connection
│
├── auth/
│   ├── login.php           # Login API
│   ├── register.php        # Registration API
│   ├── logout.php          # Logout API
│   └── check.php           # Session check API
│
├── api/
│   ├── users.php           # User profile API
│   ├── progress.php        # Workout progress API
│   ├── contact.php         # Contact form API
│   ├── meals.php           # Meals data API
│   └── exercises.php       # Exercises data API
│
├── includes/
│   ├── functions.php       # Helper functions
│   └── auth_check.php      # Auth middleware
│
├── css/
│   └── style.css           # Stylesheet
│
├── js/
│   └── main.js             # Frontend JavaScript
│
├── img/                    # Images
│
├── *.html                  # HTML pages
├── database.sql            # Database schema
└── setup.php               # Setup script
```

---

## Features

### Authentication
- User registration with email/password
- Secure login with password hashing (bcrypt)
- Session-based authentication
- Logout functionality

### Workout Tracking
- Log daily workouts
- Track workout streak
- View progress history

### Other Features
- Calorie & macro calculator
- Contact form
- Training program guides
- Healthy meals catalog

---

## Database Tables

| Table | Description |
|-------|-------------|
| `users` | User accounts with hashed passwords |
| `meals` | Healthy meal options |
| `exercises` | Exercise library with videos |
| `training_programs` | Workout split programs |
| `user_progress` | Workout logs and streak data |
| `contacts` | Contact form submissions |

---

## API Endpoints

### Authentication
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/auth/register.php` | POST | Register new user |
| `/auth/login.php` | POST | Login user |
| `/auth/logout.php` | POST | Logout user |
| `/auth/check.php` | GET | Check session status |

### Data APIs
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/users.php` | GET/PUT | User profile |
| `/api/progress.php` | GET/POST | Workout progress |
| `/api/contact.php` | POST | Submit contact form |
| `/api/meals.php` | GET | Get meals list |
| `/api/exercises.php` | GET | Get exercises list |

---

## Security Features

- ✅ Password hashing with `password_hash()`
- ✅ Prepared statements (PDO)
- ✅ Input sanitization
- ✅ Session security with `session_regenerate_id()`
- ✅ HTTP-only session cookies
- ✅ JSON error responses (no sensitive info)

---

## Troubleshooting

### "Database connection error"
1. Ensure MySQL is running in XAMPP
2. Check credentials in `config/db.php`
3. Run `setup.php` to create database

### "404 Not Found"
1. Ensure project is in `htdocs` folder
2. Check file permissions
3. Verify Apache is running

### "Login not working"
1. Clear browser cookies
2. Check browser console for errors
3. Verify database has users table

---

## Manual Database Setup (Alternative)

If `setup.php` doesn't work, you can manually import:

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create database named `fitzone_db`
3. Select the database
4. Go to "Import" tab
5. Choose `database.sql` file
6. Click "Go"

---

## Contact

For support or questions, use the Contact form in the application.

---

© 2025 FitZone Gym
