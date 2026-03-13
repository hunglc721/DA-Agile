-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 12, 2026 at 01:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duan1`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `AssignmentID` int NOT NULL,
  `DepartureID` int NOT NULL,
  `StaffID` int DEFAULT NULL,
  `ServiceDetails` varchar(255) NOT NULL,
  `ServiceCost` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`AssignmentID`, `DepartureID`, `StaffID`, `ServiceDetails`, `ServiceCost`) VALUES
(1, 1, 1, 'Phân công Hướng dẫn viên A', '0.00'),
(2, 1, 2, 'Phân công Lái xe B', '0.00'),
(3, 1, NULL, 'Thuê khách sạn 3 sao 3 đêm', '15000000.00'),
(4, 2, 3, 'Phân công Phục vụ C', '0.00'),
(5, 2, NULL, 'Thuê sân bãi Team Building', '8000000.00'),
(1, 1, 1, 'Phân công Hướng dẫn viên A', '0.00'),
(2, 1, 2, 'Phân công Lái xe B', '0.00'),
(3, 1, NULL, 'Thuê khách sạn 3 sao 3 đêm', '15000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `departure_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `booking_date` date DEFAULT NULL,
  `number_of_people` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','paid','completed','cancelled') DEFAULT 'pending',
  `special_requests` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `checkin_status` enum('pending','checked_in','absent') DEFAULT 'pending',
  `checkin_time` datetime DEFAULT NULL,
  `booking_type` enum('retail','group','admin_create') DEFAULT 'retail',
  `customer_list` json DEFAULT NULL COMMENT 'Danh sách khách đi cùng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tour_id`, `departure_id`, `user_id`, `booking_date`, `number_of_people`, `total_price`, `status`, `special_requests`, `created_at`, `checkin_status`, `checkin_time`, `booking_type`, `customer_list`) VALUES
