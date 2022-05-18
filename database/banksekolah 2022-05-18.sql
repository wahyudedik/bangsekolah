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
  `date` date DEFAULT NULL,
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

INSERT INTO `invoice` (`id`, `school_id`, `student_id`, `school_payment_id`, `number`, `year`, `month`, `description`, `amount`, `paid_amount`, `date`, `due_date`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(454,	1,	1,	4,	'00421/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	70000,	'2021-06-01',	'2021-06-30',	'PAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:45:14'),
(455,	1,	1,	4,	'00422/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	70000,	'2021-07-01',	'2021-07-31',	'PAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:45:14'),
(456,	1,	1,	4,	'00423/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(457,	1,	1,	4,	'00424/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(458,	1,	1,	4,	'00425/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(459,	1,	1,	4,	'00426/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(460,	1,	1,	4,	'00427/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(461,	1,	1,	4,	'00428/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(462,	1,	1,	4,	'00429/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(463,	1,	1,	4,	'00430/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(464,	1,	1,	4,	'00431/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:44:47',	'2022-05-17 23:44:47'),
(465,	1,	1,	4,	'00432/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:44:48'),
(466,	1,	2,	4,	'00433/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	70000,	'2021-06-01',	'2021-06-30',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:45:02'),
(467,	1,	2,	4,	'00434/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	70000,	'2021-07-01',	'2021-07-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:45:02'),
(468,	1,	2,	4,	'00435/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	70000,	'2021-08-01',	'2021-08-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:45:02'),
(469,	1,	2,	4,	'00436/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	70000,	'2021-09-01',	'2021-09-30',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:45:02'),
(470,	1,	2,	4,	'00437/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	70000,	'2021-10-01',	'2021-10-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(471,	1,	2,	4,	'00438/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	70000,	'2021-11-01',	'2021-11-30',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(472,	1,	2,	4,	'00439/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	70000,	'2022-12-01',	'2022-12-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(473,	1,	2,	4,	'00440/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	70000,	'2022-01-01',	'2022-01-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(474,	1,	2,	4,	'00441/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	70000,	'2022-02-01',	'2022-02-28',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(475,	1,	2,	4,	'00442/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	70000,	'2022-03-01',	'2022-03-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(476,	1,	2,	4,	'00443/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	70000,	'2022-04-01',	'2022-04-30',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(477,	1,	2,	4,	'00444/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	70000,	'2022-05-01',	'2022-05-31',	'PAID',	22,	'2022-05-17 23:44:48',	'2022-05-17 23:57:56'),
(478,	1,	3,	4,	'00445/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	0,	'2021-06-01',	'2021-06-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(479,	1,	3,	4,	'00446/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	0,	'2021-07-01',	'2021-07-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(480,	1,	3,	4,	'00447/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(481,	1,	3,	4,	'00448/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(482,	1,	3,	4,	'00449/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(483,	1,	3,	4,	'00450/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(484,	1,	3,	4,	'00451/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(485,	1,	3,	4,	'00452/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(486,	1,	3,	4,	'00453/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(487,	1,	3,	4,	'00454/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(488,	1,	3,	4,	'00455/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(489,	1,	3,	4,	'00456/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(490,	1,	4,	4,	'00457/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	0,	'2021-06-01',	'2021-06-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(491,	1,	4,	4,	'00458/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	0,	'2021-07-01',	'2021-07-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(492,	1,	4,	4,	'00459/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(493,	1,	4,	4,	'00460/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(494,	1,	4,	4,	'00461/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(495,	1,	4,	4,	'00462/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(496,	1,	4,	4,	'00463/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(497,	1,	4,	4,	'00464/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(498,	1,	4,	4,	'00465/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(499,	1,	4,	4,	'00466/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(500,	1,	4,	4,	'00467/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(501,	1,	4,	4,	'00468/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(502,	1,	5,	4,	'00469/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	70000,	'2021-06-01',	'2021-06-30',	'PAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:58:27'),
(503,	1,	5,	4,	'00470/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	70000,	'2021-07-01',	'2021-07-31',	'PAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:58:27'),
(504,	1,	5,	4,	'00471/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	70000,	'2021-08-01',	'2021-08-31',	'PAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:58:28'),
(505,	1,	5,	4,	'00472/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	70000,	'2021-09-01',	'2021-09-30',	'PAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:58:28'),
(506,	1,	5,	4,	'00473/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(507,	1,	5,	4,	'00474/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(508,	1,	5,	4,	'00475/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(509,	1,	5,	4,	'00476/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(510,	1,	5,	4,	'00477/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(511,	1,	5,	4,	'00478/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(512,	1,	5,	4,	'00479/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(513,	1,	5,	4,	'00480/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:19',	'2022-05-17 23:56:19'),
(514,	1,	6,	4,	'00481/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	0,	'2021-06-01',	'2021-06-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(515,	1,	6,	4,	'00482/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	0,	'2021-07-01',	'2021-07-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(516,	1,	6,	4,	'00483/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(517,	1,	6,	4,	'00484/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(518,	1,	6,	4,	'00485/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(519,	1,	6,	4,	'00486/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(520,	1,	6,	4,	'00487/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(521,	1,	6,	4,	'00488/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(522,	1,	6,	4,	'00489/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(523,	1,	6,	4,	'00490/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(524,	1,	6,	4,	'00491/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(525,	1,	6,	4,	'00492/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(526,	1,	7,	4,	'00493/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	70000,	'2021-06-01',	'2021-06-30',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(527,	1,	7,	4,	'00494/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	70000,	'2021-07-01',	'2021-07-31',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(528,	1,	7,	4,	'00495/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	70000,	'2021-08-01',	'2021-08-31',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(529,	1,	7,	4,	'00496/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	70000,	'2021-09-01',	'2021-09-30',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(530,	1,	7,	4,	'00497/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	70000,	'2021-10-01',	'2021-10-31',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(531,	1,	7,	4,	'00498/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	70000,	'2021-11-01',	'2021-11-30',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(532,	1,	7,	4,	'00499/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	70000,	'2022-12-01',	'2022-12-31',	'PAID',	22,	'2022-05-17 23:56:20',	'2022-05-18 08:31:45'),
(533,	1,	7,	4,	'00500/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(534,	1,	7,	4,	'00501/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(535,	1,	7,	4,	'00502/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(536,	1,	7,	4,	'00503/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(537,	1,	7,	4,	'00504/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(538,	1,	8,	4,	'00505/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	0,	'2021-06-01',	'2021-06-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(539,	1,	8,	4,	'00506/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	0,	'2021-07-01',	'2021-07-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(540,	1,	8,	4,	'00507/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(541,	1,	8,	4,	'00508/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(542,	1,	8,	4,	'00509/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(543,	1,	8,	4,	'00510/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(544,	1,	8,	4,	'00511/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(545,	1,	8,	4,	'00512/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(546,	1,	8,	4,	'00513/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(547,	1,	8,	4,	'00514/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(548,	1,	8,	4,	'00515/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(549,	1,	8,	4,	'00516/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(550,	1,	9,	4,	'00517/INV/001/06/2021',	'2021/2022',	'Juni',	'SPP',	70000,	0,	'2021-06-01',	'2021-06-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(551,	1,	9,	4,	'00518/INV/001/07/2021',	'2021/2022',	'Juli',	'SPP',	70000,	0,	'2021-07-01',	'2021-07-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(552,	1,	9,	4,	'00519/INV/001/08/2021',	'2021/2022',	'Agustus',	'SPP',	70000,	0,	'2021-08-01',	'2021-08-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(553,	1,	9,	4,	'00520/INV/001/09/2021',	'2021/2022',	'September',	'SPP',	70000,	0,	'2021-09-01',	'2021-09-30',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(554,	1,	9,	4,	'00521/INV/001/10/2021',	'2021/2022',	'Oktober',	'SPP',	70000,	0,	'2021-10-01',	'2021-10-31',	'UNPAID',	22,	'2022-05-17 23:56:20',	'2022-05-17 23:56:20'),
(555,	1,	9,	4,	'00522/INV/001/11/2021',	'2021/2022',	'November',	'SPP',	70000,	0,	'2021-11-01',	'2021-11-30',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(556,	1,	9,	4,	'00523/INV/001/12/2022',	'2021/2022',	'Desember',	'SPP',	70000,	0,	'2022-12-01',	'2022-12-31',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(557,	1,	9,	4,	'00524/INV/001/01/2022',	'2021/2022',	'Januari',	'SPP',	70000,	0,	'2022-01-01',	'2022-01-31',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(558,	1,	9,	4,	'00525/INV/001/02/2022',	'2021/2022',	'Februari',	'SPP',	70000,	0,	'2022-02-01',	'2022-02-28',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(559,	1,	9,	4,	'00526/INV/001/03/2022',	'2021/2022',	'Maret',	'SPP',	70000,	0,	'2022-03-01',	'2022-03-31',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(560,	1,	9,	4,	'00527/INV/001/04/2022',	'2021/2022',	'April',	'SPP',	70000,	0,	'2022-04-01',	'2022-04-30',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(561,	1,	9,	4,	'00528/INV/001/05/2022',	'2021/2022',	'Mei',	'SPP',	70000,	0,	'2022-05-01',	'2022-05-31',	'UNPAID',	22,	'2022-05-17 23:56:21',	'2022-05-17 23:56:21'),
(562,	1,	1,	6,	'00529/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(563,	1,	2,	6,	'00530/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(564,	1,	3,	6,	'00531/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(565,	1,	4,	6,	'00532/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(566,	1,	5,	6,	'00533/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(567,	1,	6,	6,	'00534/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(568,	1,	7,	6,	'00535/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(569,	1,	8,	6,	'00536/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47'),
(570,	1,	9,	6,	'00537/INV/001/05/2022',	'2021/2022',	NULL,	'Zakat Idul Fitri',	35000,	0,	'2022-05-18',	'2022-05-31',	'UNPAID',	22,	'2022-05-18 07:07:47',	'2022-05-18 07:07:47');

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
(19,	'Master Pembayaran',	NULL,	'school-payment',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-receipt',	NULL,	1,	1,	'2022-05-12 09:02:48',	'2022-05-12 09:06:12'),
(20,	'Daftar Siswa',	NULL,	'student',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-users',	NULL,	1,	1,	'2022-05-12 09:07:11',	'2022-05-12 09:07:11'),
(21,	'Tagihan',	NULL,	'invoice',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-file-invoice',	NULL,	1,	1,	'2022-05-12 09:14:32',	'2022-05-12 09:14:32'),
(22,	'Transaksi',	NULL,	'transaction',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-hand-holding-usd',	NULL,	1,	1,	'2022-05-12 09:19:18',	'2022-05-12 09:19:18'),
(23,	'Laporan',	NULL,	'report',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-chart-line',	NULL,	1,	1,	'2022-05-12 09:21:33',	'2022-05-12 09:24:00'),
(24,	'Daftar Bulan',	NULL,	'school-month',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-tags',	6,	2,	1,	'2022-05-12 09:25:21',	'2022-05-12 09:26:40'),
(25,	'Log Out',	NULL,	'site',	'[\"logout\"]',	'logout',	'nav-icon fas fa-sign-out-alt',	NULL,	1,	1,	'2022-05-12 09:28:04',	'2022-05-12 09:28:04'),
(26,	'Daftar Kelas',	NULL,	'school-class',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-tags',	6,	2,	1,	'2022-05-12 09:25:21',	'2022-05-12 09:26:40'),
(27,	'Rekapitulasi Pembayaran',	NULL,	'payment-recap',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-tags',	23,	2,	1,	'2022-05-17 15:28:50',	'2022-05-17 15:28:50'),
(28,	'Tunggakan Bulanan',	NULL,	'overdue',	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'index',	'nav-icon fas fa-tags',	23,	2,	1,	'2022-05-17 15:29:21',	'2022-05-17 15:29:21');

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
(51,	2,	25,	'',	'',	9,	'[\"logout\"]',	'2022-05-12 09:29:34',	'2022-05-12 09:29:34'),
(52,	2,	26,	'',	'',	9,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-12 09:29:24',	'2022-05-12 09:29:24'),
(53,	2,	27,	'',	'',	10,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-17 15:29:45',	'2022-05-17 15:29:45'),
(54,	2,	28,	'',	'',	11,	'[\"index\",\"create\",\"view\",\"update\",\"delete\"]',	'2022-05-17 15:29:53',	'2022-05-17 15:29:53');

DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `invoice_number` int(11) DEFAULT NULL,
  `transaction_number` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `school` (`id`, `number`, `name`, `address`, `city`, `phone`, `invoice_number`, `transaction_number`, `status`, `created_at`, `updated_at`) VALUES
(1,	'001',	'SDN 1 Nambangrejo',	'Jln. Semeru 23',	'Ponorogo',	'0352777333',	537,	24,	'ACTIVE',	'2022-05-11 15:36:39',	'2022-05-18 08:31:45'),
(2,	'002',	'SDN 2 Nambangrejo',	'Jln. Semeru 23',	'Ponorogo',	'0352777333',	0,	0,	'ACTIVE',	'2022-05-11 15:40:22',	'2022-05-11 15:40:22');

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

INSERT INTO `school_class` (`id`, `school_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1,	1,	'I',	'ACTIVE',	'2022-05-13 07:45:01',	'2022-05-13 07:45:01'),
(2,	1,	'II',	'ACTIVE',	'2022-05-13 07:45:04',	'2022-05-13 07:45:04'),
(3,	1,	'III',	'ACTIVE',	'2022-05-13 07:45:25',	'2022-05-13 07:45:25'),
(4,	1,	'IV',	'ACTIVE',	'2022-05-13 07:45:29',	'2022-05-13 07:45:29'),
(5,	1,	'V',	'ACTIVE',	'2022-05-13 07:45:44',	'2022-05-13 07:45:44'),
(6,	1,	'VI',	'ACTIVE',	'2022-05-13 07:45:47',	'2022-05-13 07:45:47');

DROP TABLE IF EXISTS `school_month`;
CREATE TABLE `school_month` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`),
  CONSTRAINT `school_month_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `school_month` (`id`, `school_id`, `name`, `month`, `status`, `created_at`, `updated_at`) VALUES
(3,	1,	'Juni',	'06',	'ACTIVE',	'2022-05-13 07:49:49',	'2022-05-13 07:49:49'),
(4,	1,	'Juli',	'07',	'ACTIVE',	'2022-05-13 07:49:53',	'2022-05-13 07:49:53'),
(5,	1,	'Agustus',	'08',	'ACTIVE',	'2022-05-13 07:49:57',	'2022-05-13 07:49:57'),
(6,	1,	'September',	'09',	'ACTIVE',	'2022-05-13 07:50:05',	'2022-05-13 10:46:19'),
(7,	1,	'Oktober',	'10',	'ACTIVE',	'2022-05-13 07:50:10',	'2022-05-13 10:46:24'),
(8,	1,	'November',	'11',	'ACTIVE',	'2022-05-13 07:50:14',	'2022-05-13 10:46:31'),
(9,	1,	'Desember',	'12',	'ACTIVE',	'2022-05-13 07:50:17',	'2022-05-13 07:50:17'),
(10,	1,	'Januari',	'01',	'ACTIVE',	'2022-05-13 07:50:21',	'2022-05-13 07:50:21'),
(11,	1,	'Februari',	'02',	'ACTIVE',	'2022-05-13 07:50:25',	'2022-05-13 07:50:25'),
(12,	1,	'Maret',	'03',	'ACTIVE',	'2022-05-13 07:50:28',	'2022-05-13 07:50:28'),
(13,	1,	'April',	'04',	'ACTIVE',	'2022-05-13 07:50:31',	'2022-05-13 07:50:31'),
(14,	1,	'Mei',	'05',	'ACTIVE',	'2022-05-13 07:50:33',	'2022-05-13 07:50:33');

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

INSERT INTO `school_payment` (`id`, `school_id`, `payment_type_id`, `number`, `name`, `year`, `status`, `created_at`, `updated_at`) VALUES
(4,	1,	1,	'1001',	'SPP',	'2021/2022',	'ACTIVE',	'2022-05-13 11:04:54',	'2022-05-13 11:04:54'),
(5,	1,	2,	'1002',	'Uang Gedung',	'2021/2022',	'ACTIVE',	'2022-05-13 11:05:19',	'2022-05-13 11:05:19'),
(6,	1,	3,	'2001',	'Zakat Idul Fitri',	'2021/2022',	'ACTIVE',	'2022-05-13 11:05:41',	'2022-05-13 11:05:41'),
(7,	1,	2,	'1003',	'Buku LKS Kelas VI',	'2021/2022',	'ACTIVE',	'2022-05-16 09:24:52',	'2022-05-16 09:24:52');

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
(1,	1,	'7411030041',	'7411030041',	'Cahyadi Nur Rahman',	'I',	'2021/2022',	'ACTIVE',	'2022-05-12 11:17:41',	'2022-05-17 23:55:16'),
(2,	1,	'7411030042',	'7411030042',	'Arief Fakhrudin',	'I',	'2021/2022',	'ACTIVE',	'2022-05-13 07:14:25',	'2022-05-17 23:55:47'),
(3,	1,	'7411030043',	'7411030043',	'Sapta Bagus',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(4,	1,	'7411030044',	'7411030044',	'Mahandika Tri Sunarno',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(5,	1,	'7411030045',	'7411030045',	'Alam Ilahi',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(6,	1,	'7411030046',	'7411030046',	'Muhsonul Ula',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(7,	1,	'7411030047',	'7411030047',	'Toni Hermawan',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(8,	1,	'7411030048',	'7411030048',	'Risky Mulya',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59'),
(9,	1,	'7411030049',	'7411030049',	'Luqman Hakim',	'I',	'2021/2022',	'ACTIVE',	'2022-05-17 23:54:59',	'2022-05-17 23:54:59');

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `school_payment_id` int(11) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
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

INSERT INTO `transaction` (`id`, `invoice_id`, `student_id`, `school_payment_id`, `number`, `amount`, `date`, `note`, `payment_method`, `user_id`, `created_at`, `updated_at`) VALUES
(60,	466,	2,	4,	'00020/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:02',	'2022-05-17 23:45:02'),
(61,	467,	2,	4,	'00020/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:02',	'2022-05-17 23:45:02'),
(62,	468,	2,	4,	'00020/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:02',	'2022-05-17 23:45:02'),
(63,	469,	2,	4,	'00020/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:02',	'2022-05-17 23:45:02'),
(64,	454,	1,	4,	'00021/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:14',	'2022-05-17 23:45:14'),
(65,	455,	1,	4,	'00021/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:45:14',	'2022-05-17 23:45:14'),
(66,	470,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(67,	471,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(68,	472,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(69,	473,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(70,	474,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(71,	475,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(72,	476,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(73,	477,	2,	4,	'00022/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:57:56',	'2022-05-17 23:57:56'),
(74,	502,	5,	4,	'00023/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:58:27',	'2022-05-17 23:58:27'),
(75,	503,	5,	4,	'00023/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:58:27',	'2022-05-17 23:58:27'),
(76,	504,	5,	4,	'00023/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:58:27',	'2022-05-17 23:58:27'),
(77,	505,	5,	4,	'00023/TRX/001/05/2022',	70000,	'2022-05-17',	'',	'CASH',	22,	'2022-05-17 23:58:28',	'2022-05-17 23:58:28'),
(78,	526,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(79,	527,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(80,	528,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(81,	529,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(82,	530,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(83,	531,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45'),
(84,	532,	7,	4,	'00024/TRX/001/05/2022',	70000,	'2022-05-18',	'',	'CASH',	22,	'2022-05-18 08:31:45',	'2022-05-18 08:31:45');

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
  `status` varchar(255) DEFAULT NULL COMMENT 'ACTIVE, NONACTIVE',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `year` (`id`, `name`, `status`) VALUES
(1,	'2020/2021',	'NONACTIVE'),
(2,	'2021/2022',	'ACTIVE'),
(3,	'2022/2023',	'ACTIVE'),
(4,	'2023/2024',	'NONACTIVE'),
(5,	'2024/2025',	'NONACTIVE');

-- 2022-05-18 02:37:23
