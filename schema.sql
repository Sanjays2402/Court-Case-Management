-- Court Case Management System — Database Schema
-- MySQL 5.7+ / MariaDB 10.3+
-- Run: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS ccms
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE ccms;

-- ---------------------------------------------------------------------------
-- Users (clients, advocates, staff)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100)        NOT NULL,
  email         VARCHAR(190) UNIQUE NOT NULL,
  password_hash VARCHAR(255)        NOT NULL,
  dob           DATE                NULL,
  designation   VARCHAR(80)         NOT NULL DEFAULT 'Client',
  role          ENUM('client','advocate','admin') NOT NULL DEFAULT 'client',
  is_active     TINYINT(1)          NOT NULL DEFAULT 1,
  created_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_role (role)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Cases
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cases (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED        NOT NULL,
  case_type       VARCHAR(80)         NOT NULL,
  case_number     VARCHAR(60) UNIQUE  NOT NULL,
  fir_number      VARCHAR(60)         NULL,
  advocate_name   VARCHAR(120)        NOT NULL,
  advocate_id     VARCHAR(40)         NULL,
  filed_on        DATE                NOT NULL,
  next_hearing    DATE                NULL,
  description     TEXT                NULL,
  status          ENUM('open','in_progress','adjourned','closed','dismissed','won','lost')
                  NOT NULL DEFAULT 'open',
  priority        ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  created_at      TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_cases_status (status),
  INDEX idx_cases_hearing (next_hearing),
  INDEX idx_cases_user (user_id),
  FULLTEXT KEY ft_cases (case_type, case_number, advocate_name, description)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Hearings (timeline of activity for each case)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS hearings (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  case_id     INT UNSIGNED  NOT NULL,
  hearing_on  DATETIME      NOT NULL,
  outcome     VARCHAR(255)  NULL,
  notes       TEXT          NULL,
  created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_hearings_case FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
  INDEX idx_hearings_case (case_id),
  INDEX idx_hearings_when (hearing_on)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Case notes (free-form chronological log)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS case_notes (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  case_id     INT UNSIGNED  NOT NULL,
  user_id     INT UNSIGNED  NOT NULL,
  body        TEXT          NOT NULL,
  created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notes_case FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
  CONSTRAINT fk_notes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_notes_case (case_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Contact submissions
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  email       VARCHAR(190)  NOT NULL,
  subject     VARCHAR(150)  NOT NULL,
  message     TEXT          NOT NULL,
  is_read     TINYINT(1)    NOT NULL DEFAULT 0,
  created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Seed: a default admin (password: admin123 — CHANGE THIS)
-- Hash generated with: php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
-- ---------------------------------------------------------------------------
INSERT INTO users (name, email, password_hash, designation, role) VALUES
  ('Admin', 'admin@ccms.local',
   '$2y$10$wQH0R3K2Z3sO0a8XQzY7Re2xqW5vKQzD2YQxVZbC6.5n8z3y3zG9G',
   'Administrator', 'admin')
ON DUPLICATE KEY UPDATE email = email;
