CREATE DATABASE IF NOT EXISTS fkksm_lms
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE fkksm_lms;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(60) NOT NULL,
  email VARCHAR(190) DEFAULT NULL,
  google_sub VARCHAR(255) DEFAULT NULL,
  full_name VARCHAR(140) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin','admin','instructor','student','company') NOT NULL DEFAULT 'student',
  status ENUM('active','pending','suspended') NOT NULL DEFAULT 'active',
  must_change_password TINYINT(1) NOT NULL DEFAULT 0,
  last_login_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_google_sub (google_sub),
  KEY idx_users_role_status (role, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add Google account linking to databases created with an older schema.
SET @fkksm_google_sub_column_exists = (
  SELECT COUNT(*) FROM information_schema.columns
  WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'google_sub'
);
SET @fkksm_google_sub_column_sql = IF(
  @fkksm_google_sub_column_exists = 0,
  'ALTER TABLE users ADD COLUMN google_sub VARCHAR(255) DEFAULT NULL AFTER email',
  'SELECT 1'
);
PREPARE fkksm_google_sub_column_stmt FROM @fkksm_google_sub_column_sql;
EXECUTE fkksm_google_sub_column_stmt;
DEALLOCATE PREPARE fkksm_google_sub_column_stmt;

SET @fkksm_google_sub_index_exists = (
  SELECT COUNT(*) FROM information_schema.statistics
  WHERE table_schema = DATABASE() AND table_name = 'users' AND index_name = 'uq_users_google_sub'
);
SET @fkksm_google_sub_index_sql = IF(
  @fkksm_google_sub_index_exists = 0,
  'ALTER TABLE users ADD UNIQUE KEY uq_users_google_sub (google_sub)',
  'SELECT 1'
);
PREPARE fkksm_google_sub_index_stmt FROM @fkksm_google_sub_index_sql;
EXECUTE fkksm_google_sub_index_stmt;
DEALLOCATE PREPARE fkksm_google_sub_index_stmt;

CREATE TABLE IF NOT EXISTS courses (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL,
  category VARCHAR(120) DEFAULT NULL,
  summary VARCHAR(500) DEFAULT NULL,
  description TEXT DEFAULT NULL,
  instructor_id BIGINT UNSIGNED DEFAULT NULL,
  duration_hours DECIMAL(6,2) DEFAULT NULL,
  access_type ENUM('free','paid') NOT NULL DEFAULT 'free',
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_courses_slug (slug),
  KEY idx_courses_status (status),
  CONSTRAINT fk_courses_instructor FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS course_modules (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  summary TEXT DEFAULT NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_modules_course_order (course_id, sort_order),
  CONSTRAINT fk_modules_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS materials (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id BIGINT UNSIGNED NOT NULL,
  module_id BIGINT UNSIGNED DEFAULT NULL,
  title VARCHAR(180) NOT NULL,
  content_type ENUM('text','link','file') NOT NULL DEFAULT 'text',
  content_text MEDIUMTEXT DEFAULT NULL,
  content_url VARCHAR(1000) DEFAULT NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_materials_course_order (course_id, sort_order),
  CONSTRAINT fk_materials_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT fk_materials_module FOREIGN KEY (module_id) REFERENCES course_modules(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS enrollments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  course_id BIGINT UNSIGNED NOT NULL,
  status ENUM('active','completed','cancelled') NOT NULL DEFAULT 'active',
  enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  completed_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_enrollment_user_course (user_id, course_id),
  KEY idx_enrollments_course_status (course_id, status),
  CONSTRAINT fk_enrollments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_enrollments_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_progress (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  material_id BIGINT UNSIGNED NOT NULL,
  completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_progress_user_material (user_id, material_id),
  CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_progress_material FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assessments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  assessment_type ENUM('quiz','assignment','evaluation') NOT NULL DEFAULT 'quiz',
  instructions TEXT DEFAULT NULL,
  is_optional TINYINT(1) NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  due_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_assessments_course (course_id),
  CONSTRAINT fk_assessments_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assessment_submissions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  assessment_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  response_text MEDIUMTEXT DEFAULT NULL,
  score DECIMAL(6,2) DEFAULT NULL,
  feedback TEXT DEFAULT NULL,
  status ENUM('submitted','reviewed','returned') NOT NULL DEFAULT 'submitted',
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  reviewed_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_submissions_assessment (assessment_id, status),
  KEY idx_submissions_user (user_id),
  CONSTRAINT fk_submissions_assessment FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
  CONSTRAINT fk_submissions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS certificates (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  certificate_no VARCHAR(80) NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  course_id BIGINT UNSIGNED NOT NULL,
  issued_by BIGINT UNSIGNED DEFAULT NULL,
  is_revoked TINYINT(1) NOT NULL DEFAULT 0,
  issued_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_certificate_no (certificate_no),
  UNIQUE KEY uq_certificate_user_course (user_id, course_id),
  CONSTRAINT fk_certificates_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_issuer FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vouchers (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(80) NOT NULL,
  title VARCHAR(180) NOT NULL,
  benefit_type ENUM('free_access','discount','program_access') NOT NULL DEFAULT 'free_access',
  benefit_description VARCHAR(500) DEFAULT NULL,
  course_id BIGINT UNSIGNED DEFAULT NULL,
  discount_value DECIMAL(12,2) NOT NULL DEFAULT 0,
  max_uses INT UNSIGNED DEFAULT NULL,
  starts_at DATETIME DEFAULT NULL,
  expires_at DATETIME DEFAULT NULL,
  status ENUM('active','paused','expired') NOT NULL DEFAULT 'active',
  created_by BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_vouchers_code (code),
  CONSTRAINT fk_vouchers_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL,
  CONSTRAINT fk_vouchers_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS voucher_redemptions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  voucher_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  redeemed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_redemption_once (voucher_id, user_id),
  KEY idx_redemptions_user (user_id),
  CONSTRAINT fk_redemptions_voucher FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE,
  CONSTRAINT fk_redemptions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS plans (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(140) NOT NULL,
  description TEXT DEFAULT NULL,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  duration_days INT UNSIGNED DEFAULT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_plans_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS subscriptions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  plan_id BIGINT UNSIGNED NOT NULL,
  status ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending',
  starts_at DATETIME DEFAULT NULL,
  ends_at DATETIME DEFAULT NULL,
  created_by BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_subscriptions_user_status (user_id, status),
  CONSTRAINT fk_subscriptions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_subscriptions_plan FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE RESTRICT,
  CONSTRAINT fk_subscriptions_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS leads (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(160) NOT NULL,
  email VARCHAR(190) DEFAULT NULL,
  phone VARCHAR(50) DEFAULT NULL,
  source VARCHAR(140) DEFAULT NULL,
  interest VARCHAR(180) DEFAULT NULL,
  status ENUM('new','contacted','qualified','converted','closed') NOT NULL DEFAULT 'new',
  notes TEXT DEFAULT NULL,
  created_by BIGINT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_leads_status_created (status, created_at),
  CONSTRAINT fk_leads_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  title VARCHAR(180) NOT NULL,
  body TEXT DEFAULT NULL,
  channel ENUM('email','in_app','other') NOT NULL DEFAULT 'in_app',
  read_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_notifications_user_read (user_id, read_at),
  CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(120) NOT NULL,
  setting_value TEXT DEFAULT NULL,
  updated_by BIGINT UNSIGNED DEFAULT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (setting_key),
  CONSTRAINT fk_settings_updater FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activity_logs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED DEFAULT NULL,
  action VARCHAR(160) NOT NULL,
  details TEXT DEFAULT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_logs_created (created_at),
  KEY idx_logs_user (user_id),
  CONSTRAINT fk_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO users (username, email, full_name, password_hash, role, status, must_change_password)
VALUES ('adminfkksm', 'admin@fkksm.local', 'Administrator Utama', '$2y$10$ooTHigQh7ar/11lXxl3ECeshFT6uYAjWFDOoifHK/2ovoxGLresOC', 'super_admin', 'active', 1);

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
  ('notification_channel', 'in_app'),
  ('notification_events', 'course_enrollment,assessment_submission'),
  ('security_session_minutes', '60'),
  ('security_password_min_length', '10');

