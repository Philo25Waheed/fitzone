-- =====================================================
-- FitZone Gym Database Schema
-- Native PHP & MySQL Implementation
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS fitzone_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fitzone_db;

-- =====================================================
-- USERS TABLE
-- Stores registered users with hashed passwords
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    weight DECIMAL(5,2) NULL COMMENT 'Weight in kg',
    height DECIMAL(5,2) NULL COMMENT 'Height in cm',
    age INT NULL,
    gender ENUM('male', 'female') NULL,
    goal ENUM('maintenance', 'bulking', 'cutting') NULL DEFAULT 'maintenance',
    avatar VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- =====================================================
-- MEALS TABLE
-- Healthy meals data displayed on meals page
-- =====================================================
CREATE TABLE IF NOT EXISTS meals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NULL COMMENT 'Arabic name',
    image_url VARCHAR(255) NOT NULL,
    calories INT NOT NULL,
    protein DECIMAL(5,2) NULL COMMENT 'Protein in grams',
    carbs DECIMAL(5,2) NULL COMMENT 'Carbohydrates in grams',
    fat DECIMAL(5,2) NULL COMMENT 'Fat in grams',
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- EXERCISES TABLE
-- Exercise library with video links
-- =====================================================
CREATE TABLE IF NOT EXISTS exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    title_ar VARCHAR(100) NULL COMMENT 'Arabic title',
    video_url VARCHAR(255) NOT NULL,
    description TEXT NULL,
    description_ar TEXT NULL COMMENT 'Arabic description',
    category VARCHAR(50) NULL COMMENT 'e.g., chest, back, legs',
    difficulty ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- TRAINING PROGRAMS TABLE
-- Workout splits and training schedules
-- =====================================================
CREATE TABLE IF NOT EXISTS training_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    description_ar TEXT NULL,
    schedule JSON NULL COMMENT 'JSON structure of days and exercises',
    days_per_week INT DEFAULT 5,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- USER PROGRESS TABLE
-- Tracks workout history and streak data
-- =====================================================
CREATE TABLE IF NOT EXISTS user_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_date DATE NOT NULL,
    weight DECIMAL(5,2) NULL COMMENT 'Weight on this date',
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, workout_date)
) ENGINE=InnoDB;

-- =====================================================
-- CONTACTS TABLE
-- Contact form submissions
-- =====================================================
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert default meals (matching the existing frontend)
INSERT INTO meals (name, image_url, calories, protein, carbs, fat) VALUES
('Grilled Salmon', 'img/meal_1.jpg', 450, 40, 5, 28),
('Chicken Salad', 'img/meal_2.jpg', 320, 35, 15, 12),
('Quinoa Bowl', 'img/meal_3.jpg', 380, 14, 55, 10),
('Tofu Stir-fry', 'img/meal_4.jpg', 300, 20, 25, 14),
('Beef Steak', 'img/meal_5.jpg', 600, 55, 2, 40),
('Veggie Wrap', 'img/meal_6.jpg', 280, 10, 40, 8),
('Protein Pancakes', 'img/meal_7.jpg', 350, 25, 35, 12),
('Turkey Sandwich', 'img/meal_8.jpg', 400, 30, 35, 15),
('Greek Yogurt Parfait', 'img/meal_9.jpg', 220, 15, 30, 5),
('Avocado Toast', 'img/meal_10.jpg', 270, 8, 25, 16);

-- Insert default exercises
INSERT INTO exercises (title, title_ar, video_url, category, difficulty) VALUES
('Push Ups', 'تمرين الضغط', 'https://www.youtube.com/embed/_l3ySVKYVJ8', 'chest', 'beginner'),
('Squats', 'السكوات', 'https://www.youtube.com/embed/aclHkVaku9U', 'legs', 'beginner'),
('Plank', 'البلانك', 'https://www.youtube.com/embed/pSHjTRCQxIw', 'core', 'beginner'),
('Deadlift', 'الديدليفت', 'https://www.youtube.com/embed/op9kVnSso6Q', 'back', 'intermediate'),
('Shoulder Press', 'ضغط الكتف', 'https://www.youtube.com/embed/qEwKCR5JCog', 'shoulders', 'beginner'),
('Bench Press', 'ضغط الصدر', 'https://www.youtube.com/embed/gRVjAtPip0Y', 'chest', 'intermediate'),
('Barbell Row', 'التجديف بالبار', 'https://www.youtube.com/embed/FWJR5Ve8bnQ', 'back', 'intermediate'),
('Lat Pulldown', 'سحب علوي', 'https://www.youtube.com/embed/CAwf7n6Luuc', 'back', 'beginner'),
('Leg Press', 'ضغط الرجل', 'https://www.youtube.com/embed/IZxyjW7MPJQ', 'legs', 'beginner'),
('Barbell Curl', 'كيرل بالبار', 'https://www.youtube.com/embed/kwG2ipFRgfo', 'arms', 'beginner');

-- Insert training programs
INSERT INTO training_programs (name, slug, description, days_per_week, schedule) VALUES
('Bro Split', 'bro', 'Classic bodybuilding split - one muscle group per day', 5, 
 '{"saturday":"Chest","sunday":"Back","monday":"Shoulders","tuesday":"Legs","wednesday":"Arms"}'),
('Full Body', 'full', 'Full body workouts 3-4 times per week', 3,
 '{"day1":"Full Body A","day2":"Full Body B","day3":"Full Body C"}'),
('Push / Pull', 'pushpull', 'Push muscles and pull muscles split', 4,
 '{"push":"Chest + Shoulders + Triceps","pull":"Back + Biceps"}'),
('Body Part Split', 'bodypart', 'Big muscles day and small muscles day', 4,
 '{"day1":"Chest + Back + Legs","day2":"Shoulders + Arms"}'),
('Powerbuilding', 'power', 'Combination of strength and hypertrophy training', 5,
 '{"strength_days":"Low reps (3-5)","hypertrophy_days":"High reps (8-12)"}');

-- =====================================================
-- END OF SCHEMA
-- =====================================================
