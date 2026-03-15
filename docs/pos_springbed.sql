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
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activity_log` */

insert  into `activity_log`(`id`,`log_name`,`description`,`subject_type`,`event`,`subject_id`,`causer_type`,`causer_id`,`properties`,`batch_uuid`,`created_at`,`updated_at`) values 
(1,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36\"}',NULL,'2026-03-14 12:52:37','2026-03-14 12:52:37'),
(2,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',1,'App\\Models\\User',1,'{\"id\": 1, \"sku\": \"Laborum omnis neque \", \"name\": \"Barbara Gibbs\", \"size\": \"single\", \"type\": \"springbed\", \"color\": \"Id qui nesciunt en\", \"weight\": 36, \"is_active\": true, \"created_at\": \"2026-03-14 12:53:12\", \"created_by\": 1, \"updated_at\": \"2026-03-14 12:53:12\", \"updated_by\": 1, \"selling_price\": 384}',NULL,'2026-03-14 12:53:12','2026-03-14 12:53:12'),
(3,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"brand_id\": 3}',NULL,'2026-03-14 12:53:12','2026-03-14 12:53:12'),
(4,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"category_id\": 2}',NULL,'2026-03-14 12:53:12','2026-03-14 12:53:12'),
(5,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',2,'App\\Models\\User',1,'{\"id\": 2, \"sku\": \"Quod officia minima \", \"name\": \"Illiana Hayes\", \"size\": \"queen\", \"type\": \"divan\", \"color\": \"Cupidatat sint dolo\", \"weight\": 72, \"is_active\": true, \"created_at\": \"2026-03-14 12:53:42\", \"created_by\": 1, \"updated_at\": \"2026-03-14 12:53:42\", \"updated_by\": 1, \"selling_price\": 762}',NULL,'2026-03-14 12:53:42','2026-03-14 12:53:42'),
(6,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"brand_id\": 4}',NULL,'2026-03-14 12:53:42','2026-03-14 12:53:42'),
(7,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"category_id\": 3}',NULL,'2026-03-14 12:53:42','2026-03-14 12:53:42'),
(8,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',3,'App\\Models\\User',1,'{\"id\": 3, \"sku\": \"Voluptatem maiores \", \"name\": \"Alexis Hayes\", \"size\": \"king\", \"type\": \"headboard\", \"color\": \"Sunt facere velit \", \"weight\": 82, \"is_active\": true, \"created_at\": \"2026-03-14 12:54:27\", \"created_by\": 1, \"updated_at\": \"2026-03-14 12:54:27\", \"updated_by\": 1, \"selling_price\": 354000}',NULL,'2026-03-14 12:54:27','2026-03-14 12:54:27'),
(9,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"brand_id\": 1}',NULL,'2026-03-14 12:54:27','2026-03-14 12:54:27'),
(10,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',3,'App\\Models\\User',1,'{\"category_id\": 4}',NULL,'2026-03-14 12:54:27','2026-03-14 12:54:27'),
(11,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',4,'App\\Models\\User',1,'{\"id\": 4, \"sku\": \"Voluptatem Asperior\", \"name\": \"Tatum Moon\", \"size\": \"custom\", \"type\": \"bundle\", \"color\": \"Totam rerum ut vero \", \"weight\": 1, \"is_active\": true, \"created_at\": \"2026-03-14 12:55:08\", \"created_by\": 1, \"updated_at\": \"2026-03-14 12:55:08\", \"updated_by\": 1, \"selling_price\": 972000}',NULL,'2026-03-14 12:55:08','2026-03-14 12:55:08'),
(12,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"brand_id\": 2}',NULL,'2026-03-14 12:55:08','2026-03-14 12:55:08'),
(13,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',4,'App\\Models\\User',1,'{\"category_id\": 1}',NULL,'2026-03-14 12:55:08','2026-03-14 12:55:08'),
(14,'Resource','Product Created by Super Admin','App\\Models\\Product','Created',5,'App\\Models\\User',1,'{\"id\": 5, \"sku\": \"Aut commodi velit ra\", \"name\": \"Marcia Carr\", \"size\": \"queen\", \"type\": \"springbed\", \"color\": \"Quod voluptate velit\", \"weight\": 43, \"is_active\": true, \"created_at\": \"2026-03-14 12:55:42\", \"created_by\": 1, \"updated_at\": \"2026-03-14 12:55:42\", \"updated_by\": 1, \"selling_price\": 69000}',NULL,'2026-03-14 12:55:42','2026-03-14 12:55:42'),
(15,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',5,'App\\Models\\User',1,'{\"brand_id\": 4}',NULL,'2026-03-14 12:55:42','2026-03-14 12:55:42'),
(16,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',5,'App\\Models\\User',1,'{\"category_id\": 4}',NULL,'2026-03-14 12:55:42','2026-03-14 12:55:42'),
(17,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',1,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-14 12:55:51\", \"selling_price\": 384000}',NULL,'2026-03-14 12:55:51','2026-03-14 12:55:51'),
(18,'Resource','Product Updated by Super Admin','App\\Models\\Product','Updated',2,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-14 12:55:58\", \"selling_price\": 762700}',NULL,'2026-03-14 12:55:58','2026-03-14 12:55:58'),
(19,'Resource','User Updated by Admin','App\\Models\\User','Updated',2,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 13:27:46\", \"updated_by\": 2, \"store_setting_id\": 1}',NULL,'2026-03-14 13:27:46','2026-03-14 13:27:46'),
(20,'Resource','Product Created by Admin','App\\Models\\Product','Created',6,'App\\Models\\User',2,'{\"id\": 6, \"sku\": \"Qui veniam dolore u\", \"name\": \"Tes admin 111\", \"size\": \"single\", \"type\": \"divan\", \"color\": \"Aut ipsa tempor qui\", \"weight\": 43, \"is_active\": true, \"created_at\": \"2026-03-14 13:31:15\", \"created_by\": 2, \"updated_at\": \"2026-03-14 13:31:15\", \"updated_by\": 2, \"selling_price\": 52}',NULL,'2026-03-14 13:31:15','2026-03-14 13:31:15'),
(21,'Resource','Product Updated by Admin','App\\Models\\Product','Updated',6,'App\\Models\\User',2,'{\"brand_id\": 4}',NULL,'2026-03-14 13:31:15','2026-03-14 13:31:15'),
(22,'Resource','Product Updated by Admin','App\\Models\\Product','Updated',6,'App\\Models\\User',2,'{\"category_id\": 3}',NULL,'2026-03-14 13:31:15','2026-03-14 13:31:15'),
(23,'Resource','User Updated by Admin','App\\Models\\User','Updated',4,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 13:31:30\", \"updated_by\": 2, \"store_setting_id\": 2}',NULL,'2026-03-14 13:31:30','2026-03-14 13:31:30'),
(24,'Resource','Product Updated by Admin','App\\Models\\Product','Updated',6,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 13:35:21\", \"selling_price\": 525000}',NULL,'2026-03-14 13:35:21','2026-03-14 13:35:21'),
(25,'Resource','Purchase Order Created by Admin','App\\Models\\PurchaseOrder','Created',1,'App\\Models\\User',2,'{\"id\": 1, \"created_at\": \"2026-03-14 13:46:40\", \"created_by\": 2, \"updated_at\": \"2026-03-14 13:46:40\", \"updated_by\": 2, \"total_amount\": 5955000, \"purchase_date\": \"2026-03-14 00:00:00\", \"supplier_name\": \"Alisa Henson\", \"invoice_number\": \"INVUKCYR19E1773470649\", \"store_setting_id\": 1}',NULL,'2026-03-14 13:46:40','2026-03-14 13:46:40'),
(26,'Resource','Purchase Order Created by Admin','App\\Models\\PurchaseOrder','Created',2,'App\\Models\\User',2,'{\"id\": 2, \"created_at\": \"2026-03-14 13:59:04\", \"created_by\": 2, \"updated_at\": \"2026-03-14 13:59:04\", \"updated_by\": 2, \"total_amount\": 4525000, \"purchase_date\": \"2026-03-14 00:00:00\", \"supplier_name\": \"Zeph Gordon\", \"invoice_number\": \"INVNN2DOFIB1773471457\", \"store_setting_id\": 1}',NULL,'2026-03-14 13:59:04','2026-03-14 13:59:04'),
(27,'Resource','Purchase Order Created by Admin','App\\Models\\PurchaseOrder','Created',3,'App\\Models\\User',2,'{\"id\": 3, \"created_at\": \"2026-03-14 14:09:26\", \"created_by\": 2, \"updated_at\": \"2026-03-14 14:09:26\", \"updated_by\": 2, \"total_amount\": 4500000, \"purchase_date\": \"2026-03-14 00:00:00\", \"supplier_name\": \"Tes Purchase Admin\", \"invoice_number\": \"INVE6GFUSN21773472122\", \"store_setting_id\": 1}',NULL,'2026-03-14 14:09:26','2026-03-14 14:09:26'),
(28,'Resource','Promo Created by Admin','App\\Models\\Promo','Created',1,'App\\Models\\User',2,'{\"id\": 1, \"name\": \"Tes nominal\", \"type\": \"flash_sale\", \"end_date\": \"2026-03-18 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-14 19:54:28\", \"created_by\": 2, \"start_date\": \"2026-03-14 00:00:00\", \"updated_at\": \"2026-03-14 19:54:28\", \"updated_by\": 2, \"usage_limit\": 20, \"min_purchase\": 100000, \"discount_type\": \"percentage\", \"discount_value\": 50}',NULL,'2026-03-14 19:54:28','2026-03-14 19:54:28'),
(29,'Resource','Promo Created by Admin','App\\Models\\Promo','Created',2,'App\\Models\\User',2,'{\"id\": 2, \"name\": \"Tes 2\", \"type\": \"voucher\", \"end_date\": \"2026-03-20 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-14 20:19:46\", \"created_by\": 2, \"start_date\": \"2026-03-14 00:00:00\", \"updated_at\": \"2026-03-14 20:19:46\", \"updated_by\": 2, \"usage_limit\": 15, \"min_purchase\": 100000, \"discount_type\": \"percentage\", \"discount_value\": 2}',NULL,'2026-03-14 20:19:46','2026-03-14 20:19:46'),
(30,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 21:27:31\"}',NULL,'2026-03-14 21:27:31','2026-03-14 21:27:31'),
(31,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 21:27:33\"}',NULL,'2026-03-14 21:27:33','2026-03-14 21:27:33'),
(32,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 21:41:50\"}',NULL,'2026-03-14 21:41:50','2026-03-14 21:41:50'),
(33,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 21:41:53\"}',NULL,'2026-03-14 21:41:53','2026-03-14 21:41:53'),
(34,'Resource','Promo Created by Admin','App\\Models\\Promo','Created',3,'App\\Models\\User',2,'{\"id\": 3, \"name\": \"tes free shipping\", \"type\": \"free_shipping\", \"end_date\": \"2026-03-21 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-14 21:49:12\", \"created_by\": 2, \"start_date\": \"2026-03-14 00:00:00\", \"updated_at\": \"2026-03-14 21:49:12\", \"updated_by\": 2, \"usage_limit\": 10, \"min_purchase\": 100000, \"discount_type\": \"nominal\", \"discount_value\": 100000}',NULL,'2026-03-14 21:49:12','2026-03-14 21:49:12'),
(35,'Resource','Promo Created by Admin','App\\Models\\Promo','Created',4,'App\\Models\\User',2,'{\"id\": 4, \"name\": \"tes promo\", \"type\": \"flash_sale\", \"end_date\": \"2026-03-26 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-14 21:49:47\", \"created_by\": 2, \"start_date\": \"2026-03-14 00:00:00\", \"updated_at\": \"2026-03-14 21:49:47\", \"updated_by\": 2, \"usage_limit\": 5, \"min_purchase\": 150000, \"discount_type\": \"nominal\", \"discount_value\": 20000}',NULL,'2026-03-14 21:49:47','2026-03-14 21:49:47'),
(36,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"type\": \"voucher\", \"updated_at\": \"2026-03-14 21:50:56\"}',NULL,'2026-03-14 21:50:56','2026-03-14 21:50:56'),
(37,'Resource','User Updated by Admin','App\\Models\\User','Updated',2,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 22:04:18\", \"ui_preferences\": \"{\\\"ui.color\\\":\\\"#22c55e\\\"}\"}',NULL,'2026-03-14 22:04:18','2026-03-14 22:04:18'),
(38,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:17:06\"}',NULL,'2026-03-14 22:17:06','2026-03-14 22:17:06'),
(39,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:17:07\"}',NULL,'2026-03-14 22:17:07','2026-03-14 22:17:07'),
(40,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:17:56\"}',NULL,'2026-03-14 22:17:56','2026-03-14 22:17:56'),
(41,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:17:57\"}',NULL,'2026-03-14 22:17:57','2026-03-14 22:17:57'),
(42,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:19:46\"}',NULL,'2026-03-14 22:19:46','2026-03-14 22:19:46'),
(43,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:19:48\"}',NULL,'2026-03-14 22:19:48','2026-03-14 22:19:48'),
(44,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:29:03\"}',NULL,'2026-03-14 22:29:03','2026-03-14 22:29:03'),
(45,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:29:04\"}',NULL,'2026-03-14 22:29:04','2026-03-14 22:29:04'),
(46,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36\"}',NULL,'2026-03-14 22:29:59','2026-03-14 22:29:59'),
(47,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:30:52\"}',NULL,'2026-03-14 22:30:52','2026-03-14 22:30:52'),
(48,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:30:53\"}',NULL,'2026-03-14 22:30:53','2026-03-14 22:30:53'),
(49,'Resource','User Updated by Admin','App\\Models\\User','Updated',2,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 22:31:02\", \"ui_preferences\": \"{\\\"ui.color\\\":\\\"#8b5cf6\\\"}\"}',NULL,'2026-03-14 22:31:02','2026-03-14 22:31:02'),
(50,'Resource','User Updated by Admin','App\\Models\\User','Updated',2,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-14 22:31:50\", \"ui_preferences\": \"{\\\"ui.color\\\":\\\"#ec4899\\\"}\"}',NULL,'2026-03-14 22:31:50','2026-03-14 22:31:50'),
(51,'Resource','User Updated by Super Admin','App\\Models\\User','Updated',1,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-14 22:32:03\", \"updated_by\": 1, \"ui_preferences\": \"{\\\"ui.color\\\":\\\"#ec4899\\\"}\"}',NULL,'2026-03-14 22:32:03','2026-03-14 22:32:03'),
(52,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:32:45\", \"updated_by\": 1}',NULL,'2026-03-14 22:32:45','2026-03-14 22:32:45'),
(53,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:32:47\"}',NULL,'2026-03-14 22:32:47','2026-03-14 22:32:47'),
(54,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-14 22:34:33','2026-03-14 22:34:33'),
(55,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-14 22:34:43\", \"updated_by\": 1}',NULL,'2026-03-14 22:34:43','2026-03-14 22:34:43'),
(56,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-14 22:34:57','2026-03-14 22:34:57'),
(57,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-14 22:35:07\"}',NULL,'2026-03-14 22:35:07','2026-03-14 22:35:07'),
(58,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:39:31\", \"updated_by\": 1}',NULL,'2026-03-14 22:39:31','2026-03-14 22:39:31'),
(59,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:39:32\"}',NULL,'2026-03-14 22:39:32','2026-03-14 22:39:32'),
(60,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:39:34\"}',NULL,'2026-03-14 22:39:34','2026-03-14 22:39:34'),
(61,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:39:35\"}',NULL,'2026-03-14 22:39:35','2026-03-14 22:39:35'),
(62,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-14 22:40:36','2026-03-14 22:40:36'),
(63,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-14 22:40:42\"}',NULL,'2026-03-14 22:40:42','2026-03-14 22:40:42'),
(64,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36\"}',NULL,'2026-03-14 22:41:26','2026-03-14 22:41:26'),
(65,'Access','Super Admin logged in','App\\Models\\User','Login',1,'App\\Models\\User',1,'{\"ip\": \"127.0.0.1\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36\"}',NULL,'2026-03-14 22:42:06','2026-03-14 22:42:06'),
(66,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:42:15\"}',NULL,'2026-03-14 22:42:15','2026-03-14 22:42:15'),
(67,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:42:17\"}',NULL,'2026-03-14 22:42:17','2026-03-14 22:42:17'),
(68,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:42:19\"}',NULL,'2026-03-14 22:42:19','2026-03-14 22:42:19'),
(69,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-14 22:42:21\", \"updated_by\": 1}',NULL,'2026-03-14 22:42:21','2026-03-14 22:42:21'),
(70,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:42:22\"}',NULL,'2026-03-14 22:42:22','2026-03-14 22:42:22'),
(71,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:42:24\"}',NULL,'2026-03-14 22:42:24','2026-03-14 22:42:24'),
(72,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:42:24\"}',NULL,'2026-03-14 22:42:24','2026-03-14 22:42:24'),
(73,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-14 22:42:25\"}',NULL,'2026-03-14 22:42:25','2026-03-14 22:42:25'),
(74,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-14 22:47:19\", \"discount_value\": 120000}',NULL,'2026-03-14 22:47:19','2026-03-14 22:47:19'),
(75,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 07:41:17\"}',NULL,'2026-03-15 07:41:17','2026-03-15 07:41:17'),
(76,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 07:41:18\"}',NULL,'2026-03-15 07:41:18','2026-03-15 07:41:18'),
(77,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 07:41:45\"}',NULL,'2026-03-15 07:41:45','2026-03-15 07:41:45'),
(78,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 07:41:51\"}',NULL,'2026-03-15 07:41:51','2026-03-15 07:41:51'),
(79,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 07:41:53\"}',NULL,'2026-03-15 07:41:53','2026-03-15 07:41:53'),
(80,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 07:41:54\"}',NULL,'2026-03-15 07:41:54','2026-03-15 07:41:54'),
(81,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 07:41:56\"}',NULL,'2026-03-15 07:41:56','2026-03-15 07:41:56'),
(82,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 07:41:57\"}',NULL,'2026-03-15 07:41:58','2026-03-15 07:41:58'),
(83,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 07:46:06\"}',NULL,'2026-03-15 07:46:06','2026-03-15 07:46:06'),
(84,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 07:46:07\"}',NULL,'2026-03-15 07:46:07','2026-03-15 07:46:07'),
(85,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-15 07:58:35','2026-03-15 07:58:35'),
(86,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 07:58:58\"}',NULL,'2026-03-15 07:58:58','2026-03-15 07:58:58'),
(87,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-15 07:59:04','2026-03-15 07:59:04'),
(88,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 07:59:09\"}',NULL,'2026-03-15 07:59:09','2026-03-15 07:59:09'),
(89,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:00:28','2026-03-15 08:00:28'),
(90,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',3,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:00:49','2026-03-15 08:00:49'),
(91,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:01:26\"}',NULL,'2026-03-15 08:01:26','2026-03-15 08:01:26'),
(92,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:01:29\"}',NULL,'2026-03-15 08:01:29','2026-03-15 08:01:29'),
(93,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:01:34','2026-03-15 08:01:34'),
(94,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:01:39\"}',NULL,'2026-03-15 08:01:39','2026-03-15 08:01:39'),
(95,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:02:35','2026-03-15 08:02:35'),
(96,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:11:07\"}',NULL,'2026-03-15 08:11:07','2026-03-15 08:11:07'),
(97,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:12:03','2026-03-15 08:12:03'),
(98,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',3,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:12:27','2026-03-15 08:12:27'),
(99,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:12:44','2026-03-15 08:12:44'),
(100,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:13:06\"}',NULL,'2026-03-15 08:13:06','2026-03-15 08:13:06'),
(101,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:13:09\"}',NULL,'2026-03-15 08:13:09','2026-03-15 08:13:09'),
(102,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:13:11\"}',NULL,'2026-03-15 08:13:11','2026-03-15 08:13:11'),
(103,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:13:42','2026-03-15 08:13:42'),
(104,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:13:48\"}',NULL,'2026-03-15 08:13:48','2026-03-15 08:13:48'),
(105,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:15:15','2026-03-15 08:15:15'),
(106,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',3,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:15:47','2026-03-15 08:15:47'),
(107,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',2,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:16:41','2026-03-15 08:16:41'),
(108,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',1,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:16:47','2026-03-15 08:16:47'),
(109,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:17:08\"}',NULL,'2026-03-15 08:17:08','2026-03-15 08:17:08'),
(110,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:17:11\"}',NULL,'2026-03-15 08:17:11','2026-03-15 08:17:11'),
(111,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',2,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:17:13\"}',NULL,'2026-03-15 08:17:13','2026-03-15 08:17:13'),
(112,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:17:17\"}',NULL,'2026-03-15 08:17:17','2026-03-15 08:17:17'),
(113,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:18:23','2026-03-15 08:18:23'),
(114,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',3,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:19:43','2026-03-15 08:19:43'),
(115,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:19:48\"}',NULL,'2026-03-15 08:19:48','2026-03-15 08:19:48'),
(116,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:19:51\"}',NULL,'2026-03-15 08:19:51','2026-03-15 08:19:51'),
(117,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',4,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:21:52','2026-03-15 08:21:52'),
(118,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',4,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:22:18\"}',NULL,'2026-03-15 08:22:18','2026-03-15 08:22:18'),
(119,'Resource','Promo Deleted by Super Admin','App\\Models\\Promo','Deleted',3,'App\\Models\\User',1,'[]',NULL,'2026-03-15 08:28:45','2026-03-15 08:28:45'),
(120,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 08:28:53\"}',NULL,'2026-03-15 08:28:53','2026-03-15 08:28:53'),
(121,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',5,'App\\Models\\User',1,'{\"id\": 5, \"name\": \"Karly Lawson\", \"type\": \"voucher\", \"end_date\": \"2026-03-24 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:29:30\", \"created_by\": 1, \"start_date\": \"2026-03-15 00:00:00\", \"updated_at\": \"2026-03-15 08:29:30\", \"updated_by\": 1, \"usage_limit\": 22, \"min_purchase\": 22222, \"discount_type\": \"nominal\", \"discount_value\": 12222}',NULL,'2026-03-15 08:29:30','2026-03-15 08:29:30'),
(122,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',6,'App\\Models\\User',1,'{\"id\": 6, \"name\": \"Kristen Wolfe\", \"type\": \"flash_sale\", \"end_date\": \"2026-03-19 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:29:49\", \"created_by\": 1, \"start_date\": \"2026-03-15 00:00:00\", \"updated_at\": \"2026-03-15 08:29:49\", \"updated_by\": 1, \"usage_limit\": 12, \"min_purchase\": 232344, \"discount_type\": \"nominal\", \"discount_value\": 123233}',NULL,'2026-03-15 08:29:49','2026-03-15 08:29:49'),
(123,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',7,'App\\Models\\User',1,'{\"id\": 7, \"name\": \"Deacon Boyd\", \"type\": \"free_shipping\", \"end_date\": \"2026-03-15 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:30:13\", \"created_by\": 1, \"start_date\": \"2026-03-27 00:00:00\", \"updated_at\": \"2026-03-15 08:30:13\", \"updated_by\": 1, \"usage_limit\": 23, \"min_purchase\": 434343, \"discount_type\": \"percentage\", \"discount_value\": 23}',NULL,'2026-03-15 08:30:13','2026-03-15 08:30:13'),
(124,'Resource','Promo Updated by Super Admin','App\\Models\\Promo','Updated',7,'App\\Models\\User',1,'{\"start_date\": \"2026-03-13 00:00:00\", \"updated_at\": \"2026-03-15 08:32:27\"}',NULL,'2026-03-15 08:32:27','2026-03-15 08:32:27'),
(125,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',8,'App\\Models\\User',1,'{\"id\": 8, \"name\": \"Jael Oneil\", \"type\": \"free_shipping\", \"end_date\": \"2026-03-24 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:37:57\", \"created_by\": 1, \"start_date\": \"2026-03-13 00:00:00\", \"updated_at\": \"2026-03-15 08:37:57\", \"updated_by\": 1, \"usage_limit\": 34, \"min_purchase\": 22222, \"discount_type\": \"percentage\", \"discount_value\": 23}',NULL,'2026-03-15 08:37:57','2026-03-15 08:37:57'),
(126,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',9,'App\\Models\\User',1,'{\"id\": 9, \"name\": \"Deborah Kinney\", \"type\": \"voucher\", \"end_date\": \"2026-03-31 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:38:36\", \"created_by\": 1, \"start_date\": \"2026-03-05 00:00:00\", \"updated_at\": \"2026-03-15 08:38:36\", \"updated_by\": 1, \"usage_limit\": 22, \"min_purchase\": 23322, \"discount_type\": \"percentage\", \"discount_value\": 32}',NULL,'2026-03-15 08:38:36','2026-03-15 08:38:36'),
(127,'Resource','Promo Created by Super Admin','App\\Models\\Promo','Created',10,'App\\Models\\User',1,'{\"id\": 10, \"name\": \"Super Admin\", \"type\": \"flash_sale\", \"end_date\": \"2026-03-25 00:00:00\", \"is_active\": true, \"created_at\": \"2026-03-15 08:39:13\", \"created_by\": 1, \"start_date\": \"2026-03-19 00:00:00\", \"updated_at\": \"2026-03-15 08:39:13\", \"updated_by\": 1, \"usage_limit\": 123, \"min_purchase\": 1231231, \"discount_type\": \"nominal\", \"discount_value\": 132}',NULL,'2026-03-15 08:39:13','2026-03-15 08:39:13'),
(128,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',1,'App\\Models\\User',1,'{\"id\": 1, \"name\": \"Tes bundle 1\", \"is_active\": true, \"created_at\": \"2026-03-15 08:55:58\", \"created_by\": 1, \"updated_at\": \"2026-03-15 08:55:58\", \"updated_by\": 1, \"bundle_price\": 1860000}',NULL,'2026-03-15 08:55:58','2026-03-15 08:55:58'),
(129,'Resource','Bundle Updated by Super Admin','App\\Models\\Bundle','Updated',1,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-15 09:01:53\", \"bundle_price\": 1800000}',NULL,'2026-03-15 09:01:53','2026-03-15 09:01:53'),
(130,'Resource','Bundle Updated by Super Admin','App\\Models\\Bundle','Updated',1,'App\\Models\\User',1,'{\"updated_at\": \"2026-03-15 09:01:55\", \"bundle_price\": 1800000}',NULL,'2026-03-15 09:01:55','2026-03-15 09:01:55'),
(131,'Resource','Bundle Deleted by Super Admin','App\\Models\\Bundle','Deleted',1,'App\\Models\\User',1,'[]',NULL,'2026-03-15 09:17:21','2026-03-15 09:17:21'),
(132,'Resource','Bundle Updated by Super Admin','App\\Models\\Bundle','Updated',1,'App\\Models\\User',1,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 09:17:27\"}',NULL,'2026-03-15 09:17:27','2026-03-15 09:17:27'),
(133,'Resource','Bundle Updated by Super Admin','App\\Models\\Bundle','Updated',1,'App\\Models\\User',1,'{\"is_active\": false, \"updated_at\": \"2026-03-15 09:18:01\"}',NULL,'2026-03-15 09:18:01','2026-03-15 09:18:01'),
(134,'Resource','Bundle Updated by Super Admin','App\\Models\\Bundle','Updated',1,'App\\Models\\User',1,'{\"is_active\": true, \"updated_at\": \"2026-03-15 09:18:04\"}',NULL,'2026-03-15 09:18:04','2026-03-15 09:18:04'),
(135,'Resource','Bundle Deleted by Staff','App\\Models\\Bundle','Deleted',1,'App\\Models\\User',4,'[]',NULL,'2026-03-15 09:23:43','2026-03-15 09:23:43'),
(136,'Resource','Bundle Updated by Staff','App\\Models\\Bundle','Updated',1,'App\\Models\\User',4,'{\"deleted_at\": null, \"deleted_by\": null, \"updated_at\": \"2026-03-15 09:23:47\", \"updated_by\": 4}',NULL,'2026-03-15 09:23:47','2026-03-15 09:23:47'),
(137,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',2,'App\\Models\\User',1,'{\"id\": 2, \"name\": \"Bundle 2\", \"is_active\": true, \"created_at\": \"2026-03-15 13:59:28\", \"created_by\": 1, \"updated_at\": \"2026-03-15 13:59:28\", \"updated_by\": 1, \"bundle_price\": 1306000}',NULL,'2026-03-15 13:59:28','2026-03-15 13:59:28'),
(138,'Resource','Bundle Created by Super Admin','App\\Models\\Bundle','Created',3,'App\\Models\\User',1,'{\"id\": 3, \"name\": \"Bunlde 3\", \"is_active\": true, \"created_at\": \"2026-03-15 13:59:52\", \"created_by\": 1, \"updated_at\": \"2026-03-15 13:59:52\", \"updated_by\": 1, \"bundle_price\": 1566000}',NULL,'2026-03-15 13:59:52','2026-03-15 13:59:52'),
(139,'Resource','Bundle Updated by Admin','App\\Models\\Bundle','Updated',2,'App\\Models\\User',2,'{\"updated_at\": \"2026-03-15 14:03:04\", \"updated_by\": 2, \"bundle_price\": 453000}',NULL,'2026-03-15 14:03:04','2026-03-15 14:03:04'),
(140,'Resource','Bundle Updated by Staff','App\\Models\\Bundle','Updated',1,'App\\Models\\User',4,'{\"name\": \"Tes bundle toko 2\", \"updated_at\": \"2026-03-15 14:03:55\", \"bundle_price\": 1800000}',NULL,'2026-03-15 14:03:55','2026-03-15 14:03:55'),
(141,'Resource','Bundle Updated by Staff','App\\Models\\Bundle','Updated',1,'App\\Models\\User',4,'{\"updated_at\": \"2026-03-15 14:07:38\", \"bundle_price\": 2497400}',NULL,'2026-03-15 14:07:38','2026-03-15 14:07:38'),
(142,'Resource','Bundle Updated by Staff','App\\Models\\Bundle','Updated',1,'App\\Models\\User',4,'{\"updated_at\": \"2026-03-15 14:07:43\", \"bundle_price\": 1734700}',NULL,'2026-03-15 14:07:43','2026-03-15 14:07:43'),
(143,'Resource','Bundle Updated by Staff','App\\Models\\Bundle','Updated',1,'App\\Models\\User',4,'{\"updated_at\": \"2026-03-15 14:08:08\", \"bundle_price\": 2706700}',NULL,'2026-03-15 14:08:08','2026-03-15 14:08:08'),
(144,'Resource','Inventory Stock Deleted by Staff','App\\Models\\InventoryStock','Deleted',4,'App\\Models\\User',4,'[]',NULL,'2026-03-15 14:08:39','2026-03-15 14:08:39'),
(145,'Resource','Inventory Stock Created by Staff','App\\Models\\InventoryStock','Created',8,'App\\Models\\User',4,'{\"id\": 8, \"quantity\": 22, \"created_at\": \"2026-03-15 14:08:39\", \"product_id\": 4, \"updated_at\": \"2026-03-15 14:08:39\", \"store_setting_id\": 2}',NULL,'2026-03-15 14:08:39','2026-03-15 14:08:39'),
(146,'Resource','Courier Created by Admin','App\\Models\\Courier','Created',1,'App\\Models\\User',2,'{\"id\": 1, \"name\": \"Courier 1\", \"type\": \"internal\", \"is_active\": true, \"created_at\": \"2026-03-15 14:27:38\", \"created_by\": 2, \"updated_at\": \"2026-03-15 14:27:38\", \"updated_by\": 2, \"shipping_cost\": 100000}',NULL,'2026-03-15 14:27:38','2026-03-15 14:27:38'),
(147,'Resource','Courier Created by Admin','App\\Models\\Courier','Created',2,'App\\Models\\User',2,'{\"id\": 2, \"name\": \"Courier 2\", \"type\": \"internal\", \"is_active\": true, \"created_at\": \"2026-03-15 14:27:47\", \"created_by\": 2, \"updated_at\": \"2026-03-15 14:27:47\", \"updated_by\": 2, \"shipping_cost\": 120000}',NULL,'2026-03-15 14:27:47','2026-03-15 14:27:47'),
(148,'Resource','Courier Created by Admin','App\\Models\\Courier','Created',3,'App\\Models\\User',2,'{\"id\": 3, \"name\": \"Courier 3\", \"type\": \"external\", \"is_active\": true, \"created_at\": \"2026-03-15 14:27:58\", \"created_by\": 2, \"updated_at\": \"2026-03-15 14:27:58\", \"updated_by\": 2, \"shipping_cost\": 20000}',NULL,'2026-03-15 14:27:58','2026-03-15 14:27:58'),
(149,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',1,'App\\Models\\User',2,'{\"id\": 1, \"status\": \"pending\", \"promo_id\": 1, \"subtotal\": 760000, \"created_at\": \"2026-03-15 15:34:32\", \"created_by\": 2, \"updated_at\": \"2026-03-15 15:34:32\", \"updated_by\": 2, \"customer_id\": null, \"grand_total\": 480000, \"shiping_cost\": 100000, \"discount_total\": 380000, \"transaction_code\": \"TRXEZLE1ZTE20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 15:34:32','2026-03-15 15:34:32'),
(150,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',1,'App\\Models\\User',2,'{\"end_date\": \"2026-03-06 00:00:00\", \"start_date\": \"2026-03-04 00:00:00\", \"updated_at\": \"2026-03-15 15:46:34\", \"updated_by\": 2}',NULL,'2026-03-15 15:46:34','2026-03-15 15:46:34'),
(151,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',2,'App\\Models\\User',2,'{\"id\": 2, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 900000, \"created_at\": \"2026-03-15 15:47:41\", \"created_by\": 2, \"updated_at\": \"2026-03-15 15:47:41\", \"updated_by\": 2, \"customer_id\": 1, \"grand_total\": 900000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXSTDDSTHT20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 15:47:41','2026-03-15 15:47:41'),
(152,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',1,'App\\Models\\User',2,'{\"quantity\": 14, \"updated_at\": \"2026-03-15 15:47:42\"}',NULL,'2026-03-15 15:47:42','2026-03-15 15:47:42'),
(153,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',7,'App\\Models\\User',2,'{\"quantity\": 114, \"updated_at\": \"2026-03-15 15:47:42\"}',NULL,'2026-03-15 15:47:42','2026-03-15 15:47:42'),
(154,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"updated_by\": 2, \"usage_count\": 1}',NULL,'2026-03-15 15:47:42','2026-03-15 15:47:42'),
(155,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',3,'App\\Models\\User',2,'{\"id\": 3, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59845000, \"created_at\": \"2026-03-15 15:53:45\", \"created_by\": 2, \"updated_at\": \"2026-03-15 15:53:45\", \"updated_by\": 2, \"customer_id\": 2, \"grand_total\": 59845000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXAP668RVF20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 15:53:45','2026-03-15 15:53:45'),
(157,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',4,'App\\Models\\User',2,'{\"id\": 4, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59845000, \"created_at\": \"2026-03-15 15:54:25\", \"created_by\": 2, \"updated_at\": \"2026-03-15 15:54:25\", \"updated_by\": 2, \"customer_id\": 2, \"grand_total\": 59845000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXAP668RVF20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 15:54:25','2026-03-15 15:54:25'),
(159,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',5,'App\\Models\\User',2,'{\"id\": 5, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59845000, \"created_at\": \"2026-03-15 16:02:11\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:02:11\", \"updated_by\": 2, \"customer_id\": 2, \"grand_total\": 59845000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXAP668RVF20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:02:11','2026-03-15 16:02:11'),
(161,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',6,'App\\Models\\User',2,'{\"id\": 6, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59850000, \"created_at\": \"2026-03-15 16:04:26\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:04:26\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 59850000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXF7HFBAGJ20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:04:26','2026-03-15 16:04:26'),
(163,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',7,'App\\Models\\User',2,'{\"id\": 7, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59850000, \"created_at\": \"2026-03-15 16:08:12\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:08:12\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 59850000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXF7HFBAGJ20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:08:12','2026-03-15 16:08:12'),
(165,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',8,'App\\Models\\User',2,'{\"id\": 8, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59850000, \"created_at\": \"2026-03-15 16:13:24\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:13:24\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 59850000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXF7HFBAGJ20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:13:24','2026-03-15 16:13:24'),
(167,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',9,'App\\Models\\User',2,'{\"id\": 9, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 59850000, \"created_at\": \"2026-03-15 16:21:02\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:21:02\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 59850000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXF7HFBAGJ20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:21:02','2026-03-15 16:21:02'),
(168,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',7,'App\\Models\\User',2,'{\"quantity\": 0, \"updated_at\": \"2026-03-15 16:21:02\"}',NULL,'2026-03-15 16:21:02','2026-03-15 16:21:02'),
(169,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"updated_by\": 2, \"usage_count\": 2}',NULL,'2026-03-15 16:21:02','2026-03-15 16:21:02'),
(170,'Resource','Inventory Stock Deleted by Admin','App\\Models\\InventoryStock','Deleted',7,'App\\Models\\User',2,'[]',NULL,'2026-03-15 16:24:38','2026-03-15 16:24:38'),
(171,'Resource','Inventory Stock Created by Admin','App\\Models\\InventoryStock','Created',9,'App\\Models\\User',2,'{\"id\": 9, \"quantity\": 44, \"created_at\": \"2026-03-15 16:24:38\", \"product_id\": 6, \"updated_at\": \"2026-03-15 16:24:38\", \"store_setting_id\": 1}',NULL,'2026-03-15 16:24:38','2026-03-15 16:24:38'),
(172,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',10,'App\\Models\\User',2,'{\"id\": 10, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 23445000, \"created_at\": \"2026-03-15 16:26:01\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:26:01\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 23445000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXSYMIAG7220260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(173,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',9,'App\\Models\\User',2,'{\"quantity\": 0, \"updated_at\": \"2026-03-15 16:26:01\"}',NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(174,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',5,'App\\Models\\User',2,'{\"quantity\": 10, \"updated_at\": \"2026-03-15 16:26:01\"}',NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(175,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"updated_by\": 2, \"usage_count\": 3}',NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(176,'Resource','Transaction Created by Admin','App\\Models\\Transaction','Created',11,'App\\Models\\User',2,'{\"id\": 11, \"status\": \"pending\", \"promo_id\": 3, \"subtotal\": 138000, \"created_at\": \"2026-03-15 16:47:37\", \"created_by\": 2, \"updated_at\": \"2026-03-15 16:47:37\", \"updated_by\": 2, \"customer_id\": 3, \"grand_total\": 138000, \"shiping_cost\": 120000, \"discount_total\": 120000, \"store_setting_id\": 1, \"transaction_code\": \"TRXYKJR74MD20260315\", \"transaction_date\": \"2026-03-15 00:00:00\"}',NULL,'2026-03-15 16:47:37','2026-03-15 16:47:37'),
(177,'Resource','Inventory Stock Updated by Admin','App\\Models\\InventoryStock','Updated',5,'App\\Models\\User',2,'{\"quantity\": 8, \"updated_at\": \"2026-03-15 16:47:37\"}',NULL,'2026-03-15 16:47:37','2026-03-15 16:47:37'),
(178,'Resource','Promo Updated by Admin','App\\Models\\Promo','Updated',3,'App\\Models\\User',2,'{\"updated_by\": 2, \"usage_count\": 4}',NULL,'2026-03-15 16:47:37','2026-03-15 16:47:37'),
(179,'Resource','Transaction Updated by Admin','App\\Models\\Transaction','Updated',11,'App\\Models\\User',2,'{\"status\": \"processed\", \"updated_at\": \"2026-03-15 16:58:48\"}',NULL,'2026-03-15 16:58:48','2026-03-15 16:58:48'),
(180,'Resource','Transaction Updated by Admin','App\\Models\\Transaction','Updated',11,'App\\Models\\User',2,'{\"status\": \"delivered\", \"updated_at\": \"2026-03-15 16:59:00\"}',NULL,'2026-03-15 16:59:00','2026-03-15 16:59:00'),
(181,'Resource','Transaction Updated by Admin','App\\Models\\Transaction','Updated',11,'App\\Models\\User',2,'{\"status\": \"shipped\", \"updated_at\": \"2026-03-15 17:02:48\"}',NULL,'2026-03-15 17:02:48','2026-03-15 17:02:48');

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
(1,'King Koil',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(2,'Serta',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(3,'Comforta',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(4,'Florence',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL);

/*Table structure for table `bundle_items` */

DROP TABLE IF EXISTS `bundle_items`;

CREATE TABLE `bundle_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bundle_items_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_items_product_id_foreign` (`product_id`),
  CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`),
  CONSTRAINT `bundle_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bundle_items` */