(1, 1, NULL, 3, '2024-12-01', 2, '6598000.00', 'confirmed', NULL, '2025-11-26 01:37:58', 'pending', NULL, 'retail', NULL),
(2, 2, NULL, 3, '2024-12-15', 1, '1899000.00', 'confirmed', NULL, '2025-11-26 01:37:58', 'pending', NULL, 'retail', NULL),
(3, 8, NULL, 1, '2025-12-01', 12, '15756.00', 'confirmed', NULL, '2025-12-01 00:26:05', 'pending', NULL, 'group', NULL),
(4, 9, NULL, 1, '2025-12-01', 20, '26260.00', 'pending', NULL, '2025-12-01 00:26:55', 'pending', NULL, 'group', NULL),
(5, 4, NULL, 3, '2025-12-01', 12, '15756.00', 'confirmed', NULL, '2025-12-01 00:27:39', 'pending', NULL, 'group', NULL),
(6, 10, NULL, 1, '2025-12-01', 12, '144.00', 'pending', NULL, '2025-12-01 01:26:29', 'pending', NULL, 'group', NULL),
(7, 10, NULL, 4, '2025-12-05', 1, '12.00', 'pending', 'xczxczxc', '2025-12-05 00:56:38', 'pending', NULL, 'retail', NULL),
(8, 10, NULL, 4, '2025-12-05', 1, '12.00', 'confirmed', 'xczxczxc', '2025-12-05 00:58:37', 'pending', NULL, 'retail', NULL),
(9, 8, NULL, 4, '2025-12-05', 1, '1313.00', 'confirmed', 'ssss', '2025-12-05 00:58:54', 'pending', NULL, 'retail', NULL),
(10, 6, NULL, 4, '2025-12-05', 1, '1313.00', 'confirmed', '123', '2025-12-05 00:59:40', 'pending', NULL, 'retail', NULL),
(11, 8, NULL, 5, '2025-12-07', 5, '6565.00', 'confirmed', 'xzcz', '2025-12-07 17:15:41', 'pending', NULL, 'retail', NULL),
(12, 10, NULL, 6, '2025-12-07', 6, '72.00', 'confirmed', '', '2025-12-07 18:13:22', 'pending', NULL, 'retail', NULL),
(13, 7, NULL, 6, '2025-12-07', 1, '1313.00', 'confirmed', '', '2025-12-07 18:21:32', 'checked_in', '2025-12-10 10:09:29', 'retail', NULL),
(14, 11, NULL, 4, '2025-12-08', 1, '1231.00', 'confirmed', '', '2025-12-08 01:45:23', 'checked_in', '2025-12-09 19:53:38', 'retail', NULL),
(15, 12, NULL, 4, '2025-12-08', 1, '1231.00', 'confirmed', '', '2025-12-08 01:53:52', 'checked_in', '2025-12-09 19:59:46', 'retail', NULL),
(16, 7, NULL, 5, '2025-12-16', 10, '13130.00', 'pending', '', '2025-12-16 15:31:28', 'pending', NULL, 'retail', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_special_requests`
--

CREATE TABLE `customer_special_requests` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `dietary_requests` json DEFAULT NULL,
  `medical_conditions` json DEFAULT NULL,
  `room_preferences` json DEFAULT NULL,
  `other_requests` json DEFAULT NULL,
  `dietary_notes` text,
  `medical_notes` text,
  `room_notes` text,
  `other_notes` text,
  `importance_level` enum('high','medium','low') DEFAULT 'medium',
  `is_notified_guide` tinyint(1) DEFAULT '0',
  `is_notified_kitchen` tinyint(1) DEFAULT '0',
  `updated_by` int NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `rating` int DEFAULT '5',
  `content` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `experience_years` int DEFAULT NULL,
  `languages` text,
  `specialties` text,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `user_id`, `license_number`, `experience_years`, `languages`, `specialties`, `status`) VALUES
(1, 2, 'HDV-001', 5, 'Tiếng Việt, Tiếng Anh', 'Tour biển đảo, Tour văn hóa', 'active'),
(2, 4, NULL, 10, 'tiếng anh', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int DEFAULT NULL,
  `receiver_type` varchar(50) DEFAULT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `receiver_type`, `content`, `is_read`, `created_at`) VALUES
(1, 2, 1, 'admin', 'helo\r\n', 0, '2025-12-10 03:08:26'),
(2, 2, 1, 'admin', 'hello\r\n', 0, '2025-12-16 15:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `guide_id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `severity` varchar(20) DEFAULT NULL,
  `expenses` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT 'pending',
  `admin_notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_change_logs`
--

CREATE TABLE `request_change_logs` (
  `id` int NOT NULL,
  `request_id` int NOT NULL,
  `changed_field` varchar(50) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `changed_by` int NOT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên Dịch Vụ',
  `vendor` varchar(255) DEFAULT NULL COMMENT 'Nhà Cung Cấp',
  `default_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost_type` varchar(50) NOT NULL DEFAULT 'per_trip',
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int NOT NULL,
  `StaffName` varchar(255) NOT NULL,
  `Role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `StaffName`, `Role`) VALUES
(1, 'Nguyễn Văn A', 'Hướng dẫn viên'),
(2, 'Trần Thị B', 'Lái xe 45 chỗ'),
(3, 'Lê Văn C', 'Phục vụ Sự kiện');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int NOT NULL,
  `tour_code` varchar(50) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `description` text,
  `itinerary` text,
  `policy` text,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(15,2) DEFAULT '0.00',
  `duration` int DEFAULT NULL,
  `start_location` varchar(100) DEFAULT NULL,
  `destinations` text,
  `vehicle` varchar(100) DEFAULT NULL,
  `max_capacity` int DEFAULT NULL,
  `available_slots` int DEFAULT NULL,
  `status` enum('active','inactive','completed') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `tour_type` enum('domestic','international','custom') DEFAULT 'domestic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `tour_code`, `category_id`, `name`, `supplier`, `description`, `itinerary`, `policy`, `price`, `cost_price`, `duration`, `start_location`, `destinations`, `vehicle`, `max_capacity`, `available_slots`, `status`, `created_at`, `start_date`, `end_date`, `tour_type`) VALUES
(1, NULL, 1, 'Tour Hạ Long 3N2D', NULL, 'Khám phá vịnh Hạ Long kỳ vĩ, hang Sửng Sốt, đảo Titop', NULL, NULL, '3299000.00', '0.00', 3, 'Hà Nội', 'Hạ Long, Tuần Châu, Bãi Cháy', NULL, 20, 15, 'active', '2025-11-26 01:37:58', '2025-11-28', '2025-11-30', 'domestic'),
(2, NULL, 2, 'Tour Sapa 2N1D', NULL, 'Trải nghiệm vùng núi Tây Bắc, Fansipan, bản Cát Cát', NULL, NULL, '1899000.00', '0.00', 2, 'Hà Nội', 'Sapa, Fansipan, Bản Cát Cát', NULL, 15, 8, 'active', '2025-11-26 01:37:58', '2025-12-05', '2025-12-07', 'domestic'),
(3, NULL, 3, 'Tour Phú Quốc 4N3D', NULL, 'Thiên đường biển đảo miền Nam, vinpearl, safari', NULL, NULL, '4599000.00', '0.00', 4, 'HCM', 'Phú Quốc, Vinpearl, Safari', NULL, 25, 12, 'active', '2025-11-26 01:37:58', NULL, NULL, 'domestic'),
(4, 'fasdf', 1, 'sdfasf', 'ádfasf', '', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 8, 'active', '2025-11-30 23:45:25', NULL, NULL, 'international'),
(5, 'fasdf', 1, 'sdfasf', 'ádfasf', 'zXax', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 20, 'active', '2025-11-30 23:52:08', NULL, NULL, 'international'),
(6, 'fasdf', 2, 'sdfasf', 'ádfasf', '', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 19, 'active', '2025-11-30 23:56:56', NULL, NULL, 'international'),
(7, 'fasdf', 2, 'sdfasf', 'ádfasf', '', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 9, 'active', '2025-12-01 00:15:15', NULL, NULL, 'international'),
(8, 'fasdf', 1, 'sdfasf', 'ádfasf', '', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 2, 'active', '2025-12-01 00:22:42', NULL, NULL, 'international'),
(9, 'fasdf', 3, 'sdfasf', 'ádfasf', '', NULL, '', '1313.00', '123123.00', 1, 'ádfa', 'ádfaf', NULL, 20, 0, 'active', '2025-12-01 00:26:41', NULL, NULL, 'international'),
(10, ' ', 2, 'v ', ' ', ' ', NULL, '', '12.00', '10000.00', 1, ' ', ' ', NULL, 20, 0, 'active', '2025-12-01 01:26:26', NULL, NULL, 'international'),
(11, 'fasdf', 4, 'Nguyễn Văn A	', 'fgbhsfgh', 'shsh', NULL, '', '1231.00', '3213.00', 1, 'ghss', 'ghsgh', NULL, 20, 19, 'active', '2025-12-08 00:49:05', NULL, NULL, 'international'),
(12, 'fasdf', 1, 'Nguyễn Văn B', 'fgbhsfgh', '', NULL, '', '1231.00', '3213.00', 1, 'ghss', 'ghsgh', NULL, 20, 19, 'active', '2025-12-08 01:52:58', NULL, NULL, 'international');

-- --------------------------------------------------------

--
-- Table structure for table `tour_assignments`
--

CREATE TABLE `tour_assignments` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `assignment_date` date DEFAULT NULL,
  `notes` text,
  `status` enum('assigned','completed','cancelled') DEFAULT 'assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_assignments`
--

INSERT INTO `tour_assignments` (`id`, `tour_id`, `guide_id`, `assignment_date`, `notes`, `status`) VALUES
(1, 1, 1, '2024-12-01', 'Đón khách tại điểm hẹn', 'completed'),
(2, 1, 1, '2025-11-25', NULL, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `tour_categories`
--

CREATE TABLE `tour_categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_categories`
--

INSERT INTO `tour_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Tour Biển Đảo', 'Các tour du lịch biển đảo', '2025-11-26 01:37:58'),
(2, 'Tour Văn Hóa', 'Tour khám phá văn hóa địa phương', '2025-11-26 01:37:58'),
(3, 'Tour Trekking', 'Tour leo núi, khám phá thiên nhiên', '2025-11-26 01:37:58'),
(4, 'Tour Miền Bắc', 'Các tour du lịch phía Bắc', '2025-12-05 01:32:57'),
(5, 'Tour Miền Trung', 'Các tour du lịch miền Trung', '2025-12-05 01:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `tour_costs`
--

CREATE TABLE `tour_costs` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `cost_type` varchar(100) NOT NULL COMMENT 'Vd: Thuê xe, Khách sạn, Ăn uống',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_costs`
--

INSERT INTO `tour_costs` (`id`, `tour_id`, `cost_type`, `amount`, `description`, `created_at`) VALUES
(3, 11, 'service', '12.00', 'DV: dọn vệ sinh | NCC: fpt', '2025-12-08 01:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `tour_departures`
--

CREATE TABLE `tour_departures` (
  `DepartureID` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `StartDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Destination` varchar(255) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT '1',
  `tour_id` int NOT NULL,
  `departure_date` date NOT NULL DEFAULT '2025-01-01',
  `capacity` int NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'Scheduled',
  `end_date` date DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_departures`
--

INSERT INTO `tour_departures` (`DepartureID`, `name`, `StartDate`, `ReturnDate`, `Destination`, `IsActive`, `tour_id`, `departure_date`, `capacity`, `status`, `end_date`, `confirmed_at`, `completed_at`, `notes`) VALUES
(1, 'Tour Đà Lạt 4 ngày 3 đêm', '2026-12-15', '2026-12-18', 'Đà Lạt', 1, 0, '2025-01-01', 0, 'Scheduled', NULL, NULL, NULL, NULL),
(2, 'Sự kiện Team Building Vũng Tàu', '2027-01-10', '2027-01-12', 'Vũng Tàu', 1, 0, '2025-01-01', 0, 'Scheduled', NULL, NULL, NULL, NULL),
(4, 'Tour Hạ Long 3N2D - 2025-12-08', '2025-12-08', NULL, NULL, 1, 1, '2025-12-08', 122, 'ongoing', '2025-12-10', NULL, NULL, NULL),
(5, 'Tour Phú Quốc 4N3D - 2025-12-17', '2025-12-17', NULL, NULL, 1, 3, '2025-12-17', 4, 'ongoing', '2025-12-20', NULL, NULL, NULL),
(6, 'Nguyễn Văn A	 - 2025-12-10', '2025-12-10', NULL, NULL, 1, 11, '2025-12-10', 20, 'scheduled', '2025-12-10', NULL, NULL, NULL),
(7, 'Nguyễn Văn A	 - 2025-12-01', '2025-12-01', NULL, NULL, 1, 11, '2025-12-01', 20, 'scheduled', '2025-12-01', NULL, NULL, NULL),
(8, 'Nguyễn Văn A	 - 2025-12-09', '2025-12-09', NULL, NULL, 1, 11, '2025-12-09', 20, 'scheduled', '2025-12-09', NULL, NULL, NULL),
(9, 'Nguyễn Văn A	 - 2025-12-13', '2025-12-13', NULL, NULL, 1, 11, '2025-12-13', 20, 'ongoing', '2025-12-13', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour_diaries`
--

CREATE TABLE `tour_diaries` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `diary_type` enum('journey','incident','feedback','cost','image','other') NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Ghi chú',
  `content` text NOT NULL,
  `handling` text,
  `location` varchar(100) DEFAULT NULL,
  `importance_level` enum('emergency','important','normal','info') DEFAULT 'normal',
  `images` json DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_diaries`
--

INSERT INTO `tour_diaries` (`id`, `tour_id`, `diary_type`, `title`, `content`, `handling`, `location`, `importance_level`, `images`, `created_by`, `created_at`) VALUES
(1, 2, 'journey', 'adfas', 'fdsgdhj', '', 'sdgdfg', 'normal', NULL, 1, '2025-11-28 06:01:24'),
(2, 1, 'incident', 'dsfsf', 'ádfasf', 'sadfasf', 'adsfasdf', 'normal', NULL, 1, '2025-11-30 23:36:00'),
(3, 9, 'incident', 'sad', 'adfafd', 'ádf', 'sdafa', 'normal', NULL, 1, '2025-12-03 00:39:14'),
(4, 8, 'feedback', 'jnom', 'jkfjkhkg', '', 'hf,f', 'normal', NULL, 1, '2025-12-03 00:39:25'),
(6, 7, 'incident', 'áda', 'sadad', '', 'sad', 'normal', NULL, 1, '2025-12-07 17:17:05'),
(7, 9, 'journey', 'sfbgdfhgsf', 'hdfgd', '', 'ghfghdf', 'normal', NULL, 1, '2025-12-07 18:19:22'),
(8, 10, 'journey', 'dfgsdfg', 'dsfgsdg', '', 'dfgsdg', 'normal', NULL, 1, '2025-12-07 18:19:27'),
(11, 11, 'journey', 'ADSads', 'dADsd', '', '', 'normal', NULL, 1, '2025-12-08 01:40:56'),
(12, 1, 'incident', 'Mất nước', 'mất nước', NULL, NULL, 'normal', NULL, 2, '2025-12-15 10:22:11'),
(13, 1, 'incident', 'hỏng xe', 'Mức độ: medium\nxe hỏng', NULL, NULL, 'normal', NULL, 2, '2025-12-16 15:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `tour_groups`
--

CREATE TABLE `tour_groups` (
  `GroupID` int NOT NULL,
  `DepartureID` int NOT NULL,
  `GroupName` varchar(255) NOT NULL,
  `TotalGuests` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_groups`
--

INSERT INTO `tour_groups` (`GroupID`, `DepartureID`, `GroupName`, `TotalGuests`) VALUES
(1, 1, 'Đoàn Khách Hàng ABC', 25),
(2, 2, 'Đoàn Công Ty XYZ', 50);

-- --------------------------------------------------------

--
-- Table structure for table `tour_guests`
--

CREATE TABLE `tour_guests` (
  `GuestID` int NOT NULL,
  `GroupID` int NOT NULL,
  `GuestName` varchar(255) NOT NULL,
  `GuestPhone` varchar(20) DEFAULT NULL,
  `CheckedIn` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_guests`
--

INSERT INTO `tour_guests` (`GuestID`, `GroupID`, `GuestName`, `GuestPhone`, `CheckedIn`) VALUES
(1, 1, 'Phạm Văn Khánh', '0901xxxxxx', 0),
(2, 1, 'Đỗ Thị Ngọc', '0902xxxxxx', 0),
(3, 2, 'Trương Đình Lộc', '0903xxxxxx', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tour_images`
--

CREATE TABLE `tour_images` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_images`
--

INSERT INTO `tour_images` (`id`, `tour_id`, `image_url`, `is_main`, `created_at`) VALUES
(1, 1, 'halong_main.jpg', 1, '2025-11-26 01:37:58'),
(2, 1, 'halong_1.jpg', 0, '2025-11-26 01:37:58'),
(3, 2, 'sapa_main.jpg', 1, '2025-11-26 01:37:58'),
(4, 3, 'phuquoc_main.jpg', 1, '2025-11-26 01:37:58'),
(5, 10, 'tours/1764552386-10-692ceec274d94.png', 1, '2025-12-01 01:26:26'),
(6, 11, 'tours/1765154945-11-693620810bed8.jpg', 1, '2025-12-08 00:49:05');

-- --------------------------------------------------------

--
-- Table structure for table `tour_itineraries`
--

CREATE TABLE `tour_itineraries` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `day_number` int NOT NULL COMMENT 'Ngày thứ mấy',
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề (Vd: Khám phá hang động)',
  `activity` text NOT NULL COMMENT 'Mô tả chi tiết hoạt động',
  `meals` varchar(100) DEFAULT NULL COMMENT 'Sáng, Trưa, Tối'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_itineraries`
--

INSERT INTO `tour_itineraries` (`id`, `tour_id`, `day_number`, `title`, `activity`, `meals`) VALUES
(1, 1, 1, 'Khởi hành từ Hà Nội', 'Khách lên xe, khởi hành đi Hạ Long', 'Cơm trưa'),
(2, 1, 2, 'Thuyền tham quan Vịnh Hạ Long', 'Du lịch Vịnh Hạ Long, tham quan các hang động', 'Cơm trưa, Cơm tối'),
(3, 1, 3, 'Trở về Hà Nội', 'Quay lại Hà Nội', 'Cơm sáng, Cơm trưa');

-- --------------------------------------------------------

--
-- Table structure for table `tour_types`
--

CREATE TABLE `tour_types` (
  `id` int NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour_types`
--

INSERT INTO `tour_types` (`id`, `code`, `name`, `description`, `icon`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'domestic', 'Tour Trong Nước', 'Du lịch trong phạm vi đất nước', NULL, '#28a745', 1, '2025-12-07 17:13:06', '2025-12-07 17:13:06'),
(2, 'international', 'Tour Quốc Tế', 'Du lịch nước ngoài', NULL, '#007bff', 1, '2025-12-07 17:13:06', '2025-12-07 17:13:06'),
(3, 'custom', 'Tour Theo Yêu Cầu', 'Tour được tùy chỉnh theo nhu cầu khách hàng', NULL, '#ffc107', 1, '2025-12-07 17:13:06', '2025-12-07 17:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text,
  `role` varchar(50) DEFAULT 'customer',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_admin`, `full_name`, `phone`, `created_at`, `address`, `role`, `status`) VALUES
(1, 'admin', 'admin@tour.com', '123456', 1, 'Quản Trị Viên', '0123456789', '2025-11-26 01:37:58', NULL, 'customer', 'active'),
(2, 'huongdanvien1', 'hdv1@tour.com', '123456', 0, 'Nguyễn Văn A', '0987654321', '2025-11-26 01:37:58', NULL, 'customer', 'active'),
(3, 'customer1', 'customer@example.com', '123456', 0, 'Khách Hàng Mẫu', '0912345678', '2025-11-26 01:37:58', NULL, 'customer', 'active'),
(4, 'hungnymo670', 'hungnymo@gmail.com', '$2y$10$sd3/I8CxlRT8myA1FzJt8.eE3lfvy0sorksu7ROk/1Rti8/qCcHrC', 0, 'hungdki', '0827190206', '2025-12-05 00:45:31', NULL, 'customer', 'active'),
(5, 'dsfsf567', 'dsfsf@gmail.com', '$2y$10$/TxXSoj1wGrTnX7AT4XRSulx8HgZ9FOkQM6UzG1MgWd8jxxDdpbcC', 0, 'dcs', 'sdfs', '2025-12-07 17:15:21', NULL, 'customer', 'active'),
(6, 'dgsf177', 'dgsf@gmail.com', '$2y$10$DVJEybJUNallQRg2A3PmbeWXFd/peLH0wtoTL.RwFmqtfN4cJoLxK', 0, 'sfds', 'fsdf', '2025-12-07 18:12:51', NULL, 'customer', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_tour` (`tour_id`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_status` (`status`);

--
-- Indexes for table `customer_special_requests`
--
ALTER TABLE `customer_special_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `request_change_logs`
--
ALTER TABLE `request_change_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tours_category` (`category_id`);

--
-- Indexes for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `tour_categories`
--
ALTER TABLE `tour_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour_costs`
--
ALTER TABLE `tour_costs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD PRIMARY KEY (`DepartureID`);

--
-- Indexes for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_diaries_tour` (`tour_id`);

--
-- Indexes for table `tour_groups`
--
ALTER TABLE `tour_groups`
  ADD PRIMARY KEY (`GroupID`),
  ADD KEY `DepartureID` (`DepartureID`);

--
-- Indexes for table `tour_guests`
--
ALTER TABLE `tour_guests`
  ADD PRIMARY KEY (`GuestID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Indexes for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_types`
--
ALTER TABLE `tour_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer_special_requests`
--
ALTER TABLE `customer_special_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_change_logs`
--
ALTER TABLE `request_change_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tour_categories`
--
ALTER TABLE `tour_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tour_costs`
--
ALTER TABLE `tour_costs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tour_departures`
--
ALTER TABLE `tour_departures`
  MODIFY `DepartureID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tour_groups`
--
ALTER TABLE `tour_groups`
  MODIFY `GroupID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_guests`
--
ALTER TABLE `tour_guests`
  MODIFY `GuestID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tour_images`
--
ALTER TABLE `tour_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tour_types`
--
ALTER TABLE `tour_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_special_requests`
--
ALTER TABLE `customer_special_requests`
  ADD CONSTRAINT `customer_special_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_special_requests_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `request_change_logs`
--
ALTER TABLE `request_change_logs`
  ADD CONSTRAINT `request_change_logs_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `customer_special_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tour_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD CONSTRAINT `tour_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_assignments_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_costs`
--
ALTER TABLE `tour_costs`
  ADD CONSTRAINT `tour_costs_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD CONSTRAINT `tour_diaries_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_diaries_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_groups`
--
ALTER TABLE `tour_groups`
  ADD CONSTRAINT `tour_groups_ibfk_1` FOREIGN KEY (`DepartureID`) REFERENCES `tour_departures` (`DepartureID`) ON DELETE CASCADE;

--
-- Constraints for table `tour_guests`
--
ALTER TABLE `tour_guests`
  ADD CONSTRAINT `tour_guests_ibfk_1` FOREIGN KEY (`GroupID`) REFERENCES `tour_groups` (`GroupID`) ON DELETE CASCADE;

--
-- Constraints for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD CONSTRAINT `tour_itineraries_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
