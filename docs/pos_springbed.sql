/*
SQLyog Ultimate v13.1.1 (32 bit)
MySQL - 10.11.16-MariaDB-log : Database - pos_springbed
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pos_springbed` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `pos_springbed`;

/*Table structure for table `activity_log` */

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activity_log` */

insert  into `activity_log`(`id`,`log_name`,`description`,`subject_type`,`event`,`subject_id`,`causer_type`,`causer_id`,`properties`,`batch_uuid`,`created_at`,`updated_at`) values 
(1,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 6.0; Nexus 5 Build\\/MRA58N) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Mobile Safari\\/537.36\"}',NULL,'2026-03-16 09:02:33','2026-03-16 09:02:33'),
(2,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 6.0; Nexus 5 Build\\/MRA58N) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Mobile Safari\\/537.36\"}',NULL,'2026-03-16 13:50:55','2026-03-16 13:50:55'),
(3,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',1,'App\\Models\\User',1,'{\"size\":\"single\",\"type\":\"headboard\",\"name\":\"Kasur 1\",\"selling_price\":1500000,\"sku\":\"SK-09\",\"weight\":12,\"color\":\"Red\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 13:53:54\",\"created_at\":\"2026-03-16 13:53:54\",\"id\":1}',NULL,'2026-03-16 13:53:54','2026-03-16 13:53:54'),
(4,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"brand_id\":3}',NULL,'2026-03-16 13:53:54','2026-03-16 13:53:54'),
(5,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"category_id\":2}',NULL,'2026-03-16 13:53:54','2026-03-16 13:53:54'),
(6,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',2,'App\\Models\\User',1,'{\"size\":\"custom\",\"type\":\"headboard\",\"name\":\"Kasur 2\",\"selling_price\":950000,\"sku\":\"KIK-019\",\"weight\":10,\"color\":\"Blue\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 13:57:47\",\"created_at\":\"2026-03-16 13:57:47\",\"id\":2}',NULL,'2026-03-16 13:57:47','2026-03-16 13:57:47'),
(7,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"brand_id\":1}',NULL,'2026-03-16 13:57:47','2026-03-16 13:57:47'),
(8,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"category_id\":3}',NULL,'2026-03-16 13:57:47','2026-03-16 13:57:47'),
(9,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',3,'App\\Models\\User',1,'{\"size\":\"queen\",\"type\":\"bundle\",\"name\":\"Kasur 3\",\"selling_price\":2100000,\"sku\":\"OPO-01\",\"weight\":15,\"color\":\"Green\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 13:58:19\",\"created_at\":\"2026-03-16 13:58:19\",\"id\":3}',NULL,'2026-03-16 13:58:19','2026-03-16 13:58:19'),
(10,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"brand_id\":2}',NULL,'2026-03-16 13:58:19','2026-03-16 13:58:19'),
(11,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"category_id\":2}',NULL,'2026-03-16 13:58:19','2026-03-16 13:58:19'),
(12,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',4,'App\\Models\\User',1,'{\"size\":\"single\",\"type\":\"springbed\",\"name\":\"Kasur 4\",\"selling_price\":800000,\"sku\":\"LOP-133\",\"weight\":7.2,\"color\":\"Orange\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 13:59:53\",\"created_at\":\"2026-03-16 13:59:53\",\"id\":4}',NULL,'2026-03-16 13:59:53','2026-03-16 13:59:53'),
(13,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"brand_id\":4}',NULL,'2026-03-16 13:59:53','2026-03-16 13:59:53'),
(14,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"category_id\":4}',NULL,'2026-03-16 13:59:53','2026-03-16 13:59:53');

/*Table structure for table `brands` */

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `brands` */

insert  into `brands`(`id`,`name`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'King Koil',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Serta',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'Comforta',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Florence',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

/*Table structure for table `bundle_items` */

DROP TABLE IF EXISTS `bundle_items`;

CREATE TABLE `bundle_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bundle_items_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_items_product_id_foreign` (`product_id`),
  CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`),
  CONSTRAINT `bundle_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundle_items` */

/*Table structure for table `bundles` */

DROP TABLE IF EXISTS `bundles`;

