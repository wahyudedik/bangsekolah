-- Adminer 4.8.1 MySQL 5.7.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `school_payment` int(11) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'PAID, UNPAID',
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `school_payment` (`school_payment`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`school_payment`) REFERENCES `school_payment` (`id`),
  CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `controller` varchar(255) DEFAULT NULL,
  `actions` text,
  `default_action` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '1',
  `visibility` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=744;

INSERT INTO `menu` (`id`, `name`, `description`, `controller`, `actions`, `default_action`, `icon`, `parent_id`, `level`, `visibility`, `created_at`, `updated_at`) VALUES
(1,	'Home',	'',	'site',	'[\"index\"]',	'index',	'nav-icon fas fa-home',	NULL,	1,	1,	'2018-12-18 16:12:35',	'2018-12-26 09:33:30'),
(2,	'Role',	'',	'role',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-user-tag',	4,	2,	1,	'2018-12-18 16:13:09',	'2018-12-26 09:36:52'),
(3,	'Menu',	'',	'menu',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-list-ul',	4,	2,	1,	'2018-12-20 06:33:57',	'2018-12-26 09:37:18'),
(4,	'User Management',	'',	'',	'\"\"',	'',	'nav-icon fas fa-users',	NULL,	1,	1,	'2018-12-20 07:07:19',	'2021-07-05 11:52:00'),
(5,	'Role Menu',	'',	'role-menu',	'[\"create\",\"index\",\"update\",\"delete\",\"up\",\"down\",\"view\"]',	'index',	'nav-icon fas fa-tasks',	4,	2,	1,	'2018-12-20 07:18:45',	'2018-12-26 09:38:03'),
(6,	'Setting',	'',	'',	'\"\"',	'',	'nav-icon fas fa-cog',	NULL,	1,	1,	'2020-06-19 21:45:43',	'2020-06-19 21:45:43'),
(7,	'Navbar',	'',	'navbar',	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"up\",\"down\"]',	'index',	'nav-icon fas fa-compass',	6,	2,	1,	'2020-06-19 21:46:06',	'2020-06-25 09:52:33'),
(8,	'General',	'',	'setting',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-sliders-h',	6,	2,	1,	'2020-06-19 21:46:26',	'2020-06-19 21:46:26'),
(9,	'School List',	NULL,	'school',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-school',	NULL,	1,	1,	'2021-10-16 09:59:30',	'2022-05-11 14:44:46'),
(15,	'User List',	NULL,	'user',	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"change-password\"]',	'index',	'nav-icon fas fa-users',	4,	2,	1,	'2022-02-24 09:50:59',	'2022-04-06 15:39:30');

DROP TABLE IF EXISTS `payment_type`;
CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'MONTHLY, ONE PAYMENT, VOLUNTARY',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `payment_type` (`id`, `name`) VALUES
(1,	'MONTHLY'),
(2,	'ONE PAYMENT'),
(3,	'VOLUNTARY');

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `role` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1,	'Super Admin',	NULL,	'2022-01-13 09:13:15',	'2022-01-13 09:13:15'),
(2,	'Staff Keuangan',	NULL,	'2022-01-13 09:13:15',	'2022-01-13 09:13:15');

DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned DEFAULT NULL,
  `menu_id` int(11) unsigned DEFAULT NULL,
  `parameter` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `position` int(11) unsigned DEFAULT NULL,
  `privileges` varchar(255) DEFAULT NULL COMMENT 'misal: crud',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=744;

INSERT INTO `role_menu` (`id`, `role_id`, `menu_id`, `parameter`, `label`, `position`, `privileges`, `created_at`, `updated_at`) VALUES
(4,	1,	4,	'',	'',	2,	'\"\"',	'2018-12-20 07:22:17',	'2020-02-28 09:59:50'),
(6,	1,	2,	'',	'',	4,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2018-12-20 07:45:09',	'2020-06-15 10:20:11'),
(7,	1,	5,	'',	'',	5,	'[\"create\",\"index\",\"update\",\"delete\",\"up\",\"down\",\"view\"]',	'0000-00-00 00:00:00',	'2020-02-28 10:01:19'),
(17,	1,	1,	'',	'',	1,	'[\"index\"]',	'2018-12-20 16:06:16',	'2018-12-26 09:34:05'),
(26,	1,	3,	'',	'',	3,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2018-12-20 21:21:30',	'2020-06-15 10:20:11'),
(27,	1,	6,	'',	'',	6,	'\"\"',	'2021-10-16 09:24:35',	'2021-10-16 09:24:35'),
(29,	1,	8,	'',	'',	7,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2021-10-16 09:24:45',	'2021-10-16 09:25:04'),
(30,	1,	9,	'',	'',	8,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2021-10-16 11:50:53',	'2021-10-16 11:50:53'),
(36,	1,	15,	'',	'',	15,	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"change-password\"]',	'2022-02-24 09:51:13',	'2022-04-06 15:39:44');

DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `school_class`;
CREATE TABLE `school_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'I, II, III, IV, V, VI, VII DST',
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `school_class_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `school_month`;
CREATE TABLE `school_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `school_month_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `school_payment`;
CREATE TABLE `school_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `payment_type_id` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  KEY `payment_type_id` (`payment_type_id`),
  CONSTRAINT `school_payment_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`),
  CONSTRAINT `school_payment_ibfk_3` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `value` text COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `setting` (`id`, `key`, `name`, `description`, `value`, `status`, `created_at`, `updated_at`) VALUES
(3,	'whatsapp-number',	'Official whatsapp number',	'',	'+6285736775369',	1,	'2019-03-23 09:55:49',	'2020-06-20 20:25:28'),
(4,	'admin-email',	'Admin email',	'',	'muhammadarieffakhrudin@gmail.com',	1,	'2019-03-23 09:56:32',	'2020-06-20 20:25:46'),
(8,	'host-name',	'Host name',	'',	'https://pt-pus.com',	1,	'2019-11-18 08:55:32',	'2020-06-20 20:24:42'),
(9,	'order-number',	'Order Number',	'',	'5',	1,	'2022-01-14 15:28:03',	'2022-04-09 11:42:16'),
(10,	'invoice-number',	'Invoice Number',	'',	'4',	1,	'2022-01-14 15:29:22',	'2022-04-08 11:01:29'),
(11,	'po-number',	'Purchase Order Number',	'',	'2',	1,	'2022-01-14 15:29:56',	'2022-04-06 15:46:37'),
(12,	'prod-number',	'Production Number',	'',	'2',	1,	'2022-02-04 14:48:06',	'2022-04-08 10:54:44'),
(13,	'so-number',	'Stock Opname Number',	'',	'0',	1,	'2022-02-14 09:48:48',	'2022-04-06 10:53:45');

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `nis` varchar(255) DEFAULT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `note` text,
  `payment_method` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `auth_key` text,
  `password_hash` text,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `role_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `email`, `name`, `phone`, `image`, `status`, `role_id`, `created_at`, `updated_at`) VALUES
(2,	'admin',	'',	'$2y$13$rWwb0I5J6YihMMnJU6QcVekv7pweN3FH0ZrkzHuVjHtT2vVUKIutO',	'admin@pt-pus.com',	'Muhammad Arief',	'085736775369',	NULL,	'ACTIVE',	1,	'2021-10-13 06:23:58',	'2022-04-08 09:49:54');

DROP TABLE IF EXISTS `user_school`;
CREATE TABLE `user_school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `user_school_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_school_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `year`;
CREATE TABLE `year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '2021/2022, 2022/2023, DST',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2022-05-11 08:08:32
