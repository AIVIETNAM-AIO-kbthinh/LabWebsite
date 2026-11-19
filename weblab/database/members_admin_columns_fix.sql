-- Optional manual fix if Add New Member still fails because your local members table is older.
-- Run this in phpMyAdmin > database isilab > SQL.
ALTER TABLE `members`
  ADD COLUMN IF NOT EXISTS `slug` VARCHAR(160) NULL AFTER `id`,
  ADD COLUMN IF NOT EXISTS `role` VARCHAR(40) NOT NULL DEFAULT 'student' AFTER `name`,
  ADD COLUMN IF NOT EXISTS `title_en` VARCHAR(200) NULL AFTER `role`,
  ADD COLUMN IF NOT EXISTS `title_vi` VARCHAR(200) NULL AFTER `title_en`,
  ADD COLUMN IF NOT EXISTS `desc_en` TEXT NULL AFTER `title_vi`,
  ADD COLUMN IF NOT EXISTS `desc_vi` TEXT NULL AFTER `desc_en`,
  ADD COLUMN IF NOT EXISTS `image` VARCHAR(500) NULL AFTER `desc_vi`,
  ADD COLUMN IF NOT EXISTS `email` VARCHAR(150) NULL AFTER `image`,
  ADD COLUMN IF NOT EXISTS `scholar` VARCHAR(500) NULL AFTER `email`,
  ADD COLUMN IF NOT EXISTS `linkedin` VARCHAR(500) NULL AFTER `scholar`,
  ADD COLUMN IF NOT EXISTS `github` VARCHAR(500) NULL AFTER `linkedin`,
  ADD COLUMN IF NOT EXISTS `sort_order` INT NOT NULL DEFAULT 0 AFTER `github`,
  ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`;
