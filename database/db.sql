CREATE DATABASE dealance_db;
USE dealance_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('freelancer', 'employer'))
);

CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'open' CHECK (status IN ('open', 'closed')),
    employer_id INT NOT NULL,
    created_at DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (employer_id) REFERENCES users(user_id)
);

CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    cover_letter TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected')),
    applied_at DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id),
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id)
);
