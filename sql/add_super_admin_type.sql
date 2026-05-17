

ALTER TABLE `users` 
MODIFY COLUMN `user_type` ENUM('super_admin', 'admin', 'staff') NOT NULL DEFAULT 'staff';
