-- Database: fitzone

CREATE DATABASE IF NOT EXISTS fitzone;
USE fitzone;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    weight FLOAT DEFAULT NULL,
    height FLOAT DEFAULT NULL,
    goal VARCHAR(50) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT 'default_avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Workouts Table
CREATE TABLE IF NOT EXISTS workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    level VARCHAR(50),
    duration_minutes INT
);

-- Recipes Table
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    calories INT,
    image VARCHAR(255),
    description TEXT,
    ingredients TEXT, 
    instructions TEXT
);

-- Blog Table
CREATE TABLE IF NOT EXISTS blog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contacts Table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Progress Table
CREATE TABLE IF NOT EXISTS progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    weight FLOAT NOT NULL,
    note TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert dummy data for Workouts
INSERT INTO workouts (title, description, image, level, duration_minutes) VALUES
('Full Body Blast', 'A complete full body workout to tone muscles.', 'training_full.jpg', 'Beginner', 45),
('HIIT Cardio', 'High Intensity Interval Training for fat loss.', 'training_power.jpg', 'Intermediate', 30),
('Strength Master', 'Heavy lifting for building mass.', 'training_bro.jpg', 'Advanced', 60);

-- Insert dummy data for Recipes
INSERT INTO recipes (name, calories, image, description) VALUES
('Grilled Chicken Salad', 350, 'meal_1.jpg', 'Healthy grilled chicken with fresh veggies.'),
('Avocado Toast', 250, 'meal_2.jpg', 'Whole grain toast with mashed avocado and egg.'),
('Protein Smoothie', 200, 'meal_3.jpg', 'Banana, whey protein, and almond milk.');

-- Insert dummy data for Blog
INSERT INTO blog (title, content, image) VALUES
('10 Tips for Weight Loss', 'Here are the top 10 tips to lose weight effectively...', 'blog_1.jpg'),
('Benefits of Strength Training', 'Why you should lift weights atleast 3 times a week...', 'blog_2.jpg');
