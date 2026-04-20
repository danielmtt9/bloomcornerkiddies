-- ==============================================================
-- Bloom Corner Kiddies — Database Schema
-- ==============================================================
-- MariaDB (Hostinger Shared Hosting)
-- Charset: utf8mb4 (full emoji support)
-- Run via: npm run migrate
-- ==============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- --------------------------------------------------------------
-- TABLE: categories
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(100) NOT NULL UNIQUE,
  `sort_order` INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: products
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id`             INT             NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(255)    NOT NULL,
  `category_id`    INT             NOT NULL,
  `gender`         ENUM('girls','boys','unisex','babies') NOT NULL DEFAULT 'unisex',
  `age_range`      VARCHAR(100)    NULL COMMENT 'e.g. 0-3M, 3-4Y, 5-6Y',
  `description`    TEXT            NULL,
  `price`          INT UNSIGNED    NOT NULL COMMENT 'Price in Naira — whole numbers only, no decimals',
  `original_price` INT UNSIGNED    NULL     COMMENT 'Pre-discount price — NULL = not on sale',
  `brand`          VARCHAR(100)    NULL,
  `tiktok_url`     VARCHAR(500)    NULL,
  `is_available`   TINYINT(1)      NOT NULL DEFAULT 1,
  `is_featured`    TINYINT(1)      NOT NULL DEFAULT 0,
  `sort_order`     INT             NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category`  (`category_id`),
  KEY `idx_gender`    (`gender`),
  KEY `idx_available` (`is_available`),
  KEY `idx_featured`  (`is_featured`),
  CONSTRAINT `fk_products_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: product_images
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_images` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `product_id` INT          NOT NULL,
  `file_path`  VARCHAR(500) NOT NULL COMMENT 'Relative to public_html/ e.g. uploads/products/42/img1.jpg',
  `sort_order` INT          NOT NULL DEFAULT 0 COMMENT '0 = primary photo',
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
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `product_id`  INT          NOT NULL,
  `size_label`  VARCHAR(50)  NOT NULL COMMENT 'e.g. 3-4Y, S, Medium, 0-3M',
  `stock_qty`   INT UNSIGNED NOT NULL DEFAULT 0,
  `is_sold_out` TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_product_sizes_product` (`product_id`),
  CONSTRAINT `fk_product_sizes_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: seller_config
-- Key-value store for all seller/store settings
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `seller_config` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `key`        VARCHAR(100) NOT NULL UNIQUE,
  `value`      TEXT         NULL,
  `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: bot_sessions
-- Telegram bot conversation state (keyed by Telegram chat_id)
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bot_sessions` (
  `chat_id`      BIGINT       NOT NULL COMMENT 'Telegram chat ID',
  `step`         VARCHAR(50)  NOT NULL DEFAULT 'start',
  `product_id`   INT          NULL,
  `size_label`   VARCHAR(20)  NULL,
  `buyer_name`   VARCHAR(255) NULL,
  `address`      TEXT         NULL,
  `is_gift`      TINYINT(1)   NOT NULL DEFAULT 0,
  `gift_message` TEXT         NULL,
  `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: referral_codes
-- Unique referral codes issued to customers
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `referral_codes` (
  `id`              INT            NOT NULL AUTO_INCREMENT,
  `code`            VARCHAR(50)    NOT NULL UNIQUE COMMENT 'e.g. BCK-SARAH042',
  `referrer_name`   VARCHAR(255)   NOT NULL,
  `referrer_wa`     VARCHAR(20)    NOT NULL COMMENT 'WhatsApp number — international, no +',
  `total_referrals` INT UNSIGNED   NOT NULL DEFAULT 0 COMMENT 'Accumulative successful redemption count',
  `status`          ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE: referral_uses
-- Individual referral code redemptions (one row per use)
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `referral_uses` (
  `id`               INT          NOT NULL AUTO_INCREMENT,
  `code`             VARCHAR(50)  NOT NULL COMMENT 'FK to referral_codes.code',
  `new_buyer_name`   VARCHAR(255) NULL,
  `new_buyer_wa`     VARCHAR(20)  NULL,
  `discount_percent` TINYINT      NOT NULL DEFAULT 0 COMMENT 'Discount % snapshot at time of use',
  `created_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_referral_uses_code` (`code`),
  CONSTRAINT `fk_referral_uses_code`
    FOREIGN KEY (`code`) REFERENCES `referral_codes` (`code`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- SEED DATA
-- ==============================================================

-- Seed categories
INSERT IGNORE INTO `categories` (`name`, `slug`, `sort_order`) VALUES
  ('Girls',   'girls',   1),
  ('Boys',    'boys',    2),
  ('Babies',  'babies',  3),
  ('Unisex',  'unisex',  4);

-- Seed seller_config (placeholder values — update via admin panel)
INSERT IGNORE INTO `seller_config` (`key`, `value`) VALUES
  ('store_name',                'Bloom Corner Kiddies'),
  ('tagline',                   'Premium kids'' clothing, delivered with love 💛'),
  ('intro_text',                'Hi! I''m your personal kids'' stylist. Browse my curated collection of beautiful children''s clothing and message me directly to order.'),
  ('wa_number',                 '2349049308656'),
  ('telegram_link',             'https://t.me/+2349049308656'),
  ('delivery_info',             'Delivery details coming soon. Message us on WhatsApp for current rates.'),
  ('status_message',            'Replies within a few hours 💛'),
  ('payment_info',              'Bank transfer — account details provided on order confirmation'),
  ('seller_status',             'online'),
  ('referral_discount_percent', NULL);

-- Re-enable FK checks
SET foreign_key_checks = 1;
