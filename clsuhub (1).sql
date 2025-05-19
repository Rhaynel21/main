-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 10:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clsuhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `college` varchar(255) NOT NULL,
  `college_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `college`, `college_desc`) VALUES
(1, 'CAG', 'COLLEGE OF AGRICULTURE'),
(2, 'CASS', 'COLLEGE OF ARTS AND SOCIAL SCIENCES'),
(3, 'CBAA', 'COLLEGE OF BUSINESS ADMINISTRATION AND ACCOUNTANCY'),
(4, 'CED', 'COLLEGE OF EDUCATION'),
(5, 'CEN', 'COLLEGE OF ENGINEERING'),
(6, 'CF', 'COLLEGE OF FISHERIES'),
(7, 'CHSI', 'COLLEGE OF HOME SCIENCE AND INDUSTRY'),
(8, 'COS', 'COLLEGE OF SCIENCE'),
(9, 'CVSM', 'COLLEGE OF VETERINARY SCIENCE AND MEDICINE'),
(10, 'DOT UNI', 'DISTANCE, OPEN AND TRANSNATIONAL UNIVERSITY');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` varchar(10000) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `parent_id`, `body`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '[Deleted Content]', NULL, '2025-03-04 22:20:19', '2025-03-05 21:36:38'),
(2, 1, 1, 1, '[Deleted Content]', NULL, '2025-03-04 22:20:26', '2025-03-05 21:36:46'),
(3, 1, 1, NULL, '<p>Wow, I love your work!</p>', NULL, '2025-03-04 22:20:46', '2025-03-09 22:41:32'),
(4, 1, 1, 3, '<p>Hello there, it\'s nice to meet you!</p>', NULL, '2025-03-04 22:21:03', '2025-03-09 22:41:43'),
(5, 1, 1, 4, '<p>OMG HI!</p>', NULL, '2025-03-04 22:39:12', '2025-03-10 23:21:54'),
(6, 1, 1, 5, '<p>HELLO, HI!</p>', NULL, '2025-03-04 22:39:19', '2025-03-10 23:22:11'),
(7, 1, 1, 5, 'KING', NULL, '2025-03-04 22:39:26', '2025-03-04 22:39:26'),
(8, 1, 1, 4, '[Deleted Content]', NULL, '2025-03-04 22:39:39', '2025-03-12 23:05:32'),
(9, 1, 1, 8, '<p>NO U TOO</p>', NULL, '2025-03-04 22:41:47', '2025-03-09 18:31:04'),
(10, 1, 2, 2, 'PLEASE LOOK AT THIS', 'comment_images/GwHwHWUQgwXt1nTacvhOKP0mb9kCYmtY3lWgqmjN.jpg', '2025-03-05 17:41:52', '2025-03-05 17:41:52'),
(11, 1, 1, NULL, '<p>Melchizedek</p>', NULL, '2025-03-09 17:33:47', '2025-03-09 17:33:47'),
(12, 1, 1, 9, '<p><strong>WHY ARE YOU HERE</strong></p>', NULL, '2025-03-09 17:34:11', '2025-03-09 17:34:11'),
(13, 1, 1, 11, '<p style=\"text-align: left;\"><span style=\"text-decoration: underline;\"><strong><em>asdfhasdkljgvhasdvlhsadv</em></strong></span></p>', NULL, '2025-03-09 17:34:39', '2025-03-09 17:34:39'),
(14, 1, 1, 11, '[Deleted Content]', NULL, '2025-03-09 19:30:46', '2025-03-16 18:01:55'),
(15, 1, 1, 11, '<p>B</p>', NULL, '2025-03-09 19:30:53', '2025-03-09 19:30:53'),
(16, 1, 1, 11, '[Deleted Content]', NULL, '2025-03-09 19:31:05', '2025-03-16 18:32:57'),
(17, 1, 1, 11, '[Deleted Content]', NULL, '2025-03-09 19:31:10', '2025-03-09 19:32:08'),
(18, 1, 1, 11, '<p>E</p>', NULL, '2025-03-09 19:31:15', '2025-03-09 19:31:15'),
(19, 1, 1, 11, '<p>F</p>', NULL, '2025-03-09 19:31:22', '2025-03-09 19:31:22'),
(20, 1, 1, 11, '<p>G</p>', NULL, '2025-03-09 19:31:35', '2025-03-09 19:31:35'),
(21, 1, 1, 11, '<p>H</p>', NULL, '2025-03-09 19:31:41', '2025-03-09 19:31:41'),
(22, 1, 1, 17, '<p>vzcvzcvzcvzvcvczx</p>', NULL, '2025-03-09 19:57:26', '2025-03-09 19:57:26'),
(23, 1, 2, 6, '<p>fasdfadfa</p>', NULL, '2025-03-10 16:55:16', '2025-03-10 16:55:16'),
(24, 1, 2, NULL, '<p>Why are you like this</p>\r\n<p>&nbsp;</p>', NULL, '2025-03-10 17:30:13', '2025-03-10 17:30:13'),
(25, 1, 2, 24, '<p>HRM</p>', NULL, '2025-03-10 17:30:37', '2025-03-10 17:30:37'),
(26, 1, 2, 25, '<p>YO</p>', NULL, '2025-03-10 17:30:49', '2025-03-10 17:30:49'),
(27, 1, 2, 26, '<p>HEY</p>', NULL, '2025-03-10 17:30:59', '2025-03-10 17:30:59'),
(28, 1, 2, 27, '<p>afdas</p>', 'comment_images/kQWHgGk8OJ0BFj22ZBmQXVE97F2Cm9QnPiIlUX1X.jpg', '2025-03-10 18:27:07', '2025-03-10 18:27:07'),
(29, 1, 2, NULL, '', 'comment_images/QEQJR50bNDbrnHtgZAbX7kcA8lM0OSxSDzcNqhpJ.jpg', '2025-03-10 18:59:02', '2025-03-10 18:59:02'),
(30, 1, 1, NULL, '<p style=\"text-align: center;\">Hello there</p>', NULL, '2025-03-11 00:25:09', '2025-03-11 00:25:09'),
(31, 1, 1, NULL, '<p>Nasuada&rsquo;s approach to the magicians made me think of apartheid. She doesn&rsquo;t understand or trust them, so instead enslaves them and those who won&rsquo;t go willingly, she drugs.</p>\r\n<p>She saw a problem with a particular part of her population and did not give a flying hoot if her solution to this was humane or that she basically did a Galbatorix 2.0 by forcing subjects to swear oaths or be punished.</p>', NULL, '2025-03-13 18:05:10', '2025-03-13 18:05:10'),
(32, 1, 1, 31, '<p>The other problem is, it doesn\'t necessarily stop smart magicians from gaming the system, but punishes innocent magicians. I wouldn\'t have an issue with obligatory signing up to a guild, and having to do basic training to ensure safe magic use, and having to answer questions in the ancient language if suspicious stuff was happening in your area. But forced oaths in the ancient language, drugging or imprisoning people who don\'t immediately agree to sign up, and randomly scrying people day and night?</p>\r\n<p>The scrying would only be permissible in my eyes if there was significant evidence of a serious crime, and shouldn\'t be deployed indiscriminately. Imprisonment or oaths should only be employed when someone performs serious magical crimes, and should only be enacted after a thorough investigation. Yes, magic\'s dangerous, but so are lots of things.</p>\r\n<p>It seems a far better idea to get magicians on board, using their gifts responsibly to help their community and the people around them.</p>', NULL, '2025-03-13 18:05:44', '2025-03-13 18:05:44'),
(33, 1, 1, 16, '<p>sadfas</p>', NULL, '2025-03-16 18:32:44', '2025-03-16 18:32:44'),
(34, 1, 1, NULL, '[Deleted Content]', NULL, '2025-03-16 19:03:01', '2025-03-16 19:03:28'),
(35, 1, 1, 34, '[Deleted Content]', NULL, '2025-03-16 19:03:13', '2025-03-16 19:03:34'),
(36, 1, 1, 35, '[Deleted Content]', NULL, '2025-03-16 19:03:21', '2025-03-16 19:03:40'),
(37, 1, 1, NULL, '<p>New Comment!</p>', NULL, '2025-03-16 19:19:50', '2025-03-16 19:19:50'),
(38, 1, 1, 37, '<p>Comment</p>', NULL, '2025-03-16 19:20:04', '2025-03-16 19:20:04'),
(39, 1, 1, 38, '<p>dfaa</p>', NULL, '2025-03-16 19:20:21', '2025-03-16 19:20:21'),
(40, 1, 1, 39, '<p>fadfasf</p>', NULL, '2025-03-16 19:22:12', '2025-03-16 19:22:12'),
(41, 1, 1, 40, '<p>asdfadf</p>', NULL, '2025-03-16 19:29:14', '2025-03-16 19:29:14'),
(42, 1, 1, 37, '[Deleted Content]', NULL, '2025-03-16 19:40:15', '2025-03-16 19:40:15'),
(43, 1, 1, 31, '<p>what the heck</p>', NULL, '2025-03-16 19:41:49', '2025-03-16 19:41:49'),
(44, 1, 1, 42, '<p>Nah, no u</p>', NULL, '2025-03-16 19:43:47', '2025-03-16 19:43:47'),
(45, 1, 1, 39, '<p>wahat</p>', NULL, '2025-03-16 19:53:45', '2025-03-16 19:53:45'),
(46, 1, 1, 39, '<p>dafasdf</p>', NULL, '2025-03-16 19:54:45', '2025-03-16 19:54:45'),
(47, 1, 1, 37, '<p>New Reply!</p>', NULL, '2025-03-16 19:55:26', '2025-03-16 19:55:26'),
(48, 1, 1, NULL, '<p>Hello good morning</p>', NULL, '2025-03-18 17:00:48', '2025-03-18 17:00:48'),
(49, 1, 2, 31, '<p>Upvoted because you\'re based</p>', NULL, '2025-03-18 21:26:11', '2025-03-18 21:26:11'),
(51, 1, 1, 26, '<p>Hey hey people, Seth here</p>', NULL, '2025-04-20 19:27:23', '2025-04-20 19:27:23'),
(52, 1, 1, NULL, '<p><img src=\"/storage/quill_images/F3HQRoOAoTHD8sSjjfbbDX3y1V1wJgnHjCl1OgNy.png\"></p>', NULL, '2025-04-20 21:22:19', '2025-04-20 21:22:19'),
(53, 1, 1, NULL, '[Deleted Content]', NULL, '2025-04-20 21:41:28', '2025-04-20 21:41:45'),
(54, 7, 1, NULL, '<p>Lol</p>', NULL, '2025-04-21 00:21:40', '2025-04-21 00:21:40'),
(55, 8, 1, NULL, '<p>Booo you suck</p>', NULL, '2025-04-21 17:12:41', '2025-04-21 17:12:41'),
(56, 6, 1, NULL, '<p>BOOO</p>', NULL, '2025-04-22 00:26:57', '2025-04-22 00:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `comment_votes`
--

CREATE TABLE `comment_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vote` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment_votes`
--

INSERT INTO `comment_votes` (`id`, `comment_id`, `user_id`, `vote`, `created_at`, `updated_at`) VALUES
(1, 1, 2, -1, '2025-03-05 17:39:33', '2025-03-05 17:39:43'),
(2, 10, 2, -1, '2025-03-05 17:42:01', '2025-03-05 17:42:06'),
(6, 2, 1, 1, '2025-03-09 17:04:49', '2025-03-13 17:40:04'),
(8, 1, 1, -1, '2025-03-09 17:33:33', '2025-03-13 16:55:46'),
(9, 27, 2, 1, '2025-03-10 18:26:07', '2025-03-10 18:26:07'),
(10, 27, 1, -1, '2025-03-10 22:43:05', '2025-03-10 22:43:05'),
(13, 25, 1, 1, '2025-03-12 19:50:07', '2025-03-13 00:27:28'),
(14, 30, 1, -1, '2025-03-12 19:50:12', '2025-03-12 19:50:13'),
(15, 10, 1, -1, '2025-03-12 22:44:58', '2025-04-21 19:14:41'),
(17, 8, 1, -1, '2025-03-12 23:08:38', '2025-03-12 23:08:38'),
(18, 5, 1, 1, '2025-03-12 23:08:41', '2025-03-12 23:08:41'),
(19, 9, 1, 1, '2025-03-12 23:08:45', '2025-03-12 23:08:45'),
(20, 4, 1, 1, '2025-03-13 00:27:36', '2025-03-16 18:01:09'),
(24, 24, 1, 1, '2025-03-13 22:56:23', '2025-03-18 17:50:48'),
(26, 31, 1, 1, '2025-03-16 19:14:46', '2025-03-16 19:14:48'),
(27, 3, 1, 1, '2025-04-20 21:21:04', '2025-04-20 21:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `college_id` varchar(255) DEFAULT NULL,
  `course` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `college_id`, `course`, `description`) VALUES
(1, '2', 'AALL', 'ASSOCIATE OF ARTS IN LANGUAGE AND LITERATURE'),
(2, '2', 'ABDEVCOM', 'BACHELOR OF ARTS IN DEVELOPMENT COMMUNICATION'),
(3, '5', 'ACT', 'ASSOCIATE IN COMPUTER TECHNOLOGY'),
(4, '5', 'AGMECH', 'CERTIFICATE IN AGRICULTURAL MECHANICS'),
(5, '8', 'Agroecology Training (Pilot Testing of MS Agroecology)', 'Agroecology Training (Pilot Testing of MS Agroecology)'),
(6, '3', 'BABA', 'BA in Business Admin'),
(7, '2', 'BADC', 'BA in Development Communication'),
(8, '2', 'BAFIL', 'BACHELOR OF ARTS IN FILIPINO'),
(9, '2', 'BALITT', 'BACHELOR OF ARTS IN LITERATURE'),
(10, '2', 'BALL', 'BACHELOR OF ARTS IN LANGUAGE AND LITERATURE  '),
(11, '2', 'BAPSYCH', 'BACHELOR OF ARTS IN PSYCHOLOGY'),
(12, '2', 'BASS', 'BACHELOR OF ARTS IN SOCIAL SCIENCES'),
(13, '4', 'BCAE', 'Bachelor of Culture and Arts Education'),
(14, '4', 'BCAED', 'BACHELOR OF CULTURE AND ARTS EDUCATION'),
(15, '4', 'BECE', 'Bachelor of Early Childhood Education'),
(16, '4', 'BECED', 'BACHELOR OF EARLY CHILDHOOD EDUCATION'),
(17, '4', 'BEED', 'BACHELOR OF ELEMENTARY EDUCATION'),
(18, '4', 'BPE', 'Bachelor of Physical Education'),
(19, '4', 'BPED', 'BACHELOR OF PHYSICAL EDUCATION'),
(20, '1', 'BSA', 'BACHELOR OF SCIENCE IN AGRICULTURE'),
(21, '1', 'BSAB', 'BACHELOR OF SCIENCE IN AGRIBUSINESS'),
(22, '5', 'BSABE', 'BACHELOR OF SCIENCE IN AGRICULTURAL AND BIOSYSTEMS ENGINEERING'),
(23, '3', 'BSAC', 'BACHELOR OF SCIENCE IN ACCOUNTANCY'),
(24, '3', 'BSACCT', 'BS in Accountancy'),
(25, '5', 'BSAEN', 'BACHELOR OF SCIENCE IN AGRICULTURAL ENGINEERING'),
(26, '4', 'BSAEXED', 'BACHELOR OF SCIENCE IN AGRICULTURAL EXTENSION EDUCATION'),
(27, '4', 'BSAGED', 'BACHELOR OF SCIENCE IN AGRICULTURAL EDUCATION'),
(28, '1', 'BSAGRI', 'BS in Agriculture'),
(29, '9', 'BSAH', 'BACHELOR OF SCIENCE IN ANIMAL HUSBANDRY'),
(30, '1', 'BSAM', 'BACHELOR OF SCIENCE IN AGRIBUSINESS MANAGEMENT'),
(31, '3', 'BSAT', 'BACHELOR OF SCIENCE IN ACCOUNTING TECHNOLOGY'),
(32, '3', 'BSBA', 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION'),
(33, '8', 'BSBIO', 'BACHELOR OF SCIENCE IN BIOLOGY'),
(34, '5', 'BSCE', 'BACHELOR OF SCIENCE IN CIVIL ENGINEERING'),
(35, '8', 'BSCHEM', 'BACHELOR OF SCIENCE IN CHEMISTRY'),
(36, '2', 'BSDC', 'BACHELOR OF SCIENCE IN DEVELOPMENT COMMUNICATION'),
(37, '3', 'BSE', 'BS in Entrepreneurship'),
(38, '4', 'BSED', 'BACHELOR OF SECONDARY EDUCATION'),
(39, '3', 'BSENTREP', 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP'),
(40, '8', 'BSES', 'BACHELOR OF SCIENCE IN ENVIRONMENTAL SCIENCE'),
(41, '6', 'BSF', 'BACHELOR OF SCIENCE IN FISHERIES'),
(42, '7', 'BSFT', 'BACHELOR OF SCIENCE IN FOOD TECHNOLOGY'),
(43, '7', 'BSHE', 'BACHELOR OF SCIENCE IN HOME ECONOMICS'),
(44, '7', 'BSHM', 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT'),
(45, '7', 'BSHRM', 'BACHELOR OF SCIENCE IN HOTEL AND RESTAURANT MANAGEMENT'),
(46, '6', 'BSIF', 'BACHELOR OF SCIENCE IN INLAND FISHERIES'),
(47, '5', 'BSIT', 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY'),
(48, '3', 'BSMA', 'BACHELOR OF SCIENCE IN MANAGEMENT ACCOUNTING'),
(49, '8', 'BSMATH', 'BACHELOR OF SCIENCE IN MATHEMATICS'),
(50, '8', 'BSMET', 'BACHELOR OF SCIENCE IN METEOROLOGY'),
(51, '2', 'BSPSYCH', 'BACHELOR OF SCIENCE IN PSYCHOLOGY'),
(52, '8', 'BSSTAT', 'BACHELOR OF SCIENCE IN STATISTICS'),
(53, '7', 'BSTFT', 'BACHELOR OF SCIENCE IN TEXTILE AND FASHION TECHNOLOGY'),
(54, '7', 'BSTGT', 'BACHELOR OF SCIENCE IN TEXTILE AND GARMENT TECHNOLOGY'),
(55, '7', 'BSTM', 'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT'),
(56, '4', 'BTLED', 'BACHELOR OF TECHNOLOGY AND LIVELIHOOD EDUCATION'),
(57, '1', 'CAS', 'CERTIFICATE IN AGRICULTURAL SCIENCE'),
(58, '', 'CERTARM', 'Certificate in Agricultural Research Management'),
(59, '', 'CERTBEIA', 'Certificate in Basic Environmental Impact Assessment'),
(60, '', 'CERTBLG', 'Certificate in Basic Local Governance'),
(61, '', 'CERTENT', 'Certificate in Entrepreneurship'),
(62, '', 'CERTLDP', 'Certificate in Local Development Planning'),
(63, '', 'CERTPFP', 'Certificate in Project Feasibility Preparation and Implementation'),
(64, '', 'CERTPHYED', 'Certificate in Physical Education'),
(65, '', 'CERTTEACH', 'Certificate in Teaching'),
(66, '', 'CERTTM', 'Certificate in Training Management'),
(67, '2', 'CFY', 'COMMON FIRST YEAR'),
(68, '8', 'CHEMTECH', 'CERTIFICATE IN CHEMICAL TECHNOLOGY'),
(69, '4', 'CPE', 'CERTIFICATE IN PHYSICAL EDUCATION'),
(70, '8', 'CROSS ENROLLEE', 'CROSS ENROLLEE'),
(71, '9', 'CVSM', 'DOCTOR OF VETERINARY SCIENCE AND MEDICINE'),
(72, '', 'DE', 'Doctor of Education'),
(73, '', 'DIPLGM', 'Diploma in Local Government Management'),
(74, '', 'DIPLUP', 'Diploma in Land Use Planning'),
(75, '10', 'DOTUni-BSBA', 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION'),
(76, '10', 'DOTUni-CBEIA', 'CERTIFICATE IN BASIC ENVIRONMENTAL IMPACT ASSESSMENT'),
(77, '10', 'DOTUni-CIT', 'CERTIFICATE IN TEACHING'),
(78, '10', 'DOTUni-MAB', 'MASTER OF SCIENCE IN AGRIBUSINESS'),
(79, '10', 'DOTUni-MAB', 'MASTER IN AGRIBUSINESS'),
(80, '10', 'DOTUni-MBA', 'MASTER OF SCIENCE IN?BUSINESS ADMINISTRATION'),
(81, '10', 'DOTUni-MBA', 'MASTER IN BUSINESS ADMINISTRATION'),
(82, '10', 'DOTUni-MEM', 'MASTER OF SCIENCE?IN?ENVIRONMENTAL MANAGEMENT(DOTUni)'),
(83, '10', 'DOTUni-MEM', 'MASTER IN ENVIRONMENTAL MANAGEMENT'),
(84, '10', 'DOTUni-MSDC', 'MASTER OF SCIENCE?IN DEVELOPMENT COMMUNICATION'),
(85, '10', 'DOTUni-MSED', 'MASTER OF SCIENCE IN EDUCATION (DOT.UNI)'),
(86, '10', 'DOTUni-MSRD', 'MASTER OF SCIENCE IN RURAL DEVELOPMENT'),
(87, '10', 'DOTUni-MSRES', 'MASTER OF SCIENCE IN RENEWABLE ENERGY SYSTEMS'),
(88, '10', 'DOTUni-PHD DEV ED', 'DOCTOR OF PHILOSOPHY IN DEVELOPMENT EDUCATION'),
(89, '10', 'DOTUni-PHD RD', 'DOCTOR OF PHILOSOPHY IN RURAL DEVELOPMENT'),
(90, '10', 'DOTUni-PHDSFSRP', 'DOCTOR OF PHILOSOPHY IN SFSRP'),
(91, '9', 'DVM', 'DOCTOR OF VETERINARY MEDICINE'),
(92, '9', 'DVSM', 'Doctor of Veterinary Science and Medicine'),
(93, '1', 'ETEEAP', 'EXPANDED TERTIARY EDUCATION EQUIVALENCY AND ACCREDITATION PROGRAM (CAG-ETEEAP)'),
(94, '4', 'ETEEAP', 'EXPANDED TERTIARY EDUCATION EQUIVALENCY AND ACCREDITATION PROGRAM (ETEEAP)'),
(95, '5', 'ETEEAP', 'EXPANDED TERTIARY EDUCATION EQUIVALENCY AND ACCREDITATION PROGRAM (ETEEAP)'),
(96, '6', 'ETEEAP', 'EXPANDED TERTIARY EDUCATION EQUIVALENCY AND ACCREDITATION PROGRAM (CF-ETEEAP)'),
(97, '', 'HS', 'High School'),
(98, '1', 'MAB', 'MASTER IN AGRIBUSINESS'),
(99, '2', 'MALL', 'MASTER OF ARTS IN LANGUAGE AND LITERATURE'),
(100, '', 'MAM', 'Master in Agribusiness Management'),
(101, '3', 'MBA', 'MASTER IN BUSINESS ADMINISTRATION'),
(102, '', 'MBIO', 'Master in Biology'),
(103, '', 'MBUSADM', 'Master in Business Administration'),
(104, '8', 'MCHEM', 'MASTER IN CHEMISTRY'),
(105, '', 'MEDUCMNG', 'Master of Educational Management'),
(106, '', 'MENVMNG', 'Master in Environmental Management'),
(107, '', 'MLGM', 'Master in Local Government and Management'),
(108, '1', 'MSAB', 'MASTER OF SCIENCE IN AGRIBUSINESS'),
(109, '5', 'MSAE', 'MASTER OF SCIENCE IN AGRICULTURAL ENGINEERING'),
(110, '1', 'MSAGECON', 'MASTER OF SCIENCE IN AGRICULTURAL ECONOMICS'),
(111, '', 'MSAGRI', 'MS in Aquaculture'),
(112, '', 'MSAGRIBUS', 'MS in Agribusiness'),
(113, '', 'MSAGRIECO', 'MS in Agricultural Economics'),
(114, '', 'MSAGRIENGR', 'MS in Agricultural Engineering'),
(115, '1', 'MSAM', 'MASTER OF SCIENCE IN AGRIBUSINESS MANAGEMENT'),
(116, '', 'MSANIMSCI', 'MS in Animal Science'),
(117, '1', 'MSANSCI', 'MASTER OF SCIENCE IN ANIMAL SCIENCE'),
(118, '6', 'MSAQUA', 'MASTER OF SCIENCE IN AQUACULTURE'),
(119, '8', 'MSBIO', 'MASTER OF SCIENCE IN BIOLOGY'),
(120, '4', 'MSBIOED', 'MASTER OF SCIENCE IN BIOLOGY EDUCATION'),
(121, '', 'MSBIOEDUC', 'MS in Biology Education'),
(122, '8', 'MSCHEM', 'MASTER OF SCIENCE IN CHEMISTRY'),
(123, '4', 'MSCHEMED', 'MASTER OF SCIENCE IN CHEMISTRY EDUCATION'),
(124, '', 'MSCHEMEDUC', 'MS in Chemistry Education'),
(125, '1', 'MSCPROT', 'MASTER OF SCIENCE IN CROP PROTECTION'),
(126, '', 'MSCROPPROT', 'MS in Crop Protection'),
(127, '', 'MSCROPSCI', 'MS in Crop Sicence'),
(128, '1', 'MSCRSCI', 'MASTER OF SCIENCE IN CROP SCIENCE'),
(129, '2', 'MSDC', 'MASTER OF SCIENCE IN DEVELOPMENT COMMUNICATION'),
(130, '', 'MSDEVCOM', 'MS in Development Communication'),
(131, '4', 'MSED', 'MASTER OF SCIENCE IN EDUCATION'),
(132, '', 'MSEDUC', 'MS in Education'),
(133, '8', 'MSEM', 'MASTER OF SCIENCE IN ENVIRONMENTAL MANAGEMENT'),
(134, '', 'MSENVMNG', 'MS in Environmental Management'),
(135, '4', 'MSGC', 'MASTER OF SCIENCE IN GUIDANCE AND COUNSELING'),
(136, '', 'MSGRAINSCI', 'MS in Grain Science'),
(137, '2', 'MSRD', 'MASTER OF SCIENCE IN RURAL DEVELOPMENT'),
(138, '', 'MSRES', 'MS in Renewable Energy Systems (DOTUni)'),
(139, '', 'MSRURALDEV', 'MS in Rural Development'),
(140, '1', 'MSSOILS', 'MASTER OF SCIENCE IN SOIL SCIENCE'),
(141, '', 'MSSOILSCI', 'MS in Soil Science'),
(142, '', 'MVETS', 'Master of Veterinary Studies'),
(143, '9', 'MVST', 'MASTER OF VETERINARY STUDIES'),
(144, '', 'PHDAENGR', 'PhD in Agricultural Engineering'),
(145, '', 'PHDAENTO', 'PhD in Agricultural Entomology'),
(146, '5', 'PHDAGEN', 'DOCTOR OF PHILOSOPHY IN AGRICULTURAL ENGINEERING'),
(147, '1', 'PHDAGENTOM', 'DOCTOR OF PHILOSOPHY IN AGRICULTURAL ENTOMOLOGY'),
(148, '1', 'PHDANSCI', 'DOCTOR OF PHILOSOPHY IN ANIMAL SCIENCE'),
(149, '6', 'PHDAQUA', 'DOCTOR OF PHILOSOPHY IN AQUACULTURE'),
(150, '8', 'PHDBIO', 'DOCTOR OF PHILOSOPHY IN BIOLOGY'),
(151, '', 'PHDCROPSCI', 'PhD in Crop Science'),
(152, '1', 'PHDCRSCI', 'DOCTOR OF PHILOSOPHY IN CROP SCIENCE'),
(153, '2', 'PHDDEVCOM', 'DOCTOR OF PHILOSOPHY IN DEVELOPMENT COMMUNICATION'),
(154, '4', 'PHDDEVED', 'DOCTOR OF PHILOSOPHY IN DEVELOPMENT EDUCATION'),
(155, '8', 'PHDEM', 'DOCTOR OF PHILOSOPHY IN ENVIRONMENTAL MANAGEMENT'),
(156, '', 'PHDENVMNG', 'PhD in Environmental Management'),
(157, '1', 'PHDPB', 'DOCTOR OF PHILOSOPHY IN PLANT BREEDING'),
(158, '2', 'PHDRD', 'DOCTOR OF PHILOSOPHY IN RURAL DEVELOPMENT'),
(159, '', 'PHDRDEV', 'PhD in Rural Development'),
(160, '', 'PHDSFSR', 'PhD in Sustainable Food Systems by Research Program (DOTUni)'),
(161, '1', 'PHDSOILS', 'DOCTOR OF PHILOSOPHY IN SOIL SCIENCE');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumni_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `current_address` varchar(255) NOT NULL,
  `graduated_course` varchar(255) NOT NULL,
  `graduation_year` int(11) NOT NULL,
  `graduate_study_status` varchar(255) NOT NULL,
  `job_experience_status` varchar(255) DEFAULT NULL,
  `employment_date` date NOT NULL,
  `first_workplace` varchar(255) NOT NULL,
  `first_employer_name` varchar(255) NOT NULL,
  `office_address` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `employer_contact` varchar(255) NOT NULL,
  `time_to_first_job` varchar(255) NOT NULL,
  `job_related_to_degree` varchar(255) NOT NULL,
  `optional_group_a` varchar(255) DEFAULT NULL,
  `optional_group_b` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_responses`
--

INSERT INTO `form_responses` (`id`, `alumni_id`, `first_name`, `middle_name`, `last_name`, `current_address`, `graduated_course`, `graduation_year`, `graduate_study_status`, `job_experience_status`, `employment_date`, `first_workplace`, `first_employer_name`, `office_address`, `position`, `employer_contact`, `time_to_first_job`, `job_related_to_degree`, `optional_group_a`, `optional_group_b`, `created_at`, `updated_at`) VALUES
(1, 3, 'John', 'Doe', 'Smith', '123 Main St', 'CS', 2020, 'Yes', 'Yes', '2024-01-01', 'Company X', 'Employer Y', '456 Office St', 'Developer', '123-456-7890', '1 month', 'Yes', NULL, NULL, '2025-01-07 06:50:00', '2025-01-07 06:50:00'),
(2, 3, 'Melchizedek', 'Alde', 'Gonzales', 'Nueva Ecija', '1', 2025, 'No', 'Yes', '2025-02-08', 'local', 'MotivIT', 'Baloc, Sto. Domingo, Nueva Ecija', 'Head Engineer', '09616166622', '1', 'No', NULL, NULL, '2025-01-07 07:09:22', '2025-01-07 07:09:22'),
(6, 9, 'Melchizedek', 'A.', 'Gonzales', 'isfugiuad', 'BSIT', 2013, 'Yes', 'No', '2025-01-23', 'local', 'Test Company', 'Test Address', 'fasdfa', 'galdiufh', 'Within a year', 'Yes', NULL, NULL, '2025-01-19 20:25:05', '2025-01-19 20:25:05'),
(7, 10, 'Melchizedek', 'A.', 'Gonzales', 'Nueva Ecija', 'BSIT', 2025, 'No', 'No', '2025-01-31', 'local', 'Test Company', 'Test Address', 'HR department', '09179404890', 'More than two years', 'Yes', NULL, NULL, '2025-01-20 18:31:07', '2025-01-20 18:31:07'),
(8, 5, 'Melchizedek', 'A.', 'Gonzales', 'hfasdf', 'BSF', 2025, 'No', 'No', '2025-03-11', 'Local', 'CLSU', 'gadsfadf', 'CLSU', 'adfasf', 'Within 2 years', 'No', NULL, NULL, '2025-03-20 18:50:26', '2025-03-20 18:50:26'),
(9, 1, 'Zed', 'S', 'Gonzales', 'adf', 'BSE', 2025, 'No', 'No', '2025-03-12', 'Foreign Country', 'CLSU', 'gadsfadf', 'CLSU', 'adfasf', 'Within 2 years', 'No', 'Both', 'Both', '2025-03-21 00:36:37', '2025-04-21 16:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `hidden_posts`
--

CREATE TABLE `hidden_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2025_03_19_050932_add_media_columns_to_posts_table', 2),
(11, '2025_04_22_030105_create_hidden_posts_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(80) DEFAULT NULL,
  `body` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `slug`, `body`, `created_at`, `updated_at`, `images`, `video`) VALUES
(1, 1, 'Welcome to the Forum!', 'asdfJ', 'In the Chapter ‘ The Dagshelgr Invocation’ from Eldest, Eragon and co are travelling through Du Weldenvarden with the elves Narí and Lifaen. They reach the Elven city of Sílthrim and Arya decides to send the two elves with them to get horses. Narí asks what to say to ‘Captain Damítha’ (a character I don’t think is ever mentioned again) as he and Lifaen have left there posts and Arya says to alert this Captain that Ceris requires reinforcements.\n\nShe then says ‘Tell her that that which she once hoped for - and feared- has occurred; the wyrm has bitten its own tail. She will understand.’\n\nWhat does Arya mean by this? Does the wyrm describe Saphira hatching and a Rider returning? Why send this message so cryptically?', '2025-03-04 22:16:36', '2025-03-04 22:16:36', NULL, NULL),
(2, 1, '[Deleted Content]', NULL, '[Deleted Content]', '2025-03-18 22:00:46', '2025-04-21 19:15:06', NULL, NULL),
(3, 1, 'Life Could Be Dream', NULL, '<p><img src=\"../storage/tinymce/1742366595_mceclip0.png\"><img src=\"../storage/tinymce/1742366596_mceclip1.png\"><img src=\"../storage/tinymce/1742366596_mceclip2.png\"><img src=\"../storage/tinymce/1742366595_mceclip3.png\"></p>', '2025-03-18 22:43:22', '2025-03-18 22:43:22', NULL, NULL),
(4, 1, 'My, my, Miss american pie', NULL, '<p>adf</p>', '2025-03-18 22:45:48', '2025-03-18 22:45:48', NULL, NULL),
(6, 1, 'Life is but a dream', NULL, '<p><img src=\"../storage/tinymce/1742369013_blobid0.png\" alt=\"0cd.png\" width=\"128\" height=\"126\"></p>', '2025-03-18 23:23:48', '2025-03-18 23:23:48', NULL, NULL),
(7, 1, 'Hello mga kaibigan welcome to my vlogs', NULL, '<p>HAHAHA JOKE LANG BITCH</p>', '2025-04-20 23:54:09', '2025-04-20 23:54:09', NULL, NULL),
(8, 1, 'Hi vlogs welcome to my guys', 'cnioadA', '<p>PSYCH BITCH</p>', '2025-04-21 00:32:49', '2025-04-21 00:32:49', '[\"post_images\\/1745224369_0cd.png\",\"post_images\\/1745224369_134965_ktPCrvB8.png\",\"post_images\\/1745224369_139707_jef34uuO.png\"]', NULL),
(9, 1, 'Henlo', NULL, '<p>Hi there</p>', '2025-04-21 18:36:13', '2025-04-21 19:28:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_votes`
--

CREATE TABLE `post_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vote` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_votes`
--

INSERT INTO `post_votes` (`id`, `post_id`, `user_id`, `vote`, `created_at`, `updated_at`) VALUES
(7, 1, 2, 1, '2025-03-16 21:48:59', '2025-03-18 21:25:48'),
(8, 5, 1, 1, '2025-03-18 23:00:38', '2025-03-18 23:00:38'),
(9, 6, 1, 1, '2025-03-18 23:33:00', '2025-04-22 00:19:17'),
(10, 4, 1, 1, '2025-04-21 16:38:43', '2025-04-22 00:09:37'),
(11, 8, 1, 1, '2025-04-21 23:06:22', '2025-04-22 00:12:41'),
(13, 1, 1, -1, '2025-04-21 23:25:33', '2025-04-22 00:12:45'),
(14, 7, 1, 1, '2025-04-22 00:09:27', '2025-04-22 00:12:33'),
(15, 3, 1, -1, '2025-04-22 00:19:20', '2025-04-22 00:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ahnEPTzYEN5OE0CKNmBHLptskgGKwMEWYrFYJnYb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiT21vbHplSFp5clByTjlQbk56NlpOQTVKNjJWU3kyQ1FmWnI3WWEwSCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZm9ydW0/c29ydF9ieT1tb3N0X2Rpc2xpa2VkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1741937824),
('x9NED3Wld8EhRuElDRlXcJwT8cvLQVcCfmbYmEQI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNG9OUTM1Sk1pWEpvdjhkbVljNlI0S29NdHM5c29FbWV5VDN5SXpTayI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZm9ydW0/c29ydF9ieT1vbGRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1741924154);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'alumni',
  `educational_achievement` varchar(20) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `email_verified_at`, `password`, `role`, `educational_achievement`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Melchizedek Gonzales', 'test@example.com', 'profile_photos/WvfORulwwGbASeMhOo06l2Fq2zX12Jne2NUEW2np.png', '2025-03-04 22:16:36', '$2y$12$qLdTwWJM11KhLIV3f2NwTup6c9yzjCrHuqYbz78LyOslMEOuXYqem', 'alumni', '', '4J1LDUcIXldOYGn0w0ZOj0qzbYEJ33STW5O2OBDpnhlbklZ3JhMpu5XoeV9I', '2025-03-04 22:16:36', '2025-04-21 17:11:30'),
(2, 'Test User 2', 'test2@example.com', NULL, '2025-03-04 22:37:13', '$2y$12$nqF3KurpgRLG77xgvN0h9emPA3SIev8/d1fneQ24yDdkkqqMEitwS', 'alumni', '', 'T7MVTjx1pgryYFSiZYQ98BYySXVeTmGLdLhw1QKvMDybBDqEtPJYrbEMN7nV', '2025-03-04 22:37:14', '2025-03-04 22:37:14'),
(3, 'melchi', 'kdon@gmail.com', NULL, '2025-03-20 01:11:06', '$2y$10$Ns2Kh2VOmVrcIwdERT1aHewFn376T72eJbwcZ6OO8Ilsl9E79h4GC', 'alumni', '', 'ZX75oR2UckYYCR7SbUZLMRUpET86U1rHlVOyxAFTnFBKGKqPxPqOLNNUPS1S', '2025-03-20 01:11:06', '2025-03-19 17:15:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `comment_votes`
--
ALTER TABLE `comment_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_votes_comment_id_foreign` (`comment_id`),
  ADD KEY `comment_votes_user_id_foreign` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hidden_posts_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `hidden_posts_post_id_foreign` (`post_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `post_votes`
--
ALTER TABLE `post_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_votes_post_id_foreign` (`post_id`),
  ADD KEY `post_votes_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `comment_votes`
--
ALTER TABLE `comment_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `post_votes`
--
ALTER TABLE `post_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_votes`
--
ALTER TABLE `comment_votes`
  ADD CONSTRAINT `comment_votes_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hidden_posts`
--
ALTER TABLE `hidden_posts`
  ADD CONSTRAINT `hidden_posts_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hidden_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
