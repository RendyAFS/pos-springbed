/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - pos_springbed
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `activity_log` */

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activity_log` */

insert  into `activity_log`(`id`,`log_name`,`description`,`subject_type`,`event`,`subject_id`,`causer_type`,`causer_id`,`properties`,`batch_uuid`,`created_at`,`updated_at`) values
(1,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',NULL,'2026-05-01 13:15:20','2026-05-01 13:15:20'),
(2,'Resource','Category Created','App\\Models\\Category','Created',1,NULL,NULL,'{\"id\": 1, \"name\": \"Pocket\", \"slug\": \"pocket-1777616251\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(3,'Resource','Category Created','App\\Models\\Category','Created',2,NULL,NULL,'{\"id\": 2, \"name\": \"Bonnel\", \"slug\": \"bonnel-1777616251\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(4,'Resource','Category Created','App\\Models\\Category','Created',3,NULL,NULL,'{\"id\": 3, \"name\": \"Hybird\", \"slug\": \"hybird-1777616251\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(5,'Resource','Category Created','App\\Models\\Category','Created',4,NULL,NULL,'{\"id\": 4, \"name\": \"Mattress\", \"slug\": \"mattress-1777616251\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(6,'Resource','Brand Created','App\\Models\\Brand','Created',1,NULL,NULL,'{\"id\": 1, \"name\": \"King Koil\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(7,'Resource','Brand Created','App\\Models\\Brand','Created',2,NULL,NULL,'{\"id\": 2, \"name\": \"Serta\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(8,'Resource','Brand Created','App\\Models\\Brand','Created',3,NULL,NULL,'{\"id\": 3, \"name\": \"Comforta\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(9,'Resource','Brand Created','App\\Models\\Brand','Created',4,NULL,NULL,'{\"id\": 4, \"name\": \"Florence\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(10,'Resource','Courier Created','App\\Models\\Courier','Created',1,NULL,NULL,'{\"id\": 1, \"name\": \"JNE\", \"type\": \"external\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null, \"shipping_cost\": 100000}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(11,'Resource','Courier Created','App\\Models\\Courier','Created',2,NULL,NULL,'{\"id\": 2, \"name\": \"SiCepat\", \"type\": \"external\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null, \"shipping_cost\": 120000}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(12,'Resource','Courier Created','App\\Models\\Courier','Created',3,NULL,NULL,'{\"id\": 3, \"name\": \"J&T\", \"type\": \"internal\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null, \"shipping_cost\": 130000}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(13,'Resource','Courier Created','App\\Models\\Courier','Created',4,NULL,NULL,'{\"id\": 4, \"name\": \"Kurir Toko\", \"type\": \"internal\", \"is_active\": true, \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null, \"shipping_cost\": 140000}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(14,'Resource','Customer Created','App\\Models\\Customer','Created',1,NULL,NULL,'{\"id\": 1, \"name\": \"John Doe\", \"email\": \"johndoe@yahoo.com\", \"phone\": \"1234567890\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(15,'Resource','Customer Created','App\\Models\\Customer','Created',2,NULL,NULL,'{\"id\": 2, \"name\": \"Jane Doe\", \"email\": \"janedoe@yahoo.com\", \"phone\": \"123153242\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(16,'Resource','Customer Created','App\\Models\\Customer','Created',3,NULL,NULL,'{\"id\": 3, \"name\": \"David Smith\", \"email\": \"david@yahoo.com\", \"phone\": \"64646546\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(17,'Resource','Store Setting Created','App\\Models\\StoreSetting','Created',1,NULL,NULL,'{\"id\": 1, \"email\": \"toko1@yahoo.com\", \"phone\": \"1234567890\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"store_name\": \"Toko 1\", \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(18,'Resource','Store Setting Created','App\\Models\\StoreSetting','Created',2,NULL,NULL,'{\"id\": 2, \"email\": \"toko2@yahoo.com\", \"phone\": \"123153242\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"store_name\": \"Toko 2\", \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31'),
(19,'Resource','Store Setting Created','App\\Models\\StoreSetting','Created',3,NULL,NULL,'{\"id\": 3, \"email\": \"toko3@yahoo.com\", \"phone\": \"64646546\", \"address\": \"123 Main St\", \"created_at\": \"2026-05-01 13:17:31\", \"created_by\": null, \"store_name\": \"Toko 3\", \"updated_at\": \"2026-05-01 13:17:31\", \"updated_by\": null}',NULL,'2026-05-01 13:17:31','2026-05-01 13:17:31');

/*Table structure for table `brands` */

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `brands` */

insert  into `brands`(`id`,`name`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'King Koil',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(2,'Serta',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(3,'Comforta',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(4,'Florence',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL);

/*Table structure for table `bundle_items` */

DROP TABLE IF EXISTS `bundle_items`;

CREATE TABLE `bundle_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `qty` int DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bundle_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundles` */

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`slug`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Pocket','pocket-1777616251',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(2,'Bonnel','bonnel-1777616251',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(3,'Hybird','hybird-1777616251',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(4,'Mattress','mattress-1777616251',1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL);

/*Table structure for table `couriers` */

DROP TABLE IF EXISTS `couriers`;

CREATE TABLE `couriers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `couriers` */

