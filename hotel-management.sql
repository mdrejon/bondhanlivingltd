-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table hotel-beach-way.backups
CREATE TABLE IF NOT EXISTS `backups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `error` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backups_created_by_foreign` (`created_by`),
  CONSTRAINT `backups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.backups: ~8 rows (approximately)
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` (`id`, `filename`, `path`, `size`, `status`, `error`, `created_by`, `created_at`, `updated_at`) VALUES
	(4, 'backup_2026-07-06_182523.sql', 'backups/backup_2026-07-06_182523.sql', 89979, 'completed', NULL, 2, '2026-07-06 22:25:23', '2026-07-06 22:25:23'),
	(5, 'backup_2026-07-10_130427.sql', 'backups/backup_2026-07-10_130427.sql', 90309, 'completed', NULL, 2, '2026-07-10 17:04:27', '2026-07-10 17:04:27'),
	(6, 'backup_2026-07-11_085451.sql', 'backups/backup_2026-07-11_085451.sql', 92440, 'completed', NULL, 2, '2026-07-11 12:54:51', '2026-07-11 12:54:51'),
	(7, 'backup_2026-07-13_150009.sql', 'backups/backup_2026-07-13_150009.sql', 105686, 'completed', NULL, 2, '2026-07-13 19:00:09', '2026-07-13 19:00:09'),
	(8, 'backup_2026-07-13_150458_uploaded.sql', 'backups/backup_2026-07-13_150458_uploaded.sql', 105686, 'completed', NULL, 2, '2026-07-13 19:04:58', '2026-07-13 19:04:58'),
	(9, 'backup_2026-07-13_150458_pre_restore.sql', 'backups/backup_2026-07-13_150458_pre_restore.sql', 105687, 'completed', NULL, 2, '2026-07-13 19:04:59', '2026-07-13 19:04:59'),
	(10, 'backup_2026-07-21_190811_uploaded.sql', 'backups/backup_2026-07-21_190811_uploaded.sql', 108333, 'completed', NULL, 2, '2026-07-21 19:08:11', '2026-07-21 19:08:11'),
	(11, 'backup_2026-07-21_190811_pre_restore.sql', 'backups/backup_2026-07-21_190811_pre_restore.sql', 112198, 'completed', NULL, 2, '2026-07-21 19:08:12', '2026-07-21 19:08:12');
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.blogs
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_bio` text COLLATE utf8mb4_unicode_ci,
  `author_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_category_id_foreign` (`category_id`),
  CONSTRAINT `blogs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.blogs: ~2 rows (approximately)
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` (`id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `feature_image`, `og_image`, `tags`, `author_name`, `author_bio`, `author_avatar`, `status`, `published_at`, `is_featured`, `meta_title`, `meta_description`, `meta_keywords`, `view_count`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, NULL, '5 Tips For A Perfect Cox\'s Bazar Beach Vacation', '5-tips-for-a-perfect-coxs-bazar-beach-vacation', 'Hell world', NULL, 'blog/KMofwzVU3uVMKoQpLFozy1dKaSvv8CJXp6ZRq8L0.jpg', NULL, NULL, NULL, NULL, NULL, 'published', '2026-06-21 22:45:00', 0, NULL, NULL, 'tips, perfect, vacation, hell, world', 144, 0, '2026-06-21 22:45:42', '2026-07-21 12:07:43'),
	(3, NULL, '3-Star Experience on a Budget', '3-star-experience-on-a-budget', NULL, NULL, 'blog/YsumbyBHhO2SR6TuZuIWvlfWWNeRU4mDumZapxgR.jpg', NULL, NULL, NULL, NULL, NULL, 'published', '2026-07-03 15:03:00', 0, '3-Star Experience on a Budget – Hotel Beach Way Blog', NULL, 'star, experience, budget', 106, 0, '2026-07-03 15:03:06', '2026-07-20 21:46:08');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.blog_categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.blog_categories: ~0 rows (approximately)
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.blog_comments
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_blog_id_foreign` (`blog_id`),
  KEY `blog_comments_parent_id_foreign` (`parent_id`),
  CONSTRAINT `blog_comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.blog_comments: ~0 rows (approximately)
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
INSERT INTO `blog_comments` (`id`, `blog_id`, `parent_id`, `name`, `email`, `message`, `is_approved`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'Ferris Witt', 'sufoq@mailinator.com', 'Eaque laboris tempor', 0, '2026-06-25 05:08:07', '2026-06-25 05:08:07');
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.booking_followups
CREATE TABLE IF NOT EXISTS `booking_followups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_followups_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booking_followups_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.booking_followups: ~0 rows (approximately)
/*!40000 ALTER TABLE `booking_followups` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_followups` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.booking_logs
CREATE TABLE IF NOT EXISTS `booking_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `action` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performed_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_logs_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booking_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.booking_logs: ~2 rows (approximately)
/*!40000 ALTER TABLE `booking_logs` DISABLE KEYS */;
INSERT INTO `booking_logs` (`id`, `booking_id`, `action`, `description`, `old_value`, `new_value`, `performed_by`, `created_at`, `updated_at`) VALUES
	(37, 35, 'status_changed', 'Status changed to: confirmed', 'pending', 'confirmed', 'Admin', '2026-07-17 08:54:03', '2026-07-17 08:54:03'),
	(46, 38, 'status_changed', 'Status changed to: confirmed', 'pending', 'confirmed', 'Admin', '2026-07-21 20:19:29', '2026-07-21 20:19:29'),
	(48, 38, 'status_changed', 'Status changed to: checked_in', 'confirmed', 'checked_in', 'Admin', '2026-07-21 20:55:29', '2026-07-21 20:55:29');
/*!40000 ALTER TABLE `booking_logs` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.booking_rooms
CREATE TABLE IF NOT EXISTS `booking_rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_booking_id` bigint(20) unsigned NOT NULL,
  `room_type_id` bigint(20) unsigned DEFAULT NULL,
  `room_id` bigint(20) unsigned DEFAULT NULL,
  `adults` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `children` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `nights` smallint(5) unsigned NOT NULL DEFAULT '1',
  `price_per_night` decimal(10,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','confirmed','payment_pending','checked_in','checked_out','no_show','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `actual_check_in_at` timestamp NULL DEFAULT NULL,
  `actual_check_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_rooms_room_booking_id_foreign` (`room_booking_id`),
  KEY `booking_rooms_room_type_id_foreign` (`room_type_id`),
  KEY `booking_rooms_room_id_foreign` (`room_id`),
  CONSTRAINT `booking_rooms_room_booking_id_foreign` FOREIGN KEY (`room_booking_id`) REFERENCES `room_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.booking_rooms: ~4 rows (approximately)
/*!40000 ALTER TABLE `booking_rooms` DISABLE KEYS */;
INSERT INTO `booking_rooms` (`id`, `room_booking_id`, `room_type_id`, `room_id`, `adults`, `children`, `nights`, `price_per_night`, `line_total`, `status`, `actual_check_in_at`, `actual_check_out_at`, `created_at`, `updated_at`) VALUES
	(59, 35, 2, 3, 3, 1, 4, 3600.00, 14400.00, 'pending', '2026-07-21 20:17:39', NULL, '2026-07-16 18:34:25', '2026-07-21 20:43:24'),
	(60, 35, 2, 10, 3, 1, 4, 3600.00, 14400.00, 'pending', '2026-07-21 20:17:39', NULL, '2026-07-16 18:34:25', '2026-07-21 20:43:24'),
	(61, 35, 3, 58, 4, 1, 4, 4200.00, 16800.00, 'pending', '2026-07-21 20:17:39', NULL, '2026-07-16 18:34:25', '2026-07-21 20:43:24'),
	(68, 38, 1, 4, 2, 0, 3, 3000.00, 9000.00, 'checked_in', '2026-07-21 20:55:29', NULL, '2026-07-21 20:18:48', '2026-07-21 20:55:29');
