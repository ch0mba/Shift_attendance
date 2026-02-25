CREATE DATABASE IF NOT EXISTS zkteco_attendance;
USE zkteco_attendance;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100),
    fingerprint_id INT,
    card_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shifts table
CREATE TABLE shifts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    shift_name VARCHAR(50),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert the two shifts
INSERT INTO shifts (shift_name, start_time, end_time) VALUES 
('Morning Shift', '06:00:00', '18:00:00'),
('Night Shift', '18:00:00', '06:00:00');

-- Attendance records
CREATE TABLE attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id VARCHAR(50),
    user_name VARCHAR(100),
    shift_id INT,
    clock_in_time DATETIME,
    clock_out_time DATETIME,
    status ENUM('active', 'completed', 'auto_logout') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id),
    INDEX idx_status (status),
    INDEX idx_user_shift (user_id, shift_id, status)
);

-- Device logs (raw data from ZKTeco)
CREATE TABLE device_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    uid INT,
    user_id VARCHAR(50),
    timestamp DATETIME,
    type INT,
    state INT,
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);