insert  into `couriers`(`id`,`name`,`type`,`shipping_cost`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'JNE','external',100000.00,1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(2,'SiCepat','external',120000.00,1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(3,'J&T','internal',130000.00,1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(4,'Kurir Toko','internal',140000.00,1,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL);

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`name`,`phone`,`email`,`address`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'John Doe','1234567890','johndoe@yahoo.com','123 Main St','2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(2,'Jane Doe','123153242','janedoe@yahoo.com','123 Main St','2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(3,'David Smith','64646546','david@yahoo.com','123 Main St','2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `inventory_stocks` */

DROP TABLE IF EXISTS `inventory_stocks`;

CREATE TABLE `inventory_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `quantity` int DEFAULT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
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
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(20,'2026_03_07_080657_create_promos_table',1),
(21,'2026_03_07_080658_create_transactions_table',1),
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
(35,'2026_03_16_055804_add_price_to_bundle_items',1),
(36,'2026_04_29_083004_create_referals_table',1),
(37,'2026_05_01_084344_add_set_max_reward_to_store_settings',1),
(38,'2026_05_01_092156_add_use_discount_referal_to_transactions',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values
(1,'ViewAny:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(2,'View:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(3,'Create:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(4,'Update:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(5,'Delete:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(6,'Restore:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(7,'ForceDelete:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(8,'ForceDeleteAny:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(9,'RestoreAny:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(10,'Replicate:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(11,'Reorder:Brand','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(12,'ViewAny:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(13,'View:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(14,'Create:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(15,'Update:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(16,'Delete:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(17,'Restore:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(18,'ForceDelete:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(19,'ForceDeleteAny:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(20,'RestoreAny:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(21,'Replicate:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(22,'Reorder:Bundle','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(23,'ViewAny:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(24,'View:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(25,'Create:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(26,'Update:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(27,'Delete:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(28,'Restore:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(29,'ForceDelete:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(30,'ForceDeleteAny:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(31,'RestoreAny:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(32,'Replicate:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(33,'Reorder:Category','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(34,'ViewAny:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(35,'View:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(36,'Create:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(37,'Update:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(38,'Delete:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(39,'Restore:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(40,'ForceDelete:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(41,'ForceDeleteAny:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(42,'RestoreAny:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(43,'Replicate:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(44,'Reorder:Courier','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(45,'ViewAny:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(46,'View:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(47,'Create:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(48,'Update:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(49,'Delete:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(50,'Restore:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(51,'ForceDelete:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(52,'ForceDeleteAny:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(53,'RestoreAny:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(54,'Replicate:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(55,'Reorder:Customer','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(56,'ViewAny:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(57,'View:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(58,'Create:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(59,'Update:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(60,'Delete:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(61,'Restore:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(62,'ForceDelete:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(63,'ForceDeleteAny:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(64,'RestoreAny:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(65,'Replicate:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(66,'Reorder:InventoryStock','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(67,'ViewAny:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(68,'View:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(69,'Create:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(70,'Update:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(71,'Delete:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(72,'Restore:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(73,'ForceDelete:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(74,'ForceDeleteAny:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(75,'RestoreAny:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(76,'Replicate:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(77,'Reorder:Product','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(78,'ViewAny:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(79,'View:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(80,'Create:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(81,'Update:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(82,'Delete:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(83,'Restore:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(84,'ForceDelete:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(85,'ForceDeleteAny:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(86,'RestoreAny:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(87,'Replicate:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(88,'Reorder:Promo','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(89,'ViewAny:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(90,'View:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(91,'Create:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(92,'Update:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(93,'Delete:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(94,'Restore:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(95,'ForceDelete:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(96,'ForceDeleteAny:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(97,'RestoreAny:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(98,'Replicate:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(99,'Reorder:PurchaseOrder','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(100,'ViewAny:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(101,'View:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(102,'Create:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(103,'Update:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(104,'Delete:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(105,'Restore:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(106,'ForceDelete:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(107,'ForceDeleteAny:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(108,'RestoreAny:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(109,'Replicate:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(110,'Reorder:Role','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(111,'ViewAny:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(112,'View:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(113,'Create:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(114,'Update:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(115,'Delete:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(116,'Restore:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(117,'ForceDelete:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(118,'ForceDeleteAny:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(119,'RestoreAny:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(120,'Replicate:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(121,'Reorder:StoreSetting','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(122,'ViewAny:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(123,'View:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(124,'Create:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(125,'Update:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(126,'Delete:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(127,'Restore:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(128,'ForceDelete:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(129,'ForceDeleteAny:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(130,'RestoreAny:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(131,'Replicate:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(132,'Reorder:Transaction','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(133,'ViewAny:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(134,'View:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(135,'Create:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(136,'Update:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(137,'Delete:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(138,'Restore:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(139,'ForceDelete:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(140,'ForceDeleteAny:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(141,'RestoreAny:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(142,'Replicate:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(143,'Reorder:User','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(144,'ViewAny:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(145,'View:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(146,'Create:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(147,'Update:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(148,'Delete:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(149,'Restore:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(150,'ForceDelete:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(151,'ForceDeleteAny:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(152,'RestoreAny:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(153,'Replicate:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(154,'Reorder:Activity','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(155,'View:Dashboard','web','2026-05-01 13:14:56','2026-05-01 13:14:56'),
(156,'View:SelectStore','web','2026-05-01 13:14:57','2026-05-01 13:14:57'),
(157,'View:StatsOverviewWidget','web','2026-05-01 13:14:57','2026-05-01 13:14:57'),
(158,'View:SalesOverviewChartWidget','web','2026-05-01 13:14:57','2026-05-01 13:14:57'),
(159,'View:RightPanelWidget','web','2026-05-01 13:14:57','2026-05-01 13:14:57');

/*Table structure for table `product_images` */

DROP TABLE IF EXISTS `product_images`;

CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_product` text COLLATE utf8mb4_unicode_ci,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_images` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `products_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

/*Table structure for table `promo_products` */

DROP TABLE IF EXISTS `promo_products`;

CREATE TABLE `promo_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promo_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promo_id` bigint unsigned DEFAULT NULL,
  `transaction_id` bigint unsigned DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_count` int NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promos` */

/*Table structure for table `purchase_order_items` */

DROP TABLE IF EXISTS `purchase_order_items`;

CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `qty_purchased` int NOT NULL,
  `qty_remaining` int NOT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_orders_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `purchase_orders_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_orders` */

/*Table structure for table `referals` */

DROP TABLE IF EXISTS `referals`;

CREATE TABLE `referals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `name_customer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referals_referal_code_unique` (`referal_code`),
  KEY `referals_customer_id_foreign` (`customer_id`),
  CONSTRAINT `referals_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `referals` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
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
(157,1),
(158,1),
(159,1);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values
(1,'Super Admin','web','2026-05-01 13:14:43','2026-05-01 13:14:43'),
(2,'Admin','web','2026-05-01 13:14:43','2026-05-01 13:14:43'),
(3,'Owner','web','2026-05-01 13:14:43','2026-05-01 13:14:43'),
(4,'Staff','web','2026-05-01 13:14:43','2026-05-01 13:14:43');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

/*Table structure for table `stock_adjustments` */

DROP TABLE IF EXISTS `stock_adjustments`;

CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `qty_before` int DEFAULT NULL,
  `qty_after` int DEFAULT NULL,
  `qty_difference` int DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `qty` int DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `set_max_reward` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `store_settings` */

insert  into `store_settings`(`id`,`store_name`,`phone`,`email`,`address`,`set_max_reward`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Toko 1','1234567890','toko1@yahoo.com','123 Main St',0,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(2,'Toko 2','123153242','toko2@yahoo.com','123 Main St',0,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL),
(3,'Toko 3','64646546','toko3@yahoo.com','123 Main St',0,'2026-05-01 13:17:31','2026-05-01 13:17:31',NULL,NULL,NULL,NULL);

/*Table structure for table `transaction_item_costs` */

DROP TABLE IF EXISTS `transaction_item_costs`;

CREATE TABLE `transaction_item_costs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_item_id` bigint unsigned DEFAULT NULL,
  `purchase_order_item_id` bigint unsigned DEFAULT NULL,
  `qty_taken` int DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `bundle_id` bigint unsigned DEFAULT NULL,
  `qty` int NOT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `courier_id` bigint unsigned NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `discount_total` decimal(10,2) DEFAULT NULL,
  `shiping_cost` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_id` bigint unsigned DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `is_referal` tinyint(1) NOT NULL DEFAULT '0',
  `referal_customer_id` bigint unsigned DEFAULT NULL,
  `nominal_referal` int DEFAULT NULL,
  `use_discount_referal` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_customer_id_foreign` (`customer_id`),
  KEY `transactions_promo_id_foreign` (`promo_id`),
  KEY `transactions_store_setting_id_foreign` (`store_setting_id`),
  KEY `transactions_referal_customer_id_foreign` (`referal_customer_id`),
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `transactions_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`),
  CONSTRAINT `transactions_referal_customer_id_foreign` FOREIGN KEY (`referal_customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transactions` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `store_setting_id` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `ui_preferences` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `users_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`is_active`,`store_setting_id`,`remember_token`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`,`ui_preferences`) values
(1,'Super Admin','superadmin@springbed.id',NULL,'$2y$12$cCakAHDeV5xHFubmucO44uZbEMsFw3UpgAZUTT0J8MPzsvQT/CG3.',1,NULL,NULL,'2026-05-01 13:14:43','2026-05-01 13:14:43',NULL,NULL,NULL,NULL,NULL),
(2,'Admin','admin@springbed.id',NULL,'$2y$12$bGE8at9MKThOSxJdxkfcbu4to9vuMgGX008h3JfEShf2qgHn9h9JW',1,NULL,NULL,'2026-05-01 13:14:43','2026-05-01 13:14:43',NULL,NULL,NULL,NULL,NULL),
(3,'Owner','owner@springbed.id',NULL,'$2y$12$8S5gPYtBevsQz/M0.UobuOX/U3MZ1hhgrcT1UTjvzsTnUVj5njUma',1,NULL,NULL,'2026-05-01 13:14:43','2026-05-01 13:14:43',NULL,NULL,NULL,NULL,NULL),
(4,'Staff','staff@springbed.id',NULL,'$2y$12$GFbBn4PwvgFhBBv38dr4.uZwSzLqJI8C4YrORpjvHkJ8M9CF3EYMa',1,NULL,NULL,'2026-05-01 13:14:44','2026-05-01 13:14:44',NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
