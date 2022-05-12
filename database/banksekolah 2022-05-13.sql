-- Adminer 4.8.0 MySQL 5.7.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `school_payment_id` int(11) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `description` text,
  `amount` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'PAID, UNPAID',
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `user_id` (`user_id`),
  KEY `school_payment_id` (`school_payment_id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `invoice_ibfk_4` FOREIGN KEY (`school_payment_id`) REFERENCES `school_payment` (`id`),
  CONSTRAINT `invoice_ibfk_5` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
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
(6,	'Pengaturan',	'',	'',	'\"\"',	'',	'nav-icon fas fa-cog',	NULL,	1,	1,	'2020-06-19 21:45:43',	'2022-05-12 08:56:23'),
(7,	'Navbar',	'',	'navbar',	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"up\",\"down\"]',	'index',	'nav-icon fas fa-compass',	6,	2,	1,	'2020-06-19 21:46:06',	'2020-06-25 09:52:33'),
(8,	'General',	'',	'setting',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-sliders-h',	6,	2,	1,	'2020-06-19 21:46:26',	'2020-06-19 21:46:26'),
(9,	'Daftar Sekolah',	NULL,	'school',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-school',	NULL,	1,	1,	'2021-10-16 09:59:30',	'2022-05-12 08:56:07'),
(15,	'Daftar User',	NULL,	'user',	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"change-password\"]',	'index',	'nav-icon fas fa-users',	4,	2,	1,	'2022-02-24 09:50:59',	'2022-05-12 08:56:44'),
(19,	'Daftar Pembayaran',	NULL,	'pembayaran',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-receipt',	NULL,	1,	1,	'2022-05-12 09:02:48',	'2022-05-12 09:06:12'),
(20,	'Daftar Siswa',	NULL,	'siswa',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-users',	NULL,	1,	1,	'2022-05-12 09:07:11',	'2022-05-12 09:07:11'),
(21,	'Tagihan',	NULL,	'tagihan',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-file-invoice',	NULL,	1,	1,	'2022-05-12 09:14:32',	'2022-05-12 09:14:32'),
(22,	'Transaksi',	NULL,	'transaksi',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-hand-holding-usd',	NULL,	1,	1,	'2022-05-12 09:19:18',	'2022-05-12 09:19:18'),
(23,	'Laporan',	NULL,	'laporan',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-chart-line',	NULL,	1,	1,	'2022-05-12 09:21:33',	'2022-05-12 09:24:00'),
(24,	'Daftar Bulan',	NULL,	'bulan',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-tags',	6,	2,	1,	'2022-05-12 09:25:21',	'2022-05-12 09:26:40'),
(25,	'Log Out',	NULL,	'site',	'[\"logout\"]',	'logout',	'nav-icon fas fa-sign-out-alt',	NULL,	1,	1,	'2022-05-12 09:28:04',	'2022-05-12 09:28:04');

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
(36,	1,	15,	'',	'',	15,	'[\"index\",\"create\",\"view\",\"update\",\"delete\",\"change-password\"]',	'2022-02-24 09:51:13',	'2022-04-06 15:39:44'),
(43,	2,	1,	'',	'',	1,	'[\"index\"]',	'2022-05-12 09:08:13',	'2022-05-12 09:08:13'),
(44,	2,	19,	'',	'',	2,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:08:56',	'2022-05-12 09:08:56'),
(45,	2,	20,	'',	'',	3,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:09:04',	'2022-05-12 09:09:04'),
(46,	2,	21,	'',	'',	4,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:28:29',	'2022-05-12 09:28:29'),
(47,	2,	22,	'',	'',	5,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:28:44',	'2022-05-12 09:28:44'),
(48,	2,	23,	'',	'',	6,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:28:54',	'2022-05-12 09:28:54'),
(49,	2,	6,	'',	'',	7,	'\"\"',	'2022-05-12 09:29:05',	'2022-05-12 09:29:05'),
(50,	2,	24,	'',	'',	8,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:29:24',	'2022-05-12 09:29:24'),
(51,	2,	25,	'',	'',	9,	'[\"logout\"]',	'2022-05-12 09:29:34',	'2022-05-12 09:29:34');

DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `school` (`id`, `number`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1,	NULL,	'SDN 1 Nambangrejo',	'ACTIVE',	'2022-05-11 15:36:39',	'2022-05-11 15:36:39'),
(2,	NULL,	'SDN 2 Nambangrejo',	'ACTIVE',	'2022-05-11 15:40:22',	'2022-05-11 15:40:22');

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
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `school_month_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `school_month` (`id`, `school_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1,	1,	'Juli',	'ACTIVE',	'2022-05-12 09:58:23',	'2022-05-12 10:37:45'),
(2,	1,	'Agustus',	'ACTIVE',	'2022-05-12 10:39:12',	'2022-05-12 10:39:12');

DROP TABLE IF EXISTS `school_payment`;
CREATE TABLE `school_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `payment_type_id` int(11) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
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

INSERT INTO `student` (`id`, `school_id`, `nis`, `nisn`, `name`, `class`, `year`, `status`, `created_at`, `updated_at`) VALUES
(1,	1,	'10001',	'10001',	'Cahyadi Nur Rahman',	'1',	'2021/2022',	'ACTIVE',	'2022-05-12 11:17:41',	'2022-05-12 11:17:41');

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `school_payment_id` int(11) DEFAULT NULL,
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
  KEY `school_payment_id` (`school_payment_id`),
  CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `transaction_ibfk_4` FOREIGN KEY (`school_payment_id`) REFERENCES `school_payment` (`id`)
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
(2,	'admin',	'',	'$2y$13$rWwb0I5J6YihMMnJU6QcVekv7pweN3FH0ZrkzHuVjHtT2vVUKIutO',	'admin@pt-pus.com',	'Muhammad Arief',	'085736775369',	NULL,	'ACTIVE',	1,	'2021-10-13 06:23:58',	'2022-05-12 08:12:26'),
(22,	'sdn1nbr',	NULL,	'$2y$13$yW6u1.7qLl9POvXlN/ljsex7M3437uON/tgLkU3xUTvGWyOjPrjYu',	'adminsdn1nambangrejo@gmail.com',	'Admin SDN 1 Nambangrejo',	NULL,	NULL,	'ACTIVE',	2,	'2022-05-12 08:20:21',	'2022-05-12 08:20:21');

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

INSERT INTO `user_school` (`id`, `user_id`, `school_id`, `created_at`, `updated_at`) VALUES
(5,	22,	1,	'2022-05-12 08:20:21',	'2022-05-12 08:20:21');

DROP TABLE IF EXISTS `year`;
CREATE TABLE `year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '2021/2022, 2022/2023, DST',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `year` (`id`, `name`) VALUES
(1,	'2020/2021'),
(2,	'2021/2022'),
(3,	'2022/2023'),
(4,	'2023/2024'),
(5,	'2024/2025');

-- 2022-05-12 23:42:41
