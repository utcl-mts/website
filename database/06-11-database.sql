CREATE TABLE `staff`(
    `staff_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `group` TEXT NOT NULL
);
CREATE TABLE `meds_name`(
    `meds_name_id` INT NOT NULL,
    `meds_name` TEXT NOT NULL
);
CREATE TABLE `audits`(
    `audit_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `staff_id` INT NOT NULL,
    `action` TEXT NOT NULL,
    `date_time` INT NOT NULL
);
CREATE TABLE `meds`(
    `student_id` INT NOT NULL,
    `meds_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `meds_name` TEXT NOT NULL,
    `brand` TEXT NOT NULL,
    `strength` TEXT NOT NULL,
    `remaning_dose` INT NOT NULL,
    `total_dose` INT NOT NULL,
    `exp_date` DATE NOT NULL
);
CREATE TABLE `students`(
    `student_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `year_group` INT NOT NULL
);
CREATE TABLE `brand_name`(
    `brand_id` INT NOT NULL,
    `brand_name` TEXT NOT NULL,
    PRIMARY KEY(`brand_name`)
);
ALTER TABLE
    `brand_name` ADD CONSTRAINT `brand_name_brand_id_foreign` FOREIGN KEY(`brand_id`) REFERENCES `meds`(`brand`);
ALTER TABLE
    `meds_name` ADD CONSTRAINT `meds_name_meds_name_id_foreign` FOREIGN KEY(`meds_name_id`) REFERENCES `meds`(`meds_name`);
ALTER TABLE
    `audits` ADD CONSTRAINT `audits_staff_id_foreign` FOREIGN KEY(`staff_id`) REFERENCES `staff`(`staff_id`);
ALTER TABLE
    `meds` ADD CONSTRAINT `meds_student_id_foreign` FOREIGN KEY(`student_id`) REFERENCES `students`(`student_id`);
