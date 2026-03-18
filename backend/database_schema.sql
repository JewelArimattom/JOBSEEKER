-- CareerBridge database schema
-- Use this file to create the database on your hosting provider.

CREATE DATABASE IF NOT EXISTS `jobfinder`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `jobfinder`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_roles_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `employer_id` INT NULL,
  `job_title` VARCHAR(255) NOT NULL,
  `job_category` VARCHAR(255) DEFAULT NULL,
  `location` VARCHAR(255) NOT NULL,
  `job_type` VARCHAR(50) DEFAULT NULL,
  `experience_level` VARCHAR(50) DEFAULT NULL,
  `openings` INT DEFAULT 1,
  `application_deadline` DATE DEFAULT NULL,
  `salary_min` INT DEFAULT NULL,
  `salary_max` INT DEFAULT NULL,
  `salary_unit` VARCHAR(50) DEFAULT NULL,
  `description` TEXT NOT NULL,
  `skills` VARCHAR(255) DEFAULT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `company_website` VARCHAR(255) DEFAULT NULL,
  `company_logo_path` VARCHAR(255) DEFAULT NULL,
  `recruiter_name` VARCHAR(255) DEFAULT NULL,
  `recruiter_email` VARCHAR(255) DEFAULT NULL,
  `posted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jobs_employer_id` (`employer_id`),
  CONSTRAINT `fk_jobs_employer_id`
    FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `job_id` INT NOT NULL,
  `user_id` INT DEFAULT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `portfolio_url` VARCHAR(255) DEFAULT NULL,
  `current_salary` VARCHAR(100) DEFAULT NULL,
  `expected_salary` VARCHAR(100) DEFAULT NULL,
  `notice_period` VARCHAR(100) DEFAULT NULL,
  `resume_path` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Applied',
  `application_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `applied_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_applications_job_id` (`job_id`),
  KEY `idx_applications_user_id` (`user_id`),
  CONSTRAINT `fk_applications_job`
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_applications_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `recipient_id` INT NOT NULL,
  `message_text` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_messages_sender_id` (`sender_id`),
  KEY `idx_messages_recipient_id` (`recipient_id`),
  CONSTRAINT `fk_messages_sender`
    FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_messages_recipient`
    FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  KEY `idx_user_roles_role_id` (`role_id`),
  CONSTRAINT `fk_user_roles_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_role`
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`) VALUES
  (1, 'jobseeker'),
  (2, 'employer'),
  (3, 'admin')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