CREATE TABLE `bundles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `bundle_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundles` */

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`slug`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Pocket','pocket-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Bonnel','bonnel-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'Hybird','hybird-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Mattress','mattress-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

/*Table structure for table `couriers` */

DROP TABLE IF EXISTS `couriers`;

CREATE TABLE `couriers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `shipping_cost` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `couriers` */

insert  into `couriers`(`id`,`name`,`type`,`shipping_cost`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'JNE','external',100000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'SiCepat','external',120000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'J&T','internal',130000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Kurir Toko','internal',140000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`name`,`phone`,`email`,`address`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'John Doe','1234567890','johndoe@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Jane Doe','123153242','janedoe@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'David Smith','64646546','david@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `inventory_stocks` */

DROP TABLE IF EXISTS `inventory_stocks`;

CREATE TABLE `inventory_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_stocks_product_id_foreign` (`product_id`),
  KEY `inventory_stocks_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `inventory_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `inventory_stocks_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `inventory_stocks` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `media` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_02_08_100253_create_permission_tables',1),
(5,'2026_02_08_104950_add_ui_preferences_to_users_table',1),
(6,'2026_02_08_114448_create_activity_log_table',1),
(7,'2026_02_08_114449_add_event_column_to_activity_log_table',1),
(8,'2026_02_08_114450_add_batch_uuid_column_to_activity_log_table',1),
(9,'2026_02_08_132648_create_media_table',1),
(10,'2026_03_01_201441_create_store_settings_table',1),
(11,'2026_03_02_082024_create_brands_table',1),
(12,'2026_03_02_083719_create_categories_table',1),
(13,'2026_03_02_085741_create_products_table',1),
(14,'2026_03_02_094948_create_product_images_table',1),
(15,'2026_03_04_043159_create_stock_movements_table',1),
(16,'2026_03_04_045242_create_inventory_stocks_table',1),
(17,'2026_03_06_081429_create_bundles_table',1),
(18,'2026_03_06_081430_create_bundle_items_table',1),
(19,'2026_03_06_085156_create_customers_table',1),
(20,'2026_03_07_080658_create_transactions_table',1),
(21,'2026_03_07_080659_create_promos_table',1),
(22,'2026_03_07_081942_create_promo_products_table',1),
(23,'2026_03_07_082134_create_promo_usages_table',1),
(24,'2026_03_07_083252_create_transaction_items_table',1),
(25,'2026_03_07_083451_create_transaction_payments_table',1),
(26,'2026_03_07_101656_create_purchase_orders_table',1),
(27,'2026_03_07_102109_create_purchase_order_items_table',1),
(28,'2026_03_07_104950_create_transaction_item_costs_table',1),
(29,'2026_03_10_191314_create_stock_adjustments_table',1),
(30,'2026_03_11_081431_add_store_setting_to_users',1),
(31,'2026_03_13_082405_add_store_setting_id_some_table',1),
(32,'2026_03_15_141019_create_couriers_table',1),
(33,'2026_03_15_141807_create_transaction_shipments_table',1),
(34,'2026_03_16_042629_add_bundle_id_to_transaction_items',1),
(35,'2026_03_16_055804_add_price_to_bundle_items',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(1,'App\\Models\\User',1),
(2,'App\\Models\\User',2),
(3,'App\\Models\\User',3),
(4,'App\\Models\\User',4);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'ViewAny:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(2,'View:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(3,'Create:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(4,'Update:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(5,'Delete:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(6,'Restore:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(7,'ForceDelete:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(8,'ForceDeleteAny:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(9,'RestoreAny:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(10,'Replicate:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(11,'Reorder:Brand','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(12,'ViewAny:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(13,'View:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(14,'Create:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(15,'Update:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(16,'Delete:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(17,'Restore:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(18,'ForceDelete:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(19,'ForceDeleteAny:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(20,'RestoreAny:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(21,'Replicate:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(22,'Reorder:Bundle','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(23,'ViewAny:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(24,'View:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(25,'Create:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(26,'Update:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(27,'Delete:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(28,'Restore:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(29,'ForceDelete:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(30,'ForceDeleteAny:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(31,'RestoreAny:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(32,'Replicate:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(33,'Reorder:Category','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(34,'ViewAny:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(35,'View:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(36,'Create:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(37,'Update:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(38,'Delete:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(39,'Restore:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(40,'ForceDelete:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(41,'ForceDeleteAny:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(42,'RestoreAny:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(43,'Replicate:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(44,'Reorder:Courier','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(45,'ViewAny:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(46,'View:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(47,'Create:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(48,'Update:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(49,'Delete:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(50,'Restore:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(51,'ForceDelete:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(52,'ForceDeleteAny:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(53,'RestoreAny:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(54,'Replicate:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(55,'Reorder:Customer','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(56,'ViewAny:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(57,'View:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(58,'Create:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(59,'Update:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(60,'Delete:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(61,'Restore:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(62,'ForceDelete:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(63,'ForceDeleteAny:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(64,'RestoreAny:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(65,'Replicate:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(66,'Reorder:InventoryStock','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(67,'ViewAny:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(68,'View:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(69,'Create:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(70,'Update:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(71,'Delete:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(72,'Restore:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(73,'ForceDelete:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(74,'ForceDeleteAny:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(75,'RestoreAny:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(76,'Replicate:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(77,'Reorder:Product','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(78,'ViewAny:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(79,'View:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(80,'Create:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(81,'Update:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(82,'Delete:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(83,'Restore:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(84,'ForceDelete:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(85,'ForceDeleteAny:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(86,'RestoreAny:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(87,'Replicate:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(88,'Reorder:Promo','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(89,'ViewAny:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(90,'View:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(91,'Create:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(92,'Update:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(93,'Delete:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(94,'Restore:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(95,'ForceDelete:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(96,'ForceDeleteAny:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(97,'RestoreAny:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(98,'Replicate:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(99,'Reorder:PurchaseOrder','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(100,'ViewAny:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(101,'View:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(102,'Create:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(103,'Update:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(104,'Delete:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(105,'Restore:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(106,'ForceDelete:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(107,'ForceDeleteAny:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(108,'RestoreAny:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(109,'Replicate:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(110,'Reorder:Role','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(111,'ViewAny:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(112,'View:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(113,'Create:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(114,'Update:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(115,'Delete:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(116,'Restore:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(117,'ForceDelete:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(118,'ForceDeleteAny:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(119,'RestoreAny:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(120,'Replicate:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(121,'Reorder:StoreSetting','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(122,'ViewAny:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(123,'View:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(124,'Create:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(125,'Update:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(126,'Delete:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(127,'Restore:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(128,'ForceDelete:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(129,'ForceDeleteAny:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(130,'RestoreAny:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(131,'Replicate:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(132,'Reorder:Transaction','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(133,'ViewAny:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(134,'View:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(135,'Create:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(136,'Update:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(137,'Delete:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(138,'Restore:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(139,'ForceDelete:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(140,'ForceDeleteAny:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(141,'RestoreAny:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(142,'Replicate:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(143,'Reorder:User','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(144,'ViewAny:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(145,'View:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(146,'Create:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(147,'Update:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(148,'Delete:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(149,'Restore:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(150,'ForceDelete:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(151,'ForceDeleteAny:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(152,'RestoreAny:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(153,'Replicate:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(154,'Reorder:Activity','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(155,'View:Dashboard','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(156,'View:AccountWidget','web','2026-03-16 09:02:17','2026-03-16 09:02:17'),
(157,'View:FilamentInfoWidget','web','2026-03-16 09:02:17','2026-03-16 09:02:17');

/*Table structure for table `product_images` */

DROP TABLE IF EXISTS `product_images`;

CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `image_product` text DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_images` */

insert  into `product_images`(`id`,`product_id`,`image_product`,`is_primary`) values 
(2,1,NULL,0),
(4,2,NULL,0),
(6,3,NULL,0),
(8,4,NULL,0);

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `products_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`store_setting_id`,`brand_id`,`category_id`,`name`,`type`,`selling_price`,`sku`,`size`,`weight`,`color`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,NULL,3,2,'Kasur 1','headboard',1500000.00,'SK-09','single',12.00,'Red',1,'2026-03-16 13:53:54','2026-03-16 13:53:54',1,1,NULL,NULL),
(2,NULL,1,3,'Kasur 2','headboard',950000.00,'KIK-019','custom',10.00,'Blue',1,'2026-03-16 13:57:47','2026-03-16 13:57:47',1,1,NULL,NULL),
(3,NULL,2,2,'Kasur 3','bundle',2100000.00,'OPO-01','queen',15.00,'Green',1,'2026-03-16 13:58:19','2026-03-16 13:58:19',1,1,NULL,NULL),
(4,NULL,4,4,'Kasur 4','springbed',800000.00,'LOP-133','single',7.20,'Orange',1,'2026-03-16 13:59:53','2026-03-16 13:59:53',1,1,NULL,NULL);

/*Table structure for table `promo_products` */

DROP TABLE IF EXISTS `promo_products`;

CREATE TABLE `promo_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promo_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promo_products_promo_id_foreign` (`promo_id`),
  KEY `promo_products_product_id_foreign` (`product_id`),
  CONSTRAINT `promo_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `promo_products_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_products` */

/*Table structure for table `promo_usages` */

DROP TABLE IF EXISTS `promo_usages`;

CREATE TABLE `promo_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promo_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promo_usages_promo_id_foreign` (`promo_id`),
  KEY `promo_usages_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `promo_usages_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`),
  CONSTRAINT `promo_usages_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_usages` */

/*Table structure for table `promos` */

DROP TABLE IF EXISTS `promos`;

CREATE TABLE `promos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promos` */

/*Table structure for table `purchase_order_items` */

DROP TABLE IF EXISTS `purchase_order_items`;

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `qty_purchased` int(11) NOT NULL,
  `qty_remaining` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_order_items` */

/*Table structure for table `purchase_orders` */

DROP TABLE IF EXISTS `purchase_orders`;

CREATE TABLE `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_orders_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `purchase_orders_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_orders` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values 
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(17,1),
(18,1),
(19,1),
(20,1),
(21,1),
(22,1),
(23,1),
(24,1),
(25,1),
(26,1),
(27,1),
(28,1),
(29,1),
(30,1),
(31,1),
(32,1),
(33,1),
(34,1),
(35,1),
(36,1),
(37,1),
(38,1),
(39,1),
(40,1),
(41,1),
(42,1),
(43,1),
(44,1),
(45,1),
(46,1),
(47,1),
(48,1),
(49,1),
(50,1),
(51,1),
(52,1),
(53,1),
(54,1),
(55,1),
(56,1),
(57,1),
(58,1),
(59,1),
(60,1),
(61,1),
(62,1),
(63,1),
(64,1),
(65,1),
(66,1),
(67,1),
(68,1),
(69,1),
(70,1),
(71,1),
(72,1),
(73,1),
(74,1),
(75,1),
(76,1),
(77,1),
(78,1),
(79,1),
(80,1),
(81,1),
(82,1),
(83,1),
(84,1),
(85,1),
(86,1),
(87,1),
(88,1),
(89,1),
(90,1),
(91,1),
(92,1),
(93,1),
(94,1),
(95,1),
(96,1),
(97,1),
(98,1),
(99,1),
(100,1),
(101,1),
(102,1),
(103,1),
(104,1),
(105,1),
(106,1),
(107,1),
(108,1),
(109,1),
(110,1),
(111,1),
(112,1),
(113,1),
(114,1),
(115,1),
(116,1),
(117,1),
(118,1),
(119,1),
(120,1),
(121,1),
(122,1),
(123,1),
(124,1),
(125,1),
(126,1),
(127,1),
(128,1),
(129,1),
(130,1),
(131,1),
(132,1),
(133,1),
(134,1),
(135,1),
(136,1),
(137,1),
(138,1),
(139,1),
(140,1),
(141,1),
(142,1),
(143,1),
(144,1),
(145,1),
(146,1),
(147,1),
(148,1),
(149,1),
(150,1),
(151,1),
(152,1),
(153,1),
(154,1),
(155,1),
(156,1),
(157,1);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'Super Admin','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(2,'Admin','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(3,'Owner','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(4,'Staff','web','2026-03-16 09:02:05','2026-03-16 09:02:05');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('kvOLOvllN0TFQOv9FUd0neuCa8QJnRcRbJTLWxGH',1,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiTzgyenpRZUtPYlY4M3hscVNzaGVPNFdPTThyZUtkSERRcmtyN0FicSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwczovL3Bvcy1zcHJpbmdiZWQubG9jYWwvYWRtaW4vcHVyY2hhc2Utb3JkZXJzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czo0NzoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnB1cmNoYXNlLW9yZGVycy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiI4Mjc2M2QwYzliMDNmMmVmNWViOTg1NTliNGY4NDA2YzA0NzUyOTdlNGYxN2FhNWFkNTI0NjAwNDI0OWIxMTdlIjtzOjY6InRhYmxlcyI7YTo0OntzOjQwOiJiYzEwNDZkMjgzNDVmODZkZDAwMGRkNWJmNzVjZWI1OV9jb2x1bW5zIjthOjEwOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJ0eXBlIjtzOjU6ImxhYmVsIjtzOjQ6IlR5cGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJkaXNjb3VudF90eXBlIjtzOjU6ImxhYmVsIjtzOjEzOiJEaXNjb3VudCB0eXBlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoiZGlzY291bnRfdmFsdWUiO3M6NToibGFiZWwiO3M6MTQ6IkRpc2NvdW50IHZhbHVlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoibWluX3B1cmNoYXNlIjtzOjU6ImxhYmVsIjtzOjEyOiJNaW4gcHVyY2hhc2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ1c2FnZV9saW1pdCI7czo1OiJsYWJlbCI7czoxMToiVXNhZ2UgbGltaXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ1c2FnZV9jb3VudCI7czo1OiJsYWJlbCI7czoxMToiVXNhZ2UgY291bnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJzdGFydF9kYXRlIjtzOjU6ImxhYmVsIjtzOjEwOiJTdGFydCBkYXRlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJlbmRfZGF0ZSI7czo1OiJsYWJlbCI7czo4OiJFbmQgZGF0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjk7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjk6IklzIGFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiOGZhYzZlYjFjZWMyNjgwM2IzZjdmYjQ0MGEyNzExMWJfY29sdW1ucyI7YTo4OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo3OiJQcm9kdWN0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMzoic3RvcmVTZXR0aW5nLnN0b3JlX25hbWUiO3M6NToibGFiZWwiO3M6NToiU3RvcmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjM6InNrdSI7czo1OiJsYWJlbCI7czozOiJTS1UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjYXRlZ29yeS5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkNhdGVnb3J5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzaXplIjtzOjU6ImxhYmVsIjtzOjQ6IlNpemUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJzZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEzOiJTZWxsaW5nIFByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyNDoiaW52ZW50b3J5U3RvY2tzLnF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjU6IlN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NjoiQWN0aXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiJkYzQyZThlMmIwNzVjZTJlYTg0ZGNmMWI1YTczOWU0Yl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToicHJvZHVjdC5za3UiO3M6NToibGFiZWwiO3M6MzoiU0tVIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoicHJvZHVjdC5uYW1lIjtzOjU6ImxhYmVsIjtzOjc6IlByb2R1Y3QiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjIxOiJwcm9kdWN0LmNhdGVnb3J5Lm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ2F0ZWdvcnkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjU6IlN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMToicHJvZHVjdC5zZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEwOiJVbml0IFByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToidG90YWxfdmFsdWUiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIFZhbHVlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI4MDk1Zjg5M2ZhMzcwMGYwMWQ2MDBlZDNlMGQxZTAxZF9jb2x1bW5zIjthOjQ6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoic3VwcGxpZXJfbmFtZSI7czo1OiJsYWJlbCI7czoxMzoiU3VwcGxpZXIgbmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6Imludm9pY2VfbnVtYmVyIjtzOjU6ImxhYmVsIjtzOjE0OiJJbnZvaWNlIG51bWJlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InB1cmNoYXNlX2RhdGUiO3M6NToibGFiZWwiO3M6MTM6IlB1cmNoYXNlIGRhdGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJ0b3RhbF9hbW91bnQiO3M6NToibGFiZWwiO3M6MTI6IlRvdGFsIGFtb3VudCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1773644494);

/*Table structure for table `stock_adjustments` */

DROP TABLE IF EXISTS `stock_adjustments`;

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `qty_before` int(11) DEFAULT NULL,
  `qty_after` int(11) DEFAULT NULL,
  `qty_difference` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_product_id_foreign` (`product_id`),
  KEY `stock_adjustments_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `stock_adjustments_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_adjustments` */

/*Table structure for table `stock_movements` */

DROP TABLE IF EXISTS `stock_movements`;

CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `reference_type` varchar(255) NOT NULL,
  `reference_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `cost_price_snapshot` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_store_setting_id_foreign` (`store_setting_id`),
  KEY `stock_movements_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `stock_movements_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_movements` */

/*Table structure for table `store_settings` */

DROP TABLE IF EXISTS `store_settings`;

CREATE TABLE `store_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `store_settings` */

insert  into `store_settings`(`id`,`store_name`,`phone`,`email`,`address`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Toko 1','1234567890','toko1@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Toko 2','123153242','toko2@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'Toko 3','64646546','toko3@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

/*Table structure for table `transaction_item_costs` */

DROP TABLE IF EXISTS `transaction_item_costs`;

CREATE TABLE `transaction_item_costs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_item_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_order_item_id` bigint(20) unsigned DEFAULT NULL,
  `qty_taken` int(11) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_item_costs_transaction_item_id_foreign` (`transaction_item_id`),
  KEY `transaction_item_costs_purchase_order_item_id_foreign` (`purchase_order_item_id`),
  CONSTRAINT `transaction_item_costs_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`),
  CONSTRAINT `transaction_item_costs_transaction_item_id_foreign` FOREIGN KEY (`transaction_item_id`) REFERENCES `transaction_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_item_costs` */

