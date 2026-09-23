CREATE DATABASE IF NOT EXISTS travel_website;
USE travel_website;

CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    best_time VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    travel_date DATE NOT NULL,
    persons INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO destinations (name,country,description,days,price,best_time,image) VALUES
('Madurai','India','Visit the Meenakshi Amman Temple, local markets and important heritage places in Madurai.',3,6500,'October to March','assets/india.svg'),
('Paris','France','Explore the Eiffel Tower area, museums, city streets and famous landmarks of Paris.',5,45000,'April to June','assets/paris.svg'),
('Bali','Indonesia','Enjoy beaches, temples, local culture and scenic places around Bali.',5,38000,'April to October','assets/bali.svg'),
('Dubai','UAE','Experience modern city attractions, desert activities and shopping areas.',4,42000,'November to March','assets/dubai.svg'),
('Singapore','Singapore','Explore Marina Bay, Gardens by the Bay and other city attractions.',4,48000,'February to April','assets/singapore.svg'),
('Seoul','South Korea','Explore palaces, markets, city attractions and Korean cultural areas.',6,55000,'April to May','assets/korea.svg');
