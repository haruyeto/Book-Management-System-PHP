CREATE DATABASE IF NOT EXISTS `books`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `books`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED          NOT NULL AUTO_INCREMENT,
    `username`      VARCHAR(60)           NOT NULL UNIQUE,
    `password_hash` VARCHAR(255)          NOT NULL,
    `full_name`     VARCHAR(120)          NOT NULL,
    `role`          ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    `created_at`    TIMESTAMP             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `books` (
    `id`               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `title`            VARCHAR(200)     NOT NULL,
    `author`           VARCHAR(120)     NOT NULL,
    `genre`            VARCHAR(100)     NOT NULL,
    `publication_year` YEAR             NOT NULL,
    `price`            DECIMAL(10,2)    NOT NULL,
    `created_at`       TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `books` (`title`, `author`, `genre`, `publication_year`, `price`) VALUES
('To Kill a Mockingbird', 'Harper Lee',       'Fiction',        1960, 299.00),
('1984',                  'George Orwell',    'Dystopian',      1949, 349.00),
('The Great Gatsby',      'F. Scott Fitzgerald', 'Classic',     1925, 279.00),
('Harry Potter',          'J.K. Rowling',     'Fantasy',        1997, 499.00),
('Clean Code',            'Robert C. Martin', 'Technology',     2008, 899.00);