/*Table structure for table `transaction_items` */

DROP TABLE IF EXISTS `transaction_items`;

CREATE TABLE `transaction_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `bundle_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  KEY `transaction_items_bundle_id_foreign` (`bundle_id`),
  CONSTRAINT `transaction_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_items` */

/*Table structure for table `transaction_payments` */

DROP TABLE IF EXISTS `transaction_payments`;

CREATE TABLE `transaction_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_payments_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `transaction_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_payments` */

/*Table structure for table `transaction_shipments` */

DROP TABLE IF EXISTS `transaction_shipments`;

CREATE TABLE `transaction_shipments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `courier_id` bigint(20) unsigned NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_shipments_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_shipments_courier_id_foreign` (`courier_id`),
  CONSTRAINT `transaction_shipments_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id`),
  CONSTRAINT `transaction_shipments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_shipments` */

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_code` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `discount_total` decimal(10,2) DEFAULT NULL,
  `shiping_cost` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `promo_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_customer_id_foreign` (`customer_id`),
  KEY `transactions_promo_id_foreign` (`promo_id`),
  KEY `transactions_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `transactions_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `transactions_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transactions` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `store_setting_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `ui_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ui_preferences`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `users_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`is_active`,`store_setting_id`,`remember_token`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`,`ui_preferences`) values 
(1,'Super Admin','superadmin@springbed.id',NULL,'$2y$12$Z76zA490/7lL3SdvtcEsIO4k5IFQn.e4L6JXPRICnxfBEOxVdOTGC',1,NULL,NULL,'2026-03-16 09:02:05','2026-03-16 09:02:05',NULL,NULL,NULL,NULL,NULL),
(2,'Admin','admin@springbed.id',NULL,'$2y$12$XQ8RrYRpi1NODZ.mrKNHHeQy4OmrzvrcZX8J9D0NgFJpz8mdtUYLi',1,NULL,NULL,'2026-03-16 09:02:05','2026-03-16 09:02:05',NULL,NULL,NULL,NULL,NULL),
(3,'Owner','owner@springbed.id',NULL,'$2y$12$tx7nLgi7Oy6gBYeLOckpbu4rSm9.lJxoonMdoDmODDuIAUuOJT7rO',1,NULL,NULL,'2026-03-16 09:02:05','2026-03-16 09:02:05',NULL,NULL,NULL,NULL,NULL),
(4,'Staff','staff@springbed.id',NULL,'$2y$12$CbaPoHpdkyXUDY2ebMlNKO4YV6Oes.3rWobmJGYTByhGfOmXoMiNu',1,NULL,NULL,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
