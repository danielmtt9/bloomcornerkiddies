-- ==============================================================
-- Bloom Corner Kiddies — Database Schema
-- ==============================================================
-- MariaDB (Hostinger Shared Hosting)
-- Charset: utf8mb4
-- Run via: npm run migrate
-- ==============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- --------------------------------------------------------------
-- TABLE: categories
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: products
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `gender` ENUM('girls', 'boys', 'unisex', 'babies') NOT NULL DEFAULT 'unisex',
  `age_range` VARCHAR(100) NULL COMMENT 'e.g. 0-3M, 3-4Y, 5-6Y',
  `description` TEXT NULL,
  `price` INT UNSIGNED NOT NULL COMMENT 'Price in Naira — whole numbers only',
  `original_price` INT UNSIGNED NULL COMMENT 'Pre-discount price — NULL = not on sale',
  `brand` VARCHAR(100) NULL,
  `material` VARCHAR(100) NULL,
  `season_tag` VARCHAR(100) NULL,
  `tiktok_url` VARCHAR(500) NULL,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_products_gender` (`gender`),
  KEY `idx_products_available` (`is_available`),
  KEY `idx_products_featured` (`is_featured`),
  KEY `idx_products_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: product_images
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `file_path` VARCHAR(500) NOT NULL COMMENT 'Relative to public_html/',
  `sort_order` INT NOT NULL DEFAULT 0 COMMENT '0 = primary photo',
  PRIMARY KEY (`id`),
  KEY `idx_product_images_product` (`product_id`),
  CONSTRAINT `fk_product_images_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: product_sizes
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_sizes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `size_label` VARCHAR(50) NOT NULL COMMENT 'e.g. 3-4Y, S, 0-3M',
  `stock_qty` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_sold_out` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_sizes_product_size` (`product_id`, `size_label`),
  KEY `idx_product_sizes_product` (`product_id`),
  CONSTRAINT `fk_product_sizes_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: product_categories
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`product_id`, `category_id`),
  KEY `idx_product_categories_category` (`category_id`),
  CONSTRAINT `fk_product_categories_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_product_categories_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: seller_config
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `seller_config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_seller_config_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: referral_codes
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `referral_codes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `referrer_name` VARCHAR(255) NOT NULL,
  `referrer_wa` VARCHAR(20) NOT NULL COMMENT 'International format, no +',
  `total_referrals` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_referral_codes_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: referral_uses
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `referral_uses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL COMMENT 'Snapshot reference to referral_codes.code',
  `new_buyer_name` VARCHAR(255) NULL,
  `new_buyer_wa` VARCHAR(20) NULL,
  `discount_percent` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Snapshot at redemption time',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_referral_uses_code` (`code`),
  CONSTRAINT `fk_referral_uses_code`
    FOREIGN KEY (`code`) REFERENCES `referral_codes` (`code`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- SEED DATA
-- ==============================================================

INSERT INTO `categories` (`name`, `slug`, `sort_order`) VALUES
  ('Newborn', 'newborn', 1),
  ('Baby', 'baby', 2),
  ('Toddler', 'toddler', 3),
  ('Girls', 'girls', 4),
  ('Boys', 'boys', 5),
  ('School', 'school', 6),
  ('Occasions', 'occasions', 7),
  ('Pyjamas', 'pyjamas', 8),
  ('Footwear', 'footwear', 9)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `sort_order` = VALUES(`sort_order`);

INSERT INTO `seller_config` (`key`, `value`) VALUES
  ('store_name', 'Bloom Corner Kiddies'),
  ('tagline', 'Beautiful kids wear, ready for your next message.'),
  ('intro_text', 'Welcome to Bloom Corner Kiddies. Browse the catalogue and message us when you find something you love.'),
  ('wa_number', '2349049308656'),
  ('telegram_link', 'https://t.me/placeholder'),
  ('delivery_info', 'Delivery details placeholder. Confirm rates and timing directly with the seller.'),
  ('status_message', 'Replies as soon as possible.'),
  ('payment_info', 'Payment instructions placeholder. Confirm payment details before dispatch.'),
  ('seller_status', 'online'),
  ('referral_discount_percent', '0')
ON DUPLICATE KEY UPDATE
  `key` = `key`;

SET foreign_key_checks = 1;