/*!40000 ALTER TABLE `booking_rooms` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Bangladeshi',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_type` enum('nid','passport','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nid',
  `nid_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.customers: ~14 rows (approximately)
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `nationality`, `address`, `document_type`, `nid_number`, `passport_number`, `document_image`, `created_at`, `updated_at`) VALUES
	(1, 'Sydur', '+8801854026305', 'sydurrahmant1@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-06-23 21:15:50', '2026-06-23 21:15:50'),
	(2, 'Sydur Rahman', '01854026305', 'sydurrahman.dev@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-06-23 21:55:48', '2026-07-21 20:18:48'),
	(3, 'Sydur Rahman', '8801854026305', 'sydurrahmant1@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-06-24 18:58:35', '2026-06-24 18:58:35'),
	(4, 'smc', '01863224688', 'info@wexnix.com', 'Bangladeshi', 'Hyperion Shafi Shams, Flat-2C, 1st Floor, 114, Chandmari Road, Lakkhan Bazar Moor, Chattogram.', 'nid', '12345678', NULL, NULL, '2026-06-27 18:16:46', '2026-06-27 18:16:46'),
	(8, 'Sharuf khan', '0111111', 'hotelbeachway@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-06 12:24:07', '2026-07-06 12:24:07'),
	(9, 'Mohd. NaZim Uddin', '01752004641', 'nazim4cox@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-06 14:38:06', '2026-07-06 14:38:06'),
	(10, 'Hasan', '01810251550', 'saidy7cox@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-06 14:38:50', '2026-07-06 14:38:50'),
	(11, 'DELOWER', '01873217915', 'delowerdelower430@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-10 06:00:30', '2026-07-10 06:00:30'),
	(12, 'Mamunul Islam', '01608013125', 'mamunislam26789@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-13 10:30:41', '2026-07-13 10:30:41'),
	(13, 'Shahadat Mahamod Chowdhury', '+8801863224688', 'shahadat.smc@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-13 10:55:31', '2026-07-13 10:55:31'),
	(14, 'Mr. Hasan', '01777909595', 'raz7cox@gmail.com', 'Bangladeshi', 'Cox\'s Bazar Sadar.', 'nid', '8258583528', NULL, NULL, '2026-07-14 04:03:00', '2026-07-14 04:03:00'),
	(15, 'Beach', '01849900000', 'infohotel@gmail.com', 'Bangladeshi', 'Cox\'s Bazar', 'nid', '8523697896', NULL, NULL, '2026-07-15 06:10:49', '2026-07-15 06:10:49'),
	(16, 'Md Baki Billah', '+8801739081564', 'bbillah6@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-16 18:34:25', '2026-07-16 18:36:40'),
	(17, 'Md. Ziaul Haque Shovon', '01709635378', 'ziaulhaque.shovon22@gmail.com', 'Bangladeshi', NULL, 'nid', NULL, NULL, NULL, '2026-07-17 04:17:51', '2026-07-17 04:17:51');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.facilities
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon_svg` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.facilities: ~5 rows (approximately)
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` (`id`, `icon_svg`, `title`, `items`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">\n              <rect x="2" y="7" width="20" height="14" rx="2"></rect>\n              <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"></path>\n              <line x1="12" y1="12" x2="12" y2="16"></line>\n              <line x1="10" y1="14" x2="14" y2="14"></line>\n            </svg>', 'Basic Facilities', '["24 Hours Front Desk", "24 Hours Room Service", "Free Wi-Fi in Rooms & Lobby", "Basement Car Parking"]', 0, 1, '2026-06-22 19:32:42', '2026-06-22 19:37:47'),
	(2, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">\n              <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>\n              <polyline points="9 22 9 12 15 12 15 22"></polyline>\n            </svg>', 'Room Amenities', '["Mini Fridge with Mini Bar", "Running Hot & Cold Water", "Cable LED TV & Intercom", "Air Condition & Fan"]', 0, 1, '2026-06-22 19:33:36', '2026-06-22 19:33:36'),
	(3, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">\n              <path d="M18.36 6.64A9 9 0 015.64 19.36"></path>\n              <path d="M4 12a8 8 0 0112.66-6.51"></path>\n              <line x1="2" y1="12" x2="22" y2="12"></line>\n              <line x1="12" y1="2" x2="12" y2="4"></line>\n              <path d="M20 12c0 4.42-3.58 8-8 8s-8-3.58-8-8"></path>\n            </svg>', 'Dining Options', '["Dew Drop Restaurant", "BBQ & Fresh Seafood", "Complimentary Breakfast", "24 Hours Room Service"]', 0, 1, '2026-06-22 19:34:12', '2026-06-22 19:34:12'),
	(5, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">\n              <rect x="2" y="7" width="20" height="14" rx="1"></rect>\n              <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"></path>\n              <line x1="7" y1="12" x2="7.01" y2="12"></line>\n              <line x1="17" y1="12" x2="17.01" y2="12"></line>\n            </svg>', 'Family Facilities', '["Connecting Rooms Available", "Extra Bed on Request", "Spacious Lobby Lounge", "Spacious Luggage Room"]', 0, 1, '2026-06-22 19:35:24', '2026-06-22 19:35:24'),
	(6, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">\n              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>\n              <polyline points="9 12 11 14 15 10"></polyline>\n            </svg>', 'Additional Services', '["24 Hours Concierge", "Laundry Service", "Airport Pick Up & Drop", "Safe Deposit Box"]', 0, 1, '2026-06-22 19:36:07', '2026-06-22 19:36:07');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.faqs
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page` enum('home','about','faq') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `badge` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FAQS',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_page_index` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.faqs: ~3 rows (approximately)
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` (`id`, `page`, `badge`, `title`, `description`, `image`, `image_alt`, `items`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'home', 'FAQ\'S', 'Frequently Asked Questions', 'Find answers to the most common questions about our hotel, rooms, services, and your stay at Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat.', 'faqs/V9iw9oALVfSN76srHE6dEJaYvynurALU5tNPykKh.jpg', NULL, '[{"question":"Can I Check In Early Or Late At The Hotel?","answer":"Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in is subject to room availability. For any special arrangement please contact our 24\\/7 front desk in advance."},{"question":"Is Breakfast Included In The Room Rate?","answer":"Complimentary breakfast is included in our Executive Suite packages. Other room categories offer optional breakfast add-ons. Our Dew Drop Restaurant serves a full buffet breakfast daily from 7:00 AM to 10:30 AM."},{"question":"Do You Provide Airport Transfer Service?","answer":"Absolutely! We offer a dedicated airport pickup and drop-off service from Cox\'s Bazar Airport, which is just 10 minutes away. Simply share your flight details while booking and our driver will be there to meet you."},{"question":"Is Free Wi-Fi Available Throughout The Hotel?","answer":"Yes, complimentary high-speed Wi-Fi is available in all rooms and public areas including the lobby, restaurant, and conference hall. No password is needed \\u2014 simply connect to the Hotel Beach Way network."}]', 0, 1, '2026-06-21 21:56:22', '2026-06-29 19:48:55'),
	(2, 'about', 'FAQ\'S', 'Frequently Asked Questions', 'Find answers to the most common questions about our hotel, rooms, services, and your stay at Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat.', 'faqs/Dt4xj5mxijr2ifzoEZAeDKOb1w8Sxo15ZROkAXcf.jpg', NULL, '[{"question":"What is the check-in and check-out time?","answer":"Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in and late check-out can be arranged based on availability \\u2014 please contact our 24-hour front desk in advance and we will do our best to accommodate your request."},{"question":"Is breakfast included with the room rate?","answer":"Complimentary breakfast is included in select room packages. We offer a full buffet breakfast at our Dew Drop Restaurant from 7:00 AM to 10:30 AM. Please check your booking details or contact us for your specific package inclusions."},{"question":"Do you offer airport pickup and drop service?","answer":"Yes! We provide convenient airport pickup and drop service from Cox\'s Bazar Airport, which is just 10 minutes away. Simply share your flight details with our team at the time of booking and we will arrange a comfortable transfer for you."},{"question":"What is your cancellation policy?","answer":"We offer free cancellation up to 24 hours before your scheduled check-in date. Cancellations made within 24 hours of check-in may be subject to a one-night charge. For group bookings and special packages, different policies may apply \\u2014 contact us for details."}]', 0, 1, '2026-06-22 18:42:24', '2026-06-29 19:49:12'),
	(3, 'faq', 'FAQ\'S', NULL, NULL, 'faqs/UNg19uLc5wFgRIEqVUT3rdhPyr4bTPoJMwTI5uIG.jpg', NULL, '[{"question":"What is the check-in and check-out time?","answer":"Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in and late check-out can be arranged based on availability \\u2014 please contact our 24-hour front desk in advance."},{"question":"Is breakfast included with the room rate?","answer":"Complimentary breakfast is included in select room packages. We offer a full buffet breakfast at our Dew Drop Restaurant from 7:00 AM to 10:30 AM. Please check your booking details for your specific package inclusions."},{"question":"Do you offer airport pickup and drop service?","answer":"Yes! We provide convenient airport pickup and drop service from Cox\'s Bazar Airport, which is just 10 minutes away. Simply share your flight details with our team at the time of booking and we will arrange a comfortable transfer for you."},{"question":"What is your cancellation policy?","answer":"We offer free cancellation up to 24 hours before your scheduled check-in date. Cancellations made within 24 hours of check-in may be subject to a one-night charge. For group bookings and special packages, different policies may apply \\u2014 contact us for details."}]', 0, 1, '2026-06-30 22:54:32', '2026-06-30 22:54:32');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.gallery_images
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.gallery_images: ~21 rows (approximately)
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` (`id`, `image`, `alt`, `caption`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(13, 'gallery/4qfUTfAYLjA51Esxzlv1Jz5MRBpTI0nRMullCB53.jpg', '', NULL, 0, 1, '2026-06-27 19:04:19', '2026-07-18 08:15:21'),
	(14, 'gallery/0tMxDlMRtGN4zcoyoT2eIjKnaSxtG0jxA55YysAE.jpg', '', NULL, 1, 2, '2026-06-27 19:04:19', '2026-06-27 19:05:12'),
	(15, 'gallery/SkH1iArzrLZ2XzrZzSdMnVwvbgVGSOJQ8Kbmq8QS.jpg', '', NULL, 1, 3, '2026-06-27 19:04:19', '2026-06-27 19:05:12'),
	(16, 'gallery/3L3k0jDbPn9XiqkPHCjO7Yobn1MKosUS9nP0fatd.jpg', '', NULL, 1, 4, '2026-06-27 19:04:19', '2026-06-27 19:05:12'),
	(18, 'gallery/kuPSUud696AwhsG57JuIJLBR7TuRxIoro2lBq4Ci.jpg', '', NULL, 1, 5, '2026-06-27 19:04:19', '2026-06-27 19:05:12'),
	(19, 'gallery/isVS4Z8rwrTOhTOWrzSPc0pAef7U9j5CQLf7liNQ.jpg', '', NULL, 1, 6, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(20, 'gallery/jA4W6WhpJLwcIJGmlaTLL807gLXET99xQCakkISM.jpg', '', NULL, 1, 7, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(21, 'gallery/tGkqUP4I2sgVdrs3U8u3FqDze0lsDQxp7cBxXc2a.jpg', '', NULL, 1, 8, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(22, 'gallery/KcTVUqcIIdygStfPVmatHSRJU1InaudJadD4r7Lx.jpg', '', NULL, 1, 9, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(23, 'gallery/8aAhAdTKHXoUuoakHCjgAw589mds3qcQJ5GZbQcP.jpg', '', NULL, 1, 10, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(24, 'gallery/5CY7OjRgGhnGkNyFAkLQp0ietwzwvZR83icRLJPD.jpg', '', NULL, 1, 11, '2026-07-18 07:59:28', '2026-07-18 07:59:28'),
	(25, 'gallery/bvtlkvdiOPvcj7ZVjQCp7pDzZ1VR5w2dvDDAUJIJ.jpg', '', NULL, 1, 12, '2026-07-18 08:04:52', '2026-07-18 08:04:52'),
	(26, 'gallery/I1PqEHVAR7SJjSQJiJxU8NrVLGATeGF3Y8hYB34J.jpg', '', NULL, 1, 13, '2026-07-18 08:04:52', '2026-07-18 08:04:52'),
	(27, 'gallery/wkqHW5D267DLJNb29q36kuRBnZ38TNWLDjp9Q3C4.jpg', '', NULL, 1, 14, '2026-07-18 08:04:52', '2026-07-18 08:04:52'),
	(28, 'gallery/Dj1tmZzbYgw2WjNf0DFzzoOXoUV1dCroPSUFryFO.jpg', '', NULL, 1, 15, '2026-07-18 08:04:52', '2026-07-18 08:04:52'),
	(29, 'gallery/jZMNr8Nfsry3XlvfYUlW0T81jY0dXfnWCadZNHLd.jpg', '', NULL, 1, 16, '2026-07-18 08:08:46', '2026-07-18 08:08:46'),
	(30, 'gallery/KmxezX3VATIPLSQbXZE75XUHvT91O9eVZr4k3zMN.jpg', '', NULL, 1, 17, '2026-07-18 08:08:46', '2026-07-18 08:08:46'),
	(31, 'gallery/AXJyklwgpMVXhuJfdWYzGfZsaJoyvrXYyNWZs2j3.jpg', '', NULL, 1, 18, '2026-07-18 08:08:46', '2026-07-18 08:08:46'),
	(32, 'gallery/1pyJ8LFZoBPG6MrxJjYym2QFr7kYsrCFJEBmEPk7.jpg', '', NULL, 1, 19, '2026-07-18 08:08:46', '2026-07-18 08:08:46'),
	(33, 'gallery/G6TCbmFfcDCVn7j4EhSwrvlPvb14odXz7ctRDWjg.jpg', '', NULL, 1, 20, '2026-07-18 08:08:46', '2026-07-18 08:08:46'),
	(34, 'gallery/LmvDng9d9cPhFa6ePlB5g0x5i8fUjlos8OTcWcTn.jpg', '', NULL, 1, 21, '2026-07-18 08:08:46', '2026-07-18 08:08:46');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.global_settings
CREATE TABLE IF NOT EXISTS `global_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `global_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.global_settings: ~164 rows (approximately)
/*!40000 ALTER TABLE `global_settings` DISABLE KEYS */;
INSERT INTO `global_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'header_phone', '+88 01777-909595', '2026-06-14 18:28:49', '2026-06-14 18:32:45'),
	(2, 'header_email', 'infohotelbeachway@gmail.com', '2026-06-14 18:28:49', '2026-07-10 05:35:48'),
	(3, 'header_address', 'Kolatoli Road, Cox\'s Bazar, Bangladesh', '2026-06-14 18:28:49', '2026-06-14 18:28:49'),
	(4, 'header_facebook_url', 'https://www.facebook.com/HotelBeachWay', '2026-06-14 18:28:49', '2026-07-13 10:46:11'),
	(5, 'header_twitter_url', '#', '2026-06-14 18:28:49', '2026-06-14 18:28:49'),
	(6, 'header_instagram_url', '#', '2026-06-14 18:28:49', '2026-06-14 18:28:49'),
	(7, 'header_pinterest_url', '#', '2026-06-14 18:28:49', '2026-06-14 18:28:49'),
	(8, 'header_book_btn_text', 'Book Online', '2026-06-14 18:28:49', '2026-06-14 18:28:49'),
	(9, 'header_book_btn_url', '/booking', '2026-06-14 18:28:49', '2026-06-23 20:54:50'),
	(10, 'header_logo', 'settings/6c2ZDBF1I1nZZzozeObD24mBhAc0FH8lFPImMDrq.png', '2026-06-14 18:29:34', '2026-06-14 18:29:34'),
	(11, 'footer_logo', 'settings/CgkQxoVluakmMq4AhRhQZy9W1hley512KptUVUcp.png', '2026-06-14 19:04:13', '2026-06-14 19:04:13'),
	(12, 'footer_brand_description', 'A 3-star boutique hotel near Kolatoli Beach with eco-friendly infrastructure, skilled staff & world-class hospitality at affordable prices in Cox\'s Bazar.', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(13, 'footer_facebook_url', 'https://www.facebook.com/HotelBeachWay', '2026-06-14 19:04:13', '2026-07-13 10:46:02'),
	(14, 'footer_twitter_url', '#', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(15, 'footer_instagram_url', '#', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(16, 'footer_youtube_url', '#', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(17, 'footer_phone_1', '+880 1777-909595', '2026-06-14 19:04:13', '2026-07-10 07:04:30'),
	(18, 'footer_phone_2', '+880 1849-900000', '2026-06-14 19:04:13', '2026-07-10 07:04:30'),
	(19, 'footer_phone_3', NULL, '2026-06-14 19:04:13', '2026-07-10 07:04:30'),
	(20, 'footer_email_1', 'infohotelbeachway@gmail.com', '2026-06-14 19:04:13', '2026-07-10 07:04:30'),
	(21, 'footer_email_2', NULL, '2026-06-14 19:04:13', '2026-07-10 07:04:30'),
	(22, 'footer_address_line1', 'House #21, Block #C, Kolatoli Road', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(23, 'footer_address_line2', 'Cox\'s Bazar, Bangladesh', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(24, 'footer_website_url', 'www.hotelbeachway.com', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(25, 'footer_newsletter_title', 'Stay Updated With Hotel Beach Way Special Offers & Events', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(26, 'footer_privacy_url', '#', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(27, 'footer_terms_url', '#', '2026-06-14 19:04:13', '2026-06-21 22:17:56'),
	(28, 'about_deco_badge_value', '24/7', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(29, 'about_deco_badge_label', 'Front Desk', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(30, 'about_deco_years_value', '13+', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(31, 'about_deco_years_label', 'Years of Trusted Service', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(32, 'about_badge', '3-Star Boutique Hotel', '2026-06-21 11:32:21', '2026-07-10 05:42:59'),
	(33, 'about_title', '3-Star Boutique Hotel  <br> Near Kolatoli Beach', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(34, 'about_desc', '<p>Hotel Beach Way is a modern 3-star boutique hotel near Kolatoli Beach in Cox\'s Bazar, offering eco-friendly infrastructure, skilled staff, and world-class hospitality at affordable prices. Featuring well-furnished rooms with branded fixtures for a comfortable stay, the hotel is conveniently located just 10 minutes from Cox\'s Bazar Airport and the local bus terminal, and only a 5-minute walk from Kolatoli Circle, making it an ideal choice for both business and leisure travelers.</p>', '2026-06-21 11:32:21', '2026-07-10 05:42:05'),
	(35, 'about_main_image', 'about/CSoQjIKG0ZdvUeaK3L9AuLNs77uoF8YaaUSmo0fn.jpg', '2026-06-21 11:32:21', '2026-07-13 13:32:48'),
	(36, 'about_main_image_alt', NULL, '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(37, 'about_top_image', 'about/G9yRuswl8goxC0xts7yhxBLnkung2PIjqsiukDHO.jpg', '2026-06-21 11:32:21', '2026-07-03 14:44:44'),
	(38, 'about_top_image_alt', NULL, '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(39, 'about_mission_label', 'Our Mission', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(40, 'about_mission_text', '<p>We are committed to providing our guests with world-class hospitality within an affordable price. Eco-friendly infrastructure, skilled staff &amp; world-class service are our key features — making us a unique service provider in Cox\'s Bazar for families, honeymoon couples, corporate groups &amp; students.</p><p></p><p></p>', '2026-06-21 11:32:21', '2026-07-03 14:44:44'),
	(41, 'about_vision_label', 'Our Vision', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(42, 'about_vision_text', '<p>Our vision is to be the most sought-after luxury hotel in Cox\'s Bazar, setting the standard for hospitality excellence, sustainability, and guest satisfaction across Bangladesh.</p>', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(43, 'about_history_label', 'Hotel Overview', '2026-06-21 11:32:21', '2026-07-10 05:45:57'),
	(44, 'about_history_text', '<p>Founded in 2013, Hotel Beach Way has grown into one of Cox\'s Bazar\'s premier destinations, earning recognition for authentic hospitality and an unwavering commitment to excellence.</p>', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(45, 'about_more_btn_text', 'More About', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(46, 'about_more_btn_url', '#', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(47, 'about_video_url', '#', '2026-06-21 11:32:21', '2026-06-22 18:37:17'),
	(48, 'why_badge', 'WHY CHOOSE US', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(49, 'why_title', 'The Hotel Beach Way Difference', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(50, 'why_desc', 'We combine world-class hospitality with genuine warmth and eco-friendly values — making every stay truly memorable.', '2026-06-21 11:32:21', '2026-06-22 18:37:17'),
	(51, 'about_faq_badge', 'FAQ', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(52, 'about_faq_title', 'Frequently Asked Questions', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(53, 'about_faq_desc', NULL, '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(54, 'about_mission_features', '["Complimentary Breakfast & Welcome Drinks","Free Wi-Fi in Lobby & All Rooms","Basement Car Parking","24 Hours Front Desk & Room Service","Airport Pick Up & Drop Service","24 Hours CCTV Security"]', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(55, 'about_vision_features', '["World-Class Amenities","Guest-First Philosophy","Community Engagement","Eco-Friendly Practices","Sustainable Tourism","Award-Winning Service"]', '2026-06-21 11:32:21', '2026-06-21 11:32:21'),
	(56, 'about_history_features', '["Established in 2013","1000+ Happy Guests Monthly","3 Star Boutique Hotel","13+ Years of Service","5 Types Of Rooms","Make Your Stay Relaxing & Memorable"]', '2026-06-21 11:32:21', '2026-07-10 05:45:57'),
	(57, 'about_stats', '[{"value":"150","suffix":"+","label":"Happy Customer"},{"value":"42","suffix":"+","label":"Special Rooms"},{"value":null,"suffix":"+","label":"Expert Teams"},{"value":"10","suffix":"+","label":"Awards Win"}]', '2026-06-21 11:32:21', '2026-07-13 13:32:48'),
	(58, 'svc_badge', 'OUR SERVICES', '2026-06-21 21:39:54', '2026-06-21 21:39:54'),
	(59, 'svc_title', 'Key Features & Facilities', '2026-06-21 21:39:54', '2026-07-15 10:42:53'),
	(60, 'svc_desc', 'Located in the heart of Cox\'s Bazar, minutes from the beach — offering 58 guestrooms with modern amenities, multi-cuisine Dew Drop Restaurant, Banquet Hall , Conference & Meeting rooms.', '2026-06-21 21:39:54', '2026-07-15 10:42:53'),
	(61, 'svc_btn_text', 'View All Services', '2026-06-21 21:39:54', '2026-06-21 21:54:07'),
	(62, 'svc_btn_url', NULL, '2026-06-21 21:39:54', '2026-06-21 21:39:54'),
	(63, 'testi_badge', 'TESTIMONIALS', '2026-06-21 22:11:10', '2026-06-21 22:11:10'),
	(64, 'testi_title', 'Hear From Our Satisfied Customers', '2026-06-21 22:11:10', '2026-06-21 22:11:10'),
	(65, 'testi_image', 'settings/rc5IBGW9z37HRUvzeSHa9Vi5fGfsoWWaTeYwBEF2.jpg', '2026-06-21 22:11:10', '2026-06-27 19:06:58'),
	(66, 'testi_image_alt', 'Happy guests at Hotel Beach Way', '2026-06-21 22:11:10', '2026-06-21 22:11:10'),
	(67, 'footer_quick_links', '[{"label":"About Us","url":"\\/about"},{"label":"Rooms & Suites","url":"\\/rooms"},{"label":"Facilities & Services","url":"\\/service"},{"label":"Gallery","url":"\\/gallery"},{"label":"Contact Us","url":"\\/contact"}]', '2026-06-21 22:17:56', '2026-06-21 22:17:56'),
	(68, 'footer_service_links', '[{"label":"Dew Drop Restaurant","url":"#"},{"label":"Conference & Banquet","url":"#"},{"label":"Airport Pickup Service","url":"#"}]', '2026-06-21 22:18:47', '2026-06-21 22:18:47'),
	(69, 'about_hero_title', 'Profile', '2026-06-22 18:23:46', '2026-07-13 14:41:56'),
	(70, 'about_seo_title', NULL, '2026-06-22 18:23:46', '2026-06-22 18:23:46'),
	(71, 'about_seo_description', '<p>Hotel Beach Way is a modern 3-star boutique hotel near Kolatoli Beach in Cox\'s Bazar, offering eco-friendly infrastructure, skilled staff, and world-class hospitality at affordable prices. Featuring well-furnished rooms with branded fixtures for a comfortable stay, the hotel is conveniently located just 10 minutes from Cox\'s Bazar Airport and the local bus terminal, and only a 5-minute walk from Kolatoli Circle, making it an ideal choice for both business and leisure travelers.</p>', '2026-06-22 18:23:46', '2026-07-10 05:42:05'),
	(72, 'about_seo_keywords', 'star, boutique, near, kolatoli', '2026-06-22 18:23:46', '2026-07-03 14:34:30'),
	(73, 'about_hero_image', 'about/UaesvD0XjmQFR1XlVCYhJlEIUpx93Us8KeF4wQ4J.jpg', '2026-06-22 18:37:17', '2026-07-03 14:44:44'),
	(74, 'about_video_thumb', 'about/Kpc8S9yR7FnwkMM6ydGu9jk17v08wUyW5dsSDjFi.jpg', '2026-06-22 18:37:17', '2026-06-29 19:48:23'),
	(75, 'why_features', '[{"icon_svg":"<svg xmlns=\\"http:\\/\\/www.w3.org\\/2000\\/svg\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"1.5\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\">\\r\\n              <path d=\\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\\"><\\/path>\\r\\n            <\\/svg>","title":"Keeping You Safe","description":"24\\/7 CCTV security surveillance throughout the hotel premises, safe deposit boxes at the front desk, a standby generator, and fully trained staff \\u2014 so you always feel secure during your stay."},{"icon_svg":"<svg xmlns=\\"http:\\/\\/www.w3.org\\/2000\\/svg\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"1.5\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\">\\r\\n              <circle cx=\\"12\\" cy=\\"12\\" r=\\"10\\"><\\/circle>\\r\\n              <polyline points=\\"12 6 12 12 16 14\\"><\\/polyline>\\r\\n              <path d=\\"M21.5 5.5a2 2 0 01-2 2\\"><\\/path>\\r\\n            <\\/svg>","title":"Flexible Cancellation","description":"Free cancellation up to 72 hours before check-in. All rates include Service Charges & VAT. Check-in: 1:00 PM | Check-out: 11:00 AM. Express check-in & check-out available."},{"icon_svg":"<svg xmlns=\\"http:\\/\\/www.w3.org\\/2000\\/svg\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"1.5\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\">\\r\\n              <path d=\\"M2 9h20M2 15h20M5 9V5a2 2 0 012-2h10a2 2 0 012 2v4M5 15v4a2 2 0 002 2h10a2 2 0 002-2v-4\\"><\\/path>\\r\\n              <path d=\\"M9 12h6\\"><\\/path>\\r\\n            <\\/svg>","title":"Full Room Amenities","description":"Every room has A\\/C & Fan, Cable LED TV, Mini Fridge with Mini Bar, Running Hot & Cold Water, Intercom, complimentary Mineral Water , Room Amenities, and hotel-wide high-speed Wi-Fi."}]', '2026-06-22 18:37:17', '2026-07-13 13:32:48'),
	(76, 'hist_hero_title', 'Our History', '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(77, 'hist_badge', 'OUR JOURNEY', '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(78, 'hist_title', 'A Decade of Warmth,<br>Hospitality & Excellence', '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(79, 'hist_desc', NULL, '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(80, 'hist_seo_title', NULL, '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(81, 'hist_seo_description', NULL, '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(82, 'hist_seo_keywords', NULL, '2026-06-22 19:16:18', '2026-06-22 19:16:18'),
	(83, 'hist_timeline', '[{"year":"2013","tag":"Foundation","heading":"Grand Opening \\u2014 Hotel Beach Way Is Born","content":"With a vision to redefine coastal hospitality, Hotel Beach Way opened its doors on Kolatoli Road, Cox\'s Bazar in 2013.","badges":["30 Rooms Launched","24\\/7 Front Desk","Kolatoli Beach Access"],"image":"history\\/timeline\\/0B4QUGVeLLWEyQVg7nu1fmn450hiVuyeUxzDW8rg.jpg","reversed":false},{"year":"2015","tag":"Dining","heading":"Dew Drop Restaurant Opens Its Doors","content":"In 2015, Hotel Beach Way unveiled the Dew Drop Restaurant \\u2014 a full-service dining experience offering local Bangladeshi flavors and international cuisine.","badges":["Dew Drop Restaurant","Buffet Breakfast","BBQ Evenings"],"image":"history\\/timeline\\/qSkSruiSPx5pO0nRqDHkTna7tT64gBgdNsqbTQEu.jpg","reversed":true},{"year":"2017","tag":"Expansion","heading":"Premium Room Renovation & Suite Expansion","content":"2017 marked a major transformation. All rooms were upgraded and a new wing introduced the Honeymoon Suite and Executive Suite.","badges":["Honeymoon Suite Added","Executive Suite","Smart Room Upgrades"],"image":"history\\/timeline\\/bvVtvfWhXul0LjnwUGBbskTYaMmNazTwImAbfJu3.jpg","reversed":false},{"year":"2025","tag":"A Decade & Beyond","heading":"Celebrating Excellence \\u2014 The Journey Continues","content":"Over a decade after opening our doors, Hotel Beach Way stands as a symbol of warmth, quality, and coastal luxury in Cox\'s Bazar.","badges":["10+ Years of Service","10,000+ Happy Guests","Continuously Growing"],"image":"history\\/timeline\\/T6pu8izRTGWdMEn6Qi0yViTs51IaDSxGufe2Lg4T.jpg","reversed":true}]', '2026-06-22 19:16:18', '2026-06-29 19:52:17'),
	(84, 'fac_badge', 'FACILITIES', '2026-06-22 19:36:11', '2026-06-22 19:36:11'),
	(85, 'fac_title', 'Hotel\'s Facilities', '2026-06-22 19:36:11', '2026-06-22 19:36:11'),
	(86, 'fac_hero_title', NULL, '2026-06-22 19:48:43', '2026-06-22 19:48:43'),
	(87, 'fac_seo_title', NULL, '2026-06-22 19:48:43', '2026-06-22 19:48:43'),
	(88, 'fac_seo_description', NULL, '2026-06-22 19:48:43', '2026-06-22 19:48:43'),
	(89, 'fac_seo_keywords', NULL, '2026-06-22 19:48:43', '2026-06-22 19:48:43'),
	(90, 'fac_hero_image', 'settings/vA77YsXqhPmOTuUpgiORnbtSLwtGxlNq9AAyiSuB.jpg', '2026-06-22 19:48:43', '2026-06-30 22:50:52'),
	(91, 'gallery_hero_title', NULL, '2026-06-22 20:59:33', '2026-06-22 20:59:33'),
	(92, 'gallery_seo_title', NULL, '2026-06-22 20:59:33', '2026-06-22 20:59:33'),
	(93, 'gallery_seo_description', NULL, '2026-06-22 20:59:33', '2026-06-22 20:59:33'),
	(94, 'gallery_seo_keywords', NULL, '2026-06-22 20:59:33', '2026-06-22 20:59:33'),
	(95, 'gallery_hero_image', 'settings/b3kX0qe8fiF9faWw7JlDzGW8MeMF0tAYph3b6sj6.jpg', '2026-06-22 20:59:33', '2026-07-18 08:13:23'),
	(96, 'contact_hero_title', NULL, '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(97, 'contact_hero_image', 'contact/fvLGNMCMCI1B9o75R6VNbyiTmAiLbMAk7YDrsP0H.jpg', '2026-06-22 21:59:34', '2026-06-22 21:59:44'),
	(98, 'contact_seo_title', NULL, '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(99, 'contact_seo_description', NULL, '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(100, 'contact_seo_keywords', '', '2026-06-22 21:59:34', '2026-07-03 16:14:34'),
	(101, 'contact_form_badge', 'SEND US EMAIL', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(102, 'contact_form_title', 'Feel Free To Write', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(103, 'contact_form_btn_text', 'Send Message', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(104, 'contact_agent_name', 'Hotel Beach Way', '2026-06-22 21:59:34', '2026-07-03 16:14:34'),
	(105, 'contact_agent_desc', 'We are the dedicated Reservation Team at Hotel Beach Way, here to answer all your questions and\r\nassist with your booking needs around the clock.', '2026-06-22 21:59:34', '2026-07-10 12:35:14'),
	(106, 'contact_agent_whatsapp', 'https://wa.me/8801777909595', '2026-06-22 21:59:34', '2026-07-03 16:14:57'),
	(107, 'contact_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2432.6336149740646!2d91.9828191!3d21.419817699999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30adc629e6275e73%3A0x7d772e42b73d7824!2sHotel%20Beach%20Way!5e1!3m2!1sen!2sbd!4v1784309540090!5m2!1sen!2sbd', '2026-06-22 21:59:34', '2026-07-17 17:32:56'),
	(108, 'sidebar_about_title', 'About Us', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(109, 'sidebar_about_text', NULL, '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(110, 'sidebar_quote_title', 'Get A Free Quote', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(111, 'sidebar_quote_btn_text', 'Submit Now', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(112, 'contact_widget_title', 'Have a query?', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(113, 'contact_widget_subtitle', NULL, '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(114, 'contact_widget_btn_text', 'Send Message', '2026-06-22 21:59:34', '2026-06-22 21:59:34'),
	(115, 'svc_page_hero_image', 'services-settings/aDs3Fnu9CFUm8plGCzuRTnf5GrGsFUkPY2YYOIfX.jpg', '2026-06-23 19:15:15', '2026-06-25 04:55:15'),
	(116, 'svc_page_hero_title', 'Service', '2026-06-23 19:15:15', '2026-06-23 19:15:15'),
	(117, 'svc_seo_title', 'Key Features & Facilities – Hotel Beach Way', '2026-06-23 19:15:15', '2026-07-15 10:42:53'),
	(118, 'svc_seo_description', 'Located in the heart of Cox\'s Bazar, minutes from the beach — offering 58 guestrooms with modern amenities, multi-cuisine Dew Drop Restaurant, Banquet Hall , Conference & Meeting rooms.', '2026-06-23 19:15:15', '2026-07-15 10:42:53'),
	(119, 'svc_seo_keywords', 'features, services, located, heart, minutes, offering, guestrooms, modern, amenities, multi, cuisine, drop', '2026-06-23 19:15:15', '2026-07-10 12:36:44'),
	(120, 'svc_help_title', NULL, '2026-06-25 04:27:47', '2026-06-25 04:27:47'),
	(121, 'svc_help_desc', NULL, '2026-06-25 04:27:47', '2026-06-25 04:27:47'),
	(122, 'about_seo_og_image', 'about/xocQgi7G5OhcMyBGqrjoy3CS7WZitVxWueR7ntt4.jpg', '2026-06-29 19:25:45', '2026-07-03 14:44:44'),
	(123, 'hist_hero_image', 'history/SjUxV50iZ97MVdeQd1tWdlqLL6ICZQXOUGiJgKfL.jpg', '2026-06-29 19:52:17', '2026-06-29 19:52:17'),
	(124, 'og_watermark_image', 'og-watermark/GhszGJX4dTq8HEb8kj9RdjUh3WvpLNQOvVrlyRPu.png', '2026-06-30 17:17:48', '2026-06-30 17:17:48'),
	(125, 'og_watermark_position', 'bottom-right', '2026-06-30 17:17:48', '2026-06-30 17:17:48'),
	(126, 'og_watermark_opacity', '80', '2026-06-30 17:17:48', '2026-06-30 17:17:48'),
	(127, 'og_watermark_padding', '20', '2026-06-30 17:17:48', '2026-06-30 17:17:48'),
	(128, 'og_watermark_size', '25', '2026-06-30 17:17:48', '2026-06-30 17:17:48'),
	(129, 'mail_enabled', '0', '2026-07-01 18:09:42', '2026-07-21 20:18:15'),
	(130, 'mail_driver', 'smtp', '2026-07-01 18:09:42', '2026-07-01 18:09:42'),
	(131, 'mail_host', 'mail.hotelbeachway.com', '2026-07-01 18:09:42', '2026-07-06 18:28:54'),
	(132, 'mail_port', '465', '2026-07-01 18:09:42', '2026-07-06 18:28:54'),
	(133, 'mail_encryption', 'ssl', '2026-07-01 18:09:42', '2026-07-06 18:28:54'),
	(134, 'mail_username', 'reservation@hotelbeachway.com', '2026-07-01 18:09:42', '2026-07-06 18:28:54'),
	(135, 'mail_password', 'HD@y6tk8_U', '2026-07-01 18:09:42', '2026-07-06 18:33:21'),
	(136, 'mail_from_address', 'reservation@hotelbeachway.com', '2026-07-01 18:09:42', '2026-07-06 18:29:08'),
	(137, 'mail_from_name', 'Hotel Beach Way', '2026-07-01 18:09:42', '2026-07-01 18:09:42'),
	(138, 'mail_admin_emails', '["reservation@hotelbeachway.com","Infohotelbeachway@gmail.com","sydurrahmant1@gmail.com","shahadat.smc@gmail.com"]', '2026-07-01 18:09:42', '2026-07-07 08:04:35'),
	(139, 'blog_hero_title', NULL, '2026-07-03 16:11:59', '2026-07-03 16:11:59'),
	(140, 'blog_seo_title', NULL, '2026-07-03 16:11:59', '2026-07-03 16:11:59'),
	(141, 'blog_seo_description', NULL, '2026-07-03 16:11:59', '2026-07-03 16:11:59'),
	(142, 'blog_seo_keywords', '', '2026-07-03 16:11:59', '2026-07-03 16:11:59'),
	(143, 'blog_hero_image', 'settings/nHYk2cJiaPH6F7nV00zDF6BZB6PjSWpg5NgUoyYX.jpg', '2026-07-03 16:11:59', '2026-07-03 16:11:59'),
	(144, 'contact_agent_avatar', 'contact/ny5AeaULqYksrMTgMRhfhF8WLDsYqNLubxyl07xd.png', '2026-07-03 16:14:34', '2026-07-13 14:59:50'),
	(145, 'faq_hero_title', NULL, '2026-07-03 16:16:29', '2026-07-03 16:16:29'),
	(146, 'faq_seo_title', NULL, '2026-07-03 16:16:29', '2026-07-03 16:16:29'),
	(147, 'faq_seo_description', NULL, '2026-07-03 16:16:29', '2026-07-03 16:16:29'),
	(148, 'faq_seo_keywords', '', '2026-07-03 16:16:29', '2026-07-03 16:16:29'),
	(149, 'faq_hero_image', 'settings/ycYVxz3ih9HJgMKlSvDbluZ5KpMT0DNKFAZcrkyA.jpg', '2026-07-03 16:16:29', '2026-07-03 16:16:29'),
	(150, 'rooms_hero_title', NULL, '2026-07-13 14:24:52', '2026-07-13 14:24:52'),
	(151, 'rooms_seo_title', NULL, '2026-07-13 14:24:52', '2026-07-13 14:24:52'),
	(152, 'rooms_seo_description', NULL, '2026-07-13 14:24:52', '2026-07-13 14:24:52'),
	(153, 'rooms_seo_keywords', '', '2026-07-13 14:24:52', '2026-07-13 14:24:52'),
	(154, 'rooms_badge', NULL, '2026-07-13 14:24:52', '2026-07-13 14:24:52'),
	(155, 'rooms_title', 'Book Your Stay-Relax in Boutique Hotel', '2026-07-13 14:24:52', '2026-07-15 10:37:51'),
	(156, 'booking_hero_title', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(157, 'booking_seo_title', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(158, 'booking_seo_description', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(159, 'booking_seo_keywords', '', '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(160, 'booking_child_policy_note', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(161, 'booking_cancellation_policy', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(162, 'booking_assist_phone', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(163, 'booking_assist_email', NULL, '2026-07-13 14:31:55', '2026-07-13 14:31:55'),
	(164, 'gallery_seo_og_image', 'settings/WiRuzykrqhLcM3g2DwpeIGJnrvCaaAfLai7S8Y2d.jpg', '2026-07-18 08:12:45', '2026-07-18 08:13:23');
/*!40000 ALTER TABLE `global_settings` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.inquiries
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('quote','contact_widget','contact_page') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contact_page',
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.inquiries: ~32 rows (approximately)
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
INSERT INTO `inquiries` (`id`, `type`, `name`, `email`, `phone`, `subject`, `message`, `status`, `notes`, `ip_address`, `created_at`, `updated_at`) VALUES
	(1, 'contact_page', 'Vera Lawrence', 'gurecotil@mailinator.com', '+1 (109) 932-8837', 'Rerum est eum aut od', 'Ullam eveniet obcae', 'read', NULL, '127.0.0.1', '2026-06-22 22:20:32', '2026-06-22 22:34:32'),
	(2, 'contact_page', 'Emerson Underwood', 'typa@mailinator.com', '+1 (727) 257-2581', 'Irure suscipit cum m', 'Molestiae id aliquam', 'new', NULL, '127.0.0.1', '2026-06-23 21:53:45', '2026-06-23 21:53:45'),
	(3, 'contact_page', 'Fletcher Lowe', 'masomuqaja@mailinator.com', '+1 (762) 281-6736', 'Exercitation est rep', 'Quia magna adipisici', 'new', NULL, '127.0.0.1', '2026-06-23 21:54:36', '2026-06-23 21:54:36'),
	(4, 'contact_page', 'Yuli Hutchinson', 'xeguje@mailinator.com', '+1 (133) 369-3568', 'Et reprehenderit est', 'Sed dolor ut quam ma', 'read', NULL, '127.0.0.1', '2026-06-23 22:21:07', '2026-06-27 16:22:03'),
	(5, 'quote', 'Shahadat Mahamod Chowdhury', 'info@wexnix.com', NULL, NULL, 'jm,.', 'new', NULL, '103.200.95.105', '2026-06-27 18:11:06', '2026-06-27 18:11:06'),
	(6, 'contact_page', 'Tasha Huckstep', 'tasha.huckstep@gmail.com', '890832686', 'Drive more visitors to hotelbeachway.com with our proven AI powered traffic system.', 'Save big with our AI-driven service, providing location-targeted traffic at a fraction of the cost of paid advertising platforms. Contact us to begin.\r\n\r\nhttps://cutt.ly/Vt4CHSQG', 'new', NULL, '38.141.62.120', '2026-07-01 02:26:13', '2026-07-01 02:26:13'),
	(7, 'contact_page', 'Nicholas Brunskill', 'nicholas.brunskill@gmail.com', '512681287', 'Drive more visitors to hotelbeachway.com with our tested AI powered traffic system.', 'Our AI-optimized service sends high-intent, keyword-targeted visitors to your site, saving you money compared to paid ad campaigns. Ready to get started?\r\n\r\nhttps://cutt.ly/Vt4CHSQG', 'new', NULL, '23.80.153.131', '2026-07-01 15:33:56', '2026-07-01 15:33:56'),
	(8, 'contact_page', 'Isiah Armijo', 'armijo.isiah@googlemail.com', '42261367', 'Discover the Secret to More Website Leads', 'Not getting enough leads for hotelbeachway.com? Check out this video to see our AI traffic solution in action: https://www.youtube.com/watch?v=UEooLHpFYW0', 'new', NULL, '196.51.187.81', '2026-07-01 17:38:04', '2026-07-01 17:38:04'),
	(9, 'contact_page', 'Reda McBeath', 'mcbeath.reda@gmail.com', '7016500407', 'Question', 'Hey,\r\n\r\nIf your company is still using paper checks, you might be interested in this.\r\n\r\nWe’re FairwayPay, and we help businesses save time, lower payment costs, reduce fraud risk, and eliminate the hassle of printing, mailing, and tracking checks.\r\n\r\nWith FairwayPay, businesses can send checks by email in seconds. Payees only need an email address, and each check is accessed and downloaded through a password-protected link.\r\n\r\nThere is no setup fee, and pricing is only $4.95/month + 39¢ per check transaction.\r\n\r\nVisit our site now for more details: https://bit.ly/fairwaypay', 'new', NULL, '23.106.201.123', '2026-07-01 21:44:33', '2026-07-01 21:44:33'),
	(10, 'contact_page', 'Joanna Riggs', 'joannariggs278@gmail.com', '261771017', 'Explainer Video for your website', 'Hi,\r\n\r\nI just visited hotelbeachway.com and wondered if you\'ve ever considered an impactful video to advertise your business? Our videos can generate impressive results on both your website and across social media.\r\n\r\nOur videos cost just $195 (USD) for a 30 second video ($239 for 60 seconds) and include a full script, voice-over and video.\r\n\r\nI can show you some previous videos we\'ve done if you want me to send some over. Let me know if you\'re interested in seeing samples of our previous work.\r\n\r\nRegards,\r\nJoanna', 'new', NULL, '78.128.99.14', '2026-07-05 01:57:10', '2026-07-05 01:57:10'),
	(11, 'contact_page', 'Elvira Reade', 'reade.elvira@yahoo.com', '48518211', 'Quick Question', 'Hello,\r\n\r\nIf you’re an executive struggling to stay in shape, listen up.\r\n\r\nWhat if the secret to weight loss and building muscle wasn’t spending more time at the gym, but simply wearing smarter gear.\r\n\r\nDiscover how much weight you can lose in 30 days by wearing EleBands — wearable body weights that work as you go about your day.\r\n\r\nPlus, EleBands help you too also:\r\n- Slim down all over\r\n- Tone arms and legs\r\n- Tighten glutes\r\n- Firm up abs\r\n\r\nTake the EleBands one-minute Weight-Loss Quiz and see how much weight you could lose: https://bit.ly/elebands-quiz', 'new', NULL, '23.105.145.46', '2026-07-05 04:28:39', '2026-07-05 04:28:39'),
	(12, 'contact_page', 'Jann Hager', 'jann.hager@googlemail.com', NULL, 'Hello hotelbeachway.com admin!', 'I\'ve noticed that your hotelbeachway.com website could be missing out on approximately 1,000 visitors daily. Our AI powered traffic system is designed to significantly boost your site\'s visibility. https://cutt.ly/Sw2BAXtw\r\nWe\'re offering a free trial that includes 500 targeted visitors to demonstrate the potential benefits. After the trial, we can provide up to 250,000 targeted visitors per month. This opportunity could greatly enhance your website\'s reach and engagement.', 'new', NULL, '196.51.187.81', '2026-07-05 20:51:54', '2026-07-05 20:51:54'),
	(13, 'quote', 'Test Email', 'jinexar@mailinator.com', NULL, NULL, 'Sed fugiat nemo dolo', 'new', NULL, '103.114.11.42', '2026-07-06 18:34:42', '2026-07-06 18:34:42'),
	(14, 'contact_page', 'Adelaida Schmitt', 'adelaida.schmitt@outlook.com', '487529518', 'Struggling with website traffic? Try Our 7-Day Free Trial', 'Our AI-optimized solution brings keyword and location-targeted traffic to your website, offering better value than costly paid advertising. Reach out to see results.\r\n\r\nhttps://blankslatelife.com/', 'new', NULL, '196.51.187.45', '2026-07-07 04:40:19', '2026-07-07 04:40:19'),
	(15, 'contact_page', 'Davis Dacey', 'dacey.davis69@gmail.com', '5619471445', 'See immediate results for hotelbeachway.com\'s visitor flow with our AI traffic service.', 'Our AI-driven service delivers keyword and location-specific visitors to your site, offering significant savings over paid ads. Connect with us today.\r\n\r\nhttps://cutt.ly/Gt4CHOyw', 'new', NULL, '23.80.153.88', '2026-07-07 10:00:29', '2026-07-07 10:00:29'),
	(16, 'contact_page', 'Alfred Henley', 'henley.alfred55@gmail.com', '353954419', 'Unlock expansion opportunities for hotelbeachway.com with a trial of our AI traffic tool.', 'Get more clicks for less with our AI-optimized traffic service, offering targeted visitors at a lower cost than paid advertising. Grow your site now.\r\n\r\nhttps://cutt.ly/Xt4CHP2t', 'new', NULL, '38.141.62.29', '2026-07-08 00:03:11', '2026-07-08 00:03:11'),
	(17, 'contact_page', 'Reta Bohn', 'reta.bohn@hotmail.com', '306148461', 'Need more traffic for your business?', 'There\'s a new hack taking the internet by storm that forces ChatGPT to send you buyers on autopilot. Take a look: lazytraffic.com\r\n\r\n\r\nReta, Bohn\r\n\r\n4016 Watson Lane, Baltimore, MD 21202, United States.\r\n\r\n 306148461\r\n\r\nIf you do not wish to receive further emails from us, you may opt-out by filling this form:\r\nhttps://forms.gle/gW3WjWjfkdNVn1yVA.\r\n\r\nPlease note that it may take up to 10 business days for your request to be processed.\r\n\r\nIf you have received this communication in error, please delete it and notify the sender immediately.', 'new', NULL, '172.81.58.46', '2026-07-09 18:52:23', '2026-07-09 18:52:23'),
	(18, 'quote', 'OEPelPzwmdWhaOpg', 'ka.c.uk.eg.775@gmail.com', NULL, NULL, 'CVOwsQpKNfzsnXodbP', 'new', NULL, '185.220.101.24', '2026-07-10 10:19:58', '2026-07-10 10:19:58'),
	(19, 'contact_page', 'StmXmGHCKknYdbYBKj', 'ka.c.uk.eg.775@gmail.com', '2336162141', 'LQwatFcWIAojbdFnieFjYUu', 'FttyJvFbVOhkYggigOzk', 'new', NULL, '95.155.151.200', '2026-07-10 10:21:20', '2026-07-10 10:21:20'),
	(20, 'quote', 'vIwBpJWsDERjNVGmDuPZTWJd', 'djguggi@yahoo.com', NULL, NULL, 'qpoCsaNeBEWUluvTN', 'new', NULL, '185.129.61.6', '2026-07-10 16:07:12', '2026-07-10 16:07:12'),
	(21, 'contact_page', 'poGkRcbZBYuqHDonxOzMjKF', 'cm.c.b.a.r.r@gmail.com', '2825279073', 'CpAdeNUYoFfTSEVbbBvsXEgF', 'ZWZRqjbUjuuyNsDqKrtcE', 'new', NULL, '104.244.76.121', '2026-07-10 18:42:11', '2026-07-10 18:42:11'),
	(22, 'quote', 'WMmGUWktfxiTJHAdWTEksbf', 'lawrfrancois@yahoo.com', NULL, NULL, 'DdujjtPJwXnYHZuHTMItsqkp', 'new', NULL, '45.84.107.222', '2026-07-10 21:05:49', '2026-07-10 21:05:49'),
	(23, 'quote', 'LWqfszDUHTihjuWS', 'skrap.l@tuparks.com', NULL, NULL, 'eNXeszoKHedLjNmIUVlOOrH', 'new', NULL, '45.84.107.76', '2026-07-11 02:12:32', '2026-07-11 02:12:32'),
	(24, 'contact_page', 'kufmtGhCBQyuHmfI', 'skrap.l@tuparks.com', '8062067085', 'sTnDXrOYThCplVVZYpQU', 'qKmTmZgtDHFUpCuQbmsT', 'new', NULL, '45.84.107.76', '2026-07-11 02:13:11', '2026-07-11 02:13:11'),
	(25, 'contact_page', 'Jacki Dupuy', 'dupuy.jacki@outlook.com', '890010524', 'To the hotelbeachway.com admin!', 'I\'ve noticed that your hotelbeachway.com website could be missing out on approximately 1,000 visitors daily. Our AI powered traffic system is designed to significantly boost your site\'s visibility. https://cutt.ly/jt6WZbmY\r\nWe\'re offering a free trial that includes 500 targeted visitors to demonstrate the potential benefits. After the trial, we can provide up to 250,000 targeted visitors per month. This opportunity could greatly enhance your website\'s reach and engagement.', 'new', NULL, '196.51.184.37', '2026-07-11 05:36:34', '2026-07-11 05:36:34'),
	(26, 'quote', 'bNXcbFTCVwAUGUMJvqqWVUJb', 'creeksnursery@att.net', NULL, NULL, 'HFZsXjFcDRmCyGDHyosRBu', 'new', NULL, '107.189.13.180', '2026-07-11 06:56:02', '2026-07-11 06:56:02'),
	(27, 'quote', 'XcpzGIZIFKwOuawqmpSrKKXt', 'r.y.an.gem.b.i.t.sky.8.71.1@gmail.com', NULL, NULL, 'rYTpwOciRyJnDmuLQU', 'new', NULL, '62.182.80.112', '2026-07-11 11:35:37', '2026-07-11 11:35:37'),
	(28, 'quote', 'mjsYPVdEONQtRSuFWmmXCl', 'cl.bu.rch54@gmail.com', NULL, NULL, 'SKMLCEzXNRDvhMyRUGmlT', 'new', NULL, '85.206.174.98', '2026-07-11 14:47:41', '2026-07-11 14:47:41'),
	(29, 'quote', 'HgvUGTKjmhoirdMJfI', 'peterromeo@neurocatch.com', NULL, NULL, 'stdZxtmdCdEUdNOhoePCKYV', 'new', NULL, '192.76.153.253', '2026-07-11 17:31:06', '2026-07-11 17:31:06'),
	(30, 'quote', 'SlZOHhcmSbUebGOsdDtavMH', 'dustya@midrivers.com', NULL, NULL, 'yfVZxGWdtNfEIGrtBWNSc', 'new', NULL, '185.220.101.142', '2026-07-12 04:31:39', '2026-07-12 04:31:39'),
	(31, 'contact_page', 'cXjbCjiOvUNQkUzSFBXEuJ', 'dustya@midrivers.com', '7425353498', 'KiOSeQUQAhPzUPaPAfkw', 'fmUdmvJxwMVvByIZzc', 'new', NULL, '147.90.234.116', '2026-07-12 04:32:30', '2026-07-12 04:32:30'),
	(32, 'contact_page', 'Saidy Hasan', 'saidy7cox@gmail.com', NULL, NULL, 'Test\r\ntest', 'read', NULL, '144.48.116.30', '2026-07-14 03:51:18', '2026-07-14 03:52:16');
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.migrations: ~36 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_01_01_000001_create_sliders_table', 2),
	(6, '2024_01_01_000002_create_global_settings_table', 2),
	(7, '2024_01_02_000001_create_services_table', 3),
	(8, '2024_01_02_000002_create_gallery_images_table', 4),
	(9, '2024_01_02_000003_create_faqs_table', 5),
	(10, '2024_01_02_000004_create_testimonials_table', 6),
	(11, '2024_01_02_000005_add_image_to_faqs_table', 7),
	(12, '2024_01_02_000006_create_facilities_table', 8),
	(13, '2024_01_03_000001_create_room_types_table', 9),
	(14, '2024_01_03_000002_create_rooms_table', 9),
	(15, '2024_01_03_000003_create_customers_table', 9),
	(16, '2024_01_03_000004_create_room_bookings_table', 9),
	(17, '2024_01_04_000001_create_roles_table', 10),
	(18, '2024_01_04_000002_create_role_permissions_table', 10),
	(19, '2024_01_04_000003_add_role_id_to_users_table', 10),
	(20, '2026_06_16_000001_add_feature_image_and_seo_to_room_types_table', 11),
	(21, '2026_06_16_000002_update_booking_system', 12),
	(22, '2026_06_16_061708_create_inquiries_table', 13),
	(23, '2024_01_04_000001_create_blog_categories_table', 14),
	(24, '2024_01_04_000002_create_blogs_table', 14),
	(25, '2024_01_04_000003_create_blog_comments_table', 14),
	(26, '2026_06_22_173135_alter_faqs_page_enum_add_faq', 15),
	(27, '2026_06_28_000001_create_booking_followups_table', 16),
	(28, '2026_06_28_000002_create_booking_logs_table', 16),
	(29, '2026_06_28_000003_redesign_booking_followups_table', 17),
	(30, '2026_06_29_000001_add_pricing_offer_fields_to_room_types_table', 18),
	(31, '2026_06_29_200001_create_room_amenities_table', 19),
	(32, '2026_06_29_200002_create_room_type_amenity_table', 19),
	(33, '2026_06_30_000001_add_discount_to_room_bookings', 20),
	(34, '2026_06_30_000001_add_room_rules_to_room_types_table', 20),
	(35, '2026_07_03_000001_create_booking_rooms_table', 21),
	(36, '2026_07_03_000002_drop_legacy_room_columns_from_room_bookings', 22);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.password_reset_tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.personal_access_tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.roles: ~2 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_super_admin`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'super-admin', 'Full access to all modules.', 1, 1, '2026-06-12 18:12:01', '2026-06-12 18:12:01'),
	(2, 'shahadat', 'shahadat', NULL, 0, 1, '2026-07-13 11:39:46', '2026-07-13 11:39:46');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `module_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_id_module_key_unique` (`role_id`,`module_key`),
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.role_permissions: ~12 rows (approximately)
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` (`id`, `role_id`, `module_key`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`, `updated_at`) VALUES
	(1, 2, 'dashboard', 1, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(2, 2, 'room-availability', 1, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(3, 2, 'bookings', 1, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(4, 2, 'room-management', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(5, 2, 'customers', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(6, 2, 'inquiries', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(7, 2, 'website-management', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(8, 2, 'reports', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(9, 2, 'global-settings', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(10, 2, 'email-smtp-setting', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(11, 2, 'user-management', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46'),
	(12, 2, 'backups', 0, 0, 0, 0, '2026-07-13 11:39:46', '2026-07-13 11:39:46');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_type_id` bigint(20) unsigned NOT NULL,
  `room_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','occupied','maintenance','out_of_order') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  KEY `rooms_room_type_id_foreign` (`room_type_id`),
  CONSTRAINT `rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.rooms: ~58 rows (approximately)
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `room_name`, `floor`, `building`, `image`, `status`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
	(3, 2, '103', NULL, '1st Floor', NULL, NULL, 'occupied', NULL, 1, '2026-06-24 19:55:37', '2026-07-21 20:09:31'),
	(4, 1, '104', NULL, '1st Floor', '1', 'rooms/49ech1HKqR5yELjRUBlYXkt6nWKfTmKnFW7uCBMc.jpg', 'occupied', NULL, 1, '2026-07-13 11:17:39', '2026-07-21 20:55:29'),
	(5, 1, '105', NULL, '1st Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:45:19', '2026-07-14 04:05:20'),
	(6, 2, '106', NULL, '1st Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:46:17', '2026-07-13 12:46:17'),
	(7, 1, '107', NULL, '1st Floor', 'With Out Balcony', NULL, 'available', NULL, 1, '2026-07-13 12:46:53', '2026-07-13 12:46:53'),
	(8, 1, '108', NULL, '1st Floor', NULL, NULL, 'out_of_order', NULL, 1, '2026-07-13 12:47:31', '2026-07-13 12:50:55'),
	(9, 1, '109', NULL, '1st Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 12:48:14', '2026-07-13 12:48:14'),
	(10, 2, '110', NULL, '1st Floor', NULL, NULL, 'occupied', NULL, 1, '2026-07-13 12:48:32', '2026-07-21 20:09:31'),
	(11, 2, '111', NULL, '1st Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:48:49', '2026-07-13 12:48:49'),
	(12, 3, '201', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:49:22', '2026-07-13 12:49:22'),
	(13, 5, '202', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:50:19', '2026-07-13 12:50:19'),
	(14, 2, '203', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:51:10', '2026-07-13 12:51:10'),
	(15, 1, '204', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:51:38', '2026-07-13 12:51:38'),
	(16, 4, '205', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:52:11', '2026-07-13 12:52:11'),
	(17, 2, '206', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:52:26', '2026-07-13 12:52:26'),
	(18, 1, '207', NULL, '2nd Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 12:52:57', '2026-07-13 12:52:57'),
	(19, 1, '208', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:53:40', '2026-07-17 09:10:29'),
	(20, 1, '209', NULL, '2nd Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 12:54:08', '2026-07-13 12:54:08'),
	(21, 2, '210', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:55:28', '2026-07-13 12:55:28'),
	(22, 2, '211', NULL, '2nd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:56:07', '2026-07-13 12:56:07'),
	(23, 3, '301', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:56:29', '2026-07-13 12:56:29'),
	(24, 1, '302', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:57:01', '2026-07-13 12:57:01'),
	(25, 4, '303', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:57:19', '2026-07-13 12:57:19'),
	(26, 2, '304', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:57:42', '2026-07-13 12:57:42'),
	(27, 1, '305', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:57:59', '2026-07-13 12:57:59'),
	(28, 1, '306', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:58:14', '2026-07-13 12:58:14'),
	(29, 2, '307', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 12:59:07', '2026-07-13 12:59:07'),
	(30, 1, '308', NULL, '3rd Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 12:59:36', '2026-07-13 12:59:36'),
	(31, 1, '309', NULL, NULL, NULL, NULL, 'out_of_order', NULL, 1, '2026-07-13 13:00:00', '2026-07-13 13:00:00'),
	(32, 1, '310', NULL, '3rd Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 13:00:34', '2026-07-13 13:00:34'),
	(33, 2, '311', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:00:58', '2026-07-13 13:00:58'),
	(34, 2, '312', NULL, '3rd Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:01:20', '2026-07-13 13:01:20'),
	(35, 3, '401', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:01:46', '2026-07-13 13:01:46'),
	(36, 1, '402', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:02:12', '2026-07-13 13:02:12'),
	(37, 4, '403', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:02:31', '2026-07-13 13:02:31'),
	(38, 2, '404', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:03:10', '2026-07-13 13:03:10'),
	(39, 1, '405', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:03:22', '2026-07-13 13:03:22'),
	(40, 1, '406', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:04:00', '2026-07-13 13:04:00'),
	(41, 2, '407', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:04:23', '2026-07-13 13:04:23'),
	(42, 1, '409', NULL, '4th Floor', NULL, NULL, 'out_of_order', NULL, 1, '2026-07-13 13:04:52', '2026-07-13 13:04:52'),
	(43, 1, '408', NULL, '4th Floor', 'Without Balcony', NULL, 'available', NULL, 1, '2026-07-13 13:06:07', '2026-07-13 13:06:07'),
	(44, 3, '410', NULL, '4th Floor', NULL, NULL, 'out_of_order', NULL, 1, '2026-07-13 13:06:40', '2026-07-13 13:06:40'),
	(45, 2, '411', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:07:02', '2026-07-13 13:07:02'),
	(46, 2, '412', NULL, '4th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:07:23', '2026-07-13 13:07:23'),
	(47, 3, '501', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:07:56', '2026-07-13 13:07:56'),
	(48, 1, '502', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:08:26', '2026-07-13 13:08:26'),
	(49, 4, '503', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:09:10', '2026-07-13 13:09:10'),
	(50, 2, '504', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:09:30', '2026-07-13 13:09:30'),
	(51, 1, '505', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:10:12', '2026-07-13 13:10:12'),
	(52, 1, '506', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:10:27', '2026-07-13 13:10:27'),
	(53, 2, '507', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:10:48', '2026-07-13 13:10:48'),
	(54, 1, '508', NULL, '5th Floor', 'Without Balcony', NULL, 'occupied', NULL, 1, '2026-07-13 13:11:20', '2026-07-14 04:57:38'),
	(55, 1, '509', NULL, '5th Floor', NULL, NULL, 'out_of_order', NULL, 1, '2026-07-13 13:11:45', '2026-07-13 13:11:45'),
	(56, 1, '510', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:12:03', '2026-07-14 04:55:20'),
	(57, 2, '511', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:12:28', '2026-07-13 13:12:28'),
	(58, 3, '101', NULL, '1st Floor', NULL, NULL, 'occupied', NULL, 1, '2026-07-13 13:26:21', '2026-07-21 20:09:31'),
	(59, 5, '102', NULL, '1st Floor', NULL, NULL, 'available', NULL, 1, '2026-07-13 13:27:16', '2026-07-13 13:27:16'),
	(60, 2, '512', NULL, '5th Floor', NULL, NULL, 'available', NULL, 1, '2026-07-14 04:55:44', '2026-07-14 04:55:44');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.room_amenities
CREATE TABLE IF NOT EXISTS `room_amenities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_svg` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.room_amenities: ~16 rows (approximately)
/*!40000 ALTER TABLE `room_amenities` DISABLE KEYS */;
INSERT INTO `room_amenities` (`id`, `name`, `icon_svg`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'WiFi', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>', 0, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(2, 'Double Bed', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v5"/><path d="M2 20v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><path d="M2 20h20"/><path d="M2 9h20"/><rect x="6" y="9" width="4" height="4" rx="1"/><rect x="14" y="9" width="4" height="4" rx="1"/></svg>', 1, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(3, 'Single Bed', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v5"/><path d="M2 20v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><path d="M2 20h20"/><path d="M2 9h20"/><rect x="8" y="9" width="8" height="4" rx="1"/></svg>', 2, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(4, 'Cable TV', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/></svg>', 3, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(5, 'Restaurant', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>', 4, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(6, 'Air Conditioning', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 19H5c-1 0-2-.9-2-2V7c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10c0 1.1-.9 2-2 2h-3"/><path d="M8 19v2"/><path d="M16 19v2"/><path d="M12 12v7"/><path d="M9 15l3-3 3 3"/></svg>', 5, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(7, 'Mini Fridge', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="5" y1="10" x2="19" y2="10"/><line x1="9" y1="6" x2="9" y2="8"/><line x1="9" y1="14" x2="9" y2="18"/></svg>', 6, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(8, 'Hot Water', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v2"/><path d="M12 20v2"/><path d="M4.93 4.93l1.41 1.41"/><path d="M17.66 17.66l1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="M6.34 17.66l-1.41 1.41"/><path d="M19.07 4.93l-1.41 1.41"/><circle cx="12" cy="12" r="4"/></svg>', 7, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(9, 'Balcony', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="9" x2="9" y2="21"/><line x1="15" y1="9" x2="15" y2="21"/></svg>', 8, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(10, 'Sofa', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H6v-2a2 2 0 0 0-4 0Z"/><line x1="6" y1="18" x2="6" y2="21"/><line x1="18" y1="18" x2="18" y2="21"/></svg>', 9, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(11, 'Parking', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/></svg>', 10, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(12, 'CCTV', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>', 11, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(13, 'Room Service', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>', 12, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45'),
	(14, 'City View', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20"/><path d="M2 17c1.5-1 3-1 4.5 0s3 1 4.5 0 3-1 4.5 0 3 1 4.5 0"/><path d="M12 2v5"/><path d="M8 5l4-3 4 3"/></svg>', 13, 1, '2026-06-29 22:01:45', '2026-07-13 14:27:04'),
	(15, 'Swimming Pool ( Under Construction)', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20"/><path d="M2 17c1.5-1 3-1 4.5 0s3 1 4.5 0 3-1 4.5 0 3 1 4.5 0"/><circle cx="17" cy="5" r="2"/><path d="M15 7l-2 5h4"/></svg>', 14, 1, '2026-06-29 22:01:45', '2026-07-13 14:28:15'),
	(16, 'Safe Locker', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M12 9v-2"/><path d="M12 17v-2"/><path d="M15 12h2"/><path d="M7 12h2"/></svg>', 15, 1, '2026-06-29 22:01:45', '2026-06-29 22:01:45');
/*!40000 ALTER TABLE `room_amenities` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.room_bookings
CREATE TABLE IF NOT EXISTS `room_bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_reference` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `adults` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `children` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total_nights` smallint(5) unsigned NOT NULL DEFAULT '1',
  `total_amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `advance_payment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','card','bkash','nagad','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `booking_status` enum('pending','confirmed','payment_pending','checked_in','checked_out','no_show','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `booked_by` bigint(20) unsigned DEFAULT NULL,
  `source` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_bookings_booking_reference_unique` (`booking_reference`),
  KEY `room_bookings_customer_id_foreign` (`customer_id`),
  KEY `room_bookings_booked_by_foreign` (`booked_by`),
  CONSTRAINT `room_bookings_booked_by_foreign` FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `room_bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.room_bookings: ~2 rows (approximately)
/*!40000 ALTER TABLE `room_bookings` DISABLE KEYS */;
INSERT INTO `room_bookings` (`id`, `booking_reference`, `customer_id`, `check_in_date`, `check_out_date`, `adults`, `children`, `total_nights`, `total_amount`, `currency`, `advance_payment`, `discount_amount`, `payment_method`, `special_requests`, `booking_status`, `notes`, `booked_by`, `source`, `created_at`, `updated_at`) VALUES
	(35, 'HBW-20260716-28382', 16, '2026-07-21', '2026-07-25', 10, 3, 4, 45600.00, 'BDT', 5000.00, 0.00, 'bkash', 'Bkash Last Digit-  564', 'pending', NULL, NULL, 'web', '2026-07-16 18:34:25', '2026-07-21 20:43:24'),
	(38, 'HBW-20260721-55443', 2, '2026-07-23', '2026-07-26', 2, 0, 3, 9000.00, 'BDT', 0.00, 0.00, 'cash', NULL, 'checked_in', 'Hello world', NULL, 'web', '2026-07-21 20:18:48', '2026-07-21 20:55:29');
/*!40000 ALTER TABLE `room_bookings` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.room_types
CREATE TABLE IF NOT EXISTS `room_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_desc` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_usd` decimal(10,2) DEFAULT NULL,
  `discount_type_bdt` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value_bdt` decimal(10,2) DEFAULT NULL,
  `discount_type_usd` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value_usd` decimal(10,2) DEFAULT NULL,
  `offer_expires_at` datetime DEFAULT NULL,
  `price_unit` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/Night',
  `check_in_time` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '12:30',
  `check_out_time` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '11:30',
  `max_adults` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `max_children` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bed_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `room_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT '5.0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.room_types: ~8 rows (approximately)
/*!40000 ALTER TABLE `room_types` DISABLE KEYS */;
INSERT INTO `room_types` (`id`, `name`, `slug`, `short_desc`, `description`, `price`, `price_usd`, `discount_type_bdt`, `discount_value_bdt`, `discount_type_usd`, `discount_value_usd`, `offer_expires_at`, `price_unit`, `check_in_time`, `check_out_time`, `max_adults`, `max_children`, `bed_type`, `amenities`, `features`, `room_rules`, `gallery_images`, `feature_image`, `meta_title`, `meta_description`, `meta_keywords`, `og_image`, `rating`, `is_featured`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Deluxe Double Room', 'deluxe-double-room', 'Deluxe Double Room consists of a King size bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 'Deluxe Double Room consists of a King size bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 5000.00, 45.00, 'percentage', 40.00, 'percentage', 40.00, '2026-08-25 12:00:00', '/Night', '01:00 PM', '11:00 AM', 2, 1, NULL, '[]', '["King size bed - 01","Bed side table","Sofa","Tea table","Wall cabinet","Wall mirror","Spacious bathroom","Running hot and cold water","Air condition and Fan","Mini Fridge with Mini Bar","Cable LED TV","Intercom systems","Free Inercom call"]', NULL, '["room-types\\/nMPlmzimleciKWkmDwgDTBddIsO65mQzOf5Cd684.jpg","room-types\\/qibN4nkcUHpvQlwICLZysssdi7S4Ln8TQbp8Sk3Q.jpg","room-types\\/G83D9d24yc88HpKILL5BjQhko024O39LwPCoa3Cm.jpg","room-types\\/A4EI47lpJKf6mSKtSkEDKOiuAHU8CttPWux74tkG.jpg"]', 'room-types/HNupg1aBdIB46TRJAcPwM7DOyhAYBKE73QBR1x5f.jpg', NULL, NULL, 'deluxe, double, consists, king, size, head, board, side, table, sofa, wall, cabinet', NULL, 5.0, 0, 0, 1, '2026-06-21 11:57:50', '2026-07-15 08:45:00'),
	(2, 'Deluxe Triple Room', 'deluxe-triple-room', 'Deluxe Triple Room consists of a One King size bed & One single separate bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 'Deluxe Triple Room consists of a One King size bed & One single separate bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 6000.00, 50.00, 'percentage', 40.00, 'percentage', 40.00, '2026-08-25 12:00:00', '/Night', '01:00 PM', '11:00 AM', 3, 1, NULL, '[{"label":"Balcony","icon_svg":"<svg xmlns=\\"http:\\/\\/www.w3.org\\/2000\\/svg\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"2\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><rect x=\\"3\\" y=\\"3\\" width=\\"18\\" height=\\"18\\" rx=\\"2\\"\\/><line x1=\\"3\\" y1=\\"9\\" x2=\\"21\\" y2=\\"9\\"\\/><line x1=\\"9\\" y1=\\"9\\" x2=\\"9\\" y2=\\"21\\"\\/><line x1=\\"15\\" y1=\\"9\\" x2=\\"15\\" y2=\\"21\\"\\/><\\/svg>"}]', '["One King size bed & One single separate bed","Sofa, Tea table","Wall cabinet, Wall mirror","Balcony Available","Maximum 03 Adults"]', NULL, '["room-types\\/QqYlrQhVVyGerLoKIHn9DvQrzo396UFlg4E2WlSs.jpg","room-types\\/jHnzn9IciirazT8BP52bEUYeUHXnOAJBfLt2yW5S.jpg","room-types\\/gYeg0VN3WhbC5MXpgQwaMfH4m1Y4JIkct5rRCsMN.jpg","room-types\\/Y9c1sVAkuWmhHvmuHsquEGfrdYsCyEmgFmsjYXmB.jpg","room-types\\/ruPKxIliTilclIL69I4rw9IPcjgPHF2UzoSSUm7l.jpg","room-types\\/PajCzKY8VUovvxDUzjAQqvvTdo8D0czKgGBRCAST.jpg","room-types\\/ZQqcMtH3wwSfGOvQHPWVahUQ4YJPfiPBELijAMF1.jpg"]', 'room-types/E3nDjbPx5NL4yMBgBMPkD0wPE1GFFpBXXQxwuRlp.jpg', NULL, NULL, 'deluxe, triple, consists, king, size, single, separate, head, board, side, table, sofa', NULL, 5.0, 0, 0, 1, '2026-06-21 11:59:42', '2026-07-15 08:52:20'),
	(3, 'Deluxe Four', 'deluxe-four', 'Deluxe Four Beds Room consists of a Two separate King bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 'Deluxe Four Beds Room consists of a Two separate King bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom, Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 7000.00, 58.00, 'percentage', 40.00, 'percentage', 40.00, '2026-08-25 12:00:00', '/Night', '01:00 PM', '11:00 AM', 4, 2, NULL, '[]', '["King bed 04","Sofa, Tea table","Wall cabinet, Wall mirror,","Balcony","Running hot and cold water","Air condition and Fan","Mini Fridge with Mini Bar"]', NULL, '["room-types\\/4SrFTwoJ97hQzSSVuB0GTRoWyR8eRYObAlkDo6lw.jpg","room-types\\/8CfGb9JrgWQ4b18dsgw9hcr9suLB0rBcpo3rCMt6.jpg","room-types\\/DHx4vSNWhKMydr18h9QGPmkLbg2ea9yfAlVpf99a.jpg","room-types\\/b6xdpMX2r39YoDqYjuvJ8vBp3MLg6LvKfqxTPfGx.jpg","room-types\\/xL2VoO4gkWqtsFiurVckssXIr58ceXVjNiI6N699.jpg","room-types\\/It2X5PDJ11D5EDsR5CQEnqQIgV6DmGdcYwwVC68n.jpg"]', 'room-types/yuHebo1nBqiYSZg5sSkzZ3jDWRZ7G2AuZK1787Qm.jpg', 'Deluxe Four  – Hotel Beach Way', NULL, 'deluxe, four, beds, consists, separate, king, head, board, side, table, sofa, wall', NULL, 5.0, 0, 0, 1, '2026-06-21 12:00:24', '2026-07-15 08:47:44'),
	(4, 'Honeymoon Room', 'honeymoon-room', 'Honeymoon Room consists of a King size bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom with bathtub, Hair dryer Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 'Honeymoon Room consists of a King size bed with head board, Bed side table, Sofa, Tea table, Wall cabinet, Wall mirror, Balcony, Spacious bathroom with bathtub, Hair dryer Running hot and cold water, Air condition and Fan, Mini Fridge with Mini Bar, Cable LED TV, Intercom systems.', 6000.00, 50.00, 'percentage', 40.00, 'percentage', 40.00, '2026-08-25 12:00:00', '/Night', '01:00 PM', '11:00 AM', 2, 1, NULL, '[]', '["King size bed 01","2 persons stay","Sofa","Hair dryer","Running hot and cold water"]', NULL, '["room-types\\/Czt64ecOSHpiEnnipN5Xen8szZmRqAfx5wft9w7r.jpg","room-types\\/RFDwqbDyJGPtGwXpdplKSXQAdp8Rz2PZk5BfhphM.jpg","room-types\\/KswHavlJGBXbZJJViYX7o3GgozTbrlp54Uu7BCiQ.jpg","room-types\\/SvS7KVkf8p44MK5KzI6zuV0d7XVq7L8DChpi2dWO.jpg","room-types\\/tuwo16b1Jfhwl8vKoATSZFm7gUBGF3hxrzjNZ4xL.jpg","room-types\\/aP331j6GuqjHptWq9NlbCMOgjkYWhB4rpzqPWbCy.jpg","room-types\\/tTuB9ykF7p6W5uKjrQfsMn6PHS460SWeM9axWCIn.jpg"]', 'room-types/wojQ7STlR4LsLxvR8J2iUrMK1VLeEbQpxEnmEBEM.jpg', NULL, NULL, 'honeymoon, consists, king, size, head, board, side, table, sofa, wall, cabinet, mirror', NULL, 5.0, 0, 0, 1, '2026-06-21 12:04:29', '2026-07-15 08:54:57'),
	(5, 'Executive Suite', 'executive-suite', 'Executive Suite Connecting Room consists of Two room with Two separate King size bed with head board, Bed side table, Sofa, Tea table, Two Wall cabinet, Wall mirror, Two Balcony, Spacious Two bathroom with One bathtub, Running hot and cold water, Two Air condition and Two Fan, Mini Fridge with Mini Bar, Two Cable LED TV, Intercom systems.', 'Executive Suite Connecting Room consists of Two room with Two separate King size bed with head board, Bed side table, Sofa, Tea table, Two Wall cabinet, Wall mirror, Two Balcony, Spacious Two bathroom with One bathtub, Running hot and cold water, Two Air condition and Two Fan, Mini Fridge with Mini Bar, Two Cable LED TV, Intercom systems.', 12000.00, 100.00, 'percentage', 40.00, 'percentage', 40.00, '2026-08-25 12:00:00', '/Night', '01:00 PM', '11:00 AM', 5, 2, NULL, '[]', '["Two Separate King Size Beds & 1 Single Bed","Two Wall cabinet","Compatible with 5 persons stay","Two Cable LED TV"]', NULL, '["room-types\\/Y5mmgArKlOphoBHR7SAzLa9l80S2cJnttVXLqvPR.jpg","room-types\\/XkH8I463hf9kosGOc8oETYdz5S59DrYIYaBNjaK4.jpg","room-types\\/L4CRZomOBvPp5yFrku3WLMtuKGDUgCUkP8N6ZDTr.jpg","room-types\\/qeeFBcj3UR9uC02txIsSoTEyM5GSb4dpmoP4rvbE.jpg","room-types\\/8azOqePySJn6HgZp7WJjShMdlI1giORlNlrejd0b.jpg"]', 'room-types/U3bESFNkKWbgt1lbsTBrSPrnpa43ZPNvckphGLZa.jpg', NULL, NULL, 'executive, suite, connecting, consists, separate, king, size, head, board, side, table, sofa', NULL, 5.0, 0, 0, 1, '2026-06-21 12:05:08', '2026-07-15 08:50:26'),
	(6, 'Conference & Banquet Hall-1', 'conference-banquet-hall-1', 'We have a very luxurious & spacious hall room on the top floor which is large enough to accommodate more than 200 guests for different kinds of parties and ceremonies. Our excellent service team are always ready to offer all kinds of facilities in a pleasing environment.', 'We have a very luxurious & spacious hall room on the top floor which is large enough to accommodate more than 200 guests for different kinds of parties and ceremonies. Our excellent service team are always ready to offer all kinds of facilities in a pleasing environment.\r\nYou can also enjoy the view of the longest sea beach from the rooftop. Just sitting at the same place. You can make all your moments memorable and spectacular where you organize a seminar, workshop, AGM, wedding and any other occasion.\r\nHot Line: 01777 90 95 95-6', 40000.00, 325.00, NULL, NULL, NULL, NULL, NULL, '/Event', '0', '0', 2, 0, NULL, '[]', '["Luxurious & Spacious Hall room","Accommodate more than 200 guests+","View of the longest sea beach from the rooftop"]', NULL, '[]', 'room-types/iBewjYIeQThqNlh0h68MgOhXCwMVEvGO7j5HvzQ8.jpg', 'Conference & Banquet Hall 1 – Hotel Beach Way', NULL, 'conference, banquet, hall, very, luxurious, spacious, floor, which, large, enough, accommodate, more', NULL, 5.0, 0, 0, 1, '2026-06-21 12:05:43', '2026-07-15 08:57:19'),
	(7, 'Conference Hall-02', 'conference-hall-02', 'We have a very luxurious & spacious hall room on the top floor which is large enough to accommodate more than 60 guests for different kinds of parties and ceremonies. Our excellent service team are always ready to offer all kinds of facilities in a pleasing environment.', NULL, 30000.00, 245.00, NULL, NULL, NULL, NULL, NULL, '/Event', '0', '0', 1, 0, NULL, '[]', '["Luxurious & Spacious Hall room","Hall-02 : Maximum 60 Persons (size 22\\/34 fit)"]', NULL, '[]', 'room-types/1OvBAFjNN8wSWSGpoRAtpEJCEVyPKgQGoiZQbCnu.jpg', 'Conference Hall-02 – Hotel Beach Way', 'We have a very luxurious & spacious hall room on the top floor which is large enough to accommodate more than 60 guests for different kinds of parties and ceremonies. Our excellent service team are always ready to offer all kinds of facilities in a pleasing environment.', 'conference, banquet, hall, very, luxurious, spacious, room, floor, which, large, enough, accommodate', NULL, 5.0, 0, 0, 1, '2026-07-12 16:54:01', '2026-07-15 09:47:17'),
	(8, 'Conference Hall-03', 'conference-hall-03', NULL, NULL, 20000.00, 165.00, NULL, NULL, NULL, NULL, NULL, '/Event', '0', '0', 2, 0, NULL, '[]', '["Luxurious & Spacious Hall room","Hall-03 : Maximum 30 Persons (Size 20\\/28 fit)"]', '["null"]', '[]', 'room-types/5Y232fQ5fMCu8s5GirT1uoOX6ofwygxdZBWOdaq9.jpg', 'Conference & Banquet Hall-03 – Hotel Beach Way', NULL, 'conference, banquet, hall', NULL, 5.0, 0, 0, 1, '2026-07-12 16:55:11', '2026-07-15 09:49:08');
/*!40000 ALTER TABLE `room_types` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.room_type_amenity
CREATE TABLE IF NOT EXISTS `room_type_amenity` (
  `room_type_id` bigint(20) unsigned NOT NULL,
  `room_amenity_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`room_type_id`,`room_amenity_id`),
  KEY `room_type_amenity_room_amenity_id_foreign` (`room_amenity_id`),
  CONSTRAINT `room_type_amenity_room_amenity_id_foreign` FOREIGN KEY (`room_amenity_id`) REFERENCES `room_amenities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_type_amenity_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.room_type_amenity: ~0 rows (approximately)
/*!40000 ALTER TABLE `room_type_amenity` DISABLE KEYS */;
INSERT INTO `room_type_amenity` (`room_type_id`, `room_amenity_id`) VALUES
	(2, 9);
/*!40000 ALTER TABLE `room_type_amenity` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_svg` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_desc` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `benefits_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'We Give The Best Services',
  `benefits_text` longtext COLLATE utf8mb4_unicode_ci,
  `gallery_image_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_image_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `faqs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `btn_text` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'View Services',
  `btn_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.services: ~5 rows (approximately)
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` (`id`, `title`, `slug`, `icon_svg`, `image`, `short_desc`, `description`, `benefits_title`, `benefits_text`, `gallery_image_1`, `gallery_image_2`, `features`, `faqs`, `btn_text`, `btn_url`, `is_featured`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Conference & Banquet Hall', 'conference-banquet-hall', NULL, 'services/2FUT8NDdHOwDKMe83qIv7vGfrjHfNTenFWGEE44v.jpg', 'You can also enjoy the view of the longest sea beach from the rooftop. Just sitting at the same place. You can make all your moments memorable and spectacular where you organize a seminar, workshop, AGM, wedding and any other occasion.\r\nHot Line: 01777 90 95 95-6', NULL, 'We Give The Best Services', NULL, NULL, NULL, NULL, NULL, 'View Services', '#', 0, 5, 1, '2026-06-21 12:12:47', '2026-06-27 22:07:41'),
	(2, 'Restaurant', 'restaurant', NULL, 'services/jgwvyBxipbujBMUdtpZ0RxpT6Z3IIQeaSxi4561j.jpg', 'Dew Drop restaurant located at the ground level beside of the reception opens always with variety specials sea food & delicious cuisines let you savor the multi dimensional of flavor our Restaurant & BBQ will make your Cox’s Bazar tour memorable.\r\nHot Line :01849 90 00 00', NULL, 'We Give The Best Services', NULL, NULL, NULL, NULL, NULL, 'View Services', '#', 0, 4, 1, '2026-06-21 12:13:08', '2026-07-15 09:27:17'),
	(3, 'Free WIFI', 'free-wifi', NULL, 'services/4wPDFH3iLyCJWhpJAYXuso5WhfK1dzDUSC7qqr5y.png', 'Wi-Fi Internet Connection in available in Hotel Primises.', NULL, 'We Give The Best Services', NULL, NULL, NULL, NULL, NULL, 'View Services', '#', 0, 1, 1, '2026-06-21 12:13:24', '2026-07-15 09:20:49'),
	(4, 'CCTV Security', 'cctv-security', NULL, 'services/wYwF6193uoI3pDfsM2s8dbXyI1B4EQqsrL4Dcjry.jpg', 'The Hotel Beach Way have high quality CCTV cameras in the hotel primises.', NULL, 'We Give The Best Services', NULL, NULL, NULL, NULL, NULL, 'View Services', '#', 0, 2, 1, '2026-06-27 19:14:35', '2026-07-15 09:15:27'),
	(5, 'Transport Sevice', 'transport-sevice', NULL, 'services/0xip0JHCuPcJo6qBQuih0h5gu1JmlXfQobrFv5tX.jpg', 'We offer the pick service for our customers. This is a special facility, which primarily aims to make it convenient for our guests to reach their destinations in a hassle free manner. There are a number of instances, when unscrupulous taxi operators and touts, hassle the visitors and make a hell of it. Our Pick up and drop facility is one of ours luxury service for our customers.', '<p>We offer the pick service for our customers. This is a special facility, which primarily aims to make it convenient for our guests to reach their destinations in a hassle free manner. There are a number of instances, when unscrupulous taxi operators and touts, hassle the visitors and make a hell of it. Our Pick up and drop facility is one of ours luxury service for our customers.</p><p></p>', 'We Give The Best Services', NULL, NULL, NULL, NULL, NULL, 'View Services', '#', 0, 3, 1, '2026-06-27 19:15:13', '2026-07-15 09:27:02');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.sliders
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Explore',
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#',
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `star_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `star_rating` tinyint(3) unsigned NOT NULL DEFAULT '5',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.sliders: ~3 rows (approximately)
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` (`id`, `label`, `title`, `subtitle`, `description`, `button_text`, `button_url`, `background_image`, `star_label`, `star_rating`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Welcome To Hotel Beach Way', 'Modern &amp; Luxurious<br>Stay Near <span class="accent">Kolatoli</span><br>  <span class="accent">Beach</span>, Cox\'s Bazar', NULL, 'A boutique hotel with world-class amenities, just steps from<br>\r\nthe longest unbroken sandy sea beach in the world.', 'Explore Rooms', '#', 'sliders/v4LJVXoDcKc9NNthF2Mu89jzB2o72DE12QAMVia7.jpg', '3-Star Boutique Hotel', 3, 1, 1, '2026-06-14 19:07:27', '2026-07-13 15:07:05'),
	(2, 'Rooms & Suites', 'Elegantly Furnished<br>Rooms With <span class="accent">First Class</span><br> <span class="accent">Comfort</span> &amp; Style', NULL, 'From Deluxe Double to Executive Suite — every room features<br> branded fixtures, Mini Fridge, Cable LED TV &amp; hot/cold water.', 'Explore Rooms', '#', 'sliders/SgRYFzZHPBmO4NUtqGWK3u4Ma35Z4viPCWjwrQGw.jpg', '5 Rooms Categories', 3, 2, 1, '2026-06-21 21:24:15', '2026-07-15 10:23:55'),
	(3, 'Multi-Cuisine Restaurant & Banquet Hall', 'Dining Dew Drop Restaurant,<br> Where Exquisite Dining Meets &amp;<br> <span class="accent">Memorable Celebration</span>', NULL, 'Enjoy authentic local and international cuisine, elegant interiors, and a modern banquet hall—\r\nperfect for family gatherings, corporate events, weddings, and special occasions.', 'Book Your Event', '#', 'sliders/cNaFUr1elJz4LliLoyG0oR0a848XIKGiuqS1NN1f.jpg', 'Multi-Cuisine Restaurant nt', 3, 3, 1, '2026-06-21 21:25:31', '2026-07-13 15:08:32');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.testimonials
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT '5.0',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.testimonials: ~3 rows (approximately)
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` (`id`, `review`, `name`, `role`, `avatar`, `rating`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, '"Very nice hotel in Cox\'s Bazar. Hotel Beach Way is the most modern and well furnished with luxurious fixture and fittings which offer everything you need to ensure a comfortable and pleasant stay."', 'Md. Minhajul Islam', 'Software Engineer, O.R Nizam Road, Chittagong', 'testimonials/6c51xAXnW0vVGhQ0FqvoHoBRjZ4E6JahlnJGvEeM.jpg', 5.0, 1, 1, '2026-06-21 22:10:11', '2026-06-21 22:10:11'),
	(2, '"Incredible personal service. I could spend a few hours from morning till lunch using the Wi-Fi at the Café. Good value and comfortable beds — highly recommended!"', 'Shawkeen', 'Leisure Trip, United Kingdom', 'testimonials/ziZnmeTYHHVppq5FhLcHmRZTBsvElH3D41WHA9TC.jpg', 5.0, 0, 1, '2026-06-21 22:10:32', '2026-06-21 22:10:32'),
	(3, '"Very clean place — staffs are really nice. I made good friends with the staff members. I would recommend this hotel to everyone. Next time I go to Cox\'s Bazar I will definitely stay there again."', 'Shaheda', 'Leisure Trip, United Kingdom', 'testimonials/2lsjau2SwUP3Ij4bz8bnQDr3wpaH6QtQOFzzAfmw.jpg', 5.0, 0, 1, '2026-06-21 22:10:57', '2026-06-21 22:10:57');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;

-- Dumping structure for table hotel-beach-way.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table hotel-beach-way.users: ~3 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_active`, `created_at`, `updated_at`, `role_id`) VALUES
	(1, 'Sydur', 'sydurrahmant1@gmail.com', NULL, '$2y$12$573Jz/9TOLemyggPLdUn3uZEYqueTYxGiC8Ga65DHfnEF0Bb7zHPG', NULL, 1, '2026-06-10 18:38:31', '2026-06-12 18:12:01', 1),
	(2, 'Admin', 'admin@admin.com', NULL, '$2y$12$U3iB/rj1o47lpXGGJs16OOFnkVxlRbayAC5ZddZgJ.fsSTsllaRJu', 'sD0vDDCbzcnlKrnAE72cJnNz1Igo2q4WjWJJ3Jjr992aB4G86fnKzgxpmlAC', 1, '2026-06-22 18:18:05', '2026-06-22 18:18:05', NULL),
	(3, 'shahadat mahamod chy', 'shahadat.smc@gmail.com', NULL, '$2y$12$isUOguNSa8gzYEHZ9qzhXOl6OBX3lFUj.cdxPRyLJCxCkttMckSHC', 'H4uIFqQyNMlc18QtTVlwbYtVPgFVRxXLM6wsfqr2gdWJoxqxAw2R8YJL2CbI', 1, '2026-07-11 08:32:46', '2026-07-11 08:32:46', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
