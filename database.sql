CREATE DATABASE psychotest;

USE psychotest;

CREATE TABLE users(

id INT AUTO_INCREMENT PRIMARY KEY,

nama VARCHAR(100),

email VARCHAR(100) UNIQUE,

whatsapp VARCHAR(20),

password VARCHAR(255),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE questions(

id INT AUTO_INCREMENT PRIMARY KEY,

question TEXT,

option_a VARCHAR(255),

option_b VARCHAR(255),

option_c VARCHAR(255),

option_d VARCHAR(255),

answer CHAR(1)

);

CREATE TABLE user_answers(

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

question_id INT,

answer CHAR(1),

FOREIGN KEY(user_id) REFERENCES users(id),

FOREIGN KEY(question_id) REFERENCES questions(id)

);

CREATE TABLE payments(

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

bukti VARCHAR(255),

status ENUM('pending','approved','rejected') DEFAULT 'pending',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY(user_id) REFERENCES users(id)

);

CREATE TABLE results(

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

score INT,

status ENUM('locked','active') DEFAULT 'locked',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY(user_id) REFERENCES users(id)

);

CREATE TABLE admins(

id INT AUTO_INCREMENT PRIMARY KEY,

username VARCHAR(100),

password VARCHAR(255)

);