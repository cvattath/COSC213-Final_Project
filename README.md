# COSC213-Final_Project
PRIME-OKG: A community content management system.

# Team Members
Christy Vattatharakooran Kunjachan
Ralph Juliano
Tatsuki Sugawara


## Project - Content Management System
Prime-OKG
-> Local food sports
-> Local news and updates;
-> Hikes and go-to places

This is content management website like reddit, where people post recent events and updates in Kelowna that is accessible to both locals and out-of-towners. This includes the local food spots, raves, hikes and etc. for people to share and enjoy.

# Layout of the website
- Home page 
- Contact Us page

-> User Login -> User Register 
-> Admin Login

## Features

- User registration & secure login  
- Create / Edit / Delete posts 
- Posts show author name and creation date  
- Three fixed categories 
- Responsive navigation (Home, Contact Us, Login/Register)  
- Contact form submissions stored in database (admin-only view)  


## Tech Stack
•	Frontend: HTML, CSS
•	Backend: PHP
•	Database: MySQL
•	Server: Apache

## PROJECT INSTRUCTIONS 
- Clone the project to the web host 'XAMPP' C:/xampp/htdocs/COSC-213-Final_Project/
- Database 'local_blog' created in XAMPP admin and tables (USERS, OKGPOSTS, CATEGORIES, CONTACTS)
- DB connection establised using db.php
- Open the following link in the browser - http://localhost/COSC-213-Final_Project/home.php

| Role          | Username   | Password     |
|---------------|------------|--------------|
| Admin         | `admin`    | `admin123`   |    
| Regular User  | `chris`    | `12345`      | 


## SQL Schema for creating the required tables for storing POSTS, USERS, CATEGORIES and CONTACTS

CREATE TABLE OKGPOSTS (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
image VARCHAR(255),
author_id INT NOT NULL,
cat_id INT NOT NULL,
content TEXT NOT NULL,
createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (cat_id) REFERENCES CATEGORIES(id),
FOREIGN KEY (author_id) REFERENCES users(id));

CREATE TABLE USERS (
id INT AUTO_INCREMENT PRIMARY KEY,
u_name VARCHAR(20) NOT NULL UNIQUE,
pass VARCHAR(255) NOT NULL,
age INT NOT NULL CHECK (age >= 0),
name VARCHAR(50) NOT NULL,
createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);


CREATE TABLE CATEGORIES (
id INT AUTO_INCREMENT PRIMARY KEY,
cat_name VARCHAR(50) NOT NULL,
log_name VARCHAR(50) NOT NULL UNIQUE);

INSERT INTO categories (cat_name, log_name) VALUES
('Local Hikes', 'local_hikes'),
('Local News', 'local_news'),
('Local Foods', 'local_foods');


CREATE TABLE CONTACTS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(150),
    message TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-----------------------------------------END---------------------------------------------------------