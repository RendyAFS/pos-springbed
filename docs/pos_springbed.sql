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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pos_springbed` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
-- mariaDB
-- CREATE DATABASE /*!32312 IF NOT EXISTS*/`pos_springbed` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `pos_springbed`;

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
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`),
  CONSTRAINT `activity_log_chk_1` CHECK (json_valid(`properties`))
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(14,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"category_id\":4}',NULL,'2026-03-16 13:59:53','2026-03-16 13:59:53'),
(15,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/145.0.0.0 Safari\\/537.36\"}',NULL,'2026-03-16 19:03:04','2026-03-16 19:03:04'),
(16,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"is_active\":false,\"updated_at\":\"2026-03-16 19:03:23\"}',NULL,'2026-03-16 19:03:23','2026-03-16 19:03:23'),
(17,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"is_active\":true,\"updated_at\":\"2026-03-16 19:03:40\"}',NULL,'2026-03-16 19:03:40','2026-03-16 19:03:40'),
(18,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"is_active\":false,\"updated_at\":\"2026-03-16 19:04:36\"}',NULL,'2026-03-16 19:04:36','2026-03-16 19:04:36'),
(19,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"is_active\":true,\"updated_at\":\"2026-03-16 19:07:35\"}',NULL,'2026-03-16 19:07:35','2026-03-16 19:07:35'),
(20,'Resource','Purchase Order Created by Super Admin','App\\Models\\PurchaseOrder','Created',1,'App\\Models\\User',1,'{\"store_setting_id\":1,\"supplier_name\":\"Supplier 1\",\"invoice_number\":\"INVNEICFGXZ1773662857\",\"purchase_date\":\"2026-03-16 00:00:00\",\"total_amount\":95000000,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 19:08:49\",\"created_at\":\"2026-03-16 19:08:49\",\"id\":1}',NULL,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(21,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',1,'App\\Models\\User',1,'{\"product_id\":1,\"store_setting_id\":1,\"quantity\":0,\"updated_at\":\"2026-03-16 19:08:49\",\"created_at\":\"2026-03-16 19:08:49\",\"id\":1}',NULL,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(22,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',1,'App\\Models\\User',1,'{\"quantity\":50}',NULL,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(23,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',2,'App\\Models\\User',1,'{\"product_id\":2,\"store_setting_id\":1,\"quantity\":0,\"updated_at\":\"2026-03-16 19:08:49\",\"created_at\":\"2026-03-16 19:08:49\",\"id\":2}',NULL,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(24,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',2,'App\\Models\\User',1,'{\"quantity\":50}',NULL,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(25,'Resource','Purchase Order Created by Super Admin','App\\Models\\PurchaseOrder','Created',2,'App\\Models\\User',1,'{\"store_setting_id\":2,\"supplier_name\":\"Supplier 2\",\"invoice_number\":\"INV8S62HJHD1773663059\",\"purchase_date\":\"2026-03-16 00:00:00\",\"total_amount\":50500000,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 19:13:39\",\"created_at\":\"2026-03-16 19:13:39\",\"id\":2}',NULL,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(26,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',3,'App\\Models\\User',1,'{\"product_id\":1,\"store_setting_id\":2,\"quantity\":0,\"updated_at\":\"2026-03-16 19:13:39\",\"created_at\":\"2026-03-16 19:13:39\",\"id\":3}',NULL,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(27,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',3,'App\\Models\\User',1,'{\"quantity\":25}',NULL,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(28,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',4,'App\\Models\\User',1,'{\"product_id\":2,\"store_setting_id\":2,\"quantity\":0,\"updated_at\":\"2026-03-16 19:13:39\",\"created_at\":\"2026-03-16 19:13:39\",\"id\":4}',NULL,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(29,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',4,'App\\Models\\User',1,'{\"quantity\":25}',NULL,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(30,'Resource','Purchase Order Created by Super Admin','App\\Models\\PurchaseOrder','Created',3,'App\\Models\\User',1,'{\"store_setting_id\":3,\"supplier_name\":\"Supplier 2\",\"invoice_number\":\"INVBYU4OLPY1773663219\",\"purchase_date\":\"2026-03-16 00:00:00\",\"total_amount\":81000000,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 19:14:10\",\"created_at\":\"2026-03-16 19:14:10\",\"id\":3}',NULL,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(31,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',5,'App\\Models\\User',1,'{\"product_id\":3,\"store_setting_id\":3,\"quantity\":0,\"updated_at\":\"2026-03-16 19:14:10\",\"created_at\":\"2026-03-16 19:14:10\",\"id\":5}',NULL,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(32,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',5,'App\\Models\\User',1,'{\"quantity\":30}',NULL,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(33,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',6,'App\\Models\\User',1,'{\"product_id\":4,\"store_setting_id\":3,\"quantity\":0,\"updated_at\":\"2026-03-16 19:14:10\",\"created_at\":\"2026-03-16 19:14:10\",\"id\":6}',NULL,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(34,'Resource','Inventory Stock Updated by Super Admin','App\\Models\\InventoryStock','Updated',6,'App\\Models\\User',1,'{\"quantity\":30}',NULL,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(35,'Resource','User Updated by Super Admin','App\\Models\\User','Updated',2,'App\\Models\\User',1,'{\"store_setting_id\":1,\"updated_at\":\"2026-03-16 19:26:32\",\"updated_by\":1}',NULL,'2026-03-16 19:26:32','2026-03-16 19:26:32'),
(36,'Resource','User Updated by Super Admin','App\\Models\\User','Updated',4,'App\\Models\\User',1,'{\"store_setting_id\":2,\"updated_at\":\"2026-03-16 19:26:42\",\"updated_by\":1}',NULL,'2026-03-16 19:26:42','2026-03-16 19:26:42'),
(37,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',5,'App\\Models\\User',1,'{\"size\":\"single\",\"type\":\"springbed\",\"name\":\"Tes Product 1\",\"selling_price\":111,\"sku\":\"OASI-111\",\"weight\":111,\"color\":\"Red\",\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 19:54:14\",\"created_at\":\"2026-03-16 19:54:14\",\"id\":5}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(38,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',5,'App\\Models\\User',1,'{\"brand_id\":3}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(39,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',5,'App\\Models\\User',1,'{\"category_id\":2}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(40,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',7,'App\\Models\\User',1,'{\"product_id\":5,\"store_setting_id\":1,\"quantity\":10,\"updated_at\":\"2026-03-16 19:54:14\",\"created_at\":\"2026-03-16 19:54:14\",\"id\":7}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(41,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',8,'App\\Models\\User',1,'{\"product_id\":5,\"store_setting_id\":2,\"quantity\":5,\"updated_at\":\"2026-03-16 19:54:14\",\"created_at\":\"2026-03-16 19:54:14\",\"id\":8}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(42,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',9,'App\\Models\\User',1,'{\"product_id\":5,\"store_setting_id\":3,\"quantity\":2,\"updated_at\":\"2026-03-16 19:54:14\",\"created_at\":\"2026-03-16 19:54:14\",\"id\":9}',NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(43,'Resource','Inventory Stock Deleted by Super Admin','App\\Models\\InventoryStock','Deleted',9,'App\\Models\\User',1,'[]',NULL,'2026-03-16 19:58:47','2026-03-16 19:58:47'),
(44,'Resource','Inventory Stock Created by Super Admin','App\\Models\\InventoryStock','Created',10,'App\\Models\\User',1,'{\"product_id\":5,\"store_setting_id\":3,\"quantity\":11,\"updated_at\":\"2026-03-16 19:58:47\",\"created_at\":\"2026-03-16 19:58:47\",\"id\":10}',NULL,'2026-03-16 19:58:47','2026-03-16 19:58:47'),
(45,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',1,'App\\Models\\User',1,'{\"name\":\"Promo Kasur 1 nominal\",\"type\":\"flash_sale\",\"discount_type\":\"nominal\",\"discount_value\":500000,\"min_purchase\":100000,\"start_date\":\"2026-03-09 00:00:00\",\"end_date\":\"2026-03-30 00:00:00\",\"is_active\":true,\"usage_limit\":10,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:47:31\",\"created_at\":\"2026-03-16 20:47:31\",\"id\":1}',NULL,'2026-03-16 20:47:31','2026-03-16 20:47:31'),
(46,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',2,'App\\Models\\User',1,'{\"name\":\"Promo Kasur 1 percentage\",\"type\":\"flash_sale\",\"discount_type\":\"percentage\",\"discount_value\":50,\"min_purchase\":100000,\"start_date\":\"2026-03-09 00:00:00\",\"end_date\":\"2026-03-30 00:00:00\",\"is_active\":true,\"usage_limit\":10,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:48:05\",\"created_at\":\"2026-03-16 20:48:05\",\"id\":2}',NULL,'2026-03-16 20:48:05','2026-03-16 20:48:05'),
(47,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',3,'App\\Models\\User',1,'{\"name\":\"Promo 1\",\"type\":\"free_shipping\",\"discount_type\":\"nominal\",\"discount_value\":120000,\"min_purchase\":100000,\"start_date\":\"2026-03-09 00:00:00\",\"end_date\":\"2026-03-30 00:00:00\",\"is_active\":true,\"usage_limit\":10,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:49:23\",\"created_at\":\"2026-03-16 20:49:23\",\"id\":3}',NULL,'2026-03-16 20:49:23','2026-03-16 20:49:23'),
(48,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',1,'App\\Models\\User',1,'{\"name\":\"Bundle 1\",\"bundle_price\":2300000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:49:55\",\"created_at\":\"2026-03-16 20:49:55\",\"id\":1}',NULL,'2026-03-16 20:49:55','2026-03-16 20:49:55'),
(49,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',2,'App\\Models\\User',1,'{\"name\":\"Bundle 2\",\"bundle_price\":2600000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:50:18\",\"created_at\":\"2026-03-16 20:50:18\",\"id\":2}',NULL,'2026-03-16 20:50:18','2026-03-16 20:50:18'),
(50,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',3,'App\\Models\\User',1,'{\"name\":\"Bundle 3\",\"bundle_price\":3800000,\"is_active\":true,\"created_by\":1,\"updated_by\":1,\"updated_at\":\"2026-03-16 20:50:49\",\"created_at\":\"2026-03-16 20:50:49\",\"id\":3}',NULL,'2026-03-16 20:50:49','2026-03-16 20:50:49');

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
(1,'King Koil',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Serta',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'Comforta',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Florence',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundle_items` */

insert  into `bundle_items`(`id`,`bundle_id`,`product_id`,`qty`,`price`,`created_at`,`updated_at`) values
(1,1,1,1,1400000.00,'2026-03-16 20:49:55','2026-03-16 20:49:55'),
(2,1,2,1,900000.00,'2026-03-16 20:49:55','2026-03-16 20:49:55'),
(3,2,3,1,2000000.00,'2026-03-16 20:50:18','2026-03-16 20:50:18'),
(4,2,4,1,600000.00,'2026-03-16 20:50:18','2026-03-16 20:50:18'),
(5,3,1,2,1000000.00,'2026-03-16 20:50:49','2026-03-16 20:50:49'),
(6,3,2,2,900000.00,'2026-03-16 20:50:49','2026-03-16 20:50:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundles` */

insert  into `bundles`(`id`,`name`,`bundle_price`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Bundle 1',2300000.00,1,'2026-03-16 20:49:55','2026-03-16 20:49:55',1,1,NULL,NULL),
(2,'Bundle 2',2600000.00,1,'2026-03-16 20:50:18','2026-03-16 20:50:18',1,1,NULL,NULL),
(3,'Bundle 3',3800000.00,1,'2026-03-16 20:50:49','2026-03-16 20:50:49',1,1,NULL,NULL);

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
(1,'Pocket','pocket-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Bonnel','bonnel-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'Hybird','hybird-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Mattress','mattress-1773626526',1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

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
(1,'JNE','external',100000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'SiCepat','external',120000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'J&T','internal',130000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(4,'Kurir Toko','internal',140000.00,1,'2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

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
(1,'John Doe','1234567890','johndoe@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(2,'Jane Doe','123153242','janedoe@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL),
(3,'David Smith','64646546','david@yahoo.com','123 Main St','2026-03-16 09:02:06','2026-03-16 09:02:06',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `inventory_stocks` */

insert  into `inventory_stocks`(`id`,`product_id`,`store_setting_id`,`quantity`,`created_at`,`updated_at`) values
(1,1,1,50,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(2,2,1,50,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(3,1,2,25,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(4,2,2,25,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(5,3,3,30,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(6,4,3,30,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(7,5,1,10,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(8,5,2,5,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(10,5,3,11,'2026-03-16 19:58:47','2026-03-16 19:58:47');

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
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`),
  CONSTRAINT `media_chk_1` CHECK (json_valid(`manipulations`)),
  CONSTRAINT `media_chk_2` CHECK (json_valid(`custom_properties`)),
  CONSTRAINT `media_chk_3` CHECK (json_valid(`generated_conversions`)),
  CONSTRAINT `media_chk_4` CHECK (json_valid(`responsive_images`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `media` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_product` text COLLATE utf8mb4_unicode_ci,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_images` */

insert  into `product_images`(`id`,`product_id`,`image_product`,`is_primary`) values
(2,1,NULL,0),
(4,2,NULL,0),
(6,3,NULL,0),
(8,4,NULL,0),
(10,5,'images-product/01KKVC1BKH23ECDHH9CH2KVZF3.jpg',0),
(11,5,'images-product/01KKVC1BKM98BK1JB408MHZ7JQ.jpg',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`store_setting_id`,`brand_id`,`category_id`,`name`,`type`,`selling_price`,`sku`,`size`,`weight`,`color`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,1,3,2,'Kasur 1','headboard',1500000.00,'SK-09','single',12.00,'Red',1,'2026-03-16 13:53:54','2026-03-16 19:08:49',1,1,NULL,NULL),
(2,1,1,3,'Kasur 2','headboard',950000.00,'KIK-019','custom',10.00,'Blue',1,'2026-03-16 13:57:47','2026-03-16 19:08:49',1,1,NULL,NULL),
(3,3,2,2,'Kasur 3','bundle',2100000.00,'OPO-01','queen',15.00,'Green',1,'2026-03-16 13:58:19','2026-03-16 19:14:10',1,1,NULL,NULL),
(4,3,4,4,'Kasur 4','springbed',800000.00,'LOP-133','single',7.20,'Orange',1,'2026-03-16 13:59:53','2026-03-16 19:14:10',1,1,NULL,NULL),
(5,1,3,2,'Tes Product 1','springbed',111.00,'OASI-111','single',111.00,'Red',1,'2026-03-16 19:54:14','2026-03-16 19:54:14',1,1,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_products` */

insert  into `promo_products`(`id`,`promo_id`,`product_id`,`created_at`,`updated_at`) values
(1,1,1,NULL,NULL),
(2,2,1,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promos` */

insert  into `promos`(`id`,`name`,`type`,`discount_type`,`discount_value`,`min_purchase`,`usage_limit`,`usage_count`,`start_date`,`end_date`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,'Promo Kasur 1 nominal','flash_sale','nominal',500000.00,100000.00,10,0,'2026-03-09 00:00:00','2026-03-30 00:00:00',1,'2026-03-16 20:47:31','2026-03-16 20:47:31',1,1,NULL,NULL),
(2,'Promo Kasur 1 percentage','flash_sale','percentage',50.00,100000.00,10,0,'2026-03-09 00:00:00','2026-03-30 00:00:00',1,'2026-03-16 20:48:05','2026-03-16 20:48:05',1,1,NULL,NULL),
(3,'Promo 1','free_shipping','nominal',120000.00,100000.00,10,0,'2026-03-09 00:00:00','2026-03-30 00:00:00',1,'2026-03-16 20:49:23','2026-03-16 20:49:23',1,1,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_order_items` */

insert  into `purchase_order_items`(`id`,`purchase_order_id`,`product_id`,`qty_purchased`,`qty_remaining`,`cost_price`,`created_at`,`updated_at`) values
(1,1,1,50,50,1000000.00,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(2,1,2,50,50,900000.00,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(3,2,1,25,25,1100000.00,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(4,2,2,25,25,920000.00,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(5,3,3,30,30,2000000.00,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(6,3,4,30,30,700000.00,'2026-03-16 19:14:10','2026-03-16 19:14:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `purchase_orders` */

insert  into `purchase_orders`(`id`,`store_setting_id`,`supplier_name`,`invoice_number`,`purchase_date`,`total_amount`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values
(1,1,'Supplier 1','INVNEICFGXZ1773662857','2026-03-16 00:00:00',95000000,'2026-03-16 19:08:49','2026-03-16 19:08:49',1,1,NULL,NULL),
(2,2,'Supplier 2','INV8S62HJHD1773663059','2026-03-16 00:00:00',50500000,'2026-03-16 19:13:39','2026-03-16 19:13:39',1,1,NULL,NULL),
(3,3,'Supplier 2','INVBYU4OLPY1773663219','2026-03-16 00:00:00',81000000,'2026-03-16 19:14:10','2026-03-16 19:14:10',1,1,NULL,NULL);

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
(1,2),
(2,2),
(3,2),
(4,2),
(5,2),
(6,2),
(7,2),
(8,2),
(9,2),
(10,2),
(11,2),
(12,2),
(13,2),
(14,2),
(15,2),
(16,2),
(17,2),
(18,2),
(19,2),
(20,2),
(21,2),
(22,2),
(23,2),
(24,2),
(25,2),
(26,2),
(27,2),
(28,2),
(29,2),
(30,2),
(31,2),
(32,2),
(33,2),
(34,2),
(35,2),
(36,2),
(37,2),
(38,2),
(39,2),
(40,2),
(41,2),
(42,2),
(43,2),
(44,2),
(45,2),
(46,2),
(47,2),
(48,2),
(49,2),
(50,2),
(51,2),
(52,2),
(53,2),
(54,2),
(55,2),
(56,2),
(57,2),
(58,2),
(59,2),
(60,2),
(61,2),
(62,2),
(63,2),
(64,2),
(65,2),
(66,2),
(67,2),
(68,2),
(69,2),
(70,2),
(71,2),
(72,2),
(73,2),
(74,2),
(75,2),
(76,2),
(77,2),
(78,2),
(79,2),
(80,2),
(81,2),
(82,2),
(83,2),
(84,2),
(85,2),
(86,2),
(87,2),
(88,2),
(89,2),
(90,2),
(91,2),
(92,2),
(93,2),
(94,2),
(95,2),
(96,2),
(97,2),
(98,2),
(99,2),
(100,2),
(101,2),
(102,2),
(103,2),
(104,2),
(105,2),
(106,2),
(107,2),
(108,2),
(109,2),
(110,2),
(111,2),
(112,2),
(113,2),
(114,2),
(115,2),
(116,2),
(117,2),
(118,2),
(119,2),
(120,2),
(121,2),
(122,2),
(123,2),
(124,2),
(125,2),
(126,2),
(127,2),
(128,2),
(129,2),
(130,2),
(131,2),
(132,2),
(133,2),
(134,2),
(135,2),
(136,2),
(137,2),
(138,2),
(139,2),
(140,2),
(141,2),
(142,2),
(143,2),
(144,2),
(145,2),
(146,2),
(147,2),
(148,2),
(149,2),
(150,2),
(151,2),
(152,2),
(153,2),
(154,2),
(155,2),
(156,2),
(157,2),
(1,4),
(2,4),
(3,4),
(4,4),
(5,4),
(6,4),
(7,4),
(8,4),
(9,4),
(10,4),
(11,4),
(12,4),
(13,4),
(14,4),
(15,4),
(16,4),
(17,4),
(18,4),
(19,4),
(20,4),
(21,4),
(22,4),
(23,4),
(24,4),
(25,4),
(26,4),
(27,4),
(28,4),
(29,4),
(30,4),
(31,4),
(32,4),
(33,4),
(34,4),
(35,4),
(36,4),
(37,4),
(38,4),
(39,4),
(40,4),
(41,4),
(42,4),
(43,4),
(44,4),
(45,4),
(46,4),
(47,4),
(48,4),
(49,4),
(50,4),
(51,4),
(52,4),
(53,4),
(54,4),
(55,4),
(56,4),
(57,4),
(58,4),
(59,4),
(60,4),
(61,4),
(62,4),
(63,4),
(64,4),
(65,4),
(66,4),
(67,4),
(68,4),
(69,4),
(70,4),
(71,4),
(72,4),
(73,4),
(74,4),
(75,4),
(76,4),
(77,4),
(78,4),
(79,4),
(80,4),
(81,4),
(82,4),
(83,4),
(84,4),
(85,4),
(86,4),
(87,4),
(88,4),
(89,4),
(90,4),
(91,4),
(92,4),
(93,4),
(94,4),
(95,4),
(96,4),
(97,4),
(98,4),
(99,4),
(100,4),
(101,4),
(102,4),
(103,4),
(104,4),
(105,4),
(106,4),
(107,4),
(108,4),
(109,4),
(110,4),
(111,4),
(112,4),
(113,4),
(114,4),
(115,4),
(116,4),
(117,4),
(118,4),
(119,4),
(120,4),
(121,4),
(122,4),
(123,4),
(124,4),
(125,4),
(126,4),
(127,4),
(128,4),
(129,4),
(130,4),
(131,4),
(132,4),
(133,4),
(134,4),
(135,4),
(136,4),
(137,4),
(138,4),
(139,4),
(140,4),
(141,4),
(142,4),
(143,4),
(144,4),
(145,4),
(146,4),
(147,4),
(148,4),
(149,4),
(150,4),
(151,4),
(152,4),
(153,4),
(154,4),
(155,4),
(156,4),
(157,4);

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
(1,'Super Admin','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(2,'Admin','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(3,'Owner','web','2026-03-16 09:02:05','2026-03-16 09:02:05'),
(4,'Staff','web','2026-03-16 09:02:05','2026-03-16 09:02:05');

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

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values
('kvOLOvllN0TFQOv9FUd0neuCa8QJnRcRbJTLWxGH',1,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiTzgyenpRZUtPYlY4M3hscVNzaGVPNFdPTThyZUtkSERRcmtyN0FicSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU2OiJodHRwczovL3Bvcy1zcHJpbmdiZWQubG9jYWwvYWRtaW4vcHVyY2hhc2Utb3JkZXJzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czo0NzoiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnB1cmNoYXNlLW9yZGVycy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiI4Mjc2M2QwYzliMDNmMmVmNWViOTg1NTliNGY4NDA2YzA0NzUyOTdlNGYxN2FhNWFkNTI0NjAwNDI0OWIxMTdlIjtzOjY6InRhYmxlcyI7YTo0OntzOjQwOiJiYzEwNDZkMjgzNDVmODZkZDAwMGRkNWJmNzVjZWI1OV9jb2x1bW5zIjthOjEwOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJ0eXBlIjtzOjU6ImxhYmVsIjtzOjQ6IlR5cGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJkaXNjb3VudF90eXBlIjtzOjU6ImxhYmVsIjtzOjEzOiJEaXNjb3VudCB0eXBlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoiZGlzY291bnRfdmFsdWUiO3M6NToibGFiZWwiO3M6MTQ6IkRpc2NvdW50IHZhbHVlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoibWluX3B1cmNoYXNlIjtzOjU6ImxhYmVsIjtzOjEyOiJNaW4gcHVyY2hhc2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ1c2FnZV9saW1pdCI7czo1OiJsYWJlbCI7czoxMToiVXNhZ2UgbGltaXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ1c2FnZV9jb3VudCI7czo1OiJsYWJlbCI7czoxMToiVXNhZ2UgY291bnQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJzdGFydF9kYXRlIjtzOjU6ImxhYmVsIjtzOjEwOiJTdGFydCBkYXRlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJlbmRfZGF0ZSI7czo1OiJsYWJlbCI7czo4OiJFbmQgZGF0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjk7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjk6IklzIGFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiOGZhYzZlYjFjZWMyNjgwM2IzZjdmYjQ0MGEyNzExMWJfY29sdW1ucyI7YTo4OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo3OiJQcm9kdWN0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMzoic3RvcmVTZXR0aW5nLnN0b3JlX25hbWUiO3M6NToibGFiZWwiO3M6NToiU3RvcmUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjM6InNrdSI7czo1OiJsYWJlbCI7czozOiJTS1UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjYXRlZ29yeS5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkNhdGVnb3J5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzaXplIjtzOjU6ImxhYmVsIjtzOjQ6IlNpemUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJzZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEzOiJTZWxsaW5nIFByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyNDoiaW52ZW50b3J5U3RvY2tzLnF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjU6IlN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo5OiJpc19hY3RpdmUiO3M6NToibGFiZWwiO3M6NjoiQWN0aXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiJkYzQyZThlMmIwNzVjZTJlYTg0ZGNmMWI1YTczOWU0Yl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToicHJvZHVjdC5za3UiO3M6NToibGFiZWwiO3M6MzoiU0tVIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoicHJvZHVjdC5uYW1lIjtzOjU6ImxhYmVsIjtzOjc6IlByb2R1Y3QiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjIxOiJwcm9kdWN0LmNhdGVnb3J5Lm5hbWUiO3M6NToibGFiZWwiO3M6ODoiQ2F0ZWdvcnkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6InF1YW50aXR5IjtzOjU6ImxhYmVsIjtzOjU6IlN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMToicHJvZHVjdC5zZWxsaW5nX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEwOiJVbml0IFByaWNlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToidG90YWxfdmFsdWUiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIFZhbHVlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI4MDk1Zjg5M2ZhMzcwMGYwMWQ2MDBlZDNlMGQxZTAxZF9jb2x1bW5zIjthOjQ6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoic3VwcGxpZXJfbmFtZSI7czo1OiJsYWJlbCI7czoxMzoiU3VwcGxpZXIgbmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6Imludm9pY2VfbnVtYmVyIjtzOjU6ImxhYmVsIjtzOjE0OiJJbnZvaWNlIG51bWJlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6InB1cmNoYXNlX2RhdGUiO3M6NToibGFiZWwiO3M6MTM6IlB1cmNoYXNlIGRhdGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJ0b3RhbF9hbW91bnQiO3M6NToibGFiZWwiO3M6MTI6IlRvdGFsIGFtb3VudCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1773644494);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_adjustments` */

insert  into `stock_adjustments`(`id`,`store_setting_id`,`product_id`,`qty_before`,`qty_after`,`qty_difference`,`reason`,`created_at`,`updated_at`) values
(1,1,5,0,10,10,'Super admin add Adjustment 10','2026-03-16 19:54:14','2026-03-16 19:54:14'),
(2,2,5,0,5,5,'Super admin add Adjustment 5','2026-03-16 19:54:14','2026-03-16 19:54:14'),
(3,3,5,0,2,2,'Super admin add Adjustment 2','2026-03-16 19:54:14','2026-03-16 19:54:14'),
(4,3,5,2,11,9,'adjustment to 11 toko 3 ','2026-03-16 19:58:47','2026-03-16 19:58:47');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_movements` */

insert  into `stock_movements`(`id`,`product_id`,`store_setting_id`,`type`,`reference_type`,`reference_id`,`qty`,`cost_price_snapshot`,`created_at`,`updated_at`) values
(1,1,1,'in','App\\Models\\PurchaseOrder',1,50,1000000.00,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(2,2,1,'in','App\\Models\\PurchaseOrder',1,50,900000.00,'2026-03-16 19:08:49','2026-03-16 19:08:49'),
(3,1,2,'in','App\\Models\\PurchaseOrder',2,25,1100000.00,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(4,2,2,'in','App\\Models\\PurchaseOrder',2,25,920000.00,'2026-03-16 19:13:39','2026-03-16 19:13:39'),
(5,3,3,'in','App\\Models\\PurchaseOrder',3,30,2000000.00,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(6,4,3,'in','App\\Models\\PurchaseOrder',3,30,700000.00,'2026-03-16 19:14:10','2026-03-16 19:14:10'),
(7,5,1,'adjustment','App\\Models\\StockAdjustment',1,10,NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(8,5,2,'adjustment','App\\Models\\StockAdjustment',2,5,NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(9,5,3,'adjustment','App\\Models\\StockAdjustment',3,2,NULL,'2026-03-16 19:54:14','2026-03-16 19:54:14'),
(10,5,3,'adjustment','App\\Models\\StockAdjustment',4,9,NULL,'2026-03-16 19:58:47','2026-03-16 19:58:47');

/*Table structure for table `store_settings` */

DROP TABLE IF EXISTS `store_settings`;

CREATE TABLE `store_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
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
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `transactions_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `customers` (`id`),
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
  `ui_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_store_setting_id_foreign` (`store_setting_id`),
  CONSTRAINT `users_store_setting_id_foreign` FOREIGN KEY (`store_setting_id`) REFERENCES `store_settings` (`id`),
  CONSTRAINT `users_chk_1` CHECK (json_valid(`ui_preferences`))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`is_active`,`store_setting_id`,`remember_token`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`,`ui_preferences`) values
(1,'Super Admin','superadmin@springbed.id',NULL,'$2y$12$Z76zA490/7lL3SdvtcEsIO4k5IFQn.e4L6JXPRICnxfBEOxVdOTGC',1,NULL,NULL,'2026-03-16 09:02:05','2026-03-16 09:02:05',NULL,NULL,NULL,NULL,NULL),
(2,'Admin','admin@springbed.id',NULL,'$2y$12$XQ8RrYRpi1NODZ.mrKNHHeQy4OmrzvrcZX8J9D0NgFJpz8mdtUYLi',1,1,NULL,'2026-03-16 09:02:05','2026-03-16 19:26:32',NULL,1,NULL,NULL,NULL),
(3,'Owner','owner@springbed.id',NULL,'$2y$12$tx7nLgi7Oy6gBYeLOckpbu4rSm9.lJxoonMdoDmODDuIAUuOJT7rO',1,NULL,NULL,'2026-03-16 09:02:05','2026-03-16 09:02:05',NULL,NULL,NULL,NULL,NULL),
(4,'Staff','staff@springbed.id',NULL,'$2y$12$CbaPoHpdkyXUDY2ebMlNKO4YV6Oes.3rWobmJGYTByhGfOmXoMiNu',1,2,NULL,'2026-03-16 09:02:06','2026-03-16 19:26:42',NULL,1,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
