CREATE DATABASE IF NOT EXISTS `utcl-mts`;
USE `utcl-mts`;

CREATE TABLE `staff`(
    `staff_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `group` TEXT NOT NULL
);

CREATE TABLE `audit`(
    `audit_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `staff_id` INT UNSIGNED NOT NULL,  -- Ensure this matches exactly with `staff.staff_id`
    `act` TEXT NOT NULL,
    `date_time` INT NOT NULL,
    FOREIGN KEY (`staff_id`) REFERENCES `staff`(`staff_id`)
);

CREATE TABLE `student`(
    `student_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `year` TEXT NOT NULL
);

CREATE TABLE `brand`(
    `brand_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `brand_name` TEXT NOT NULL
);

CREATE TABLE `med`(
    `med_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `med_name` TEXT NOT NULL
);

CREATE TABLE `takes`(
    `takes_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `med_id` INT UNSIGNED NOT NULL,
    `brand_id` INT UNSIGNED NOT NULL,
    `exp_date` INT NOT NULL,
    `current_dose` INT NOT NULL,
    `min_dose` INT NOT NULL,
    `max_dose` INT NOT NULL,
    `strength` INT NOT NULL,
    `active` BOOLEAN NOT NULL,
    FOREIGN KEY (`med_id`) REFERENCES `med`(`med_id`),
    FOREIGN KEY (`brand_id`) REFERENCES `brand`(`brand_id`)
);

CREATE TABLE `administer`(
    `adminster_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `takes_id` INT UNSIGNED NOT NULL,
    `staff_code` TEXT NOT NULL,
    `date_time` INT NOT NULL,
    `dose_given` INT NOT NULL,
    FOREIGN KEY (`takes_id`) REFERENCES `takes`(`takes_id`)
);