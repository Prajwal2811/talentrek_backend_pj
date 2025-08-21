-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 06:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talentrek`
--

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_additional_info`
--

CREATE TABLE `talentrek_additional_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('jobseeker','recruiter','mentor','coach','assessor','expat','trainer') NOT NULL,
  `doc_type` varchar(191) DEFAULT NULL,
  `document_name` varchar(191) DEFAULT NULL,
  `document_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_additional_info`
--

INSERT INTO `talentrek_additional_info` (`id`, `user_id`, `user_type`, `doc_type`, `document_name`, `document_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'jobseeker', 'resume', 'resume_John Doe.pdf', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, 1, 'jobseeker', 'profile_picture', 'profile_John Doe.png', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(3, 2, 'jobseeker', 'resume', 'resume_Priya Sharma.pdf', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 2, 'jobseeker', 'profile_picture', 'profile_Priya Sharma.png', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 3, 'jobseeker', 'resume', 'resume_Arjun Mehta.pdf', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(6, 3, 'jobseeker', 'profile_picture', 'profile_Arjun Mehta.png', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(7, 4, 'jobseeker', 'resume', 'resume_Sneha Rao.pdf', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(8, 4, 'jobseeker', 'profile_picture', 'profile_Sneha Rao.png', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(9, 5, 'jobseeker', 'resume', 'resume_Ravi Kumar.pdf', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(10, 5, 'jobseeker', 'profile_picture', 'profile_Ravi Kumar.png', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(11, 1, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Anjali Verma.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(12, 2, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Ravi Sharma.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(13, 3, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Meera Iyer.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(14, 4, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Ali Khan.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(15, 5, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Sophia Lewis.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(16, 6, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Rahul Patil.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(17, 7, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Lina Wang.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(18, 8, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_David Green.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(19, 9, 'mentor', 'mentor_profile_picture', 'mentor_profile_picture_Fatima Noor.jpg', 'http://hancockogundiyapartners.com/wp-content/uploads/2019/07/dummy-profile-pic-300x300.jpg', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_admins`
--

CREATE TABLE `talentrek_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `role` enum('superadmin','admin') NOT NULL,
  `notes` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_admins`
--

INSERT INTO `talentrek_admins` (`id`, `name`, `email`, `password`, `pass`, `phone`, `role`, `notes`, `status`, `permissions`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Prajwal Ingole', 'prajwal@talentrek.com', '$2y$10$j8pZL0zgOVRhnKbm2UQzEuVYZztFnw4yxp4MCc/uiR5R1vi2g9W4q', 'prajwal@talentrek', '9975239057', 'superadmin', NULL, 'active', NULL, NULL, '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, 'Sumesh Chaure', 'sumesh@talentrek.com', '$2y$10$he/KFkD3ukTDWCunf7IB/eKmP23yl706P4h/9a5PcqTAJ8EK9.IhG', 'sumesh@talentrek', '9975239057', 'superadmin', NULL, 'active', NULL, NULL, '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(3, 'Hemchandra', 'hemchandra@talentrek.com', '$2y$10$D8NzOe2mY.mxGSxIb5npMOeRJCMT/hIKmZ7.NeZeL1hhZgkIfoXIC', 'hemchandra@talentrek', '9975239063', 'admin', NULL, 'active', NULL, NULL, '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(4, 'Nimish Gupta', 'nimish@talentrek.com', '$2y$10$e77qdxxhCPBtBr0QtNTneeickmZdp3RCBRcRJTLp0jhBhUkbaMHJS', 'nimish@talentrek', '9975239064', 'admin', NULL, 'active', NULL, NULL, '2025-08-04 04:10:07', '2025-08-04 04:10:07');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_admin_permissions`
--

CREATE TABLE `talentrek_admin_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_assessment_options`
--

CREATE TABLE `talentrek_assessment_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `options` varchar(191) NOT NULL,
  `correct_option` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_assessment_options`
--

INSERT INTO `talentrek_assessment_options` (`id`, `trainer_id`, `assessment_id`, `question_id`, `options`, `correct_option`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'A framework', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 1, 1, 1, 'A database', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 1, 1, 1, 'A programming language', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 1, 1, 1, 'An operating system', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 1, 1, 2, 'A Laravel feature to style pages', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 1, 1, 2, 'A tool to manage database schema', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 1, 1, 2, 'A command-line interface', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 1, 1, 2, 'A blade engine helper', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 1, 1, 3, 'A database driver', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 1, 1, 3, 'A templating engine', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(11, 1, 1, 3, 'An ActiveRecord implementation', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(12, 1, 1, 3, 'A routing method', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(13, 1, 1, 4, 'Route::get()', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(14, 1, 1, 4, 'DB::table()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(15, 1, 1, 4, 'Schema::create()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(16, 1, 1, 4, 'Blade::render()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(17, 1, 1, 5, 'Styling pages', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(18, 1, 1, 5, 'Validating requests', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(19, 1, 1, 5, 'Managing database', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(20, 1, 1, 5, 'Writing tests', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(21, 1, 1, 6, 'Define routes', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(22, 1, 1, 6, 'Load and bind classes into the service container', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(23, 1, 1, 6, 'Create tables', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(24, 1, 1, 6, 'Run migrations', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(25, 1, 1, 7, 'A Laravel GUI tool', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(26, 1, 1, 7, 'A command-line interface', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(27, 1, 1, 7, 'A debugging tool', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(28, 1, 1, 7, 'An ORM', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(29, 1, 1, 8, 'Define templates', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(30, 1, 1, 8, 'Add dummy data to database', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(31, 1, 1, 8, 'Run migrations', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(32, 1, 1, 8, 'Validate forms', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(33, 1, 1, 9, 'A static interface to classes', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(34, 1, 1, 9, 'Database schema', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(35, 1, 1, 9, 'URL routing', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(36, 1, 1, 9, 'View files', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(37, 1, 1, 10, 'A file format', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(38, 1, 1, 10, 'A templating engine', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(39, 1, 1, 10, 'A routing method', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(40, 1, 1, 10, 'A type of migration', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(41, 1, 1, 11, 'Environment variables', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(42, 1, 1, 11, 'HTML code', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(43, 1, 1, 11, 'Controllers', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(44, 1, 1, 11, 'Seeder data', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(45, 1, 1, 12, 'Validator::make()', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(46, 1, 1, 12, 'Route::validate()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(47, 1, 1, 12, 'Blade::check()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(48, 1, 1, 12, 'Model::save()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(49, 1, 1, 13, 'hasOne, belongsTo', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(50, 1, 1, 13, 'start, stop', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(51, 1, 1, 13, 'link, unlink', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(52, 1, 1, 13, 'get, set', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(53, 1, 1, 14, 'XSS attacks', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(54, 1, 1, 14, 'Cross-site request forgery', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(55, 1, 1, 14, 'Spam emails', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(56, 1, 1, 14, 'Brute-force login', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(57, 1, 1, 15, 'Use move() method on request file', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(58, 1, 1, 15, 'Use DB::insert()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(59, 1, 1, 15, 'Use Route::post()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(60, 1, 1, 15, 'Use Schema::upload()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(61, 1, 2, 16, 'Real-time chat', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(62, 1, 2, 16, 'Email sending in background', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(63, 1, 2, 16, 'File storage', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(64, 1, 2, 16, 'Routing requests', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(65, 1, 2, 17, 'php artisan make:controller', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(66, 1, 2, 17, 'php make:controller', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(67, 1, 2, 17, 'php artisan generate:controller', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(68, 1, 2, 17, 'php build:controller', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(69, 1, 2, 18, 'Direction of the relationship', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(70, 1, 2, 18, 'Both are same', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(71, 1, 2, 18, 'Used for pagination', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(72, 1, 2, 18, 'Used in views', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(73, 1, 2, 19, 'Passing model via URL automatically', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(74, 1, 2, 19, 'Securing routes', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(75, 1, 2, 19, 'Styling route files', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(76, 1, 2, 19, 'Uploading files', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(77, 1, 2, 20, 'View templates', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(78, 1, 2, 20, 'Authorization logic', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(79, 1, 2, 20, 'Schema design', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(80, 1, 2, 20, 'Database seeding', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(81, 1, 2, 21, 'Single column values', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(82, 1, 2, 21, 'All rows', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(83, 1, 2, 21, 'HTML output', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(84, 1, 2, 21, 'Errors', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(85, 1, 2, 22, 'Eager loads relationships', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(86, 1, 2, 22, 'Validates data', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(87, 1, 2, 22, 'Creates migrations', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(88, 1, 2, 22, 'Saves models', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(89, 1, 2, 23, 'paginate()', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(90, 1, 2, 23, 'get()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(91, 1, 2, 23, 'sort()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(92, 1, 2, 23, 'route()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(93, 1, 2, 24, 'Long running tasks', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(94, 1, 2, 24, 'Form validations', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(95, 1, 2, 24, 'Blade rendering', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(96, 1, 2, 24, 'Database queries', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(97, 1, 2, 25, 'Mail::to()->send()', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(98, 1, 2, 25, 'Route::mail()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(99, 1, 2, 25, 'Email::send()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(100, 1, 2, 25, 'Mail::create()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(101, 1, 2, 26, 'Handling background tasks', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(102, 1, 2, 26, 'Decoupled communication between parts', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(103, 1, 2, 26, 'Routing logic', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(104, 1, 2, 26, 'Pagination', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(105, 1, 2, 27, 'Simple API token auth', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(106, 1, 2, 27, 'UI rendering', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(107, 1, 2, 27, 'Database backups', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(108, 1, 2, 27, 'Job queues', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(109, 1, 2, 28, 'OAuth2 authentication', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(110, 1, 2, 28, 'Migration rollback', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(111, 1, 2, 28, 'UI theming', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(112, 1, 2, 28, 'Form requests', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(113, 1, 2, 29, 'Auth scaffolding', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(114, 1, 2, 29, 'Route::auth()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(115, 1, 2, 29, 'DB::auth()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(116, 1, 2, 29, 'Model::login()', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(117, 1, 2, 30, 'web.php includes session and csrf, api.php does not', '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(118, 1, 2, 30, 'Both are same', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(119, 1, 2, 30, 'Only for admin', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(120, 1, 2, 30, 'Used only in CLI', '0', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_assessment_questions`
--

CREATE TABLE `talentrek_assessment_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_id` bigint(20) UNSIGNED NOT NULL,
  `questions_title` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_assessment_questions`
--

INSERT INTO `talentrek_assessment_questions` (`id`, `trainer_id`, `assessment_id`, `questions_title`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'What is Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 1, 1, 'What is a migration in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 1, 1, 'What is an Eloquent ORM?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 1, 1, 'How do you define a route in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 1, 1, 'What is middleware in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 1, 1, 'What is a service provider in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 1, 1, 'What is the use of `artisan` command?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 1, 1, 'What is a seeder in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 1, 1, 'What are Laravel facades?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 1, 1, 'How does Laravel’s Blade templating engine work?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(11, 1, 1, 'What is the purpose of the `.env` file?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(12, 1, 1, 'How can you validate a request in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(13, 1, 1, 'What are relationships in Eloquent?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(14, 1, 1, 'What is CSRF protection in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(15, 1, 1, 'How do you handle file uploads in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(16, 1, 2, 'What is the use of queues in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(17, 1, 2, 'How can you create a controller in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(18, 1, 2, 'What is the difference between `hasOne` and `belongsTo`?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(19, 1, 2, 'What is route model binding?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(20, 1, 2, 'What are Laravel policies used for?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(21, 1, 2, 'What is the difference between `pluck()` and `get()`?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(22, 1, 2, 'What is the use of `with()` in Eloquent?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(23, 1, 2, 'How to use pagination in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(24, 1, 2, 'What is the use of jobs and workers in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(25, 1, 2, 'How to send email in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(26, 1, 2, 'What are events and listeners in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(27, 1, 2, 'What is Laravel Sanctum?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(28, 1, 2, 'What is Laravel Passport?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(29, 1, 2, 'How do you implement authentication in Laravel?', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(30, 1, 2, 'What is the difference between `web.php` and `api.php` routes?', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_assessors`
--

CREATE TABLE `talentrek_assessors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `pin_code` varchar(191) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `shortlist` varchar(191) DEFAULT NULL,
  `admin_recruiter_status` varchar(191) DEFAULT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `about_assessor` text DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_assessors`
--

INSERT INTO `talentrek_assessors` (`id`, `name`, `email`, `national_id`, `phone_code`, `phone_number`, `date_of_birth`, `city`, `state`, `address`, `pin_code`, `country`, `password`, `pass`, `otp`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `shortlist`, `admin_recruiter_status`, `google_id`, `avatar`, `about_assessor`, `is_registered`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 'Ravi Kumar', 'ravi.assessor@example.com', 'IND-100001', '+91', '9876543210', '1980-06-15', 'Delhi', NULL, NULL, NULL, NULL, '$2y$10$dCF2YapvUODZozCb3TgkeOPezJjhGaqSnbHwd2lvw0j7TLOuhjHJ.', 'raviPass123', '789456', 'active', 'approved', NULL, NULL, 'yes', 'verified', 'google_assessor_001', 'avatar_ravi.png', 'Certified assessor with experience in skill evaluation for over 12 years.', '0', 'yes', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(2, 'Fatima Noor', 'fatima.assessor@example.com', 'UAE-567890', '+971', '555123456', '1992-03-22', 'Dubai', NULL, NULL, NULL, NULL, '$2y$10$JE8uF6toxnqftgibPYI.ieTcN4UdqLD86zyCLJPZkbjR87C6LM4Ve', 'fatimaPass456', '321789', 'inactive', 'pending', 'Verification documents pending', NULL, 'no', 'pending', NULL, NULL, 'Focused on assessment of technical and soft skills in the corporate sector.', '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_booking_slots`
--

CREATE TABLE `talentrek_booking_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('mentor','coach','assessor') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `slot_mode` enum('online','offline') NOT NULL DEFAULT 'online',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  `is_booked` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_booking_slots`
--

INSERT INTO `talentrek_booking_slots` (`id`, `user_type`, `user_id`, `slot_mode`, `start_time`, `end_time`, `is_available`, `is_booked`, `created_at`, `updated_at`) VALUES
(1, 'assessor', 1, 'online', '08:00:00', '13:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 'coach', 7, 'online', '09:00:00', '16:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 'assessor', 1, 'online', '12:00:00', '18:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 'coach', 8, 'offline', '09:00:00', '13:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 'assessor', 3, 'online', '09:00:00', '18:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 'mentor', 6, 'offline', '08:00:00', '15:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 'mentor', 3, 'offline', '09:00:00', '16:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 'assessor', 9, 'offline', '11:00:00', '18:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 'assessor', 3, 'offline', '12:00:00', '15:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 'assessor', 3, 'offline', '10:00:00', '16:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(11, 'assessor', 10, 'offline', '11:00:00', '15:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(12, 'mentor', 8, 'offline', '08:00:00', '14:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(13, 'assessor', 10, 'offline', '08:00:00', '17:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(14, 'assessor', 1, 'offline', '08:00:00', '13:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(15, 'mentor', 9, 'online', '11:00:00', '18:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(16, 'mentor', 2, 'offline', '12:00:00', '14:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(17, 'coach', 8, 'online', '08:00:00', '16:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(18, 'assessor', 7, 'online', '08:00:00', '17:00:00', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(19, 'assessor', 4, 'online', '10:00:00', '18:00:00', NULL, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(20, 'coach', 4, 'offline', '08:00:00', '15:00:00', NULL, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_booking_slots_unavailable_dates`
--

CREATE TABLE `talentrek_booking_slots_unavailable_dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_slot_id` bigint(20) UNSIGNED NOT NULL,
  `unavailable_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_certificate_template`
--

CREATE TABLE `talentrek_certificate_template` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_title` varchar(191) NOT NULL,
  `template_html` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_certificate_template`
--

INSERT INTO `talentrek_certificate_template` (`id`, `certificate_title`, `template_html`, `created_at`, `updated_at`) VALUES
(1, 'Job Completion Certificate', '<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n<meta charset=\"UTF-8\" />\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>\n<title>Jobseeker Certificate</title>\n<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\"/>\n<style>\n    body {\n    background-color: #f0f0f0;\n    font-family: \'Georgia\', serif;\n    }\n\n    .certificate {\n    max-width: 900px;\n    margin: 50px auto;\n    padding: 60px;\n    background-color: #fff;\n    border: 8px solid #2c3e50;\n    box-shadow: 0 0 20px rgba(0,0,0,0.2);\n    text-align: center;\n    }\n\n    .certificate h1 {\n    font-size: 2.8rem;\n    color: #2c3e50;\n    margin-bottom: 20px;\n    }\n\n    .certificate h2 {\n    font-size: 1.6rem;\n    margin-bottom: 30px;\n    color: #444;\n    }\n\n    .certificate .jobseeker-name {\n    font-size: 2.2rem;\n    font-weight: bold;\n    color: #000;\n    border-bottom: 2px solid #000;\n    display: inline-block;\n    padding: 5px 30px;\n    margin-bottom: 25px;\n    }\n\n    .certificate .course-title {\n    font-size: 1.4rem;\n    color: #1a1a1a;\n    font-weight: bold;\n    margin-bottom: 5px;\n    }\n\n    .certificate .job-role {\n    font-size: 1.2rem;\n    color: #555;\n    margin-bottom: 20px;\n    }\n\n    .certificate .details {\n    font-size: 1.2rem;\n    color: #333;\n    margin-top: 15px;\n    }\n\n    .certificate .footer {\n    margin-top: 60px;\n    display: flex;\n    justify-content: space-between;\n    padding: 0 50px;\n    }\n\n    .sign {\n    text-align: center;\n    }\n\n    .sign hr {\n    margin: 10px auto;\n    width: 200px;\n    border-top: 2px solid #000;\n    }\n\n    .logo {\n    width: 90px;\n    margin-bottom: 15px;\n    }\n</style>\n</head>\n<body>\n\n<div class=\"certificate\">\n    <img src=\"https://pixelvalues.com/wp-content/uploads/2022/03/logo.png.webp\" alt=\"Company Logo\" class=\"logo\" />\n    <h1>Certificate of Achievement</h1>\n    <h2>This certificate is proudly presented to</h2>\n\n    <div class=\"jobseeker-name\">Prajwal Ingole</div>\n\n    <div class=\"course-title\">Software Development Program</div>\n    <div class=\"job-role\">(as a Fullstack Deveoplerw)</div>\n\n    <div class=\"details\">\n    Conducted by <strong>Talentrek</strong><br />\n    From <strong>1st May 2025</strong> to <strong>30th June 2025</strong><br />\n    In recognition of outstanding performance, dedication, and learning commitment.\n    </div>\n\n    <div class=\"footer mt-5\">\n    <div class=\"sign\">\n        <hr />\n        <p><strong>HR Manager</strong><br />Anjali Sharma</p>\n    </div>\n    <div class=\"sign\">\n        <hr />\n        <p><strong>CEO</strong><br />Rahul Verma</p>\n    </div>\n    </div>\n</div>\n\n</body>\n</html>', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_cms_module`
--

CREATE TABLE `talentrek_cms_module` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `heading` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `file_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_cms_module`
--

INSERT INTO `talentrek_cms_module` (`id`, `section`, `slug`, `heading`, `description`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 'Mobile Banner', 'banner', 'Your Journey to Grow & Succeed Starts Here.', 'Improve your skills & engage with certified professional / industry leaders - anytime, anywhere.', 'Banner.png', 'https://talentrek.reviewdevelopment.net/asset/images/banner/Banner.png', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(2, 'Web Banner', 'web_banner', 'Improve your skills & engage with certified professional / industry leaders - anytime, anywhere.', '<div class=\"container mx-auto px-6 md:px-12 py-64 flex items-center\">\r\n                                    <div class=\"w-full md:w-1/2 text-white space-y-6\">\r\n                                    <h1 class=\"text-3xl md:text-5xl font-bold leading-tight text-white\">\r\n                                         Your Journey to <br />\r\n                                        <span class=\"text-white\">Grow & Succeed Starts Here</span>\r\n                                    </h1>\r\n                                    <p class=\"text-base text-gray-100 max-w-md\">\r\n                                       Improve your skills & engage with certified professional / industry leaders - anytime, anywhere\r\n                                    </p>\r\n                                    <button class=\"bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded text-sm\">\r\n                                        Sign In / Sign Up\r\n                                    </button>\r\n                                    </div>\r\n                                </div>', 'Banner.png', 'https://talentrek.reviewdevelopment.net/asset/images/banner/Banner.png', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, 'About Talentrek', 'join-talentrek', 'Join Talentrek as a Trainer, Mentor, Assessor, and Coach', ' <div class=\"lg:w-1/2\">\r\n                                        <h2 class=\"text-3xl md:text-4xl font-bold leading-snug\">\r\n                                        Join <span class=\"text-blue-600\">Talentrek</span><br />\r\n                                        as a Trainer, Mentor, Assessor, and Coach\r\n                                        </h2>\r\n                                        <p class=\"text-gray-700 mt-4 mb-6\">\r\n                                            Share your expertise, guide <span class=\"text-black font-bold\">jobseeker/professional</span>, and one stop-shop powerful platform.\r\n                                        </p>\r\n                                        <div class=\"grid grid-cols-1 sm:grid-cols-2 gap-4\">\r\n                                            <div class=\"flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600\">\r\n                                                <span class=\"inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold\">✓</span>\r\n                                                Empower Learners\r\n                                            </div>\r\n                                            <div class=\"flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600\">\r\n                                                <span class=\"inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold\">✓</span>\r\n                                                Earn & Grow\r\n                                            </div>\r\n                                            <div class=\"flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600\">\r\n                                                <span class=\"inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold\">✓</span>\r\n                                                    Flexible Engagement\r\n                                            </div>\r\n                                            <div class=\"flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600\">\r\n                                                <span class=\"inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold\">✓</span>\r\n                                                Expand Your Reach\r\n                                            </div>\r\n                                        </div>\r\n\r\n\r\n                                        <!-- CTA Button -->\r\n                                        <a href=\"#\" class=\"mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition\">\r\n                                        Join Talentrek\r\n                                        </a>\r\n                                    </div>', 'teams.png', 'https://talentrek.reviewdevelopment.net/asset/images/gallery/teams.png', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 'Countings', 'countings', NULL, '<div class=\"row text-center\">\r\n                                    <div class=\"col-md-4 mb-4 mb-md-0\">\r\n                                        <div class=\"stats-number\">35000+</div>\r\n                                        <div class=\"stats-description\">Student worldwide</div>\r\n                                    </div>\r\n                                    <div class=\"col-md-4 mb-4 mb-md-0\">\r\n                                        <div class=\"stats-number\">500+</div>\r\n                                        <div class=\"stats-description\">Course available</div>\r\n                                    </div>\r\n                                    <div class=\"col-md-4\">\r\n                                        <div class=\"stats-number\">10000+</div>\r\n                                        <div class=\"stats-description\">People loved it</div>\r\n                                    </div>\r\n                                </div>', NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 'Training Page(Course Overview)', 'course-overview', 'Course Overview', 'Unlock your creative potential with our Graphic Design course tailored for beginners and aspiring designers. Learn the fundamentals of design theory, master industry-standard tools like Adobe Photoshop, Illustrator, and Figma, and gain the confidence to create visually compelling designs for digital and print media. Whether you\'re starting fresh or leveling up, this course sets you on a professional design path.', NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(6, 'Training Page(Benefits of training)', 'benefits-of-training', 'Benefits of training', 'Enhance Creativity – Develop your artistic skills and turn ideas into visually compelling designs.\r\n                                Master Industry Tools – Learn Photoshop, Illustrator, Figma, and other top design software used by professionals.\r\n                                Career Opportunities – Open doors to roles like UI/UX Designer, Branding Expert, and Digital Illustrator.\r\n                                Freelance & Business Growth – Work independently, start your own design agency, or take on freelance projects.\r\n                                Effective Visual Communication – Learn how to design impactful branding, marketing materials, and user interfaces.\r\n                                High Demand & Competitive Salaries – Graphic designers are always needed in advertising, tech, and digital media industries.\r\n                                Build a Strong Portfolio – Work on real-world projects that showcase your skills and help you land jobs or clients.', NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(7, 'Mentorship Page(Mentorship overview)', 'mentorship-overview', 'Mentorship overview', 'As a mentor, you play a pivotal role in shaping the future of your mentees\' careers. Your expertise, guidance, and support can have a profound impact on their professional growth and success.', NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(8, 'Mentorship Page(Benefits of mentorship)', 'benefits-of-mentorship', 'Benefits of mentorship', 'lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptas?', NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_coaches`
--

CREATE TABLE `talentrek_coaches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `pin_code` varchar(191) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `shortlist` varchar(191) DEFAULT NULL,
  `admin_recruiter_status` varchar(191) DEFAULT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `about_coach` text DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_coaches`
--

INSERT INTO `talentrek_coaches` (`id`, `name`, `email`, `national_id`, `phone_code`, `phone_number`, `date_of_birth`, `city`, `state`, `address`, `pin_code`, `country`, `password`, `pass`, `otp`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `shortlist`, `admin_recruiter_status`, `google_id`, `avatar`, `about_coach`, `is_registered`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john@example.com', 'A123456789', '+1', '9876543210', '1985-12-20', 'New York', NULL, NULL, NULL, NULL, '$2y$10$rgHsWBzg4C/lszUwvoG8H.6MmGCrYSZ56Vj6MFzoELiKGMrUWjzMy', 'password123', '123456', 'active', 'approved', NULL, NULL, 'yes', 'verified', 'google_1234567890', 'default.png', 'Certified life coach with 10+ years of experience.', '0', 'yes', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(2, 'Jane Smith', 'jane@example.com', 'B987654321', '+91', '9999999999', '1990-05-10', 'Mumbai', NULL, NULL, NULL, NULL, '$2y$10$MrJrk9hBVwRQY73x6ljXaOOX1CFBx5vfvChBOjqu0pgB7ay2yPva2', 'secret456', '654321', 'inactive', 'pending', 'Incomplete documents', NULL, 'no', 'pending', NULL, NULL, 'Specialist in personal development and career coaching.', '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_education_details`
--

CREATE TABLE `talentrek_education_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('jobseeker','recruiter','mentor','coach','assessor','expat','trainer') NOT NULL,
  `high_education` varchar(191) DEFAULT NULL,
  `field_of_study` varchar(191) DEFAULT NULL,
  `institution` varchar(191) DEFAULT NULL,
  `graduate_year` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_education_details`
--

INSERT INTO `talentrek_education_details` (`id`, `user_id`, `user_type`, `high_education`, `field_of_study`, `institution`, `graduate_year`, `created_at`, `updated_at`) VALUES
(1, 1, 'jobseeker', 'Bachelor of Tech', 'Engineering', 'Institution 1', '2016', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, 1, 'jobseeker', 'Master of Tech', 'Engineering', 'Institution 2', '2017', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(3, 2, 'jobseeker', 'Bachelor of Tech', 'Engineering', 'Institution 1', '2017', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 2, 'jobseeker', 'Master of Tech', 'Engineering', 'Institution 2', '2018', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 3, 'jobseeker', 'Bachelor of Tech', 'Engineering', 'Institution 1', '2018', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(6, 3, 'jobseeker', 'Master of Tech', 'Engineering', 'Institution 2', '2019', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(7, 4, 'jobseeker', 'Bachelor of Tech', 'Engineering', 'Institution 1', '2019', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(8, 4, 'jobseeker', 'Master of Tech', 'Engineering', 'Institution 2', '2020', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(9, 5, 'jobseeker', 'Bachelor of Tech', 'Engineering', 'Institution 1', '2020', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(10, 5, 'jobseeker', 'Master of Tech', 'Engineering', 'Institution 2', '2021', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(11, 1, 'jobseeker', 'Master\'s', 'Psychology', 'IIT Bombay', '2020', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(12, 2, 'jobseeker', 'Ph.D.', 'Civil Engineering', 'BITS Pilani', '2016', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(13, 3, 'jobseeker', 'Master\'s', 'Computer Science', 'Delhi University', '2023', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(14, 4, 'jobseeker', 'Bachelor\'s', 'Civil Engineering', 'TISS Mumbai', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(15, 5, 'jobseeker', 'Diploma', 'Psychology', 'TISS Mumbai', '2018', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(16, 6, 'recruiter', 'Bachelor\'s', 'Business Administration', 'JNU Delhi', '2017', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(17, 7, 'recruiter', 'High School', 'Business Administration', 'Delhi University', '2017', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(18, 8, 'recruiter', 'Master\'s', 'Business Administration', 'Delhi University', '2018', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(19, 9, 'recruiter', 'Diploma', 'Business Administration', 'JNU Delhi', '2017', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(20, 10, 'recruiter', 'High School', 'Psychology', 'TISS Mumbai', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(21, 11, 'mentor', 'Bachelor\'s', 'Computer Science', 'JNU Delhi', '2023', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(22, 12, 'mentor', 'Diploma', 'Computer Science', 'Delhi University', '2012', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(23, 13, 'mentor', 'Bachelor\'s', 'Business Administration', 'BITS Pilani', '2011', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(24, 14, 'mentor', 'High School', 'Psychology', 'JNU Delhi', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(25, 15, 'mentor', 'Master\'s', 'Business Administration', 'TISS Mumbai', '2015', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(26, 16, 'coach', 'High School', 'Psychology', 'BITS Pilani', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(27, 17, 'coach', 'High School', 'Mechanical Engineering', 'Delhi University', '2023', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(28, 18, 'coach', 'Bachelor\'s', 'Psychology', 'IIT Bombay', '2015', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(29, 19, 'coach', 'Bachelor\'s', 'Psychology', 'Delhi University', '2020', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(30, 20, 'coach', 'Master\'s', 'Civil Engineering', 'Delhi University', '2015', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(31, 21, 'assessor', 'Master\'s', 'Civil Engineering', 'IIT Bombay', '2017', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(32, 22, 'assessor', 'High School', 'Mechanical Engineering', 'IIT Bombay', '2012', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(33, 23, 'assessor', 'High School', 'Computer Science', 'JNU Delhi', '2015', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(34, 24, 'assessor', 'Ph.D.', 'Psychology', 'Delhi University', '2015', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(35, 25, 'assessor', 'Ph.D.', 'Business Administration', 'JNU Delhi', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(36, 26, 'expat', 'High School', 'Psychology', 'IIT Bombay', '2014', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(37, 27, 'expat', 'Ph.D.', 'Business Administration', 'JNU Delhi', '2010', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(38, 28, 'expat', 'Master\'s', 'Computer Science', 'IIT Bombay', '2013', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(39, 29, 'expat', 'Ph.D.', 'Civil Engineering', 'BITS Pilani', '2011', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(40, 30, 'expat', 'Master\'s', 'Mechanical Engineering', 'BITS Pilani', '2016', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(41, 31, 'trainer', 'Bachelor\'s', 'Mechanical Engineering', 'JNU Delhi', '2023', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(42, 32, 'trainer', 'High School', 'Mechanical Engineering', 'Delhi University', '2019', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(43, 33, 'trainer', 'Bachelor\'s', 'Business Administration', 'IIT Bombay', '2012', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(44, 34, 'trainer', 'Diploma', 'Civil Engineering', 'IIT Bombay', '2017', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(45, 35, 'trainer', 'High School', 'Mechanical Engineering', 'BITS Pilani', '2023', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseekers`
--

CREATE TABLE `talentrek_jobseekers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assigned_admin` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `pin_code` varchar(191) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'jobseeker',
  `otp` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `shortlist` text DEFAULT NULL,
  `admin_recruiter_status` text DEFAULT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'yes',
  `zoom_access_token` text DEFAULT NULL,
  `zoom_refresh_token` text DEFAULT NULL,
  `zoom_token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_jobseekers`
--

INSERT INTO `talentrek_jobseekers` (`id`, `assigned_admin`, `name`, `email`, `national_id`, `gender`, `phone_code`, `phone_number`, `date_of_birth`, `city`, `state`, `address`, `pin_code`, `country`, `password`, `pass`, `role`, `otp`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `shortlist`, `admin_recruiter_status`, `google_id`, `avatar`, `is_registered`, `isSubscribtionBuy`, `zoom_access_token`, `zoom_refresh_token`, `zoom_token_expires_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'John Doe', 'john@gmail.com', NULL, 'Male', NULL, '9876543210', '2000-08-04', 'Mumbai', NULL, 'Address for John Doe', NULL, NULL, '$2y$10$Vc.pHJqrZw5wJ2zp1pMWbeHHJKnJzIZkXiOJ0Joo1Kqgu3cSvEinu', 'Password@1', 'jobseeker', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'yes', NULL, NULL, NULL, '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, NULL, 'Priya Sharma', 'priya@gmail.com', NULL, 'Female', NULL, '9876543211', '1999-08-04', 'Delhi', NULL, 'Address for Priya Sharma', NULL, NULL, '$2y$10$/y/CE2/ccKyg6bXCQcy6OuRIN3g3hDloGBSCAhaL9FkRx5zp.GpjC', 'Password@2', 'jobseeker', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'yes', NULL, NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, NULL, 'Arjun Mehta', 'arjun@gmail.com', NULL, 'Male', NULL, '9876543212', '1998-08-04', 'Bangalore', NULL, 'Address for Arjun Mehta', NULL, NULL, '$2y$10$I91ujk2Vvyn/zE8.i5Qca.i.h2AYUG7JvFes4pJXMEYMqntlBHacu', 'Password@3', 'jobseeker', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'yes', NULL, NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, NULL, 'Sneha Rao', 'sneha@gmail.com', NULL, 'Female', NULL, '9876543213', '1997-08-04', 'Hyderabad', NULL, 'Address for Sneha Rao', NULL, NULL, '$2y$10$a6IHWj9vBlx7PkJV8IJD/e5Qfii32WdwQWFzGBj6cC3VVmCFMKCAW', 'Password@4', 'jobseeker', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'yes', NULL, NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, NULL, 'Ravi Kumar', 'ravi@gmail.com', NULL, 'Male', NULL, '9876543214', '1996-08-04', 'Chennai', NULL, 'Address for Ravi Kumar', NULL, NULL, '$2y$10$rNgYjy0WoLdRNJo.FyOSkOYHTynNLUhUVveV2FEjla54QKg75pWcm', 'Password@5', 'jobseeker', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'yes', NULL, NULL, NULL, '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseeker_assessment_data`
--

CREATE TABLE `talentrek_jobseeker_assessment_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `selected_answer` varchar(191) NOT NULL,
  `correct_answer` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseeker_assessment_status`
--

CREATE TABLE `talentrek_jobseeker_assessment_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_id` bigint(20) UNSIGNED NOT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseeker_cart_items`
--

CREATE TABLE `talentrek_jobseeker_cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_jobseeker_cart_items`
--

INSERT INTO `talentrek_jobseeker_cart_items` (`id`, `jobseeker_id`, `trainer_id`, `material_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'pending', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(2, 2, 1, 2, 'pending', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(3, 3, 2, 3, 'purchased', '2025-08-04 04:10:12', '2025-08-04 04:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseeker_saved_booking_session`
--

CREATE TABLE `talentrek_jobseeker_saved_booking_session` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('mentor','coach','assessor') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_slot_id` bigint(20) UNSIGNED NOT NULL,
  `slot_mode` varchar(191) DEFAULT NULL,
  `slot_date` date NOT NULL,
  `slot_time` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `admin_status` varchar(191) DEFAULT NULL,
  `is_postpone` tinyint(1) NOT NULL DEFAULT 0,
  `slot_date_after_postpone` date DEFAULT NULL,
  `slot_time_after_postpone` varchar(191) DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `rescheduled_at` timestamp NULL DEFAULT NULL,
  `zoom_meeting_id` varchar(191) DEFAULT NULL,
  `zoom_join_url` text DEFAULT NULL,
  `zoom_start_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_jobseeker_saved_booking_session`
--

INSERT INTO `talentrek_jobseeker_saved_booking_session` (`id`, `jobseeker_id`, `user_type`, `user_id`, `booking_slot_id`, `slot_mode`, `slot_date`, `slot_time`, `status`, `admin_status`, `is_postpone`, `slot_date_after_postpone`, `slot_time_after_postpone`, `cancellation_reason`, `cancelled_at`, `rescheduled_at`, `zoom_meeting_id`, `zoom_join_url`, `zoom_start_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'mentor', 1, 1, NULL, '2025-08-06', '10:00:00 - 12:00:00', 'confirmed', 'approved', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(2, 2, 'coach', 1, 2, NULL, '2025-08-07', '14:30:00 - 16:30:00', 'pending', 'pending', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(3, 3, 'assessor', 4, 3, NULL, '2025-08-05', '16:00:00 - 18:00:00', 'cancelled', 'rejected', 1, '2025-08-08', '17:00:00 - 19:00:00', 'Rescheduled due to conflict', NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_jobseeker_training_material_purchases`
--

CREATE TABLE `talentrek_jobseeker_training_material_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `training_type` enum('online','classroom','recorded') DEFAULT NULL,
  `session_type` enum('online','classroom') DEFAULT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_for` enum('individual','team') NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batchStatus` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_jobseeker_training_material_purchases`
--

INSERT INTO `talentrek_jobseeker_training_material_purchases` (`id`, `jobseeker_id`, `trainer_id`, `material_id`, `training_type`, `session_type`, `batch_id`, `purchase_for`, `payment_id`, `batchStatus`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'online', 'online', 1, 'individual', 1, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(2, 2, 2, 2, 'recorded', NULL, NULL, 'team', 2, NULL, '2025-08-04 04:10:12', '2025-08-04 04:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_languages`
--

CREATE TABLE `talentrek_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `english` varchar(191) NOT NULL,
  `arabic` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_languages`
--

INSERT INTO `talentrek_languages` (`id`, `code`, `english`, `arabic`, `created_at`, `updated_at`) VALUES
(1, 'name', 'Name', 'الاسم', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 'email', 'Email', 'البريد الإلكتروني', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 'phone', 'Phone', 'رقم الهاتف', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 'address', 'Address', 'العنوان', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 'city', 'City', 'المدينة', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 'country', 'Country', 'الدولة', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 'dob', 'Date of Birth', 'تاريخ الميلاد', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 'gender', 'Gender', 'الجنس', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 'password', 'Password', 'كلمة المرور', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 'confirm_password', 'Confirm Password', 'تأكيد كلمة المرور', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_mentors`
--

CREATE TABLE `talentrek_mentors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `pin_code` varchar(191) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `shortlist` varchar(191) DEFAULT NULL,
  `admin_recruiter_status` varchar(191) DEFAULT NULL,
  `google_id` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `about_mentor` text DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_mentors`
--

INSERT INTO `talentrek_mentors` (`id`, `name`, `email`, `national_id`, `phone_code`, `phone_number`, `date_of_birth`, `city`, `state`, `address`, `pin_code`, `country`, `password`, `pass`, `otp`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `shortlist`, `admin_recruiter_status`, `google_id`, `avatar`, `about_mentor`, `is_registered`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 'Anjali Verma', 'anjali@example.com', 'XYZ987654', '+1', '5551234567', '1988-11-23', 'New York', NULL, NULL, NULL, NULL, '$2y$10$Anb1.uzSBo60XEPjy0akcuxzI1LseEG1JN/C5dv2Go.9yDXUfIjfW', 'securepass', '654321', 'active', NULL, 'Profile incomplete', NULL, NULL, NULL, 'ea2088ae-e56f-40db-9d22-ba20bf596205', 'avatar2.png', NULL, '0', 'no', '2025-08-04 04:10:10', '2025-08-04 04:10:10'),
(2, 'Ravi Sharma', 'ravi@example.com', 'ABC123456', '+91', '9876543210', '1990-04-10', 'Delhi', NULL, NULL, NULL, NULL, '$2y$10$1PhOZw8gL.R5RScGEICarecEkv/ZTHI01OIiOI36BB1DAda.IhV/e', 'pass123', '123456', 'pending', 'under_review', NULL, NULL, NULL, NULL, '3aedcc97-271d-4b8d-a519-8b700c5cebc3', 'avatar3.png', NULL, '0', 'no', '2025-08-04 04:10:10', '2025-08-04 04:10:10'),
(3, 'Meera Iyer', 'meera@example.com', 'DEF234567', '+44', '7700123456', '1985-02-15', 'London', NULL, NULL, NULL, NULL, '$2y$10$s1LPzzxqsznIpBcPycBvIebtmTnixx/CRlpIPSr5EtpoIGpNQV.oq', 'meera@2025', '111111', 'active', NULL, NULL, NULL, NULL, 'approved', '5e2af65c-b024-495a-98bb-d0f012fa56be', 'avatar4.png', NULL, '0', 'yes', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 'Ali Khan', 'ali.khan@example.com', 'GHI345678', '+971', '501234567', '1992-09-21', 'Dubai', NULL, NULL, NULL, NULL, '$2y$10$xVJjvSqlO54N5Js/jMZcU.GK2qdUldemcK9b4vfy6PDBwamH1oreK', 'aliSecure', '222222', 'inactive', 'rejected', 'Document missing', 'Invalid ID proof', NULL, NULL, '39f9940e-e93c-4bbe-9bb0-98c07c8ee3fc', 'avatar5.png', NULL, '0', 'no', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 'Sophia Lewis', 'sophia@example.com', 'JKL456789', '+1', '4045557890', '1995-12-03', 'Los Angeles', NULL, NULL, NULL, NULL, '$2y$10$KaD9I8mM4CW6WYXcwRTgiOUQjNq0j0.FkdHL8/9eMwLnYAvSRvyhG', 'sophiaPass', '333333', 'active', NULL, NULL, NULL, 'yes', NULL, '0a6cb846-6771-4e6e-9a5b-30c45a94086c', 'avatar6.png', NULL, '0', 'yes', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 'Rahul Patil', 'rahul.p@example.com', 'MNO567890', '+91', '7894561230', '1983-06-30', 'Pune', NULL, NULL, NULL, NULL, '$2y$10$G/Afk79ZqPb07qOnDGB/euDXe8ypo7OkBNyoxYF1MdCh3Z/8xo9oK', 'rahulPass', '444444', 'active', NULL, NULL, NULL, 'yes', 'approved', 'ca82991a-1cf8-4719-a593-64ada80ed29d', 'avatar7.png', NULL, '0', 'yes', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 'Lina Wang', 'lina@example.com', 'PQR678901', '+86', '13800138000', '1987-08-18', 'Beijing', NULL, NULL, NULL, NULL, '$2y$10$8mqfCtrYE9gbxPqbdTYmoOSKVgNguxfWNM3K5k9ak.uuQquOufMyG', 'lina123', '555555', 'pending', NULL, NULL, NULL, NULL, NULL, '6c0f5516-b1bd-49e8-9e6e-5d266ae0db24', 'avatar8.png', NULL, '0', 'no', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 'David Green', 'david.g@example.com', 'STU789012', '+61', '412345678', '1979-03-25', 'Sydney', NULL, NULL, NULL, NULL, '$2y$10$hWrr.WH0VdsGlxG9SgdYium2eDbNW3.Hq75LSIzEZ44lRQBE5.gKi', 'davidSecure', '666666', 'inactive', 'reviewed', 'Inactive too long', NULL, NULL, NULL, 'a2c1436a-6822-464b-864d-4159789e563c', 'avatar9.png', NULL, '0', 'no', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 'Fatima Noor', 'fatima.noor@example.com', 'VWX890123', '+92', '3219876543', '1994-07-09', 'Lahore', NULL, NULL, NULL, NULL, '$2y$10$X4PQ7.gk383ydZnbAZFEB.urzTdZej4.WyBfEo2bCT57Cnv.2MABG', 'fatima2025', '777777', 'active', NULL, NULL, NULL, NULL, 'approved', '8205918c-44b6-4fc5-8601-9628d5f9dc1b', 'avatar10.png', NULL, '0', 'yes', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_migrations`
--

CREATE TABLE `talentrek_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_migrations`
--

INSERT INTO `talentrek_migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_06_20_093558_create_admins_table', 1),
(3, '2025_06_20_093721_create_jobseekers_table', 1),
(4, '2025_06_20_095027_create_education_details_table', 1),
(5, '2025_06_20_095220_create_work_experience_table', 1),
(6, '2025_06_20_095351_create_skills_table', 1),
(7, '2025_06_20_095515_create_additional_info_table', 1),
(8, '2025_06_20_104816_create_recruiters_table', 1),
(9, '2025_06_20_105000_create_recruiters_company_table', 1),
(10, '2025_06_20_110202_create_trainers_table', 1),
(11, '2025_06_20_110323_create_training_experience_table', 1),
(12, '2025_06_20_111425_create_coaches_table', 1),
(13, '2025_06_20_111553_create_mentors_table', 1),
(14, '2025_06_20_111853_create_assessors_table', 1),
(15, '2025_06_20_121333_create_training_materials_table', 1),
(16, '2025_06_20_121759_create_training_materials_documents_table', 1),
(17, '2025_06_20_122028_create_training_batches_table', 1),
(18, '2025_07_02_183842_create_testimonials_table', 1),
(19, '2025_07_02_184005_create_newsletter_table', 1),
(20, '2025_07_02_184057_create_social_media_table', 1),
(21, '2025_07_02_184237_create_site_settings_table', 1),
(22, '2025_07_02_184533_create_subscriptions_table', 1),
(23, '2025_07_03_110542_create_cms_module_table', 1),
(24, '2025_07_04_103541_create_recruiter_jobseeker_shortlist_table', 1),
(25, '2025_07_05_145058_create_certificate_template_table', 1),
(26, '2025_07_05_154308_create_admin_permissions_table', 1),
(27, '2025_07_06_124722_create_trainer_assessments_table', 1),
(28, '2025_07_06_125156_create_assessment_questions_table', 1),
(29, '2025_07_06_125418_create_assessment_options_table', 1),
(30, '2025_07_06_161327_create_languages_table', 1),
(31, '2025_07_08_181257_create_resume_format_table', 1),
(32, '2025_07_09_124855_create_jobseeker_assessment_data_table', 1),
(33, '2025_07_09_143559_create_training_categories_table', 1),
(34, '2025_07_09_164430_create_reviews_table', 1),
(35, '2025_07_10_075222_create_booking_slots_table', 1),
(36, '2025_07_11_083212_create_payments_history_table', 1),
(37, '2025_07_12_161452_create_jobseeker_saved_booking_session_table', 1),
(38, '2025_07_15_132157_create_booking_slots_unavailable_dates_table', 1),
(39, '2025_07_17_114453_create_jobseeker_training_material_purchases_table', 1),
(40, '2025_07_20_134200_create_jobseeker_cart_items_table', 1),
(41, '2025_07_22_165604_create_jobseeker_assessment_status_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_newsletter`
--

CREATE TABLE `talentrek_newsletter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_payments_history`
--

CREATE TABLE `talentrek_payments_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `payment_reference` varchar(191) NOT NULL,
  `transaction_id` varchar(191) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(191) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_payments_history`
--

INSERT INTO `talentrek_payments_history` (`id`, `jobseeker_id`, `material_id`, `payment_reference`, `transaction_id`, `amount_paid`, `payment_status`, `payment_method`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 'S0AVW05AUS', '9QIOCSPOET', 299.00, 'completed', 'UPI', '2025-07-21 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(2, 2, 3, 'T3RKHZE8WY', 'VOHYNS8RYF', 399.50, 'failed', 'UPI', '2025-08-02 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(3, 1, 8, 'DEQJINNPF1', 'PUOM3QVPIB', 899.99, 'failed', 'Card', '2025-07-19 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(4, 4, 3, 'SEFOTLF63I', 'MHEMK06LFI', 399.50, 'failed', 'Card', '2025-07-08 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(5, 2, 4, 'FWDYISFDGE', '95S4UUOVQS', 599.00, 'pending', 'Card', '2025-07-28 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(6, 3, 3, '3UKQGLNBTQ', '1VVNF3HGMR', 399.50, 'failed', 'UPI', '2025-07-17 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(7, 4, 3, 'OGQFVTQ8HR', '6OS6OTAAWR', 399.50, 'completed', 'Card', '2025-07-12 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(8, 5, 9, 'URIRFRXRX4', 'QU8QOPTPZO', 499.00, 'pending', 'Card', '2025-07-25 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(9, 2, 4, 'OBUG5CIPJ2', 'CK68YTYKPH', 599.00, 'refunded', 'Card', '2025-07-26 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12'),
(10, 4, 2, '10AO6P4PJR', 'MHUN86UGJD', 299.00, 'pending', 'Card', '2025-07-27 09:40:12', '2025-08-04 04:10:12', '2025-08-04 04:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_personal_access_tokens`
--

CREATE TABLE `talentrek_personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(100) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_recruiters`
--

CREATE TABLE `talentrek_recruiters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `inactive_reason` varchar(191) DEFAULT NULL,
  `admin_status` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_recruiters`
--

INSERT INTO `talentrek_recruiters` (`id`, `name`, `email`, `national_id`, `status`, `inactive_reason`, `admin_status`, `rejection_reason`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 'Rahul Mehta', 'rahul@techsolutions.com', NULL, 'active', NULL, NULL, NULL, 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(2, 'Sneha Patil', 'sneha@fincorp.com', NULL, 'inactive', NULL, NULL, NULL, 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, 'Ankit Sharma', 'ankit@edunation.com', NULL, 'active', NULL, NULL, NULL, 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 'Priya Verma', 'priya@healthbridge.com', NULL, 'active', NULL, NULL, NULL, 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 'Karan Kapoor', 'karan@greentech.com', NULL, 'inactive', NULL, NULL, NULL, 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_recruiters_company`
--

CREATE TABLE `talentrek_recruiters_company` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recruiter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(191) DEFAULT NULL,
  `company_website` varchar(191) DEFAULT NULL,
  `company_city` varchar(191) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `business_email` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `company_phone_number` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `no_of_employee` varchar(191) DEFAULT NULL,
  `industry_type` varchar(191) DEFAULT NULL,
  `registration_number` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_recruiters_company`
--

INSERT INTO `talentrek_recruiters_company` (`id`, `recruiter_id`, `company_name`, `company_website`, `company_city`, `company_address`, `business_email`, `phone_code`, `company_phone_number`, `password`, `pass`, `otp`, `no_of_employee`, `industry_type`, `registration_number`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `is_registered`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 1, 'TechSolutions Pvt Ltd', 'https://techsolutions.com', 'Bangalore', '123, Tech Park', 'contact@techsolutions.com', '+91', '9876543210', '$2y$10$AhA.8BY8j6AgPRo3W0svwupyVKupNTUcbge0gMksaWYOr.np2rfTS', 'pass123', 123456, '51-200', 'IT', 'TS001', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(2, 2, 'FinCorp India', 'https://fincorp.com', 'Mumbai', '45, Finance Street', 'support@fincorp.com', '+91', '9123456789', '$2y$10$pCqzcgjDHWwDQpjmvcnm3O639hBvPNKmPJ8bzU8hEC8beIPfwYR6.', 'pass123', 234567, '201-500', 'Finance', 'FC002', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, 3, 'EduNation Ltd', 'https://edunation.com', 'Delhi', '78, Knowledge Park', 'info@edunation.com', '+91', '9988776655', '$2y$10$uJBup0M2XMq8X3eGO.Ip8.qkgNcMGbRtUP1XiAvTbCsa8BjFEeFN.', 'pass123', 345678, '11-50', 'Education', 'EN003', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 4, 'HealthBridge Pvt Ltd', 'https://healthbridge.com', 'Hyderabad', '67, Wellness Lane', 'care@healthbridge.com', '+91', '9090909090', '$2y$10$k69IzefQAoE7Z1vEtMbv0.XQKfr2Owpd5KfnxvaXrYUegY2MM7ULa', 'pass123', 456789, '100+', 'Healthcare', 'HB004', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 5, 'GreenTech Innovations', 'https://greentech.com', 'Pune', '22, Eco Street', 'hello@greentech.com', '+91', '8080808080', '$2y$10$ceFWIS9jkOgEwlisxcqI.OJerm6K5MNUI9MqaSA1FsN7vtJ7XM8Mu', 'pass123', 567890, '1-10', 'Environment', 'GT005', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_recruiter_jobseeker_shortlist`
--

CREATE TABLE `talentrek_recruiter_jobseeker_shortlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recruiter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jobseeker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `interview_request` varchar(191) DEFAULT NULL,
  `admin_status` varchar(191) DEFAULT NULL,
  `rejection_reason` varchar(191) DEFAULT NULL,
  `interview_url` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_resumes_format`
--

CREATE TABLE `talentrek_resumes_format` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resume` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_resumes_format`
--

INSERT INTO `talentrek_resumes_format` (`id`, `resume`, `created_at`, `updated_at`) VALUES
(1, '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n<meta charset=\"UTF-8\" />\r\n<title>Resume - Prajwal Ingole</title>\r\n<style>\r\nbody {\r\nfont-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;\r\nline-height: 1.6;\r\nmargin: 0;\r\npadding: 0;\r\nbackground-color: #f4f4f4;\r\ncolor: #333;\r\n}\r\n.container {\r\nwidth: 80%;\r\nmax-width: 900px;\r\nmargin: 30px auto;\r\nbackground: #fff;\r\npadding: 30px;\r\nbox-shadow: 0 0 10px rgba(0,0,0,0.1);\r\n}\r\n.header {\r\ntext-align: center;\r\nborder-bottom: 2px solid #333;\r\npadding-bottom: 15px;\r\n}\r\n.header h1 {\r\nmargin: 0;\r\nfont-size: 32px;\r\ncolor: #2c3e50;\r\n}\r\n.header p {\r\nmargin: 5px 0;\r\nfont-size: 14px;\r\ncolor: #777;\r\n}\r\n.section {\r\nmargin-top: 30px;\r\n}\r\n.section h2 {\r\ncolor: #2c3e50;\r\nmargin-bottom: 10px;\r\nborder-bottom: 1px solid #ccc;\r\npadding-bottom: 5px;\r\n}\r\n.section p, .section li {\r\nfont-size: 15px;\r\n}\r\n.section ul {\r\npadding-left: 20px;\r\n}\r\n.job-title {\r\nfont-weight: bold;\r\ncolor: #34495e;\r\n}\r\n.company {\r\nfont-style: italic;\r\ncolor: #666;\r\n}\r\n.date {\r\nfloat: right;\r\ncolor: #888;\r\n}\r\n.skills span {\r\nbackground-color: #e0e0e0;\r\npadding: 5px 10px;\r\nmargin: 5px 5px 0 0;\r\ndisplay: inline-block;\r\nborder-radius: 5px;\r\n}\r\n.clearfix::after {\r\ncontent: \"\";\r\ndisplay: table;\r\nclear: both;\r\n}\r\n</style>\r\n</head>\r\n<body>\r\n<div class=\"container\">\r\n<div class=\"header\">\r\n    <h1>Prajwal Ingole</h1>\r\n    <p>Email: prajwal@example.com | Phone: +91-9975239057 | GitHub: github.com/prajwal</p>\r\n</div>\r\n<div class=\"section\">\r\n    <h2>Professional Summary</h2>\r\n    <p>Motivated and detail-oriented web developer with 3+ years of experience building responsive websites and web apps. Proficient in front-end technologies like HTML, CSS, JavaScript and frameworks such as React and Laravel.</p>\r\n</div>\r\n<div class=\"section\">\r\n    <h2>Work Experience</h2>\r\n    <div class=\"clearfix\">\r\n    <p class=\"job-title\">Frontend Developer <span class=\"date\">Jan 2022 – Present</span></p>\r\n    <p class=\"company\">ABC Tech Pvt. Ltd., Pune</p>\r\n    </div>\r\n    <ul>\r\n    <li>Developed modern web apps using React.js and Bootstrap.</li>\r\n    <li>Collaborated with designers and back-end developers for seamless UI/UX.</li>\r\n    <li>Implemented REST APIs and handled state management.</li>\r\n    </ul>\r\n    <div class=\"clearfix\" style=\"margin-top:20px;\">\r\n    <p class=\"job-title\">Web Developer Intern <span class=\"date\">Jun 2021 – Dec 2021</span></p>\r\n    <p class=\"company\">XYZ Solutions, Nagpur</p>\r\n    </div>\r\n    <ul>\r\n    <li>Assisted in building and maintaining Laravel-based web apps.</li>\r\n    <li>Worked on UI improvements and bug fixes.</li>\r\n    </ul>\r\n</div>\r\n<div class=\"section\">\r\n    <h2>Education</h2>\r\n    <p><strong>Bachelor of Engineering (Computer Science)</strong><br />\r\n    RTM Nagpur University, 2018 – 2022</p>\r\n</div>\r\n<div class=\"section\">\r\n    <h2>Skills</h2>\r\n    <div class=\"skills\">\r\n    <span>HTML5</span>\r\n    <span>CSS3</span>\r\n    <span>JavaScript</span>\r\n    <span>React.js</span>\r\n    <span>Laravel</span>\r\n    <span>Bootstrap</span>\r\n    <span>MySQL</span>\r\n    <span>Git</span>\r\n    <span>REST API</span>\r\n    </div>\r\n</div>\r\n<div class=\"section\">\r\n<h2>Projects</h2>\r\n<ul>\r\n    <li><strong>Inventory Management System</strong>: Built a full-stack web app using React, Node.js, and MySQL for warehouse tracking.</li>\r\n    <li><strong>GoWash Laundry Platform</strong>: Created a laundry booking platform with user authentication and multi-service support using Laravel and Vue.js.</li>\r\n    <li><strong>Talentrek</strong>: Developed a complete jobseeker and recruiter platform with features like real-time chat, applicant tracking, resume uploads, and admin dashboards using Laravel, Alpine.js, and Tailwind CSS.</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</body>\r\n</html>', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_reviews`
--

CREATE TABLE `talentrek_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('trainer','mentor','coach','assessor') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reviews` text DEFAULT NULL,
  `ratings` tinyint(3) UNSIGNED DEFAULT NULL,
  `trainer_material` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_reviews`
--

INSERT INTO `talentrek_reviews` (`id`, `jobseeker_id`, `user_type`, `user_id`, `reviews`, `ratings`, `trainer_material`, `created_at`, `updated_at`) VALUES
(1, 1, 'trainer', 1, 'Very knowledgeable and supportive trainer.', 5, '1', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 2, 'mentor', 2, 'Guided me really well throughout the program.', 4, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 3, 'coach', 3, 'Helped me build confidence and prepare for interviews.', 4, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 4, 'assessor', 4, 'Fair and objective evaluation.', 5, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_site_settings`
--

CREATE TABLE `talentrek_site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `header_logo` varchar(191) DEFAULT NULL,
  `footer_logo` varchar(191) DEFAULT NULL,
  `favicon` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_site_settings`
--

INSERT INTO `talentrek_site_settings` (`id`, `header_logo`, `footer_logo`, `favicon`, `created_at`, `updated_at`) VALUES
(1, 'https://talentrek.reviewdevelopment.net/asset/images/logo.png', 'https://talentrek.reviewdevelopment.net/asset/images/logo.png', 'https://talentrek.reviewdevelopment.net/asset/images/logo.png', '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_skills`
--

CREATE TABLE `talentrek_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jobseeker_id` bigint(20) UNSIGNED NOT NULL,
  `skills` text DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `job_category` varchar(191) DEFAULT NULL,
  `website_link` varchar(191) DEFAULT NULL,
  `portfolio_link` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_skills`
--

INSERT INTO `talentrek_skills` (`id`, `jobseeker_id`, `skills`, `interest`, `job_category`, `website_link`, `portfolio_link`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laravel, React, SQL', 'Web Development, AI', 'Software Engineering', 'https://jobseeker1.dev', 'https://portfolio.jobseeker1.dev', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, 2, 'Laravel, React, SQL', 'Web Development, AI', 'Software Engineering', 'https://jobseeker2.dev', 'https://portfolio.jobseeker2.dev', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, 3, 'Laravel, React, SQL', 'Web Development, AI', 'Software Engineering', 'https://jobseeker3.dev', 'https://portfolio.jobseeker3.dev', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 4, 'Laravel, React, SQL', 'Web Development, AI', 'Software Engineering', 'https://jobseeker4.dev', 'https://portfolio.jobseeker4.dev', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 5, 'Laravel, React, SQL', 'Web Development, AI', 'Software Engineering', 'https://jobseeker5.dev', 'https://portfolio.jobseeker5.dev', '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_social_media`
--

CREATE TABLE `talentrek_social_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `media_name` varchar(191) NOT NULL,
  `icon_class` varchar(191) NOT NULL,
  `media_link` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_social_media`
--

INSERT INTO `talentrek_social_media` (`id`, `media_name`, `icon_class`, `media_link`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'fab fa-facebook-f', 'https://facebook.com', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(2, 'Twitter', 'fa-brands fa-twitter', 'https://twitter.com', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(3, 'Instagram', 'fa-brands fa-instagram', 'https://instagram.com', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 'Ticktoc', 'fa-brands fa-tiktok', 'https://ticktoc.com', '2025-08-04 04:10:08', '2025-08-04 04:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_subscriptions`
--

CREATE TABLE `talentrek_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('jobseeker','recruiter','mentor','coach','assessor','expat','trainer') NOT NULL,
  `title` varchar(191) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `duration_days` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_testimonials`
--

CREATE TABLE `talentrek_testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `designation` varchar(191) DEFAULT NULL,
  `message` text NOT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `file_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_testimonials`
--

INSERT INTO `talentrek_testimonials` (`id`, `name`, `designation`, `message`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 'Amit Sharma', 'Software Engineer', 'This platform has significantly helped me grow my career. Highly recommended!', 'amit.jpg', 'uploads/testimonials/amit.jpg', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(2, 'Samantha Lee', 'Product Manager', 'Amazing experience! The tools and mentorship were top-notch.', 'samantha.jpg', 'uploads/testimonials/samantha.jpg', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(3, 'Ravi Kumar', 'UX Designer', 'Thanks to this team, I landed my dream job!', 'ravi.jpg', 'uploads/testimonials/ravi.jpg', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(4, 'Emily Chen', 'HR Specialist', 'The support and resources here are invaluable.', 'emily.jpg', 'uploads/testimonials/emily.jpg', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(5, 'Arjun Mehta', NULL, 'Great platform for freshers and experienced professionals alike.', NULL, NULL, '2025-08-04 04:10:09', '2025-08-04 04:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_trainers`
--

CREATE TABLE `talentrek_trainers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `national_id` varchar(191) DEFAULT NULL,
  `phone_code` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `pin_code` varchar(191) DEFAULT NULL,
  `country` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `pass` varchar(191) DEFAULT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `admin_status` varchar(191) DEFAULT NULL,
  `inactive_reason` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `is_registered` varchar(191) NOT NULL DEFAULT '0',
  `isSubscribtionBuy` varchar(191) NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_trainers`
--

INSERT INTO `talentrek_trainers` (`id`, `name`, `email`, `national_id`, `phone_code`, `phone_number`, `date_of_birth`, `city`, `state`, `address`, `pin_code`, `country`, `password`, `pass`, `otp`, `status`, `admin_status`, `inactive_reason`, `rejection_reason`, `is_registered`, `isSubscribtionBuy`, `created_at`, `updated_at`) VALUES
(1, 'Amit Sharma', 'amit.sharma@example.com', NULL, '+91', '9876543210', '1990-03-15', 'Mumbai', NULL, NULL, NULL, NULL, NULL, NULL, '123456', 'active', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(2, 'Samantha Lee', 'samantha.lee@example.com', NULL, '+1', '4085557890', '1985-07-24', 'San Francisco', NULL, NULL, NULL, NULL, NULL, NULL, '654321', 'pending', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(3, 'Ravi Kumar', 'ravi.kumar@example.com', NULL, '+91', '9998887776', '1988-12-10', 'Delhi', NULL, NULL, NULL, NULL, NULL, NULL, '789456', 'inactive', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(4, 'Emily Chen', 'emily.chen@example.com', NULL, '+44', '7512345678', '1992-05-20', 'London', NULL, NULL, NULL, NULL, NULL, NULL, '321654', 'active', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09'),
(5, 'Arjun Mehta', 'arjun.mehta@example.com', NULL, '+91', '9123456789', '1995-11-02', 'Bangalore', NULL, NULL, NULL, NULL, NULL, NULL, '147258', 'active', NULL, NULL, NULL, '0', 'no', '2025-08-04 04:10:09', '2025-08-04 04:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_trainer_assessments`
--

CREATE TABLE `talentrek_trainer_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `assessment_title` varchar(191) NOT NULL,
  `assessment_description` text DEFAULT NULL,
  `assessment_level` varchar(191) DEFAULT NULL,
  `total_questions` int(10) UNSIGNED NOT NULL,
  `passing_questions` int(10) UNSIGNED NOT NULL,
  `passing_percentage` varchar(191) DEFAULT NULL,
  `material_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_trainer_assessments`
--

INSERT INTO `talentrek_trainer_assessments` (`id`, `trainer_id`, `assessment_title`, `assessment_description`, `assessment_level`, `total_questions`, `passing_questions`, `passing_percentage`, `material_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laravel Basics', 'Introductory Laravel assessment', 'Beginner', 30, 20, '66.67', 1, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 1, 'Advanced Laravel', 'Covers advanced topics like queues, events, broadcasting', 'Advanced', 40, 28, '70', 2, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 1, 'JavaScript Fundamentals', 'Covers ES6 basics, functions, and control flow', 'Beginner', 25, 15, '60', 3, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 1, 'ReactJS Basics', 'Test your knowledge of React components and hooks', 'Intermediate', 35, 21, '60', 4, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 1, 'Database & SQL', 'Covers queries, joins, indexing, and normalization', 'Intermediate', 30, 21, '70', 5, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 1, 'API Development with Laravel', 'RESTful APIs, Resource Controllers, Sanctum', 'Advanced', 30, 24, '80', 6, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 1, 'HTML & CSS Essentials', 'Test basic understanding of web design and styling', 'Beginner', 20, 14, '70', 7, '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_training_batches`
--

CREATE TABLE `talentrek_training_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `training_material_id` bigint(20) UNSIGNED NOT NULL,
  `batch_no` varchar(191) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_timing` time DEFAULT NULL,
  `end_timing` time DEFAULT NULL,
  `duration` varchar(191) DEFAULT NULL,
  `strength` int(11) DEFAULT NULL,
  `days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`days`)),
  `location` varchar(191) DEFAULT NULL,
  `course_url` varchar(191) DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `zoom_start_url` text DEFAULT NULL,
  `zoom_join_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_training_batches`
--

INSERT INTO `talentrek_training_batches` (`id`, `trainer_id`, `training_material_id`, `batch_no`, `start_date`, `end_date`, `start_timing`, `end_timing`, `duration`, `strength`, `days`, `location`, `course_url`, `status`, `zoom_start_url`, `zoom_join_url`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'BATCH-001', '2025-07-10', NULL, '10:00:00', '12:00:00', '2 hours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 1, 1, 'BATCH-002', '2025-07-15', NULL, '14:00:00', '16:00:00', '2 hours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 1, 1, 'BATCH-003', '2025-07-20', NULL, '09:00:00', '11:00:00', '2 hours', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_training_categories`
--

CREATE TABLE `talentrek_training_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) NOT NULL,
  `image_path` varchar(191) NOT NULL,
  `image_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_training_categories`
--

INSERT INTO `talentrek_training_categories` (`id`, `category`, `image_path`, `image_name`, `created_at`, `updated_at`) VALUES
(1, 'Technical Skills', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 'Soft Skills', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 'Leadership', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 'Communication', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 'Time Management', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 'Project Management', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 'Customer Service', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 'Sales Training', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 'Compliance Training', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 'Health & Safety', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(11, 'Other', 'uploads/training_categories/default.png', 'default.png', '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_training_experience`
--

CREATE TABLE `talentrek_training_experience` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('jobseeker','recruiter','mentor','coach','assessor','expat','trainer') NOT NULL,
  `training_experience` text DEFAULT NULL,
  `training_skills` text DEFAULT NULL,
  `area_of_interest` varchar(191) DEFAULT NULL,
  `job_category` varchar(191) DEFAULT NULL,
  `website_link` varchar(191) DEFAULT NULL,
  `portfolio_link` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_training_materials`
--

CREATE TABLE `talentrek_training_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `training_type` varchar(191) DEFAULT NULL,
  `training_title` varchar(191) DEFAULT NULL,
  `training_level` varchar(191) DEFAULT NULL,
  `training_sub_title` varchar(191) DEFAULT NULL,
  `training_descriptions` text DEFAULT NULL,
  `training_category` varchar(191) DEFAULT NULL,
  `training_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `training_offer_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `thumbnail_file_path` varchar(191) DEFAULT NULL,
  `thumbnail_file_name` varchar(191) DEFAULT NULL,
  `total_duration` varchar(191) DEFAULT NULL,
  `training_objective` text DEFAULT NULL,
  `session_type` varchar(191) DEFAULT NULL,
  `admin_status` varchar(191) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_training_materials`
--

INSERT INTO `talentrek_training_materials` (`id`, `trainer_id`, `training_type`, `training_title`, `training_level`, `training_sub_title`, `training_descriptions`, `training_category`, `training_price`, `training_offer_price`, `thumbnail_file_path`, `thumbnail_file_name`, `total_duration`, `training_objective`, `session_type`, `admin_status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'online', 'Full Stack Web Development', NULL, 'HTML, CSS, JavaScript, PHP, and Laravel', '- Learn HTML, CSS, JavaScript, PHP, and Laravel\n- Build full-stack responsive web applications\n- Master both frontend and backend development\n- Work on real-world projects for hands-on experience', 'Technical', 799.00, 500.00, 'uploads/thumbnails/fullstack.jpg', 'fullstack.jpg', NULL, 'Build responsive full-stack apps with backend logic.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 2, 'classroom', 'Time Management Mastery', NULL, 'Boost Productivity and Efficiency', '- Identify personal and professional time wasters\n- Learn time-blocking and goal setting techniques\n- Improve productivity with tested frameworks\n- Participate in group activities and planning tools', 'Soft Skills', 299.00, 500.00, 'uploads/thumbnails/time-mgmt.jpg', 'time-mgmt.jpg', NULL, 'Learn effective time-blocking and prioritization.', 'Pre-recorded', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 3, 'recorded', 'Excel for Business Analytics', NULL, 'Excel + Pivot Tables + Dashboarding', '- Master Excel formulas and data cleaning\n- Create pivot tables and interactive dashboards\n- Use advanced functions like VLOOKUP and IF statements\n- Build financial and business reports efficiently', 'Technical', 399.50, 500.00, 'uploads/thumbnails/excel.jpg', 'excel.jpg', NULL, 'Use Excel for financial and business data insights.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 4, 'online', 'Design Thinking for Innovation', NULL, 'Solve Complex Problems Creatively', '- Learn the 5 phases: Empathize, Define, Ideate, Prototype, Test\n- Use real-world case studies to apply concepts\n- Improve creative problem-solving skills\n- Foster innovative thinking through design challenges', 'Leadership', 599.00, 500.00, 'uploads/thumbnails/design-thinking.jpg', 'design-thinking.jpg', NULL, 'Apply human-centered design to real-world issues.', 'Pre-recorded', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(5, 1, 'classroom', 'Communication for Managers', NULL, 'Lead with Clarity and Confidence', '- Improve verbal and non-verbal communication skills\n- Practice persuasive speaking and active listening\n- Learn strategies for team and stakeholder communication\n- Handle tough conversations with confidence', 'Soft Skills', 449.00, 500.00, 'uploads/thumbnails/communication.jpg', 'communication.jpg', NULL, 'Improve cross-functional and team communication.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(6, 2, 'online', 'Cybersecurity Fundamentals', NULL, 'Protect Digital Assets', '- Understand common cyber threats and attack types\n- Learn data protection and encryption basics\n- Explore firewalls, VPNs, and antivirus solutions\n- Build personal and organizational cybersecurity awareness', 'Technical', 699.00, 500.00, 'uploads/thumbnails/cybersecurity.jpg', 'cybersecurity.jpg', NULL, 'Understand and mitigate digital threats.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(7, 3, 'recorded', 'Creative Writing for Beginners', NULL, 'Unlock Your Imagination', '- Discover storytelling fundamentals and structure\n- Develop characters, dialogue, and plotlines\n- Practice writing short stories with guided prompts\n- Edit and refine your first creative writing piece', 'Soft Skills', 250.00, 500.00, 'uploads/thumbnails/creative-writing.jpg', 'creative-writing.jpg', NULL, 'Build your first short story with confidence.', 'Pre-recorded', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(8, 4, 'classroom', 'Agile & Scrum Certification', NULL, 'Become a Certified Scrum Master', '- Learn Agile principles and Scrum methodology\n- Understand sprint planning, reviews, and retrospectives\n- Prepare for Scrum Master certification\n- Use tools like Jira to manage Agile projects', 'Technical', 899.99, 500.00, 'uploads/thumbnails/agile.jpg', 'agile.jpg', NULL, 'Run effective sprints and deliver value.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(9, 5, 'online', 'Digital Marketing Strategy', NULL, 'SEO, SEM, Social Media', '- Master SEO, SEM, and social media marketing\n- Build and manage effective online ad campaigns\n- Use analytics tools to track performance\n- Create digital strategies that grow business visibility', 'Marketing', 499.00, 500.00, 'uploads/thumbnails/digital-marketing.jpg', 'digital-marketing.jpg', NULL, 'Create and manage online campaigns effectively.', 'Pre-recorded', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(10, 2, 'recorded', 'Machine Learning Crash Course', NULL, 'ML Concepts & Python Code', '- Understand supervised and unsupervised learning\n- Implement ML algorithms in Python using scikit-learn\n- Evaluate model accuracy and performance\n- Build and deploy models on real-world datasets', 'Technical', 999.00, 500.00, 'uploads/thumbnails/machine-learning.jpg', 'machine-learning.jpg', NULL, 'Build and deploy ML models using Python.', 'Live', NULL, NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_training_materials_documents`
--

CREATE TABLE `talentrek_training_materials_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trainer_id` bigint(20) UNSIGNED NOT NULL,
  `training_material_id` bigint(20) UNSIGNED NOT NULL,
  `training_title` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(191) DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `file_duration` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_training_materials_documents`
--

INSERT INTO `talentrek_training_materials_documents` (`id`, `trainer_id`, `training_material_id`, `training_title`, `description`, `file_path`, `file_name`, `file_duration`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'UX Design Basics', 'Introductory video on UX principles and process.', 'videos/ux-design-basics.mp4', 'ux-design-basics.mp4', NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(2, 1, 1, 'Figma Complete Tutorial', 'Comprehensive tutorial covering key Figma features.', 'videos/figma-tutorial.mp4', 'figma-tutorial.mp4', NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(3, 2, 2, 'Advanced Prototyping', 'Learn advanced prototyping techniques with Figma.', 'videos/advanced-prototyping.mp4', 'advanced-prototyping.mp4', NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11'),
(4, 2, 2, 'Design Systems Overview', 'An overview of how to build design systems effectively.', 'videos/design-systems.mp4', 'design-systems.mp4', NULL, '2025-08-04 04:10:11', '2025-08-04 04:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `talentrek_work_experience`
--

CREATE TABLE `talentrek_work_experience` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('jobseeker','recruiter','mentor','coach','assessor','expat','trainer') NOT NULL,
  `job_role` varchar(191) DEFAULT NULL,
  `organization` varchar(191) DEFAULT NULL,
  `starts_from` date DEFAULT NULL,
  `end_to` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talentrek_work_experience`
--

INSERT INTO `talentrek_work_experience` (`id`, `user_id`, `user_type`, `job_role`, `organization`, `starts_from`, `end_to`, `created_at`, `updated_at`) VALUES
(1, 1, 'jobseeker', 'Developer', 'Company 1', '2021-08-04', '2023-08-04', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(2, 1, 'jobseeker', 'Senior Developer', 'Company 2', '2022-08-04', '2024-08-04', '2025-08-04 04:10:07', '2025-08-04 04:10:07'),
(3, 2, 'jobseeker', 'Developer', 'Company 1', '2021-08-04', '2023-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(4, 2, 'jobseeker', 'Senior Developer', 'Company 2', '2022-08-04', '2024-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(5, 3, 'jobseeker', 'Developer', 'Company 1', '2021-08-04', '2023-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(6, 3, 'jobseeker', 'Senior Developer', 'Company 2', '2022-08-04', '2024-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(7, 4, 'jobseeker', 'Developer', 'Company 1', '2021-08-04', '2023-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(8, 4, 'jobseeker', 'Senior Developer', 'Company 2', '2022-08-04', '2024-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(9, 5, 'jobseeker', 'Developer', 'Company 1', '2021-08-04', '2023-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08'),
(10, 5, 'jobseeker', 'Senior Developer', 'Company 2', '2022-08-04', '2024-08-04', '2025-08-04 04:10:08', '2025-08-04 04:10:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `talentrek_additional_info`
--
ALTER TABLE `talentrek_additional_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_additional_info_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `talentrek_admins`
--
ALTER TABLE `talentrek_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_admins_email_unique` (`email`);

--
-- Indexes for table `talentrek_admin_permissions`
--
ALTER TABLE `talentrek_admin_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_admin_permissions_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `talentrek_assessment_options`
--
ALTER TABLE `talentrek_assessment_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_assessment_options_trainer_id_foreign` (`trainer_id`),
  ADD KEY `talentrek_assessment_options_assessment_id_foreign` (`assessment_id`),
  ADD KEY `talentrek_assessment_options_question_id_foreign` (`question_id`);

--
-- Indexes for table `talentrek_assessment_questions`
--
ALTER TABLE `talentrek_assessment_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_assessment_questions_trainer_id_foreign` (`trainer_id`),
  ADD KEY `talentrek_assessment_questions_assessment_id_foreign` (`assessment_id`);

--
-- Indexes for table `talentrek_assessors`
--
ALTER TABLE `talentrek_assessors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_assessors_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_assessors_national_id_unique` (`national_id`),
  ADD UNIQUE KEY `talentrek_assessors_google_id_unique` (`google_id`);

--
-- Indexes for table `talentrek_booking_slots`
--
ALTER TABLE `talentrek_booking_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_booking_slots_unavailable_dates`
--
ALTER TABLE `talentrek_booking_slots_unavailable_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_certificate_template`
--
ALTER TABLE `talentrek_certificate_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_cms_module`
--
ALTER TABLE `talentrek_cms_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_coaches`
--
ALTER TABLE `talentrek_coaches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_coaches_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_coaches_national_id_unique` (`national_id`),
  ADD UNIQUE KEY `talentrek_coaches_google_id_unique` (`google_id`);

--
-- Indexes for table `talentrek_education_details`
--
ALTER TABLE `talentrek_education_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_education_details_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `talentrek_jobseekers`
--
ALTER TABLE `talentrek_jobseekers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_jobseekers_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_jobseekers_national_id_unique` (`national_id`),
  ADD UNIQUE KEY `talentrek_jobseekers_google_id_unique` (`google_id`);

--
-- Indexes for table `talentrek_jobseeker_assessment_data`
--
ALTER TABLE `talentrek_jobseeker_assessment_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_jobseeker_assessment_data_trainer_id_foreign` (`trainer_id`),
  ADD KEY `talentrek_jobseeker_assessment_data_training_id_foreign` (`training_id`),
  ADD KEY `talentrek_jobseeker_assessment_data_assessment_id_foreign` (`assessment_id`),
  ADD KEY `talentrek_jobseeker_assessment_data_jobseeker_id_foreign` (`jobseeker_id`),
  ADD KEY `talentrek_jobseeker_assessment_data_question_id_foreign` (`question_id`);

--
-- Indexes for table `talentrek_jobseeker_assessment_status`
--
ALTER TABLE `talentrek_jobseeker_assessment_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `js_assess_status_unique` (`jobseeker_id`,`assessment_id`);

--
-- Indexes for table `talentrek_jobseeker_cart_items`
--
ALTER TABLE `talentrek_jobseeker_cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_jobseeker_saved_booking_session`
--
ALTER TABLE `talentrek_jobseeker_saved_booking_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_jobseeker_training_material_purchases`
--
ALTER TABLE `talentrek_jobseeker_training_material_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_languages`
--
ALTER TABLE `talentrek_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_languages_code_unique` (`code`);

--
-- Indexes for table `talentrek_mentors`
--
ALTER TABLE `talentrek_mentors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_mentors_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_mentors_national_id_unique` (`national_id`),
  ADD UNIQUE KEY `talentrek_mentors_google_id_unique` (`google_id`);

--
-- Indexes for table `talentrek_migrations`
--
ALTER TABLE `talentrek_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_newsletter`
--
ALTER TABLE `talentrek_newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_newsletter_email_unique` (`email`);

--
-- Indexes for table `talentrek_payments_history`
--
ALTER TABLE `talentrek_payments_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_payments_history_payment_reference_unique` (`payment_reference`),
  ADD UNIQUE KEY `talentrek_payments_history_transaction_id_unique` (`transaction_id`),
  ADD KEY `talentrek_payments_history_jobseeker_id_foreign` (`jobseeker_id`),
  ADD KEY `talentrek_payments_history_material_id_foreign` (`material_id`);

--
-- Indexes for table `talentrek_personal_access_tokens`
--
ALTER TABLE `talentrek_personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_personal_access_tokens_token_unique` (`token`),
  ADD KEY `tokenable_type_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `talentrek_recruiters`
--
ALTER TABLE `talentrek_recruiters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_recruiters_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_recruiters_national_id_unique` (`national_id`);

--
-- Indexes for table `talentrek_recruiters_company`
--
ALTER TABLE `talentrek_recruiters_company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_recruiters_company_recruiter_id_foreign` (`recruiter_id`);

--
-- Indexes for table `talentrek_recruiter_jobseeker_shortlist`
--
ALTER TABLE `talentrek_recruiter_jobseeker_shortlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_recruiter_jobseeker_shortlist_recruiter_id_foreign` (`recruiter_id`),
  ADD KEY `talentrek_recruiter_jobseeker_shortlist_jobseeker_id_foreign` (`jobseeker_id`),
  ADD KEY `talentrek_recruiter_jobseeker_shortlist_company_id_foreign` (`company_id`);

--
-- Indexes for table `talentrek_resumes_format`
--
ALTER TABLE `talentrek_resumes_format`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_reviews`
--
ALTER TABLE `talentrek_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_reviews_user_type_user_id_index` (`user_type`,`user_id`),
  ADD KEY `talentrek_reviews_jobseeker_id_foreign` (`jobseeker_id`);

--
-- Indexes for table `talentrek_site_settings`
--
ALTER TABLE `talentrek_site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_skills`
--
ALTER TABLE `talentrek_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_skills_jobseeker_id_foreign` (`jobseeker_id`);

--
-- Indexes for table `talentrek_social_media`
--
ALTER TABLE `talentrek_social_media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_social_media_media_name_unique` (`media_name`),
  ADD UNIQUE KEY `talentrek_social_media_icon_class_unique` (`icon_class`),
  ADD UNIQUE KEY `talentrek_social_media_media_link_unique` (`media_link`);

--
-- Indexes for table `talentrek_subscriptions`
--
ALTER TABLE `talentrek_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_testimonials`
--
ALTER TABLE `talentrek_testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_trainers`
--
ALTER TABLE `talentrek_trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talentrek_trainers_email_unique` (`email`),
  ADD UNIQUE KEY `talentrek_trainers_national_id_unique` (`national_id`);

--
-- Indexes for table `talentrek_trainer_assessments`
--
ALTER TABLE `talentrek_trainer_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_trainer_assessments_trainer_id_foreign` (`trainer_id`),
  ADD KEY `talentrek_trainer_assessments_material_id_foreign` (`material_id`);

--
-- Indexes for table `talentrek_training_batches`
--
ALTER TABLE `talentrek_training_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_training_batches_trainer_id_foreign` (`trainer_id`),
  ADD KEY `talentrek_training_batches_training_material_id_foreign` (`training_material_id`);

--
-- Indexes for table `talentrek_training_categories`
--
ALTER TABLE `talentrek_training_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talentrek_training_experience`
--
ALTER TABLE `talentrek_training_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_training_experience_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `talentrek_training_materials`
--
ALTER TABLE `talentrek_training_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_training_materials_trainer_id_foreign` (`trainer_id`);

--
-- Indexes for table `talentrek_training_materials_documents`
--
ALTER TABLE `talentrek_training_materials_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trainer_id` (`trainer_id`),
  ADD KEY `fk_training_material_id` (`training_material_id`);

--
-- Indexes for table `talentrek_work_experience`
--
ALTER TABLE `talentrek_work_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talentrek_work_experience_user_id_user_type_index` (`user_id`,`user_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `talentrek_additional_info`
--
ALTER TABLE `talentrek_additional_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `talentrek_admins`
--
ALTER TABLE `talentrek_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talentrek_admin_permissions`
--
ALTER TABLE `talentrek_admin_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_assessment_options`
--
ALTER TABLE `talentrek_assessment_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `talentrek_assessment_questions`
--
ALTER TABLE `talentrek_assessment_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `talentrek_assessors`
--
ALTER TABLE `talentrek_assessors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `talentrek_booking_slots`
--
ALTER TABLE `talentrek_booking_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `talentrek_booking_slots_unavailable_dates`
--
ALTER TABLE `talentrek_booking_slots_unavailable_dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_certificate_template`
--
ALTER TABLE `talentrek_certificate_template`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `talentrek_cms_module`
--
ALTER TABLE `talentrek_cms_module`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `talentrek_coaches`
--
ALTER TABLE `talentrek_coaches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `talentrek_education_details`
--
ALTER TABLE `talentrek_education_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `talentrek_jobseekers`
--
ALTER TABLE `talentrek_jobseekers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_jobseeker_assessment_data`
--
ALTER TABLE `talentrek_jobseeker_assessment_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_jobseeker_assessment_status`
--
ALTER TABLE `talentrek_jobseeker_assessment_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_jobseeker_cart_items`
--
ALTER TABLE `talentrek_jobseeker_cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `talentrek_jobseeker_saved_booking_session`
--
ALTER TABLE `talentrek_jobseeker_saved_booking_session`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `talentrek_jobseeker_training_material_purchases`
--
ALTER TABLE `talentrek_jobseeker_training_material_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `talentrek_languages`
--
ALTER TABLE `talentrek_languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `talentrek_mentors`
--
ALTER TABLE `talentrek_mentors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `talentrek_migrations`
--
ALTER TABLE `talentrek_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `talentrek_newsletter`
--
ALTER TABLE `talentrek_newsletter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_payments_history`
--
ALTER TABLE `talentrek_payments_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `talentrek_personal_access_tokens`
--
ALTER TABLE `talentrek_personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_recruiters`
--
ALTER TABLE `talentrek_recruiters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_recruiters_company`
--
ALTER TABLE `talentrek_recruiters_company`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_recruiter_jobseeker_shortlist`
--
ALTER TABLE `talentrek_recruiter_jobseeker_shortlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_resumes_format`
--
ALTER TABLE `talentrek_resumes_format`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `talentrek_reviews`
--
ALTER TABLE `talentrek_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talentrek_site_settings`
--
ALTER TABLE `talentrek_site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `talentrek_skills`
--
ALTER TABLE `talentrek_skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_social_media`
--
ALTER TABLE `talentrek_social_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talentrek_subscriptions`
--
ALTER TABLE `talentrek_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_testimonials`
--
ALTER TABLE `talentrek_testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_trainers`
--
ALTER TABLE `talentrek_trainers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `talentrek_trainer_assessments`
--
ALTER TABLE `talentrek_trainer_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `talentrek_training_batches`
--
ALTER TABLE `talentrek_training_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `talentrek_training_categories`
--
ALTER TABLE `talentrek_training_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `talentrek_training_experience`
--
ALTER TABLE `talentrek_training_experience`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talentrek_training_materials`
--
ALTER TABLE `talentrek_training_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `talentrek_training_materials_documents`
--
ALTER TABLE `talentrek_training_materials_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talentrek_work_experience`
--
ALTER TABLE `talentrek_work_experience`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `talentrek_admin_permissions`
--
ALTER TABLE `talentrek_admin_permissions`
  ADD CONSTRAINT `talentrek_admin_permissions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `talentrek_admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_assessment_options`
--
ALTER TABLE `talentrek_assessment_options`
  ADD CONSTRAINT `talentrek_assessment_options_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `talentrek_trainer_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_assessment_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `talentrek_assessment_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_assessment_options_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_assessment_questions`
--
ALTER TABLE `talentrek_assessment_questions`
  ADD CONSTRAINT `talentrek_assessment_questions_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `talentrek_trainer_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_assessment_questions_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_jobseeker_assessment_data`
--
ALTER TABLE `talentrek_jobseeker_assessment_data`
  ADD CONSTRAINT `talentrek_jobseeker_assessment_data_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `talentrek_trainer_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_jobseeker_assessment_data_jobseeker_id_foreign` FOREIGN KEY (`jobseeker_id`) REFERENCES `talentrek_jobseekers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_jobseeker_assessment_data_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `talentrek_assessment_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_jobseeker_assessment_data_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_jobseeker_assessment_data_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `talentrek_training_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_payments_history`
--
ALTER TABLE `talentrek_payments_history`
  ADD CONSTRAINT `talentrek_payments_history_jobseeker_id_foreign` FOREIGN KEY (`jobseeker_id`) REFERENCES `talentrek_jobseekers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_payments_history_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `talentrek_training_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_recruiters_company`
--
ALTER TABLE `talentrek_recruiters_company`
  ADD CONSTRAINT `talentrek_recruiters_company_recruiter_id_foreign` FOREIGN KEY (`recruiter_id`) REFERENCES `talentrek_recruiters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_recruiter_jobseeker_shortlist`
--
ALTER TABLE `talentrek_recruiter_jobseeker_shortlist`
  ADD CONSTRAINT `talentrek_recruiter_jobseeker_shortlist_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `talentrek_recruiters_company` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `talentrek_recruiter_jobseeker_shortlist_jobseeker_id_foreign` FOREIGN KEY (`jobseeker_id`) REFERENCES `talentrek_jobseekers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `talentrek_recruiter_jobseeker_shortlist_recruiter_id_foreign` FOREIGN KEY (`recruiter_id`) REFERENCES `talentrek_recruiters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `talentrek_reviews`
--
ALTER TABLE `talentrek_reviews`
  ADD CONSTRAINT `talentrek_reviews_jobseeker_id_foreign` FOREIGN KEY (`jobseeker_id`) REFERENCES `talentrek_jobseekers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_skills`
--
ALTER TABLE `talentrek_skills`
  ADD CONSTRAINT `talentrek_skills_jobseeker_id_foreign` FOREIGN KEY (`jobseeker_id`) REFERENCES `talentrek_jobseekers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_trainer_assessments`
--
ALTER TABLE `talentrek_trainer_assessments`
  ADD CONSTRAINT `talentrek_trainer_assessments_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `talentrek_training_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_trainer_assessments_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_training_batches`
--
ALTER TABLE `talentrek_training_batches`
  ADD CONSTRAINT `talentrek_training_batches_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `talentrek_training_batches_training_material_id_foreign` FOREIGN KEY (`training_material_id`) REFERENCES `talentrek_training_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_training_materials`
--
ALTER TABLE `talentrek_training_materials`
  ADD CONSTRAINT `talentrek_training_materials_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talentrek_training_materials_documents`
--
ALTER TABLE `talentrek_training_materials_documents`
  ADD CONSTRAINT `fk_trainer_id` FOREIGN KEY (`trainer_id`) REFERENCES `talentrek_trainers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_training_material_id` FOREIGN KEY (`training_material_id`) REFERENCES `talentrek_training_materials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
