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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pos_springbed` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activity_log` */

insert  into `activity_log`(`id`,`log_name`,`description`,`subject_type`,`event`,`subject_id`,`causer_type`,`causer_id`,`properties`,`batch_uuid`,`created_at`,`updated_at`) values
(1,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Linux; Android 6.0; Nexus 5 Build\\/MRA58N) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/147.0.0.0 Mobile Safari\\/537.36\"}',NULL,'2026-04-29 08:41:47','2026-04-29 08:41:47'),
(2,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',1,'App\\Models\\User',1,'{\"size\":\"single\",\"type\":\"springbed\",\"name\":\"Kasur 1\",\"selling_price\":152000,\"sku\":\"KET-123\",\"weight\":12,\"color\":\"Red\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:45:19\",\"created_at\":\"2026-04-29 08:45:19\",\"id\":1}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(3,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"brand_id\":3}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(4,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"category_id\":2}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(5,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',1,'App\\Models\\User',1,'{\"product_id\":1,\"store_setting_id\":1,\"quantity\":7,\"updated_at\":\"2026-04-29 08:45:19\",\"created_at\":\"2026-04-29 08:45:19\",\"id\":1}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(6,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',2,'App\\Models\\User',1,'{\"product_id\":1,\"store_setting_id\":2,\"quantity\":15,\"updated_at\":\"2026-04-29 08:45:19\",\"created_at\":\"2026-04-29 08:45:19\",\"id\":2}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(7,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',3,'App\\Models\\User',1,'{\"product_id\":1,\"store_setting_id\":3,\"quantity\":20,\"updated_at\":\"2026-04-29 08:45:19\",\"created_at\":\"2026-04-29 08:45:19\",\"id\":3}',NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(8,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',2,'App\\Models\\User',1,'{\"size\":\"custom\",\"type\":\"headboard\",\"name\":\"Kasur 2\",\"selling_price\":250000,\"sku\":\"SK-912\",\"weight\":13,\"color\":\"Blue\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:45:59\",\"created_at\":\"2026-04-29 08:45:59\",\"id\":2}',NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(9,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"brand_id\":4}',NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(10,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"category_id\":4}',NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(11,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',4,'App\\Models\\User',1,'{\"product_id\":2,\"store_setting_id\":1,\"quantity\":12,\"updated_at\":\"2026-04-29 08:45:59\",\"created_at\":\"2026-04-29 08:45:59\",\"id\":4}',NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(12,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',5,'App\\Models\\User',1,'{\"product_id\":2,\"store_setting_id\":2,\"quantity\":15,\"updated_at\":\"2026-04-29 08:45:59\",\"created_at\":\"2026-04-29 08:45:59\",\"id\":5}',NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(13,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',3,'App\\Models\\User',1,'{\"size\":\"king\",\"type\":\"headboard\",\"name\":\"Kasur 3\",\"selling_price\":500000,\"sku\":\"ASID-12\",\"weight\":12,\"color\":\"Green\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:46:35\",\"created_at\":\"2026-04-29 08:46:35\",\"id\":3}',NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(14,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"brand_id\":4}',NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(15,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"category_id\":4}',NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(16,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',6,'App\\Models\\User',1,'{\"product_id\":3,\"store_setting_id\":3,\"quantity\":11,\"updated_at\":\"2026-04-29 08:46:35\",\"created_at\":\"2026-04-29 08:46:35\",\"id\":6}',NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(17,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',4,'App\\Models\\User',1,'{\"size\":\"queen\",\"type\":\"divan\",\"name\":\"Kasur 4\",\"selling_price\":450000,\"sku\":\"HJ-912\",\"weight\":12,\"color\":\"Purple\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:47:15\",\"created_at\":\"2026-04-29 08:47:15\",\"id\":4}',NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15'),
(18,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"brand_id\":1}',NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15'),
(19,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"category_id\":3}',NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15'),
(20,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',7,'App\\Models\\User',1,'{\"product_id\":4,\"store_setting_id\":2,\"quantity\":15,\"updated_at\":\"2026-04-29 08:47:15\",\"created_at\":\"2026-04-29 08:47:15\",\"id\":7}',NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15'),
(21,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',1,'App\\Models\\User',1,'{\"name\":\"Kasur 1\",\"type\":\"flash_sale\",\"discount_type\":\"nominal\",\"discount_value\":100000,\"min_purchase\":50000,\"start_date\":\"2026-04-29 00:00:00\",\"end_date\":\"2026-08-31 00:00:00\",\"is_active\":true,\"usage_limit\":10,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:48:14\",\"created_at\":\"2026-04-29 08:48:14\",\"id\":1}',NULL,'2026-04-29 08:48:14','2026-04-29 08:48:14'),
(22,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',2,'App\\Models\\User',1,'{\"name\":\"All\",\"type\":\"flash_sale\",\"discount_type\":\"percentage\",\"discount_value\":50,\"min_purchase\":99999,\"start_date\":\"2026-04-29 00:00:00\",\"end_date\":\"2026-08-31 00:00:00\",\"is_active\":true,\"usage_limit\":8,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:48:46\",\"created_at\":\"2026-04-29 08:48:46\",\"id\":2}',NULL,'2026-04-29 08:48:46','2026-04-29 08:48:46'),
(23,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',3,'App\\Models\\User',1,'{\"name\":\"Ongkir\",\"type\":\"free_shipping\",\"discount_type\":\"nominal\",\"discount_value\":120000,\"min_purchase\":100000,\"start_date\":\"2026-04-29 00:00:00\",\"end_date\":\"2026-08-31 00:00:00\",\"is_active\":true,\"usage_limit\":10,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:49:15\",\"created_at\":\"2026-04-29 08:49:15\",\"id\":3}',NULL,'2026-04-29 08:49:15','2026-04-29 08:49:15'),
(24,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',1,'{\"type\":\"voucher\",\"updated_at\":\"2026-04-29 08:49:28\"}',NULL,'2026-04-29 08:49:28','2026-04-29 08:49:28'),
(25,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',1,'App\\Models\\User',1,'{\"name\":\"Bundle 1\",\"bundle_price\":362000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:50:05\",\"created_at\":\"2026-04-29 08:50:05\",\"id\":1}',NULL,'2026-04-29 08:50:05','2026-04-29 08:50:05'),
(26,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',2,'App\\Models\\User',1,'{\"name\":\"Bundle 2\",\"bundle_price\":680000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:50:26\",\"created_at\":\"2026-04-29 08:50:26\",\"id\":2}',NULL,'2026-04-29 08:50:26','2026-04-29 08:50:26'),
(27,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',3,'App\\Models\\User',1,'{\"name\":\"Bundle 3\",\"bundle_price\":850000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-04-29 08:50:44\",\"created_at\":\"2026-04-29 08:50:44\",\"id\":3}',NULL,'2026-04-29 08:50:44','2026-04-29 08:50:44');

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
(1,'King Koil',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(2,'Serta',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(3,'Comforta',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(4,'Florence',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundle_items` */

insert  into `bundle_items`(`id`,`bundle_id`,`product_id`,`qty`,`price`,`created_at`,`updated_at`) values
(1,1,1,1,132000.00,'2026-04-29 08:50:05','2026-04-29 08:50:05'),
(2,1,2,1,230000.00,'2026-04-29 08:50:05','2026-04-29 08:50:05'),
(3,2,2,1,230000.00,'2026-04-29 08:50:26','2026-04-29 08:50:26'),
(4,2,3,1,450000.00,'2026-04-29 08:50:26','2026-04-29 08:50:26'),
(5,3,4,1,400000.00,'2026-04-29 08:50:44','2026-04-29 08:50:44'),
(6,3,3,1,450000.00,'2026-04-29 08:50:44','2026-04-29 08:50:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundles` */

insert  into `bundles`(`id`,`name`,`bundle_price`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Bundle 1',362000.00,1,'2026-04-29 08:50:05','2026-04-29 08:50:05',1,1,NULL,NULL),
(2,'Bundle 2',680000.00,1,'2026-04-29 08:50:26','2026-04-29 08:50:26',1,1,NULL,NULL),
(3,'Bundle 3',850000.00,1,'2026-04-29 08:50:44','2026-04-29 08:50:44',1,1,NULL,NULL);

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
(1,'Pocket','pocket-1777426883',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(2,'Bonnel','bonnel-1777426883',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(3,'Hybird','hybird-1777426883',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(4,'Mattress','mattress-1777426883',1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL);

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
(1,'JNE','external',100000.00,1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(2,'SiCepat','external',120000.00,1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(3,'J&T','internal',130000.00,1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL),
(4,'Kurir Toko','internal',140000.00,1,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL);

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
(1,'John Doe','1234567890','johndoe@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL),
(2,'Jane Doe','123153242','janedoe@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL),
(3,'David Smith','64646546','david@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `inventory_stocks` */

insert  into `inventory_stocks`(`id`,`product_id`,`store_setting_id`,`quantity`,`created_at`,`updated_at`) values
(1,1,1,7,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(2,1,2,15,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(3,1,3,20,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(4,2,1,12,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(5,2,2,15,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(6,3,3,11,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(7,4,2,15,'2026-04-29 08:47:15','2026-04-29 08:47:15');

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(36,'2026_04_29_083004_create_referals_table',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values
(1,'ViewAny:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(2,'View:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(3,'Create:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(4,'Update:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(5,'Delete:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(6,'Restore:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(7,'ForceDelete:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(8,'ForceDeleteAny:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(9,'RestoreAny:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(10,'Replicate:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(11,'Reorder:Brand','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(12,'ViewAny:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(13,'View:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(14,'Create:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(15,'Update:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(16,'Delete:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(17,'Restore:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(18,'ForceDelete:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(19,'ForceDeleteAny:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(20,'RestoreAny:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(21,'Replicate:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(22,'Reorder:Bundle','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(23,'ViewAny:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(24,'View:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(25,'Create:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(26,'Update:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(27,'Delete:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(28,'Restore:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(29,'ForceDelete:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(30,'ForceDeleteAny:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(31,'RestoreAny:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(32,'Replicate:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(33,'Reorder:Category','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(34,'ViewAny:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(35,'View:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(36,'Create:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(37,'Update:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(38,'Delete:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(39,'Restore:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(40,'ForceDelete:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(41,'ForceDeleteAny:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(42,'RestoreAny:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(43,'Replicate:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(44,'Reorder:Courier','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(45,'ViewAny:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(46,'View:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(47,'Create:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(48,'Update:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(49,'Delete:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(50,'Restore:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(51,'ForceDelete:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(52,'ForceDeleteAny:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(53,'RestoreAny:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(54,'Replicate:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(55,'Reorder:Customer','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(56,'ViewAny:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(57,'View:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(58,'Create:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(59,'Update:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(60,'Delete:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(61,'Restore:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(62,'ForceDelete:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(63,'ForceDeleteAny:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(64,'RestoreAny:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(65,'Replicate:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(66,'Reorder:InventoryStock','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(67,'ViewAny:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(68,'View:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(69,'Create:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(70,'Update:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(71,'Delete:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(72,'Restore:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(73,'ForceDelete:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(74,'ForceDeleteAny:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(75,'RestoreAny:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(76,'Replicate:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(77,'Reorder:Product','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(78,'ViewAny:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(79,'View:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(80,'Create:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(81,'Update:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(82,'Delete:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(83,'Restore:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(84,'ForceDelete:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(85,'ForceDeleteAny:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(86,'RestoreAny:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(87,'Replicate:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(88,'Reorder:Promo','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(89,'ViewAny:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(90,'View:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(91,'Create:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(92,'Update:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(93,'Delete:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(94,'Restore:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(95,'ForceDelete:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(96,'ForceDeleteAny:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(97,'RestoreAny:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(98,'Replicate:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(99,'Reorder:PurchaseOrder','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(100,'ViewAny:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(101,'View:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(102,'Create:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(103,'Update:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(104,'Delete:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(105,'Restore:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(106,'ForceDelete:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(107,'ForceDeleteAny:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(108,'RestoreAny:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(109,'Replicate:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(110,'Reorder:Role','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(111,'ViewAny:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(112,'View:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(113,'Create:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(114,'Update:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(115,'Delete:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(116,'Restore:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(117,'ForceDelete:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(118,'ForceDeleteAny:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(119,'RestoreAny:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(120,'Replicate:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(121,'Reorder:StoreSetting','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(122,'ViewAny:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(123,'View:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(124,'Create:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(125,'Update:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(126,'Delete:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(127,'Restore:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(128,'ForceDelete:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(129,'ForceDeleteAny:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(130,'RestoreAny:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(131,'Replicate:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(132,'Reorder:Transaction','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(133,'ViewAny:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(134,'View:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(135,'Create:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(136,'Update:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(137,'Delete:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(138,'Restore:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(139,'ForceDelete:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(140,'ForceDeleteAny:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(141,'RestoreAny:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(142,'Replicate:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(143,'Reorder:User','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(144,'ViewAny:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(145,'View:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(146,'Create:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(147,'Update:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(148,'Delete:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(149,'Restore:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(150,'ForceDelete:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(151,'ForceDeleteAny:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(152,'RestoreAny:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(153,'Replicate:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(154,'Reorder:Activity','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(155,'View:Dashboard','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(156,'View:SelectStore','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(157,'View:StatsOverviewWidget','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(158,'View:SalesOverviewChartWidget','web','2026-04-29 08:42:46','2026-04-29 08:42:46'),
(159,'View:RightPanelWidget','web','2026-04-29 08:42:46','2026-04-29 08:42:46');

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
(1,1,3,2,'Kasur 1','springbed',152000.00,'KET-123','single',12.00,'Red',1,'2026-04-29 08:45:19','2026-04-29 08:45:19',1,1,NULL,NULL),
(2,1,4,4,'Kasur 2','headboard',250000.00,'SK-912','custom',13.00,'Blue',1,'2026-04-29 08:45:59','2026-04-29 08:45:59',1,1,NULL,NULL),
(3,3,4,4,'Kasur 3','headboard',500000.00,'ASID-12','king',12.00,'Green',1,'2026-04-29 08:46:35','2026-04-29 08:46:35',1,1,NULL,NULL),
(4,2,1,3,'Kasur 4','divan',450000.00,'HJ-912','queen',12.00,'Purple',1,'2026-04-29 08:47:15','2026-04-29 08:47:15',1,1,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_products` */

insert  into `promo_products`(`id`,`promo_id`,`product_id`,`created_at`,`updated_at`) values
(1,1,1,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promos` */

insert  into `promos`(`id`,`name`,`type`,`discount_type`,`discount_value`,`min_purchase`,`usage_limit`,`usage_count`,`start_date`,`end_date`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Kasur 1','voucher','nominal',100000.00,50000.00,10,0,'2026-04-29 00:00:00','2026-08-31 00:00:00',1,'2026-04-29 08:48:14','2026-04-29 08:49:28',1,1,NULL,NULL),
(2,'All','flash_sale','percentage',50.00,99999.00,8,0,'2026-04-29 00:00:00','2026-08-31 00:00:00',1,'2026-04-29 08:48:46','2026-04-29 08:48:46',1,1,NULL,NULL),
(3,'Ongkir','free_shipping','nominal',120000.00,100000.00,10,0,'2026-04-29 00:00:00','2026-08-31 00:00:00',1,'2026-04-29 08:49:15','2026-04-29 08:49:15',1,1,NULL,NULL);

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

/*Table structure for table `referals` */

DROP TABLE IF EXISTS `referals`;

CREATE TABLE `referals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `name_customer` varchar(255) DEFAULT NULL,
  `referal_code` varchar(255) DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL,
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
(1,2),
(1,3),
(1,4),
(2,1),
(2,2),
(2,3),
(2,4),
(3,1),
(3,2),
(3,3),
(3,4),
(4,1),
(4,2),
(4,3),
(4,4),
(5,1),
(5,2),
(5,3),
(5,4),
(6,1),
(6,2),
(6,3),
(6,4),
(7,1),
(7,2),
(7,3),
(7,4),
(8,1),
(8,2),
(8,3),
(8,4),
(9,1),
(9,2),
(9,3),
(9,4),
(10,1),
(10,2),
(10,3),
(10,4),
(11,1),
(11,2),
(11,3),
(11,4),
(12,1),
(12,2),
(12,3),
(12,4),
(13,1),
(13,2),
(13,3),
(13,4),
(14,1),
(14,2),
(14,3),
(14,4),
(15,1),
(15,2),
(15,3),
(15,4),
(16,1),
(16,2),
(16,3),
(16,4),
(17,1),
(17,2),
(17,3),
(17,4),
(18,1),
(18,2),
(18,3),
(18,4),
(19,1),
(19,2),
(19,3),
(19,4),
(20,1),
(20,2),
(20,3),
(20,4),
(21,1),
(21,2),
(21,3),
(21,4),
(22,1),
(22,2),
(22,3),
(22,4),
(23,1),
(23,2),
(23,3),
(23,4),
(24,1),
(24,2),
(24,3),
(24,4),
(25,1),
(25,2),
(25,3),
(25,4),
(26,1),
(26,2),
(26,3),
(26,4),
(27,1),
(27,2),
(27,3),
(27,4),
(28,1),
(28,2),
(28,3),
(28,4),
(29,1),
(29,2),
(29,3),
(29,4),
(30,1),
(30,2),
(30,3),
(30,4),
(31,1),
(31,2),
(31,3),
(31,4),
(32,1),
(32,2),
(32,3),
(32,4),
(33,1),
(33,2),
(33,3),
(33,4),
(34,1),
(34,2),
(34,3),
(34,4),
(35,1),
(35,2),
(35,3),
(35,4),
(36,1),
(36,2),
(36,3),
(36,4),
(37,1),
(37,2),
(37,3),
(37,4),
(38,1),
(38,2),
(38,3),
(38,4),
(39,1),
(39,2),
(39,3),
(39,4),
(40,1),
(40,2),
(40,3),
(40,4),
(41,1),
(41,2),
(41,3),
(41,4),
(42,1),
(42,2),
(42,3),
(42,4),
(43,1),
(43,2),
(43,3),
(43,4),
(44,1),
(44,2),
(44,3),
(44,4),
(45,1),
(45,2),
(45,3),
(45,4),
(46,1),
(46,2),
(46,3),
(46,4),
(47,1),
(47,2),
(47,3),
(47,4),
(48,1),
(48,2),
(48,3),
(48,4),
(49,1),
(49,2),
(49,3),
(49,4),
(50,1),
(50,2),
(50,3),
(50,4),
(51,1),
(51,2),
(51,3),
(51,4),
(52,1),
(52,2),
(52,3),
(52,4),
(53,1),
(53,2),
(53,3),
(53,4),
(54,1),
(54,2),
(54,3),
(54,4),
(55,1),
(55,2),
(55,3),
(55,4),
(56,1),
(56,2),
(56,3),
(56,4),
(57,1),
(57,2),
(57,3),
(57,4),
(58,1),
(58,2),
(58,3),
(58,4),
(59,1),
(59,2),
(59,3),
(59,4),
(60,1),
(60,2),
(60,3),
(60,4),
(61,1),
(61,2),
(61,3),
(61,4),
(62,1),
(62,2),
(62,3),
(62,4),
(63,1),
(63,2),
(63,3),
(63,4),
(64,1),
(64,2),
(64,3),
(64,4),
(65,1),
(65,2),
(65,3),
(65,4),
(66,1),
(66,2),
(66,3),
(66,4),
(67,1),
(67,2),
(67,3),
(67,4),
(68,1),
(68,2),
(68,3),
(68,4),
(69,1),
(69,2),
(69,3),
(69,4),
(70,1),
(70,2),
(70,3),
(70,4),
(71,1),
(71,2),
(71,3),
(71,4),
(72,1),
(72,2),
(72,3),
(72,4),
(73,1),
(73,2),
(73,3),
(73,4),
(74,1),
(74,2),
(74,3),
(74,4),
(75,1),
(75,2),
(75,3),
(75,4),
(76,1),
(76,2),
(76,3),
(76,4),
(77,1),
(77,2),
(77,3),
(77,4),
(78,1),
(78,2),
(78,3),
(78,4),
(79,1),
(79,2),
(79,3),
(79,4),
(80,1),
(80,2),
(80,3),
(80,4),
(81,1),
(81,2),
(81,3),
(81,4),
(82,1),
(82,2),
(82,3),
(82,4),
(83,1),
(83,2),
(83,3),
(83,4),
(84,1),
(84,2),
(84,3),
(84,4),
(85,1),
(85,2),
(85,3),
(85,4),
(86,1),
(86,2),
(86,3),
(86,4),
(87,1),
(87,2),
(87,3),
(87,4),
(88,1),
(88,2),
(88,3),
(88,4),
(89,1),
(89,2),
(89,3),
(89,4),
(90,1),
(90,2),
(90,3),
(90,4),
(91,1),
(91,2),
(91,3),
(91,4),
(92,1),
(92,2),
(92,3),
(92,4),
(93,1),
(93,2),
(93,3),
(93,4),
(94,1),
(94,2),
(94,3),
(94,4),
(95,1),
(95,2),
(95,3),
(95,4),
(96,1),
(96,2),
(96,3),
(96,4),
(97,1),
(97,2),
(97,3),
(97,4),
(98,1),
(98,2),
(98,3),
(98,4),
(99,1),
(99,2),
(99,3),
(99,4),
(100,1),
(100,2),
(100,3),
(100,4),
(101,1),
(101,2),
(101,3),
(101,4),
(102,1),
(102,2),
(102,3),
(102,4),
(103,1),
(103,2),
(103,3),
(103,4),
(104,1),
(104,2),
(104,3),
(104,4),
(105,1),
(105,2),
(105,3),
(105,4),
(106,1),
(106,2),
(106,3),
(106,4),
(107,1),
(107,2),
(107,3),
(107,4),
(108,1),
(108,2),
(108,3),
(108,4),
(109,1),
(109,2),
(109,3),
(109,4),
(110,1),
(110,2),
(110,3),
(110,4),
(111,1),
(111,2),
(111,3),
(111,4),
(112,1),
(112,2),
(112,3),
(112,4),
(113,1),
(113,2),
(113,3),
(113,4),
(114,1),
(114,2),
(114,3),
(114,4),
(115,1),
(115,2),
(115,3),
(115,4),
(116,1),
(116,2),
(116,3),
(116,4),
(117,1),
(117,2),
(117,3),
(117,4),
(118,1),
(118,2),
(118,3),
(118,4),
(119,1),
(119,2),
(119,3),
(119,4),
(120,1),
(120,2),
(120,3),
(120,4),
(121,1),
(121,2),
(121,3),
(121,4),
(122,1),
(122,2),
(122,3),
(122,4),
(123,1),
(123,2),
(123,3),
(123,4),
(124,1),
(124,2),
(124,3),
(124,4),
(125,1),
(125,2),
(125,3),
(125,4),
(126,1),
(126,2),
(126,3),
(126,4),
(127,1),
(127,2),
(127,3),
(127,4),
(128,1),
(128,2),
(128,3),
(128,4),
(129,1),
(129,2),
(129,3),
(129,4),
(130,1),
(130,2),
(130,3),
(130,4),
(131,1),
(131,2),
(131,3),
(131,4),
(132,1),
(132,2),
(132,3),
(132,4),
(133,1),
(133,2),
(133,3),
(133,4),
(134,1),
(134,2),
(134,3),
(134,4),
(135,1),
(135,2),
(135,3),
(135,4),
(136,1),
(136,2),
(136,3),
(136,4),
(137,1),
(137,2),
(137,3),
(137,4),
(138,1),
(138,2),
(138,3),
(138,4),
(139,1),
(139,2),
(139,3),
(139,4),
(140,1),
(140,2),
(140,3),
(140,4),
(141,1),
(141,2),
(141,3),
(141,4),
(142,1),
(142,2),
(142,3),
(142,4),
(143,1),
(143,2),
(143,3),
(143,4),
(144,1),
(144,2),
(144,3),
(144,4),
(145,1),
(145,2),
(145,3),
(145,4),
(146,1),
(146,2),
(146,3),
(146,4),
(147,1),
(147,2),
(147,3),
(147,4),
(148,1),
(148,2),
(148,3),
(148,4),
(149,1),
(149,2),
(149,3),
(149,4),
(150,1),
(150,2),
(150,3),
(150,4),
(151,1),
(151,2),
(151,3),
(151,4),
(152,1),
(152,2),
(152,3),
(152,4),
(153,1),
(153,2),
(153,3),
(153,4),
(154,1),
(154,2),
(154,3),
(154,4),
(155,1),
(155,2),
(155,3),
(155,4),
(156,1),
(156,2),
(156,3),
(156,4),
(157,1),
(157,2),
(157,3),
(157,4),
(158,1),
(158,2),
(158,3),
(158,4),
(159,1),
(159,2),
(159,3),
(159,4);

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
(1,'Super Admin','web','2026-04-29 08:41:23','2026-04-29 08:41:23'),
(2,'Admin','web','2026-04-29 08:41:23','2026-04-29 08:41:23'),
(3,'Owner','web','2026-04-29 08:41:23','2026-04-29 08:41:23'),
(4,'Staff','web','2026-04-29 08:41:23','2026-04-29 08:41:23');

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
('j9uVzuB1SbFlh6uGy2VSKpJRvkHuXp9ZBxh1BshA',1,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoia1Q4N2RaN2dwcHJhSFdDVkJISWtHa3UxcWN5cGNtRGx5WnZyc0xQVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vcG9zLXNwcmluZ2JlZC5sb2NhbC9hZG1pbi90cmFuc2FjdGlvbnMiO3M6NToicm91dGUiO3M6NDM6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy50cmFuc2FjdGlvbnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiIxODUzNDlkOGE4ZTc5ODcyYzg2MjA5ZTQxY2E3NzhjM2MzMDNmZTFhYWMyZGU5NTEwMDY4MDBjZmU3YWExZDMwIjtzOjY6InRhYmxlcyI7YToxMDp7czo0MDoiZTY0NDgzM2Y0ZTRlMDg3MTIzMTVkYTcxYjMzZmFjZDJfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMzoic3RvcmVTZXR0aW5nLnN0b3JlX25hbWUiO3M6NToibGFiZWwiO3M6NToiU3RvcmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6ImVtYWlsIjtzOjU6ImxhYmVsIjtzOjEzOiJFbWFpbCBhZGRyZXNzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoicm9sZXMubmFtZSI7czo1OiJsYWJlbCI7czo0OiJSb2xlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NjoiQWN0aXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiIzNTMzNmE4MDg3Nzg4MzRmODk0MTc0NzExMmMyNjQzZV9jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJndWFyZF9uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJHdWFyZCBOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJ0ZWFtLm5hbWUiO3M6NToibGFiZWwiO3M6NDoiVGVhbSI7czo4OiJpc0hpZGRlbiI7YjoxO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTc6InBlcm1pc3Npb25zX2NvdW50IjtzOjU6ImxhYmVsIjtzOjExOiJQZXJtaXNzaW9ucyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgQXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6IjdmNWQ2OTY0ODE5ZGIzMjg3NjUwOTlhYzE3M2E4ZTYwX2NvbHVtbnMiO2E6Mjp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjY6IkFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiMDk0NmU1ZGUwOWY3MmQwYzc3ZDBiZjZjYzhkYjNkOWNfY29sdW1ucyI7YTozOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiYnVuZGxlX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEyOiJCdW5kbGUgcHJpY2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo5OiJJcyBhY3RpdmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImJjMTA0NmQyODM0NWY4NmRkMDAwZGQ1YmY3NWNlYjU5X2NvbHVtbnMiO2E6MTA6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6InR5cGUiO3M6NToibGFiZWwiO3M6NDoiVHlwZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImRpc2NvdW50X3R5cGUiO3M6NToibGFiZWwiO3M6MTM6IkRpc2NvdW50IHR5cGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJkaXNjb3VudF92YWx1ZSI7czo1OiJsYWJlbCI7czoxNDoiRGlzY291bnQgdmFsdWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJtaW5fcHVyY2hhc2UiO3M6NToibGFiZWwiO3M6MTI6Ik1pbiBwdXJjaGFzZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InVzYWdlX2xpbWl0IjtzOjU6ImxhYmVsIjtzOjExOiJVc2FnZSBsaW1pdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InVzYWdlX2NvdW50IjtzOjU6ImxhYmVsIjtzOjExOiJVc2FnZSBjb3VudCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InN0YXJ0X2RhdGUiO3M6NToibGFiZWwiO3M6MTA6IlN0YXJ0IGRhdGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImVuZF9kYXRlIjtzOjU6ImxhYmVsIjtzOjg6IkVuZCBkYXRlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6OTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6OToiSXMgYWN0aXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI4ZmFjNmViMWNlYzI2ODAzYjNmN2ZiNDQwYTI3MTExYl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjc6IlByb2R1Y3QiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjM6InNrdSI7czo1OiJsYWJlbCI7czozOiJTS1UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjYXRlZ29yeS5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkNhdGVnb3J5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzaXplIjtzOjU6ImxhYmVsIjtzOjQ6IlNpemUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJzZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEzOiJTZWxsaW5nIFByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJzdG9jayI7czo1OiJsYWJlbCI7czo1OiJTdG9jayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjY6IkFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiODA5NWY4OTNmYTM3MDBmMDFkNjAwZWQzZTBkMWUwMWRfY29sdW1ucyI7YTo0OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InN1cHBsaWVyX25hbWUiO3M6NToibGFiZWwiO3M6MTM6IlN1cHBsaWVyIG5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJpbnZvaWNlX251bWJlciI7czo1OiJsYWJlbCI7czoxNDoiSW52b2ljZSBudW1iZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJwdXJjaGFzZV9kYXRlIjtzOjU6ImxhYmVsIjtzOjEzOiJQdXJjaGFzZSBkYXRlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoidG90YWxfYW1vdW50IjtzOjU6ImxhYmVsIjtzOjEyOiJUb3RhbCBhbW91bnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImY3NjRhN2M0MTA3NzUxYWQxMDBjOTk3NWI3NzkyMTgyX2NvbHVtbnMiO2E6NTp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJzdG9yZV9uYW1lIjtzOjU6ImxhYmVsIjtzOjEwOiJTdG9yZSBOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJwaG9uZSI7czo1OiJsYWJlbCI7czo1OiJQaG9uZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6NToiRW1haWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjc6ImFkZHJlc3MiO3M6NToibGFiZWwiO3M6NzoiQWRkcmVzcyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTU6ImhvbWVwYWdlX2Jhbm5lciI7czo1OiJsYWJlbCI7czoxNToiSG9tZXBhZ2UgYmFubmVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiIxNjQ2ZWVmNTU0OGQ1MjIwZTM3MDg4YzkwYjcyOWRkNF9jb2x1bW5zIjthOjg6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNjoidHJhbnNhY3Rpb25fY29kZSI7czo1OiJsYWJlbCI7czoxNDoiVHJhbnNhY3Rpb24gSUQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE2OiJ0cmFuc2FjdGlvbl9kYXRlIjtzOjU6ImxhYmVsIjtzOjQ6IkRhdGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjdXN0b21lci5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkN1c3RvbWVyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToiZ3JhbmRfdG90YWwiO3M6NToibGFiZWwiO3M6NToiVG90YWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjI1OiJ0cmFuc2FjdGlvblBheW1lbnQuc3RhdHVzIjtzOjU6ImxhYmVsIjtzOjc6IlBheW1lbnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjI2OiJ0cmFuc2FjdGlvblNoaXBtZW50LnN0YXR1cyI7czo1OiJsYWJlbCI7czo4OiJEZWxpdmVyeSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjc7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MzI6InRyYW5zYWN0aW9uU2hpcG1lbnQuY291cmllci5uYW1lIjtzOjU6ImxhYmVsIjtzOjc6IkNvdXJpZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImYyMjgxZmJmOGUzN2ZhMjE3NDQzOWRjZGVjZjlmOGQ2X2NvbHVtbnMiO2E6NDp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NzoiQ291cmllciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoidHlwZSI7czo1OiJsYWJlbCI7czo0OiJUeXBlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoic2hpcHBpbmdfY29zdCI7czo1OiJsYWJlbCI7czoxMzoiU2hpcHBpbmcgQ29zdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjY6IkFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1777427463);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_adjustments` */

insert  into `stock_adjustments`(`id`,`store_setting_id`,`product_id`,`qty_before`,`qty_after`,`qty_difference`,`reason`,`created_at`,`updated_at`) values
(1,1,1,0,7,7,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(2,2,1,0,15,15,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(3,3,1,0,20,20,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(4,1,2,0,12,12,NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(5,2,2,0,15,15,NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(6,3,3,0,11,11,NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(7,2,4,0,15,15,NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_movements` */

insert  into `stock_movements`(`id`,`product_id`,`store_setting_id`,`type`,`reference_type`,`reference_id`,`qty`,`cost_price_snapshot`,`created_at`,`updated_at`) values
(1,1,1,'adjustment','App\\Models\\StockAdjustment',1,7,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(2,1,2,'adjustment','App\\Models\\StockAdjustment',2,15,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(3,1,3,'adjustment','App\\Models\\StockAdjustment',3,20,NULL,'2026-04-29 08:45:19','2026-04-29 08:45:19'),
(4,2,1,'adjustment','App\\Models\\StockAdjustment',4,12,NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(5,2,2,'adjustment','App\\Models\\StockAdjustment',5,15,NULL,'2026-04-29 08:45:59','2026-04-29 08:45:59'),
(6,3,3,'adjustment','App\\Models\\StockAdjustment',6,11,NULL,'2026-04-29 08:46:35','2026-04-29 08:46:35'),
(7,4,2,'adjustment','App\\Models\\StockAdjustment',7,15,NULL,'2026-04-29 08:47:15','2026-04-29 08:47:15');

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
(1,'Toko 1','1234567890','toko1@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL),
(2,'Toko 2','123153242','toko2@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL),
(3,'Toko 3','64646546','toko3@yahoo.com','123 Main St','2026-04-29 08:41:24','2026-04-29 08:41:24',NULL,NULL,NULL,NULL);

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
  `is_referal` tinyint(1) NOT NULL DEFAULT 0,
  `referal_customer_id` bigint(20) unsigned DEFAULT NULL,
  `nominal_referal` int(11) DEFAULT NULL,
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
(1,'Super Admin','superadmin@springbed.id',NULL,'$2y$12$TJFm.Tz9mP7m/OP8BnA6d.EtrmDw/qNOXQSkNv4iPrLL1oLncpOuO',1,NULL,NULL,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL,NULL),
(2,'Admin','admin@springbed.id',NULL,'$2y$12$cQ/GddYnWGM1JmQ75xXiQeC54mDOiQVD8c3L027mCNK6BPcTOM/um',1,NULL,NULL,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL,NULL),
(3,'Owner','owner@springbed.id',NULL,'$2y$12$NgVfKUL2iad4m/E.mj6tNOJ4VSR0TH7ZfeFdpYt9KM4vEhvKNJLMS',1,NULL,NULL,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL,NULL),
(4,'Staff','staff@springbed.id',NULL,'$2y$12$cjn4phkJE1VlzBIoaOIoq.Hoy3Zrc2Zd/OJJmU.CDOzl5LbCknXma',1,NULL,NULL,'2026-04-29 08:41:23','2026-04-29 08:41:23',NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