insert  into `bundle_items`(`id`,`bundle_id`,`product_id`,`qty`,`created_at`,`updated_at`) values 
(1,1,2,1,'2026-03-15 08:55:58','2026-03-15 14:07:43'),
(2,1,4,2,'2026-03-15 08:55:58','2026-03-15 14:08:08'),
(3,2,1,1,'2026-03-15 13:59:28','2026-03-15 13:59:28'),
(4,2,5,1,'2026-03-15 13:59:28','2026-03-15 14:03:04'),
(5,3,4,1,'2026-03-15 13:59:52','2026-03-15 13:59:52'),
(6,3,6,1,'2026-03-15 13:59:52','2026-03-15 13:59:52'),
(7,3,5,1,'2026-03-15 13:59:52','2026-03-15 13:59:52');

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
(1,'Tes bundle toko 2',2706700.00,1,'2026-03-15 08:55:58','2026-03-15 14:08:08',1,4,NULL,NULL),
(2,'Bundle 2',453000.00,1,'2026-03-15 13:59:28','2026-03-15 14:03:04',1,2,NULL,NULL),
(3,'Bunlde 3',1566000.00,1,'2026-03-15 13:59:52','2026-03-15 13:59:52',1,1,NULL,NULL);

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
(1,'Pocket','pocket-1773467522',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(2,'Bonnel','bonnel-1773467522',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(3,'Hybird','hybird-1773467522',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(4,'Mattress','mattress-1773467522',1,'2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `couriers` */

insert  into `couriers`(`id`,`name`,`type`,`shipping_cost`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Courier 1','internal',100000.00,1,'2026-03-15 14:27:38','2026-03-15 14:27:38',2,2,NULL,NULL),
(2,'Courier 2','internal',120000.00,1,'2026-03-15 14:27:47','2026-03-15 14:27:47',2,2,NULL,NULL),
(3,'Courier 3','external',20000.00,1,'2026-03-15 14:27:58','2026-03-15 14:27:58',2,2,NULL,NULL);

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
(1,'John Doe','1234567890','johndoe@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(2,'Jane Doe','123153242','janedoe@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(3,'David Smith','64646546','david@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `inventory_stocks` */

insert  into `inventory_stocks`(`id`,`product_id`,`store_setting_id`,`quantity`,`created_at`,`updated_at`) values 
(1,1,1,14,'2026-03-14 12:53:12','2026-03-15 15:47:42'),
(2,2,2,22,'2026-03-14 12:53:42','2026-03-14 12:53:42'),
(3,3,3,33,'2026-03-14 12:54:27','2026-03-14 12:54:27'),
(5,5,1,8,'2026-03-14 12:55:42','2026-03-15 16:47:37'),
(8,4,2,22,'2026-03-15 14:08:39','2026-03-15 14:08:39'),
(9,6,1,0,'2026-03-15 16:24:38','2026-03-15 16:26:01');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `media` */

insert  into `media`(`id`,`model_type`,`model_id`,`uuid`,`collection_name`,`name`,`file_name`,`mime_type`,`disk`,`conversions_disk`,`size`,`manipulations`,`custom_properties`,`generated_conversions`,`responsive_images`,`order_column`,`created_at`,`updated_at`) values 
(1,'App\\Models\\StoreSetting',3,'90e60225-8a83-4849-97d0-9822fc5f1c9a','homepage_banner','bg-meet-online','01KKNKAA92ERM7K4NYN4NVNXT2.jpg','image/jpeg','public','public',840131,'[]','{\"custom_headers\": {\"ContentType\": \"image/jpeg\"}}','[]','[]',1,'2026-03-14 14:17:35','2026-03-14 14:17:35'),
(2,'App\\Models\\StoreSetting',2,'5819b16a-5733-4353-bf2b-0903556e1f59','homepage_banner','black-white-mountain-background','01KKNKAJ2BJ9V4FY86EGP2V4Q5.jpg','image/jpeg','public','public',978148,'[]','{\"custom_headers\": {\"ContentType\": \"image/jpeg\"}}','[]','[]',1,'2026-03-14 14:17:43','2026-03-14 14:17:43'),
(3,'App\\Models\\StoreSetting',1,'1e3eb73d-e174-4582-ae74-5cb37f417b27','homepage_banner','wallpaperflare.com_wallpaper','01KKNKAVSBQS8DJX8AHH69DZ2J.jpg','image/jpeg','public','public',184390,'[]','{\"custom_headers\": {\"ContentType\": \"image/jpeg\"}}','[]','[]',1,'2026-03-14 14:17:53','2026-03-14 14:17:53');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(32,'2026_03_15_141019_create_couriers_table',2),
(33,'2026_03_15_141807_create_transaction_shipments_table',2);

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
(1,'ViewAny:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(2,'View:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(3,'Create:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(4,'Update:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(5,'Delete:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(6,'Restore:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(7,'ForceDelete:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(8,'ForceDeleteAny:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(9,'RestoreAny:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(10,'Replicate:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(11,'Reorder:Brand','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(12,'ViewAny:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(13,'View:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(14,'Create:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(15,'Update:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(16,'Delete:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(17,'Restore:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(18,'ForceDelete:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(19,'ForceDeleteAny:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(20,'RestoreAny:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(21,'Replicate:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(22,'Reorder:Bundle','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(23,'ViewAny:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(24,'View:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(25,'Create:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(26,'Update:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(27,'Delete:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(28,'Restore:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(29,'ForceDelete:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(30,'ForceDeleteAny:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(31,'RestoreAny:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(32,'Replicate:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(33,'Reorder:Category','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(34,'ViewAny:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(35,'View:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(36,'Create:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(37,'Update:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(38,'Delete:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(39,'Restore:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(40,'ForceDelete:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(41,'ForceDeleteAny:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(42,'RestoreAny:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(43,'Replicate:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(44,'Reorder:Customer','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(45,'ViewAny:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(46,'View:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(47,'Create:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(48,'Update:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(49,'Delete:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(50,'Restore:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(51,'ForceDelete:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(52,'ForceDeleteAny:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(53,'RestoreAny:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(54,'Replicate:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(55,'Reorder:Product','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(56,'ViewAny:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(57,'View:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(58,'Create:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(59,'Update:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(60,'Delete:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(61,'Restore:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(62,'ForceDelete:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(63,'ForceDeleteAny:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(64,'RestoreAny:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(65,'Replicate:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(66,'Reorder:Promo','web','2026-03-14 12:52:15','2026-03-14 12:52:15'),
(67,'ViewAny:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(68,'View:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(69,'Create:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(70,'Update:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(71,'Delete:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(72,'Restore:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(73,'ForceDelete:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(74,'ForceDeleteAny:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(75,'RestoreAny:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(76,'Replicate:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(77,'Reorder:PurchaseOrder','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(78,'ViewAny:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(79,'View:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(80,'Create:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(81,'Update:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(82,'Delete:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(83,'Restore:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(84,'ForceDelete:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(85,'ForceDeleteAny:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(86,'RestoreAny:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(87,'Replicate:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(88,'Reorder:Role','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(89,'ViewAny:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(90,'View:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(91,'Create:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(92,'Update:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(93,'Delete:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(94,'Restore:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(95,'ForceDelete:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(96,'ForceDeleteAny:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(97,'RestoreAny:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(98,'Replicate:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(99,'Reorder:StoreSetting','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(100,'ViewAny:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(101,'View:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(102,'Create:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(103,'Update:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(104,'Delete:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(105,'Restore:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(106,'ForceDelete:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(107,'ForceDeleteAny:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(108,'RestoreAny:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(109,'Replicate:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(110,'Reorder:Transaction','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(111,'ViewAny:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(112,'View:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(113,'Create:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(114,'Update:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(115,'Delete:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(116,'Restore:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(117,'ForceDelete:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(118,'ForceDeleteAny:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(119,'RestoreAny:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(120,'Replicate:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(121,'Reorder:User','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(122,'ViewAny:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(123,'View:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(124,'Create:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(125,'Update:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(126,'Delete:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(127,'Restore:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(128,'ForceDelete:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(129,'ForceDeleteAny:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(130,'RestoreAny:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(131,'Replicate:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(132,'Reorder:Activity','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(133,'View:Dashboard','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(134,'View:AccountWidget','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(135,'View:FilamentInfoWidget','web','2026-03-14 12:52:16','2026-03-14 12:52:16'),
(136,'ViewAny:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(137,'View:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(138,'Create:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(139,'Update:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(140,'Delete:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(141,'Restore:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(142,'ForceDelete:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(143,'ForceDeleteAny:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(144,'RestoreAny:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(145,'Replicate:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(146,'Reorder:InventoryStock','web','2026-03-15 09:34:24','2026-03-15 09:34:24'),
(147,'ViewAny:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(148,'View:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(149,'Create:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(150,'Update:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(151,'Delete:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(152,'Restore:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(153,'ForceDelete:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(154,'ForceDeleteAny:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(155,'RestoreAny:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(156,'Replicate:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35'),
(157,'Reorder:Courier','web','2026-03-15 14:24:35','2026-03-15 14:24:35');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_images` */

insert  into `product_images`(`id`,`product_id`,`image_product`,`is_primary`) values 
(3,1,'images-product/01KKNEFSH5CG9DTTCNQ6SE6RAZ.jpg',0),
(4,1,'images-product/01KKNEFSH8GRHR1KPXNKCVSW41.jpg',1),
(6,2,'images-product/01KKNEGQ69JVGXBGD1HAND8J7Z.jpg',1),
(10,3,'images-product/01KKNEJ2X32E1VCB0Z0DZZHJMQ.jpg',0),
(11,3,'images-product/01KKNEJ2X6A9FXB1S0F0X1T4GR.jpg',0),
(12,3,'images-product/01KKNEJ2X9DT0NPMBQNA6P7JPF.jpg',1),
(14,4,'images-product/01KKNEKANGE7G6XFWVAC70NSZN.jpg',1),
(16,5,'images-product/01KKNEMC9YEKDYYKKQPFQFR4E0.jpg',1),
(18,6,'images-product/01KKNGNFNAW21D0T6RE19BW3TS.jpg',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`store_setting_id`,`brand_id`,`category_id`,`name`,`type`,`selling_price`,`sku`,`size`,`weight`,`color`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,1,3,2,'Barbara Gibbs','springbed',384000.00,'ADSA-123','single',36.00,'Id qui nesciunt en',1,'2026-03-14 12:53:12','2026-03-14 12:55:51',1,1,NULL,NULL),
(2,2,4,3,'Illiana Hayes','divan',762700.00,'HI-12','queen',72.00,'Cupidatat sint dolo',1,'2026-03-14 12:53:42','2026-03-14 12:55:58',1,1,NULL,NULL),
(3,3,1,4,'Alexis Hayes','headboard',354000.00,'LKO-10','king',82.00,'Sunt facere velit ',1,'2026-03-14 12:54:27','2026-03-14 12:54:27',1,1,NULL,NULL),
(4,2,2,1,'Tatum Moon','bundle',972000.00,'SKI-20','custom',1.00,'Totam rerum ut vero ',1,'2026-03-14 12:55:08','2026-03-14 12:55:08',1,1,NULL,NULL),
(5,1,4,4,'Marcia Carr','springbed',69000.00,'AOT-20','queen',43.00,'Quod voluptate velit',1,'2026-03-14 12:55:42','2026-03-14 12:55:42',1,1,NULL,NULL),
(6,1,4,3,'Tes admin 111','divan',525000.00,'ASDS-001','single',43.00,'Aut ipsa tempor qui',1,'2026-03-14 13:31:15','2026-03-14 13:35:21',2,2,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_products` */

insert  into `promo_products`(`id`,`promo_id`,`product_id`,`created_at`,`updated_at`) values 
(1,1,1,NULL,NULL),
(2,2,1,NULL,NULL),
(3,3,6,NULL,NULL),
(4,4,1,NULL,NULL),
(5,5,1,NULL,NULL),
(6,6,5,NULL,NULL),
(7,7,2,NULL,NULL),
(8,8,5,NULL,NULL),
(9,9,6,NULL,NULL),
(10,10,5,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promo_usages` */

insert  into `promo_usages`(`id`,`promo_id`,`transaction_id`,`discount_amount`,`created_at`,`updated_at`) values 
(4,3,11,120000.00,'2026-03-15 16:47:37','2026-03-15 16:47:37');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `promos` */

insert  into `promos`(`id`,`name`,`type`,`discount_type`,`discount_value`,`min_purchase`,`usage_limit`,`usage_count`,`start_date`,`end_date`,`is_active`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(1,'Tes nominal','flash_sale','percentage',50.00,100000.00,20,15,'2026-03-04 00:00:00','2026-03-06 00:00:00',1,'2026-03-14 19:54:28','2026-03-15 15:46:34',2,2,NULL,NULL),
(2,'Tes 2','voucher','percentage',2.00,100000.00,15,5,'2026-03-14 00:00:00','2026-03-20 00:00:00',1,'2026-03-14 20:19:46','2026-03-15 08:17:13',2,1,NULL,NULL),
(3,'tes free shipping','free_shipping','nominal',120000.00,100000.00,10,4,'2026-03-14 00:00:00','2026-03-21 00:00:00',1,'2026-03-14 21:49:12','2026-03-15 16:47:37',2,1,NULL,NULL),
(4,'tes promo','voucher','nominal',20000.00,150000.00,5,5,'2026-03-14 00:00:00','2026-03-26 00:00:00',1,'2026-03-14 21:49:47','2026-03-15 08:22:18',2,1,NULL,NULL),
(5,'Karly Lawson','voucher','nominal',12222.00,22222.00,22,0,'2026-03-15 00:00:00','2026-03-24 00:00:00',1,'2026-03-15 08:29:30','2026-03-15 08:29:30',1,1,NULL,NULL),
(6,'Kristen Wolfe','flash_sale','nominal',123233.00,232344.00,12,0,'2026-03-15 00:00:00','2026-03-19 00:00:00',1,'2026-03-15 08:29:49','2026-03-15 08:29:49',1,1,NULL,NULL),
(7,'Deacon Boyd','free_shipping','percentage',23.00,434343.00,23,0,'2026-03-13 00:00:00','2026-03-15 00:00:00',1,'2026-03-15 08:30:13','2026-03-15 08:32:27',1,1,NULL,NULL),
(8,'Jael Oneil','free_shipping','percentage',23.00,22222.00,34,0,'2026-03-13 00:00:00','2026-03-24 00:00:00',1,'2026-03-15 08:37:57','2026-03-15 08:37:57',1,1,NULL,NULL),
(9,'Deborah Kinney','voucher','percentage',32.00,23322.00,22,0,'2026-03-05 00:00:00','2026-03-31 00:00:00',1,'2026-03-15 08:38:36','2026-03-15 08:38:36',1,1,NULL,NULL),
(10,'Super Admin','flash_sale','nominal',132.00,1231231.00,123,0,'2026-03-19 00:00:00','2026-03-25 00:00:00',1,'2026-03-15 08:39:13','2026-03-15 08:39:13',1,1,NULL,NULL);

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
(4,3,1,4,3,300000.00,'2026-03-14 14:09:26','2026-03-15 15:47:42'),
(5,3,5,5,0,60000.00,'2026-03-14 14:09:26','2026-03-15 16:26:01'),
(6,3,6,6,0,500000.00,'2026-03-14 14:09:26','2026-03-15 16:21:02');

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
(3,1,'Tes Purchase Admin','INVE6GFUSN21773472122','2026-03-14 00:00:00',4500000,'2026-03-14 14:09:26','2026-03-14 14:09:26',2,2,NULL,NULL);

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
(1,'Super Admin','web','2026-03-14 12:52:01','2026-03-14 12:52:01'),
(2,'Admin','web','2026-03-14 12:52:01','2026-03-14 12:52:01'),
(3,'Owner','web','2026-03-14 12:52:01','2026-03-14 12:52:01'),
(4,'Staff','web','2026-03-14 12:52:01','2026-03-14 12:52:01');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_adjustments` */

insert  into `stock_adjustments`(`id`,`store_setting_id`,`product_id`,`qty_before`,`qty_after`,`qty_difference`,`reason`,`created_at`,`updated_at`) values 
(1,1,1,0,11,11,'add 11','2026-03-14 12:53:12','2026-03-14 12:53:12'),
(2,2,2,0,22,22,'add 22','2026-03-14 12:53:42','2026-03-14 12:53:42'),
(3,3,3,0,33,33,'add 33','2026-03-14 12:54:27','2026-03-14 12:54:27'),
(4,2,4,0,2,2,'add 2','2026-03-14 12:55:08','2026-03-14 12:55:08'),
(5,1,5,0,10,10,'add 10','2026-03-14 12:55:42','2026-03-14 12:55:42'),
(6,1,6,0,111,111,'tes admin 111','2026-03-14 13:31:15','2026-03-14 13:31:15'),
(7,1,6,111,109,-2,'decrease 109\n','2026-03-14 13:32:22','2026-03-14 13:32:22'),
(8,2,4,2,22,20,'add 22','2026-03-15 14:08:39','2026-03-15 14:08:39'),
(9,1,6,0,44,44,'add 44','2026-03-15 16:24:38','2026-03-15 16:24:38');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stock_movements` */

insert  into `stock_movements`(`id`,`product_id`,`store_setting_id`,`type`,`reference_type`,`reference_id`,`qty`,`cost_price_snapshot`,`created_at`,`updated_at`) values 
(1,1,1,'adjustment','App\\Models\\StockAdjustment',1,11,NULL,'2026-03-14 12:53:12','2026-03-14 12:53:12'),
(2,2,2,'adjustment','App\\Models\\StockAdjustment',2,22,NULL,'2026-03-14 12:53:42','2026-03-14 12:53:42'),
(3,3,3,'adjustment','App\\Models\\StockAdjustment',3,33,NULL,'2026-03-14 12:54:27','2026-03-14 12:54:27'),
(4,4,2,'adjustment','App\\Models\\StockAdjustment',4,2,NULL,'2026-03-14 12:55:08','2026-03-14 12:55:08'),
(5,5,1,'adjustment','App\\Models\\StockAdjustment',5,10,NULL,'2026-03-14 12:55:42','2026-03-14 12:55:42'),
(6,6,1,'adjustment','App\\Models\\StockAdjustment',6,111,NULL,'2026-03-14 13:31:15','2026-03-14 13:31:15'),
(7,6,1,'adjustment','App\\Models\\StockAdjustment',7,-2,NULL,'2026-03-14 13:32:22','2026-03-14 13:32:22'),
(8,1,1,'in','App\\Models\\PurchaseOrder',3,4,300000.00,'2026-03-14 14:09:26','2026-03-14 14:09:26'),
(9,5,1,'in','App\\Models\\PurchaseOrder',3,5,60000.00,'2026-03-14 14:09:26','2026-03-14 14:09:26'),
(10,6,1,'in','App\\Models\\PurchaseOrder',3,6,500000.00,'2026-03-14 14:09:26','2026-03-14 14:09:26'),
(11,4,2,'adjustment','App\\Models\\StockAdjustment',8,20,NULL,'2026-03-15 14:08:39','2026-03-15 14:08:39'),
(15,6,1,'adjustment','App\\Models\\StockAdjustment',9,44,NULL,'2026-03-15 16:24:38','2026-03-15 16:24:38'),
(16,6,1,'out','App\\Models\\Transaction',10,44,NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(17,5,1,'out','App\\Models\\Transaction',10,5,NULL,'2026-03-15 16:26:01','2026-03-15 16:26:01'),
(18,5,1,'out','App\\Models\\Transaction',11,2,NULL,'2026-03-15 16:47:37','2026-03-15 16:47:37');

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
(1,'Toko 1','1234567890','toko1@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(2,'Toko 2','123153242','toko2@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL),
(3,'Toko 3','64646546','toko3@yahoo.com','123 Main St','2026-03-14 12:52:02','2026-03-14 12:52:02',NULL,NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_item_costs` */

/*Table structure for table `transaction_items` */

DROP TABLE IF EXISTS `transaction_items`;

CREATE TABLE `transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `qty` int NOT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_items` */

insert  into `transaction_items`(`id`,`transaction_id`,`product_id`,`qty`,`selling_price`,`discount`,`subtotal`,`created_at`,`updated_at`) values 
(13,11,5,2,69000.00,0.00,138000.00,'2026-03-15 16:47:37','2026-03-15 16:47:37');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_payments` */

insert  into `transaction_payments`(`id`,`transaction_id`,`method`,`amount`,`status`,`paid_at`,`created_at`,`updated_at`) values 
(11,11,'qris',138000.00,'paid','2026-03-15 16:47:37','2026-03-15 16:47:37','2026-03-15 16:47:37');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaction_shipments` */

insert  into `transaction_shipments`(`id`,`transaction_id`,`courier_id`,`tracking_number`,`status`,`created_at`,`updated_at`) values 
(1,11,2,NULL,'pending','2026-03-15 16:47:37','2026-03-15 17:02:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transactions` */

insert  into `transactions`(`id`,`store_setting_id`,`customer_id`,`transaction_code`,`subtotal`,`discount_total`,`shiping_cost`,`grand_total`,`status`,`promo_id`,`transaction_date`,`created_at`,`updated_at`,`created_by`,`updated_by`,`deleted_at`,`deleted_by`) values 
(11,1,3,'TRXYKJR74MD20260315',138000.00,120000.00,120000.00,138000.00,'shipped',3,'2026-03-15','2026-03-15 16:47:37','2026-03-15 17:02:48',2,2,NULL,NULL);

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
(1,'Super Admin','superadmin@springbed.id',NULL,'$2y$12$0jYzoC6vvZt5CtEIkmp6Xut3E.aPP2aJCpdXzRmJBmwsdXemhA592',1,NULL,NULL,'2026-03-14 12:52:01','2026-03-14 22:32:03',NULL,1,NULL,NULL,'{\"ui.color\": \"#ec4899\"}'),
(2,'Admin','admin@springbed.id',NULL,'$2y$12$uZh/G8zKfR9W6hdbjHHL4upeZjMRloFBlUM8zINZXZ4AiVPQA4cSC',1,1,NULL,'2026-03-14 12:52:01','2026-03-14 22:31:50',NULL,2,NULL,NULL,'{\"ui.color\": \"#ec4899\"}'),
(3,'Owner','owner@springbed.id',NULL,'$2y$12$mHkISCPCBcBaQm7JMbXU1.7s9PdtNsKEDaPP1WDfWzndJ.Aeo66.a',1,NULL,NULL,'2026-03-14 12:52:01','2026-03-14 12:52:01',NULL,NULL,NULL,NULL,NULL),
(4,'Staff','staff@springbed.id',NULL,'$2y$12$6d3HKr0vhO9d0q931O2.yu35jA5Eq.0XfaaZNdb6OIyb9Nstf0HES',1,2,NULL,'2026-03-14 12:52:02','2026-03-14 13:31:30',NULL,2,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
