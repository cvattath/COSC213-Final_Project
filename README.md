# COSC213-Final_Project
This is a readme file for COSC 213 Final Project

## Project - Content Management System
Kelowna Go-To
-> Local food sports
-> Local news and updates;
-> Hikes and go-to places

This is content management website like reddit, where people post recent events and updates in Kelowna that is accessible to both locals and out-of-towners. This includes the local food spots, raves, hikes and etc. for people to share and enjoy.

# Layout of the website
- HOME page 
- POST page
- Categories page
    - Local News/Rave
    - Local Food Spots
    - Local Hikes and places
- Contact Us page


 for filtered post that has most reactions or recent post.

This is a readme file for COSC 213 Final Projects


### SQL for creating the required tables for storing POSTS, USERS and CATEGORIES

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