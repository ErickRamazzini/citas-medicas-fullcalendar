CREATE DATABASE IF NOT EXISTS citas_medicas_test
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON citas_medicas_test.* TO 'citas_user'@'%';
FLUSH PRIVILEGES;