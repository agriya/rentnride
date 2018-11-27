-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2016 at 07:47 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rentnride`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_requests`
--

CREATE TABLE IF NOT EXISTS `api_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `http_response_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_requests_id_index` (`id`),
  KEY `api_requests_user_id_index` (`user_id`),
  KEY `api_requests_ip_id_index` (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachmentable_id` bigint(20) NOT NULL,
  `attachmentable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mimetype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filesize` bigint(20) NOT NULL,
  `height` bigint(20) NOT NULL,
  `width` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `created_at`, `updated_at`, `attachmentable_id`, `attachmentable_type`, `filename`, `dir`, `mimetype`, `filesize`, `height`, `width`) VALUES
(1, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 0, 'MorphUser', 'default.jpg', 'app/User/0/', 'image/jpeg', 7870, 0, 0),
(2, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 0, 'MorphVehicle', 'car-thumb.png', 'app/Vehicle/0/', 'image/png', 40500, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `booker_details`
--

CREATE TABLE IF NOT EXISTS `booker_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `item_user_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `booker_details_id_index` (`id`),
  KEY `booker_details_item_user_id_index` (`item_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_types`
--

CREATE TABLE IF NOT EXISTS `cancellation_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `minimum_duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `maximum_duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deduct_rate` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `cancellation_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_id_index` (`id`),
  KEY `cities_state_id_index` (`state_id`),
  KEY `cities_country_id_index` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `created_at`, `updated_at`, `name`, `state_id`, `country_id`, `latitude`, `longitude`, `is_active`) VALUES
(1, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Banfield', 1, 11, -34.75, -58.4, 1),
(2, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Hurlingham', 1, 11, -34.6, -58.6333, 1),
(3, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Isidro Casanova', 1, 11, -34.7, -58.5833, 1),
(4, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Lanús', 1, 11, -34.7153, -58.4078, 1),
(5, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'San Miguel', 1, 11, -34.5239, -58.7794, 1),
(6, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Alta Gracia', 2, 11, -31.666667, -64.433333, 1),
(7, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Bell Ville', 2, 11, -32.633333, -62.683333, 1),
(8, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Río Cuarto', 2, 11, -33.133333, -64.35, 1),
(9, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'San Francisco', 2, 11, -31.435556, -62.071389, 1),
(10, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Villa María', 2, 11, -32.410278, -63.231389, 1),
(11, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Concordia', 3, 11, -31.4, -58.033333, 1),
(12, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Gualeguay', 3, 11, -33.15, -59.333333, 1),
(13, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Gualeguaychú', 3, 11, -33.016667, -58.516667, 1),
(14, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Paraná', 3, 11, -31.733333, -60.533333, 1),
(15, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Villaguay', 3, 11, -31.85, -59.016667, 1),
(16, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Rafaela', 4, 11, -31.2667, -61.4833, 1),
(17, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Reconquista', 4, 11, -29.2333, -59.6, 1),
(18, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Rincon Del Pintado', 4, 11, -32.2333, -60.9917, 1),
(19, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Villa America', 4, 11, -33.0333, -60.7833, 1),
(20, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Orán', 5, 11, -23.133333, -64.333333, 1),
(21, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Salta', 5, 11, -24.783333, -65.416667, 1),
(22, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Tartagal', 5, 11, -22.5, -63.833333, 1),
(23, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Ceto', 6, 109, 46.0031, 10.3515, 1),
(24, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Gardola', 6, 109, 45.742, 10.7189, 1),
(25, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'San Paolo', 6, 109, 45.3716, 10.0278, 1),
(26, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Temu''', 6, 109, 46.249, 10.469, 1),
(27, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Valvestino', 6, 109, 45.7587, 10.582, 1),
(28, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Avondale', 7, 159, -36.898, 174.6967, 1),
(29, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Owairaka', 7, 159, -36.895071, 174.721551, 1),
(30, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Ponsonby', 7, 159, -36.852356, 174.738689, 1),
(31, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Saint Johns', 7, 159, -36.874498, 174.842433, 1),
(32, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Waitakere', 7, 159, -36.85, 174.55, 1),
(33, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Chennai', 8, 102, 13.0826802, 80.2707184, 1),
(34, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Coimbatore', 8, 102, 11.0168445, 76.9558321, 1),
(35, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Madurai', 8, 102, 9.9252007, 78.1197754, 1),
(36, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Tiruchirappalli', 8, 102, 10.7904833, 78.7046725, 1),
(37, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Salem', 8, 102, 11.664325, 78.1460142, 1),
(38, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Albany', 22, 240, 42.6503, -73.7503, 1),
(39, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Binghamton', 22, 240, 42.1008, -75.9131, 1),
(40, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Elmira', 22, 240, 42.083, -76.833, 1),
(41, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Lewiston', 22, 240, 43.16917, -79.0017, 1),
(42, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Rochester', 22, 240, 43.1614, -77.6058, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_id_index` (`id`),
  KEY `contacts_user_id_index` (`user_id`),
  KEY `contacts_ip_id_index` (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `counter_locations`
--

CREATE TABLE IF NOT EXISTS `counter_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(10,6) DEFAULT '0.000000',
  `longitude` double(10,6) DEFAULT '0.000000',
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `counter_locations_id_index` (`id`),
  KEY `counter_locations_address_index` (`address`),
  KEY `counter_locations_mobile_index` (`mobile`),
  KEY `counter_locations_email_index` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `counter_locations`
--

INSERT INTO `counter_locations` (`id`, `created_at`, `updated_at`, `address`, `latitude`, `longitude`, `fax`, `phone`, `mobile`, `email`) VALUES
(1, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Chennai International Airport, GST Rd, Meenambakkam, Chennai, Tamil Nadu 600027, India', 12.994112, 80.170867, '04485965865', '04458659856', '5468648548', 'counterlocation1@gmail.com'),
(2, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Madurai International Airport, Madurai, Tamil Nadu 625022, India', 9.838590, 78.089522, '045258658659', '045258658659', '5468648552', 'counterlocation2@gmail.com'),
(3, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Mumbai International Airport, Andheri Kurla Road, Opp. H K Studio, Safed Pul, Sakinaka, Safed Pul, Sakinaka, Mumbai, Maharashtra 400072, India', 19.097002, 9999.999999, '0228525658356', '0228525658356', '5468648548', 'counterlocation3@gmail.com'),
(4, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Bangalore International Airport, Devanahalli, Bengaluru, Karnataka 560300, India', 13.198633, 77.706593, '0802568526886', '0802568526886', '5468648549', 'counterlocation4@gmail.com'),
(5, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Hyderabad International Airport, Shamshabad, Hyderabad, Telangana 500409, India', 17.240263, 78.429385, '056585685568', '056585685568', '5468648550', 'counterlocation5@gmail.com'),
(6, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Indira Gandhi International Airport, New Delhi, New Delhi 110037, India', 28.556162, 77.099958, '011565568655', '011565568655', '5468648553', 'counterlocation6@gmail.com'),
(7, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Netaji Subhas Chandra Bose International Airport, Dum Dum, Kolkata, West Bengal 700052, India', 22.642664, 88.439122, '05235865866', '05235865866', '5468648555', 'counterlocation7@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `counter_location_vehicle`
--

CREATE TABLE IF NOT EXISTS `counter_location_vehicle` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `counter_location_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_id` bigint(20) unsigned DEFAULT NULL,
  `is_pickup` tinyint(1) DEFAULT '0',
  `is_drop` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `counter_location_vehicle_id_index` (`id`),
  KEY `counter_location_vehicle_counter_location_id_index` (`counter_location_id`),
  KEY `counter_location_vehicle_vehicle_id_index` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso2` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=254 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `created_at`, `updated_at`, `name`, `iso2`, `iso3`, `is_active`) VALUES
(1, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Afghanistan', 'AF', 'AFG', 1),
(2, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Aland Islands', 'AX', 'ALA', 1),
(3, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Albania', 'AL', 'ALB', 1),
(4, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Algeria', 'DZ', 'DZA', 1),
(5, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'American Samoa', 'AS', 'ASM', 1),
(6, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Andorra', 'AD', 'AND', 1),
(7, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Angola', 'AO', 'AGO', 1),
(8, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Anguilla', 'AI', 'AIA', 1),
(9, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Antarctica', 'AQ', 'ATA', 1),
(10, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Antigua and Barbuda', 'AG', 'ATG', 1),
(11, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Argentina', 'AR', 'ARG', 1),
(12, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Armenia', 'AM', 'ARM', 1),
(13, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Aruba', 'AW', 'ABW', 1),
(14, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Australia', 'AU', 'AUS', 1),
(15, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Austria', 'AT', 'AUT', 1),
(16, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Azerbaijan', 'AZ', 'AZE', 1),
(17, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bahamas', 'BS', 'BHS', 1),
(18, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bahrain', 'BH', 'BHR', 1),
(19, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bangladesh', 'BD', 'BGD', 1),
(20, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Barbados', 'BB', 'BRB', 1),
(21, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Belarus', 'BY', 'BLR', 1),
(22, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Belgium', 'BE', 'BEL', 1),
(23, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Belize', 'BZ', 'BLZ', 1),
(24, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Benin', 'BJ', 'BEN', 1),
(25, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bermuda', 'BM', 'BMU', 1),
(26, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bhutan', 'BT', 'BTN', 1),
(27, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bolivia', 'BO', 'BOL', 1),
(28, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bonaire, Saint Eustatius and Saba', 'BQ', 'BES', 1),
(29, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bosnia and Herzegovina', 'BA', 'BIH', 1),
(30, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Botswana', 'BW', 'BWA', 1),
(31, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bouvet Island', 'BV', 'BVT', 1),
(32, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Brazil', 'BR', 'BRA', 1),
(33, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'British Indian Ocean Territory', 'IO', 'IOT', 1),
(34, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'British Virgin Islands', 'VG', 'VGB', 1),
(35, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Brunei', 'BN', 'BRN', 1),
(36, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Bulgaria', 'BG', 'BGR', 1),
(37, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Burkina Faso', 'BF', 'BFA', 1),
(38, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Burundi	', 'BI', 'BDI', 1),
(39, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Cambodia', 'KH', 'KHM', 1),
(40, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Cameroon', 'CM', 'CMR', 1),
(41, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Canada', 'CA', 'CAN', 1),
(42, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Cape Verde', 'CV', 'CPV', 1),
(43, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Cayman Islands', 'KY', 'CYM', 1),
(44, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Central African Republic', 'CF', 'CAF', 1),
(45, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Chad', 'TD', 'TCD', 1),
(46, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Chile', 'CL', 'CHL', 1),
(47, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'China', 'CN', 'CHN', 1),
(48, '2016-09-09 17:46:35', '2016-09-09 17:46:35', 'Christmas Island', 'CX', 'CXR', 1),
(49, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Cocos Islands', 'CC', 'CCK', 1),
(50, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Colombia', 'CO', 'COL', 1),
(51, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Comoros', 'KM', 'COM', 1),
(52, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Cook Islands', 'CK', 'COK', 1),
(53, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Costa Rica', 'CR', 'CRI', 1),
(54, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Croatia', 'HR', 'HRV', 1),
(55, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Cuba', 'CU', 'CUB', 1),
(56, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Curacao', 'CW', 'CUW', 1),
(57, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Cyprus', 'CY', 'CYP', 1),
(58, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Czech Republic', 'CZ', 'CZE', 1),
(59, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Democratic Republic of the Congo', 'CD', 'COD', 1),
(60, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Denmark', 'DK', 'DNK', 1),
(61, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Djibouti', 'DJ', 'DJI', 1),
(62, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Dominica', 'DM', 'DMA', 1),
(63, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Dominican Republic', 'DO', 'DOM', 1),
(64, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'East Timor', 'TL', 'TLS', 1),
(65, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Ecuador', 'EC', 'ECU', 1),
(66, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Egypt', 'EG', 'EGY', 1),
(67, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'El Salvador', 'SV', 'SLV', 1),
(68, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Equatorial Guinea', 'GQ', 'GNQ', 1),
(69, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Eritrea', 'ER', 'ERI', 1),
(70, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Estonia', 'EE', 'EST', 1),
(71, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Ethiopia', 'ET', 'ETH', 1),
(72, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Falkland Islands', 'FK', 'FLK', 1),
(73, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Faroe Islands', 'FO', 'FRO', 1),
(74, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Fiji', 'FJ', 'FJI', 1),
(75, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Finland', 'FI', 'FIN', 1),
(76, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'France', 'FR', 'FRA', 1),
(77, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'French Guiana', 'GF', 'GUF', 1),
(78, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'French Polynesia', 'PF', 'PYF', 1),
(79, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'French Southern Territories', 'TF', 'ATF', 1),
(80, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Gabon', 'GA', 'GAB', 1),
(81, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Gambia', 'GM', 'GMB', 1),
(82, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Georgia', 'GE', 'GEO', 1),
(83, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Germany', 'DE', 'DEU', 1),
(84, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Ghana', 'GH', 'GHA', 1),
(85, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Gibraltar', 'GI', 'GIB', 1),
(86, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Greece', 'GR', 'GRC', 1),
(87, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Greenland', 'GL', 'GRL', 1),
(88, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Grenada', 'GD', 'GRD', 1),
(89, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Guadeloupe', 'GP', 'GLP', 1),
(90, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Guam', 'GU', 'GUM', 1),
(91, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Guatemala', 'GT', 'GTM', 1),
(92, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Guernsey', 'GG', 'GGY', 1),
(93, '2016-09-09 17:46:36', '2016-09-09 17:46:36', 'Guinea', 'GN', 'GIN', 1),
(94, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Guinea-Bissau', 'GW', 'GNB', 1),
(95, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Guyana', 'GY', 'GUY', 1),
(96, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Haiti', 'HT', 'HTI', 1),
(97, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Heard Island and McDonald Islands', 'HM', 'HMD', 1),
(98, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Honduras', 'HN', 'HND', 1),
(99, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Hong Kong', 'HK', 'HKG', 1),
(100, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Hungary', 'HU', 'HUN', 1),
(101, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Iceland', 'IS', 'ISL', 1),
(102, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'India', 'IN', 'IND', 1),
(103, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Indonesia', 'ID', 'IDN', 1),
(104, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Iran', 'IR', 'IRN', 1),
(105, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Iraq', 'IQ', 'IRQ', 1),
(106, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Ireland', 'IE', 'IRL', 1),
(107, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Isle of Man', 'IM', 'IMN', 1),
(108, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Israel', 'IL', 'ISR', 1),
(109, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Italy', 'IT', 'ITA', 1),
(110, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Ivory Coast', 'CI', 'CIV', 1),
(111, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Jamaica', 'JM', 'JAM', 1),
(112, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Japan', 'JP', 'JPN', 1),
(113, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Jersey', 'JE', 'JEY', 1),
(114, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Jordan', 'JO', 'JOR', 1),
(115, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kazakhstan', 'KZ', 'KAZ', 1),
(116, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kenya', 'KE', 'KEN', 1),
(117, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kiribati', 'KI', 'KIR', 1),
(118, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kosovo', 'XK', 'XKX', 1),
(119, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kuwait', 'KW', 'KWT', 1),
(120, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Kyrgyzstan', 'KG', 'KGZ', 1),
(121, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Laos', 'LA', 'LAO', 1),
(122, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Latvia', 'LV', 'LVA', 1),
(123, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Lebanon', 'LB', 'LBN', 1),
(124, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Lesotho', 'LS', 'LSO', 1),
(125, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Liberia', 'LR', 'LBR', 1),
(126, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Libya', 'LY', 'LBY', 1),
(127, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Liechtenstein', 'LI', 'LIE', 1),
(128, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Lithuania', 'LT', 'LTU', 1),
(129, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Luxembourg', 'LU', 'LUX', 1),
(130, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Macao', 'MO', 'MAC', 1),
(131, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Macedonia', 'MK', 'MKD', 1),
(132, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Madagascar', 'MG', 'MDG', 1),
(133, '2016-09-09 17:46:37', '2016-09-09 17:46:37', 'Malawi', 'MW', 'MWI', 1),
(134, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Malaysia', 'MY', 'MYS', 1),
(135, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Maldives', 'MV', 'MDV', 1),
(136, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mali', 'ML', 'MLI', 1),
(137, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Malta', 'MT', 'MLT', 1),
(138, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Marshall Islands', 'MH', 'MHL', 1),
(139, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Martinique', 'MQ', 'MTQ', 1),
(140, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mauritania', 'MR', 'MRT', 1),
(141, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mauritius', 'MU', 'MUS', 1),
(142, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mayotte', 'YT', 'MYT', 1),
(143, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mexico', 'MX', 'MEX', 1),
(144, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Micronesia', 'FM', 'FSM', 1),
(145, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Moldova', 'MD', 'MDA', 1),
(146, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Monaco', 'MC', 'MCO', 1),
(147, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mongolia', 'MN', 'MNG', 1),
(148, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Montenegro', 'ME', 'MNE', 1),
(149, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Montserrat', 'MS', 'MSR', 1),
(150, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Morocco', 'MA', 'MAR', 1),
(151, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Mozambique', 'MZ', 'MOZ', 1),
(152, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Myanmar', 'MM', 'MMR', 1),
(153, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Namibia', 'NA', 'NAM', 1),
(154, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Nauru', 'NR', 'NRU', 1),
(155, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Nepal', 'NP', 'NPL', 1),
(156, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Netherlands', 'NL', 'NLD', 1),
(157, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Netherlands Antilles', 'AN', 'ANT', 1),
(158, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'New Caledonia', 'NC', 'NCL', 1),
(159, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'New Zealand', 'NZ', 'NZL', 1),
(160, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Nicaragua', 'NI', 'NIC', 1),
(161, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Niger', 'NE', 'NER', 1),
(162, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Nigeria', 'NG', 'NGA', 1),
(163, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Niue', 'NU', 'NIU', 1),
(164, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Norfolk Island', 'NF', 'NFK', 1),
(165, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'North Korea', 'KP', 'PRK', 1),
(166, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Northern Mariana Islands', 'MP', 'MNP', 1),
(167, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Norway', 'NO', 'NOR', 1),
(168, '2016-09-09 17:46:38', '2016-09-09 17:46:38', 'Oman', 'OM', 'OMN', 1),
(169, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Pakistan', 'PK', 'PAK', 1),
(170, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Palau', 'PW', 'PLW', 1),
(171, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Palestinian Territory', 'PS', 'PSE', 1),
(172, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Panama', 'PA', 'PAN', 1),
(173, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Papua New Guinea', 'PG', 'PNG', 1),
(174, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Paraguay', 'PY', 'PRY', 1),
(175, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Peru', 'PE', 'PER', 1),
(176, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Philippines', 'PH', 'PHL', 1),
(177, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Pitcairn', 'PN', 'PCN', 1),
(178, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Poland', 'PL', 'POL', 1),
(179, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Portugal', 'PT', 'PRT', 1),
(180, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Puerto Rico', 'PR', 'PRI', 1),
(181, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Qatar', 'QA', 'QAT', 1),
(182, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Republic of the Congo', 'CG', 'COG', 1),
(183, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Reunion', 'RE', 'REU', 1),
(184, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Romania', 'RO', 'ROU', 1),
(185, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Russia', 'RU', 'RUS', 1),
(186, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Rwanda', 'RW', 'RWA', 1),
(187, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Barthelemy', 'BL', 'BLM', 1),
(188, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Helena', 'SH', 'SHN', 1),
(189, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Kitts and Nevis', 'KN', 'KNA', 1),
(190, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Lucia', 'LC', 'LCA', 1),
(191, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Martin', 'MF', 'MAF', 1),
(192, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Pierre and Miquelon', 'PM', 'SPM', 1),
(193, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1),
(194, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Samoa', 'WS', 'WSM', 1),
(195, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'San Marino', 'SM', 'SMR', 1),
(196, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Sao Tome and Principe', 'ST', 'STP', 1),
(197, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Saudi Arabia', 'SA', 'SAU', 1),
(198, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Senegal', 'SN', 'SEN', 1),
(199, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Serbia', 'RS', 'SRB', 1),
(200, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Serbia and Montenegro', 'CS', 'SCG', 1),
(201, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Seychelles', 'SC', 'SYC', 1),
(202, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Sierra Leone', 'SL', 'SLE', 1),
(203, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Singapore', 'SG', 'SGP', 1),
(204, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Sint Maarten', 'SX', 'SXM', 1),
(205, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Slovakia', 'SK', 'SVK', 1),
(206, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Slovenia', 'SI', 'SVN', 1),
(207, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Solomon Islands', 'SB', 'SLB', 1),
(208, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Somalia', 'SO', 'SOM', 1),
(209, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'South Africa', 'ZA', 'ZAF', 1),
(210, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1),
(211, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'South Korea', 'KR', 'KOR', 1),
(212, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'South Sudan', 'SS', 'SSD', 1),
(213, '2016-09-09 17:46:39', '2016-09-09 17:46:39', 'Spain', 'ES', 'ESP', 1),
(214, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Sri Lanka', 'LK', 'LKA', 1),
(215, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Sudan', 'SD', 'SDN', 1),
(216, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Suriname', 'SR', 'SUR', 1),
(217, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Svalbard and Jan Mayen', 'SJ', 'SJM', 1),
(218, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Swaziland', 'SZ', 'SWZ', 1),
(219, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Sweden', 'SE', 'SWE', 1),
(220, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Switzerland', 'CH', 'CHE', 1),
(221, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Syria', 'SY', 'SYR', 1),
(222, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Taiwan', 'TW', 'TWN', 1),
(223, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tajikistan', 'TJ', 'TJK', 1),
(224, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tanzania', 'TZ', 'TZA', 1),
(225, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Thailand', 'TH', 'THA', 1),
(226, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Togo', 'TG', 'TGO', 1),
(227, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tokelau', 'TK', 'TKL', 1),
(228, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tonga', 'TO', 'TON', 1),
(229, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Trinidad and Tobago', 'TT', 'TTO', 1),
(230, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tunisia', 'TN', 'TUN', 1),
(231, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Turkey', 'TR', 'TUR', 1),
(232, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Turkmenistan', 'TM', 'TKM', 1),
(233, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Turks and Caicos Islands', 'TC', 'TCA', 1),
(234, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Tuvalu', 'TV', 'TUV', 1),
(235, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'U.S. Virgin Islands', 'VI', 'VIR', 1),
(236, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Uganda', 'UG', 'UGA', 1),
(237, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Ukraine', 'UA', 'UKR', 1),
(238, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'United Arab Emirates', 'AE', 'ARE', 1),
(239, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'United Kingdom', 'GB', 'GBR', 1),
(240, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'United States', 'US', 'USA', 1),
(241, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'United States Minor Outlying Islands', 'UM', 'UMI', 1),
(242, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Uruguay', 'UY', 'URY', 1),
(243, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Uzbekistan', 'UZ', 'UZB', 1),
(244, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Vanuatu', 'VU', 'VUT', 1),
(245, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Vatican', 'VA', 'VAT', 1),
(246, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Venezuela', 'VE', 'VEN', 1),
(247, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Vietnam', 'VN', 'VNM', 1),
(248, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Wallis and Futuna', 'WF', 'WLF', 1),
(249, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Western Sahara', 'EH', 'ESH', 1),
(250, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Yemen', 'YE', 'YEM', 1),
(251, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Zambia', 'ZM', 'ZMB', 1),
(252, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Zimbabwe', 'ZW', 'ZWE', 1),
(253, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'United States Minor Outlying Islands', 'UM', 'UMI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `couponable_id` bigint(20) NOT NULL,
  `couponable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model_type` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `discount` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `no_of_quantity` bigint(20) NOT NULL DEFAULT '0',
  `no_of_quantity_used` bigint(20) NOT NULL DEFAULT '0',
  `validity_start_date` date DEFAULT NULL,
  `validity_end_date` date DEFAULT NULL,
  `maximum_discount_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_name_unique` (`name`),
  KEY `coupons_id_index` (`id`),
  KEY `coupons_discount_type_id_index` (`discount_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `suffix` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `decimals` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dec_point` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `thousands_sep` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `is_prefix_display_on_left` tinyint(1) NOT NULL,
  `is_use_graphic_symbol` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `currencies_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `created_at`, `updated_at`, `name`, `code`, `symbol`, `prefix`, `suffix`, `decimals`, `dec_point`, `thousands_sep`, `is_prefix_display_on_left`, `is_use_graphic_symbol`, `is_active`) VALUES
(1, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Euros', 'EUR', '€', '€', 'EUR', '2', '.', ',', 1, 1, 1),
(2, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'U.S. Dollars', 'USD', '$', '$', 'USD', '2', '.', ',', 1, 1, 1),
(3, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Australian Dollars', 'AUD', '$', '$', 'AUD', '2', '.', ',', 1, 0, 1),
(4, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'British Pounds', 'GBP', '£', '£', 'GBP', '2', '.', ',', 1, 0, 1),
(5, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Canadian Dollars', 'CAD', '$', '$', 'CAD', '2', '.', ',', 1, 0, 1),
(6, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Danish Kroner', 'DKK', 'kr', 'kr', 'DKK', '2', '.', ',', 1, 0, 1),
(7, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Hong Kong Dollars', 'HKD', 'HK$', 'HK$', 'HKD', '2', '.', ',', 1, 0, 1),
(8, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Israeli New Shekels', 'ILS', '₪', '₪', 'ILS', '2', '.', ',', 1, 0, 1),
(9, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Japanese Yen', 'JPY', '¥', '¥', 'JPY', '2', '.', ',', 1, 0, 1),
(10, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Mexican Pesos', 'MXN', '$', '$', 'MXN', '2', '.', ',', 1, 0, 1),
(11, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'New Zealand Dollars', 'NZD', '$', '$', 'NZD', '2', '.', ',', 1, 0, 1),
(12, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Norwegian Kroner', 'NOK', 'kr', 'kr', 'NOK', '2', '.', ',', 1, 0, 1),
(13, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Philippine Pesos', 'PHP', 'php', 'php', 'PHP', '2', '.', ',', 1, 0, 1),
(14, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Polish Zlotych', 'PLN', 'zł‚', 'zł‚', 'PLN', '2', '.', ',', 1, 0, 1),
(15, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Singapore Dollars', 'SGD', '$', '$', 'SGD', '2', '.', ',', 1, 0, 1),
(16, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Swedish Kronor', 'SEK', 'kr', 'kr', 'SEK', '2', '.', ',', 1, 0, 1),
(17, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Swiss Francs', 'CHF', 'CHF', 'CHF', 'CHF', '2', '.', ',', 1, 0, 1),
(18, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Thai Baht', 'THB', 'Bt', 'Bt', 'THB', '2', '.', ',', 1, 0, 1),
(19, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Indian Rupee', 'INR', '₹', '₹', 'INR', '2', '.', ',', 1, 1, 1),
(20, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'South African Rand', 'ZAR', 'ZAR', '', '', '', '', '', 1, 0, 1),
(21, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'BRL-CAD', 'BRL', 'R$', '', '', '', '', '', 1, 1, 1),
(22, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'TÃ¼rk LirasÄ±', 'TRY', 'TL', '', '', '', '', '', 1, 0, 1),
(23, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Chilean Peso', 'CLP', '$', '$', 'CLP', '1', ',', '.', 1, 1, 1),
(24, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Romanian Leu', 'RON', 'Leu', '', '', '', '', '', 1, 0, 1),
(25, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 'Egyptian Pound', 'EGP', 'EGP', '', '', '2', '.', ',', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversions`
--

CREATE TABLE IF NOT EXISTS `currency_conversions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_id` bigint(20) unsigned DEFAULT NULL,
  `converted_currency_id` bigint(20) unsigned DEFAULT NULL,
  `rate` double(10,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `currency_conversions_id_index` (`id`),
  KEY `currency_conversions_currency_id_index` (`currency_id`),
  KEY `currency_conversions_converted_currency_id_index` (`converted_currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversion_histories`
--

CREATE TABLE IF NOT EXISTS `currency_conversion_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_conversion_id` bigint(20) unsigned DEFAULT NULL,
  `rate_before_change` double(10,2) NOT NULL DEFAULT '0.00',
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `currency_conversion_histories_id_index` (`id`),
  KEY `currency_conversion_histories_currency_conversion_id_index` (`currency_conversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `discount_types`
--

CREATE TABLE IF NOT EXISTS `discount_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_types_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `discount_types`
--

INSERT INTO `discount_types` (`id`, `created_at`, `updated_at`, `type`) VALUES
(1, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'percentage'),
(2, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'amount');

-- --------------------------------------------------------

--
-- Table structure for table `dispute_closed_types`
--

CREATE TABLE IF NOT EXISTS `dispute_closed_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dispute_type_id` bigint(20) unsigned DEFAULT NULL,
  `is_booker` tinyint(1) NOT NULL DEFAULT '0',
  `resolved_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispute_closed_types_id_index` (`id`),
  KEY `dispute_closed_types_dispute_type_id_index` (`dispute_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `dispute_closed_types`
--

INSERT INTO `dispute_closed_types` (`id`, `created_at`, `updated_at`, `name`, `dispute_type_id`, `is_booker`, `resolved_type`, `reason`) VALUES
(1, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Favor Booker', 1, 1, 'refunded', 'Property doesn''t match with the one mentioned in property specification'),
(2, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Favor Host', 1, 0, 'resolve without any change', 'Property matches with the one mentioned in property specification'),
(3, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Favor Booker', 1, 1, 'refunded', 'Failure to respond in time limit'),
(4, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Favor Booker', 2, 1, 'resolve without any change', 'Property doesn''t matches the quality and requirement/specification, so no changes in feedback / rating'),
(5, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Favor Host', 2, 1, 'Update host rating', 'Property matches the quality and requirement/specification, so host rating changed to positive'),
(6, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Favor Host', 2, 1, 'Update host rating', 'Failure to respond in time limit'),
(7, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Favor Booker', 3, 1, 'Deposit amount refunded', 'Claiming reason doesn''t match with existing conversation'),
(8, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Favor Host', 3, 0, 'Deposit amount to host', 'Claiming reason matches with existing conversation'),
(9, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Favor Host', 3, 0, 'Deposit amount to host', 'Failure to respond in time limit');

-- --------------------------------------------------------

--
-- Table structure for table `dispute_statuses`
--

CREATE TABLE IF NOT EXISTS `dispute_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispute_statuses_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dispute_statuses`
--

INSERT INTO `dispute_statuses` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Open'),
(2, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Under Discussion'),
(3, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Waiting Administrator Decision'),
(4, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `dispute_types`
--

CREATE TABLE IF NOT EXISTS `dispute_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_booker` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `dispute_types_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dispute_types`
--

INSERT INTO `dispute_types` (`id`, `created_at`, `updated_at`, `name`, `is_booker`, `is_active`) VALUES
(1, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Doesn''t match the specification as mentioned by the property owner', 1, 1),
(2, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Booker given poor feedback', 0, 1),
(3, '2016-09-09 17:46:56', '2016-09-09 17:46:56', 'Claim the security damage from booker', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `duration_types`
--

CREATE TABLE IF NOT EXISTS `duration_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `duration_types_id_index` (`id`),
  KEY `duration_types_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `duration_types`
--

INSERT INTO `duration_types` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Per day'),
(2, '2016-09-09 17:46:57', '2016-09-09 17:46:57', 'Per rental');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body_content` text COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reply_to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_templates_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `created_at`, `updated_at`, `name`, `subject`, `body_content`, `filename`, `from_name`, `info`, `reply_to`) VALUES
(1, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 'Forgot Password', 'Forgot Password', 'Hi ##USERNAME##,<br><br>A password request has been made for your user account at ##SITE_NAME##.<br><br>New password: ##PASSWORD##<br><br>If you did not request this action and feel this is in error, please contact us at ##CONTACT_MAIL##.<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail, when user submit the forgot password form.', '##REPLY_TO_EMAIL##'),
(2, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 'Activation Request', 'Please activate your ##SITE_NAME## account', 'Hi ##USERNAME##,<br><br>Your account has been created. Please visit the following URL to activate your account.<br>##ACTIVATION_URL##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail, when user registering an account he/she will get an activation request.', '##REPLY_TO_EMAIL##'),
(3, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 'Welcome Email', 'Welcome to ##SITE_NAME##', 'Hi ##USERNAME##,<br><br>We wish to say a quick hello and thanks for registering at ##SITE_NAME##.<br><br>If you did not request this account and feel this is an error, please contact us at ##CONTACT_MAIL##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail, when user register in this site and get activate.', '##REPLY_TO_EMAIL##'),
(4, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'New User Join', 'New user joined in ##SITE_NAME## account', 'Hi,<br><br>A new user named "##USERNAME##" has joined in ##SITE_NAME## account.<br><br>Username  : ##USERNAME##<br>Email     : ##EMAIL##<br>Signup IP : ##SIGNUP_IP##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.', '##REPLY_TO_EMAIL##'),
(5, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin User Add', 'Welcome to ##SITE_NAME##', 'Hi ##USERNAME##,<br><br>##SITE_NAME## team added you as a user in ##SITE_NAME##.<br><br>Your account details.<br>Email:##USEREMAIL##<br>Password:##PASSWORD##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail to user, when a admin add a new user.', '##REPLY_TO_EMAIL##'),
(6, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin User Active', 'Your ##SITE_NAME## account has been activated', 'Hi ##USERNAME##,<br><br>Your account has been activated.<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when user active by administrator.', '##REPLY_TO_EMAIL##'),
(7, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin User Deactivate', 'Your ##SITE_NAME## account has been deactivated', 'Hi ##USERNAME##,<br><br>Your ##SITE_NAME## account has been deactivated.<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when user active by administrator.', '##REPLY_TO_EMAIL##'),
(8, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin User Delete', 'Your ##SITE_NAME## account has been removed', 'Hi ##USERNAME##,<br><br>Your ##SITE_NAME## account has been removed.<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when user delete by administrator.', '##REPLY_TO_EMAIL##'),
(9, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin Change Password', 'Password changed', 'Hi ##USERNAME##,<br><br>Admin reset your password for your  ##SITE_NAME## account.<br><br>Your new password:<br>##PASSWORD##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this mail to user, when admin change user''s password.', '##REPLY_TO_EMAIL##'),
(10, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Contact Us', '[##SITE_NAME##] ##SUBJECT##', '##MESSAGE##<br><br>----------------------------------------------------<br>Telephone  : ##TELEPHONE##<br>IP             : ##IP##<br>Whois       : http://whois.sc/##IP##<br>URL          : ##FROM_URL##<br>----------------------------------------------------<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to admin, when user submit any contact form.', '##REPLY_TO_EMAIL##'),
(11, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Contact Us Auto Reply', '[##SITE_NAME##] RE: ##SUBJECT##', 'Hi ##USERNAME##,<br><br>Thanks for contacting us. We''ll get back to you shortly.<br><br>Please do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##<br><br>------ you wrote -----<br><br>##MESSAGE##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL## ', '', '##CONTACT_FROM_EMAIL##', 'we will send this mail to user, when user submit the contact us form.', ''),
(12, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'New Item Activated', 'New Item Activated - ##ITEM_NAME##', 'Dear ##USERNAME##,<br><br>Your new item has been activated.<br><br>Item Name: ##ITEM_NAME##<br><br>URL: ##ITEM_URL##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Your new item has been approved and activated now', '##REPLY_TO_EMAIL##'),
(13, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Auto refund notification', 'Your security deposit has refunded', 'Dear ##USERNAME##,<br><br>Item ##ITEM_NAME## security deposit amount ##AMOUNT## has been refunded.<br><br>Booked item: ##ITEM_NAME##<br><br>Vehicle Rental no: ###ORDERNO##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Internal message will be sent to the Booker mentioning the security deposit was refunded, when the booked item checkout without any due within the auto refund limit.', '##REPLY_TO_EMAIL##'),
(14, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Item User Change Status Alert', '[##SITE_NAME##][##ITEM##] Vehicle Rental Status: ##PREVIOUS_STATUS## -> ##CURRENT_STATUS##', 'Hi,<br><br>Status was changed for booking "##ITEM_NAME##".<br><br>Status: ##PREVIOUS_STATUS## -> ##CURRENT_STATUS##<br><br>Please click the following link to view the item,<br>##ITEM_URL##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'we will send this when a item user status change.', '##REPLY_TO_EMAIL##'),
(15, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'New VehicleRental Message To Host', 'You have a new rental', 'Dear ##USERNAME##<br><br>You have a new rental from ##BOOKER_USERNAME##.<br><br>Rented item: ##ITEM_NAME##.<br><br>Please click the following link to accept the rental ##ACCEPT_URL##<br><br>Please click the following link to reject the rental ##REJECT_URL##<br><br>Thanks, <br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'When new rental was made, an internal message will be sent to the owner of the item notify new rental.', '##REPLY_TO_EMAIL##'),
(16, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'New VehicleRental Message To Booker', 'Your rental has been made.', 'Dear ##USERNAME##,<br><br>##SITE_NAME##: Thank you. Please read this information about your rental from ##HOST_NAME##<br><br>Your rental has been sent to the host.<br><br>Rented item : ##ITEM_NAME##.<br><br>-----------------------------------------------------<br>Information about your Rental<br>-----------------------------------------------------<br>rental ###ORDER_NO##<br><br>Description: ##ITEM_DESCRIPTION##<br><br>From: ##FROM_DATE##<br><br>Host: ##HOST_NAME## <br><br>-----------------------------------------------------<br><br>What to do if the host is not responding?<br>-----------------------------------------------------<br>If you feel that the host is taking too long to respond, you can ##CANCEL_URL## and get your funds back to your Account. We recommend allowing host at least ##ITEM_AUTO_EXPIRE_DATE## day(s) to respond.<br><br>-----------------------------------------------------\r\n<br><br>What if the host rejects my rental?<br>-----------------------------------------------------<br>Host may sometimes choose to give up on an rental. This may be due to their inability to perform their work based on the information you provided or they are simply too busy or currently unavailable.<br><br>If a host rejects your rental, your funds are returned to your ##SITE_NAME## account.<br><br>-----------------------------------------------------<br>##SITE_NAME## Customer Service<br>-----------------------------------------------------<br>The ##SITE_NAME## Customer service are here to help you. If you need any assistance with an rental, Please contact us here: ##CONTACT_URL##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Internal mail sent to the booker when he makes a new rental.', '##REPLY_TO_EMAIL##'),
(17, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Accepted VehicleRental Message To Host', 'You have accepted the rental', 'Hi ##USERNAME##,<br><br>You have accepted the rental.<br><br>Rented Item: ##ITEM_NAME##.<br><br>Rental No#:##ORDER_NO##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Internal message will be sent to the Host, when the rented item was accepted by the host.', '##REPLY_TO_EMAIL##'),
(18, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Accepted VehicleRental Message To Booker', 'Your rental has been accepted', 'Dear ##USERNAME##<br>Your rental has been accepted. Looking forward for your visit:)<br><br>Rented Item: ##ITEM_NAME##<br><br>##HOST_CONTACT_LINK##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Internal message will be sent to the Booker, when the rented item was accepted by the host.', '##REPLY_TO_EMAIL##'),
(19, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Dispute Open Notification', '##USER_TYPE## has opened a dispute for this booking', 'Dear ##USERNAME##,<br><br>##OTHER_USER## has made a dispute on your booking#:##ORDERNO## and sent the following dispute message<br><br>##MESSAGE##<br><br>You need to reply within ##REPLY_DAYS## to avoid making decision in favor of ##USER_TYPE_URL##.<br><br>Please click the following link to reply: ##REPLY_LINK##<br><br>Booked Item: ##ITEM_NAME##<br><br>Vehicle Rental No#: ##ORDERNO##<br><br>Thanks, <br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Notification mail when dispute is opened.', '##REPLY_TO_EMAIL##'),
(20, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Dispute Conversation Notification', '##OTHER_USER## sent the following dispute conversation', 'Hi,<br><br>##OTHER_USER## sent the following dispute conversation:<br><br>##MESSAGE##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Notification mail sent during dispute conversation', '##REPLY_TO_EMAIL##'),
(21, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Dispute Resolved Notification', 'Dispute has been closed in favor of ##FAVOUR_USER##', 'Hi ##USERNAME##,<br><br>Your booking dispute has been closed in favor of ##FAVOUR_USER##.<br><br>Reason for closed: ##REASON_FOR_CLOSING##<br><br>Resolved by: ##RESOLVED_BY##<br><br>Dispute Information:<br><br>Dispute ID#: ##DISPUTE_ID##<br><br>Vehicle Rental ID#: ##ORDER_ID##<br><br>Disputer: ##DISPUTER##<br><br>##DISPUTER_USER_TYPE## <br><br>Reason for dispute: ##REASON##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Notification mail to be sent on closing dispute', '##REPLY_TO_EMAIL##'),
(22, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Discussion Threshold for Admin Decision', 'Admin will take decision shortly for this dispute.', 'Hi,<br><br>Admin will take decision shortly for this dispute.<br><br>Thanks, <br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'Admin will take decision, after no of conversation to booker and host.', '##REPLY_TO_EMAIL##'),
(23, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin Approve Withdraw Request', 'Your Withdraw request has been approved', 'Hi ##USERNAME##,<br>Your Withdraw request has been approved.<br>Details:<br>Request Amount:##AMOUNT##<br>Thanks,<br>##SITE_NAME##<br><br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when withdraw request is approved by administrator.', '##REPLY_TO_EMAIL##'),
(24, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Admin Reject Withdraw Request', 'Your Withdraw request has been rejected', 'Hi ##USERNAME##,<br><br>Your Withdraw request has been rejected.<br>Details:<br>Request Amount:##AMOUNT##<br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when withdraw request is rejected by administrator.', '##REPLY_TO_EMAIL##'),
(25, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'New VehicleRental Message To Host On Auto Approve', 'You have a new booking', 'Dear ##USERNAME##,<br><br>You have a new booking from ##BOOKER_USERNAME##.<br><br>Booked item: ##ITEM_NAME##.<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'When new booking was made, an internal message will be sent to the owner of the item notify new booking.', '##REPLY_TO_EMAIL##'),
(26, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Feedback to Booker', '##HOST## has left a feedback about you', 'Hi ##USERNAME##,<br><br>##HOST_URL## has left a feedback about you.<br><br>Feedback: ##MESSAGE##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when withdraw request is rejected by administrator.', '##REPLY_TO_EMAIL##'),
(27, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Feedback to Host', '##BOOKER## has left a feedback on your item', 'Hi ##USERNAME##,<br><br>##BOOKER_URL## has left a feedback on your item.<br><br>Feedback: ##MESSAGE##<br><br>Thanks,<br>##SITE_NAME##<br>##SITE_URL##', '', '##FROM_EMAIL##', 'We will send this mail to user, when withdraw request is rejected by administrator.', '##REPLY_TO_EMAIL##');

-- --------------------------------------------------------

--
-- Table structure for table `extra_accessories`
--

CREATE TABLE IF NOT EXISTS `extra_accessories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `extra_accessories_id_index` (`id`),
  KEY `extra_accessories_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `extra_accessories`
--

INSERT INTO `extra_accessories` (`id`, `created_at`, `updated_at`, `name`, `slug`, `short_description`, `description`, `is_active`) VALUES
(1, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Invoicing by mail', '', 'Invoicing by mail', 'Invoice will be send to your mail with rental details.', 1),
(2, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Skierized Equipment', '', 'Skierized Equipment', 'Skierized vehicles include a ski rack (holding up to 4 pairs of skis), ice scraper and all season tires, if a city offers tires other than all season, it will be listed in the cities ski information. ', 1),
(3, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'GPS Built-In', '', 'GPS Built-In', 'i) Deposit required to built GPS.\r\nii) Deposit amount will be refunded on return of vehicle.\r\niii) BN 350.00 (Per Day BN10.00 Maximum per rental: BN300.00. Liability for Loss or damage of the GPS Unit: BN 350.00)\r\n', 1),
(4, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Satellite Radio', '', 'Satellite Radio', 'i) Whether you are traveling for business or pleasure rental cars equipped with Satellite Radio will make your drive come alive. \r\nii) You can enjoy over 150 channels, including commercial-free music plus the best sports, news, talk, comedy, entertainment, and a collection of multi-language programming. ', 1),
(5, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Child seats', '', 'Child seats', 'It is important that your child has the safest journey possible, we offers a range of child safety seats that are suitable for babies and children.', 1),
(6, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Services for physically challenged', '', 'Services for physically challenged', 'i) A full range of special services for physically challenged renters are available to both the customer and any member of the traveling party.\r\nii) Hand Controls / Spinner Knob / Visually Impaired Renters / Scooter / Wheelchair Storage.', 1),
(7, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Additional driver', '', 'Additional driver', 'i) you can share the driving responsibilities with other friends and family members in your top quality rental car. \r\nii) Opting for additional driver insurance will allow for more flexibility in your travel experience', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `to_user_id` bigint(20) unsigned DEFAULT NULL,
  `is_host` tinyint(1) NOT NULL,
  `feedbackable_id` bigint(20) NOT NULL,
  `feedbackable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_user_id` bigint(20) unsigned DEFAULT NULL,
  `feedback` text COLLATE utf8_unicode_ci NOT NULL,
  `ip_id` bigint(20) unsigned DEFAULT NULL,
  `rating` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `feedbacks_id_index` (`id`),
  KEY `feedbacks_user_id_index` (`user_id`),
  KEY `feedbacks_to_user_id_index` (`to_user_id`),
  KEY `feedbacks_item_user_id_index` (`item_user_id`),
  KEY `feedbacks_ip_id_index` (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_options`
--

CREATE TABLE IF NOT EXISTS `fuel_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fuel_options_id_index` (`id`),
  KEY `fuel_options_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fuel_options`
--

INSERT INTO `fuel_options` (`id`, `created_at`, `updated_at`, `name`, `slug`, `short_description`, `description`, `is_active`) VALUES
(1, '2016-09-09 17:46:58', '2016-09-09 17:46:58', 'Fuel Purchase Option (FPO)', '', 'Fuel Purchase Option (FPO)', 'Prepay the fuel in the tank at competitive local prices. Return at any level. NO REFUND FOR UNUSED FUEL, except for rentals of 75 miles or less.', 1),
(2, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Express Fuel', '', 'Express Fuel', 'Drive a total of 75 miles or less for a service and fueling convenience fee of USD 13.99.', 1),
(3, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Fuel and Service Charge (FSC)', '', 'Fuel and Service Charge (FSC)', 'If you return with less fuel than at the time of rent, and have not chosen the Fuel Purchase Option (FPO) or Express Fuel, we will service and refuel at a per gallon rate.', 1),
(4, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'You refuel', '', 'You refuel', 'Return with the tank at the same level as when rented. On trips of 75 miles or less, you will need to produce a gas receipt from a station near the return location.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fuel_types`
--

CREATE TABLE IF NOT EXISTS `fuel_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_types_id_index` (`id`),
  KEY `fuel_types_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `fuel_types`
--

INSERT INTO `fuel_types` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Petrol'),
(2, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Diesel'),
(3, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'CNG'),
(4, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'LPG'),
(5, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Electric');

-- --------------------------------------------------------

--
-- Table structure for table `insurances`
--

CREATE TABLE IF NOT EXISTS `insurances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `insurances_id_index` (`id`),
  KEY `insurances_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `insurances`
--

INSERT INTO `insurances` (`id`, `created_at`, `updated_at`, `name`, `slug`, `short_description`, `description`, `is_active`) VALUES
(1, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Liability coverage/Liability Insurance Supplement (LIS)', '', 'Liability coverage/Liability Insurance Supplement (LIS)', 'i) Liability Insurance Supplement (LIS) provides coverage for you and other authorized operators of your rental vehicle for third party claims. \r\nii) Liability protection up to a limit of $1,000,000 per accident.\r\niii) LIS is third party liability coverage only,except where permitted by law or pertaining to Uninsured / Underinsured Motorist Coverage.', 1),
(2, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Partial Damage Waiver (PDW)', '', 'Partial Damage Waiver (PDW)', 'A partial / certain amount will be waived off. After that limit Booker has to pay for the damage.', 1),
(3, '2016-09-09 17:46:59', '2016-09-09 17:46:59', ' Loss Damage Waiver (LDW)', '', ' Loss Damage Waiver (LDW)', 'i) The cost of LDW may vary depending on location or car type and is charged per each full or partial day of rental.\r\nii) In the event of any loss or damage to the car regardless of fault, your financial responsibility will in no event exceed the fair market value, plus actual charges for towing, storage, impound fees, and an administrative fee.', 1),
(4, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Loss Damage Waiver (including Theft Protection) with minimum excess', '', 'Loss Damage Waiver (including Theft Protection) with minimum excess', 'i) Loss or damage for any cause other than theft is limited to USD 15,500.00.\r\nii) Loss or damage related to theft is limited to USD 2,000.00, unless the theft results from your fault.', 1),
(5, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Glass Damage Waiver (GDW)', '', 'Glass Damage Waiver (GDW)', 'i) GDW is protection only for the windshield of the rental car. \r\nii) This policy covers nearly all windows in the vehicle, including the windshield, side windows, rear-window and mirror glass.', 1),
(6, '2016-09-09 17:46:59', '2016-09-09 17:46:59', 'Personal Accident Insurance (PAI)', '', 'Personal Accident Insurance (PAI)', 'i) Personal Accident Insurance (PAI), offered in combination with PEC, allows you to elect accidental death and accidental medical expense coverage for yourself and your passengers during the rental period of the vehicle. \r\nii) Total benefits for any one accident are limited to USD 225,000.00.\r\niii) Your passengers are also covered, but only for incidents occurring while they occupy the rental car.', 1),
(7, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Liability Coverage/Supplemental Liability Insurance Loss Damage Waiver', '', 'Liability Coverage/Supplemental Liability Insurance Loss Damage Waiver', 'If you are involved in an accident with your rental car, you are insured against bodily injury and property damage to a third party up to a certain monetary value.', 1),
(8, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Protection Package (PP)', '', 'Protection Package (PP)', 'The Protection Package includes the following products: \r\nLoss Damage Waiver(LDW)\r\nThird Party Liability (SLI) \r\nPersonal Accident Insurance (PAI) \r\nRoadside Assistance (BC) \r\nNavigation System (NG)\r\n	CRS Code Loss Damage Waiver (including Theft Protection) with minimum excess Personal Accident Insurance $/Day.', 1),
(9, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Uninsured Motorist Protection (UMP)', '', 'Uninsured Motorist Protection (UMP)', 'i) UMP provides up to USD 1,000,000.00 of Uninsured / Under-insured protection for bodily injury sustained while driving the rental vehicle. \r\nii) UMP is only available when first accepting Liability Insurance Supplement (LIS). \r\niii) UMP is currently not available to Gold customers who book using their Gold Plus Rewards number and profile.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ips`
--

CREATE TABLE IF NOT EXISTS `ips` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_checked` tinyint(1) NOT NULL,
  `timezone` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ips_id_index` (`id`),
  KEY `ips_city_id_index` (`city_id`),
  KEY `ips_state_id_index` (`state_id`),
  KEY `ips_country_id_index` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ips`
--

INSERT INTO `ips` (`id`, `created_at`, `updated_at`, `city_id`, `state_id`, `country_id`, `host`, `ip`, `latitude`, `longitude`, `user_agent`, `is_checked`, `timezone`) VALUES
(1, '2016-09-09 17:46:43', '2016-09-09 17:46:43', 33, 8, 102, 'localhost', '127.0.0.1', 0, 0, '', 0, 0),
(2, '2016-09-09 17:46:44', '2016-09-09 17:46:44', 33, 8, 102, 'localhost', '::1', 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_slug_unique` (`slug`),
  KEY `items_id_index` (`id`),
  KEY `items_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_users`
--

CREATE TABLE IF NOT EXISTS `item_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `item_userable_id` bigint(20) NOT NULL,
  `item_userable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_user_status_id` bigint(20) unsigned DEFAULT NULL,
  `status_updated_at` datetime NOT NULL,
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `cancellation_type_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` bigint(20) NOT NULL,
  `item_booking_start_date` datetime NOT NULL,
  `item_booking_end_date` datetime NOT NULL,
  `pickup_counter_location_id` bigint(20) unsigned DEFAULT NULL,
  `drop_counter_location_id` bigint(20) unsigned DEFAULT NULL,
  `booking_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `deposit_amount` double(10,2) DEFAULT '0.00',
  `coupon_discount_amount` double(10,2) DEFAULT '0.00',
  `special_discount_amount` double(10,2) DEFAULT '0.00',
  `type_discount_amount` double(10,2) DEFAULT '0.00',
  `surcharge_amount` double(10,2) DEFAULT '0.00',
  `extra_accessory_amount` double(10,2) DEFAULT '0.00',
  `tax_amount` double(10,2) DEFAULT '0.00',
  `insurance_amount` double(10,2) DEFAULT '0.00',
  `fuel_option_amount` double(10,2) DEFAULT '0.00',
  `drop_location_differ_charges` double(10,2) DEFAULT '0.00',
  `additional_fee` double(10,2) DEFAULT '0.00',
  `admin_commission_amount` double(10,2) DEFAULT '0.00',
  `host_service_amount` double(10,2) DEFAULT '0.00',
  `cancellation_deduct_amount` double(10,2) DEFAULT '0.00',
  `total_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `reason_for_cancellation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cancellation_date` datetime NOT NULL,
  `is_payment_cleared` tinyint(1) NOT NULL,
  `is_dispute` tinyint(1) NOT NULL,
  `claim_request_amount` double(10,2) DEFAULT '0.00',
  `late_fee` double(10,2) DEFAULT '0.00',
  `paid_deposit_amount` double(10,2) DEFAULT '0.00',
  `paid_manual_amount` double(10,2) DEFAULT '0.00',
  `booker_amount` double(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `item_users_id_index` (`id`),
  KEY `item_users_user_id_index` (`user_id`),
  KEY `item_users_item_user_status_id_index` (`item_user_status_id`),
  KEY `item_users_coupon_id_index` (`coupon_id`),
  KEY `item_users_cancellation_type_id_index` (`cancellation_type_id`),
  KEY `item_users_pickup_counter_location_id_index` (`pickup_counter_location_id`),
  KEY `item_users_drop_counter_location_id_index` (`drop_counter_location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_user_additional_charges`
--

CREATE TABLE IF NOT EXISTS `item_user_additional_charges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `item_user_id` bigint(20) unsigned DEFAULT NULL,
  `item_user_additional_chargable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_user_additional_chargable_id` bigint(20) unsigned NOT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `item_user_additional_charges_id_index` (`id`),
  KEY `item_user_additional_charges_item_user_id_index` (`item_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_user_disputes`
--

CREATE TABLE IF NOT EXISTS `item_user_disputes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `item_user_disputable_id` bigint(20) NOT NULL,
  `item_user_disputable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model_type` bigint(20) NOT NULL,
  `dispute_type_id` bigint(20) unsigned DEFAULT NULL,
  `dispute_status_id` bigint(20) unsigned DEFAULT NULL,
  `last_replied_user_id` bigint(20) unsigned DEFAULT NULL,
  `dispute_closed_type_id` bigint(20) unsigned DEFAULT NULL,
  `is_favor_booker` tinyint(1) DEFAULT NULL,
  `is_booker` tinyint(1) DEFAULT NULL,
  `last_replied_date` datetime DEFAULT NULL,
  `resolved_date` datetime DEFAULT NULL,
  `dispute_conversation_count` bigint(20) NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `item_user_disputes_id_index` (`id`),
  KEY `item_user_disputes_user_id_index` (`user_id`),
  KEY `item_user_disputes_dispute_type_id_index` (`dispute_type_id`),
  KEY `item_user_disputes_dispute_status_id_index` (`dispute_status_id`),
  KEY `item_user_disputes_last_replied_user_id_index` (`last_replied_user_id`),
  KEY `item_user_disputes_dispute_closed_type_id_index` (`dispute_closed_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_user_statuses`
--

CREATE TABLE IF NOT EXISTS `item_user_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `booking_count` bigint(20) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `display_order` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_user_statuses_slug_unique` (`slug`),
  KEY `item_user_statuses_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `item_user_statuses`
--

INSERT INTO `item_user_statuses` (`id`, `created_at`, `updated_at`, `name`, `booking_count`, `slug`, `description`, `display_order`) VALUES
(1, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Payment Pending', 0, 'payment-pending', 'Vehicle Rental is in payment pending status.', 1),
(2, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Waiting For Acceptance', 0, 'waiting-for-acceptance', 'Vehicle Rental was made by the ##BOOKER## on ##CREATED_DATE##. Waiting for Host ##HOSTER## to accept the order.', 2),
(3, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Rejected', 0, 'rejected', 'Vehicle Rental was rejected by the ##HOSTER##. Vehicle Rental amount has been refunded.', 5),
(4, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Cancelled', 0, 'cancelled', 'Vehicle Rental was cancelled by ##BOOKER##. Vehicle Rental amount has been refunded based on cancellation policies.', 4),
(5, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Cancelled By Admin', 0, 'cancelled-by-admin', 'Vehicle Rental was cancelled by Administrator. Vehicle Rental amount has been refunded based on cancellation policies.', 13),
(6, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Expired', 0, 'expired', 'expired Vehicle Rental was expired due to non acceptance by the host ##HOSTER##. Vehicle Rental amount has been refunded.', 6),
(7, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Confirmed', 0, 'confirmed', 'Vehicle Rental was accepted by ##HOSTER## on ##ACCEPTED_DATE##.', 3),
(8, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Waiting for Review', 0, 'waiting-for-review', '##BOOKER## has checked out.', 8),
(9, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Booker Reviewed', 0, 'booker-reviewed', 'Booker reviewed.', 9),
(10, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Host Reviewed', 0, 'host-reviewed', 'Host reviewed.', 10),
(11, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Completed', 0, 'completed', 'Order completed.', 12),
(12, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Attended', 0, 'attended', 'Attended.', 7),
(13, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Waiting For Payment Cleared', 0, 'waiting-for-payment-cleared', 'Waiting For Payment Cleared.', 11),
(14, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Private Note', 0, 'private-note', 'Private Note.', 14);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iso2` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `languages_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=187 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `created_at`, `updated_at`, `name`, `iso2`, `iso3`, `is_active`) VALUES
(1, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Abkhazian', 'ab', 'abk', 0),
(2, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Afar', 'aa', 'aar', 0),
(3, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Afrikaans', 'af', 'afr', 0),
(4, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Akan', 'ak', 'aka', 0),
(5, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Albanian', 'sq', 'sqi', 0),
(6, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Amharic', 'am', 'amh', 0),
(7, '2016-09-09 17:46:47', '2016-09-09 17:46:47', 'Arabic', 'ar', 'ara', 0),
(8, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Aragonese', 'an', 'arg', 0),
(9, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Armenian', 'hy', 'hye', 0),
(10, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Assamese', 'as', 'asm', 0),
(11, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Avaric', 'av', 'ava', 0),
(12, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Avestan', 'ae', 'ave', 0),
(13, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Aymara', 'ay', 'aym', 0),
(14, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Azerbaijani', 'az', 'aze', 0),
(15, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bambara', 'bm', 'bam', 0),
(16, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bashkir', 'ba', 'bak', 0),
(17, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Basque', 'eu', 'eus', 0),
(18, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Belarusian', 'be', 'bel', 0),
(19, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bengali', 'bn', 'ben', 0),
(20, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bihari', 'bh', 'bih', 0),
(21, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bislama', 'bi', 'bis', 0),
(22, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bosnian', 'bs', 'bos', 0),
(23, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Breton', 'br', 'bre', 0),
(24, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Bulgarian', 'bg', 'bul', 0),
(25, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Burmese', 'my', 'mya', 0),
(26, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Catalan', 'ca', 'cat', 0),
(27, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Chamorro', 'ch', 'cha', 0),
(28, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Chechen', 'ce', 'che', 0),
(29, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Chichewa', 'ny', 'nya', 0),
(30, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Chinese', 'zh', 'zho', 0),
(31, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Church Slavic', 'cu', 'chu', 0),
(32, '2016-09-09 17:46:48', '2016-09-09 17:46:48', 'Chuvash', 'cv', 'chv', 0),
(33, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Cornish', 'kw', 'cor', 0),
(34, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Corsican', 'co', 'cos', 0),
(35, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Cree', 'cr', 'cre', 0),
(36, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Croatian', 'hr', 'hrv', 0),
(37, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Czech', 'cs', 'ces', 0),
(38, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Danish', 'da', 'dan', 0),
(39, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Divehi', 'dv', 'div', 0),
(40, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Dutch', 'nl', 'nld', 0),
(41, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Dzongkha', 'dz', 'dzo', 0),
(42, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'English', 'en', 'eng', 1),
(43, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Esperanto', 'eo', 'epo', 0),
(44, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Estonian', 'et', 'est', 0),
(45, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Ewe', 'ee', 'ewe', 0),
(46, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Faroese', 'fo', 'fao', 0),
(47, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Fijian', 'fj', 'fij', 0),
(48, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Finnish', 'fi', 'fin', 0),
(49, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'French', 'fr', 'fra', 0),
(50, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Fulah', 'ff', 'ful', 0),
(51, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Galician', 'gl', 'glg', 0),
(52, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Ganda', 'lg', 'lug', 0),
(53, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Georgian', 'ka', 'kat', 0),
(54, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'German', 'de', 'deu', 0),
(55, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Greek', 'el', 'ell', 0),
(56, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Guaran', 'gn', 'grn', 0),
(57, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Gujarati', 'gu', 'guj', 0),
(58, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Haitian', 'ht', 'hat', 0),
(59, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Hausa', 'ha', 'hau', 0),
(60, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Hebrew', 'he', 'heb', 0),
(61, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Herero', 'hz', 'her', 0),
(62, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Hindi', 'hi', 'hin', 0),
(63, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Hiri Motu', 'ho', 'hmo', 0),
(64, '2016-09-09 17:46:49', '2016-09-09 17:46:49', 'Hungarian', 'hu', 'hun', 0),
(65, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Icelandic', 'is', 'isl', 0),
(66, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Ido', 'io', 'ido', 0),
(67, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Igbo', 'ig', 'ibo', 0),
(68, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Indonesian', 'id', 'ind', 0),
(69, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Interlingua (International Auxiliary Language Association)', 'ia', 'ina', 0),
(70, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Interlingue', 'ie', 'ile', 0),
(71, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Inuktitut', 'iu', 'iku', 0),
(72, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Inupiaq', 'ik', 'ipk', 0),
(73, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Irish', 'ga', 'gle', 0),
(74, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Italian', 'it', 'ita', 0),
(75, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Japanese', 'ja', 'jpn', 0),
(76, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Javanese', 'jv', 'jav', 0),
(77, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kalaallisut', 'kl', 'kal', 0),
(78, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kannada', 'kn', 'kan', 0),
(79, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kanuri', 'kr', 'kau', 0),
(80, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kashmiri', 'ks', 'kas', 0),
(81, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kazakh', 'kk', 'kaz', 0),
(82, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Khmer', 'km', 'khm', 0),
(83, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kikuyu', 'ki', 'kik', 0),
(84, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kinyarwanda', 'rw', 'kin', 0),
(85, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kirghiz', 'ky', 'kir', 0),
(86, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kirundi', 'rn', 'run', 0),
(87, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Komi', 'kv', 'kom', 0),
(88, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kongo', 'kg', 'kon', 0),
(89, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Korean', 'ko', 'kor', 0),
(90, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kurdish', 'ku', 'kur', 0),
(91, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Kwanyama', 'kj', 'kua', 0),
(92, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Lao', 'lo', 'lao', 0),
(93, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Latin', 'la', 'lat', 0),
(94, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Latvian', 'lv', 'lav', 0),
(95, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Limburgish', 'li', 'lim', 0),
(96, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Lingala', 'ln', 'lin', 0),
(97, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Lithuanian', 'lt', 'lit', 0),
(98, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Luba-Katanga', 'lu', 'lub', 0),
(99, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Luxembourgish', 'lb', 'ltz', 0),
(100, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Macedonian', 'mk', 'mkd', 0),
(101, '2016-09-09 17:46:50', '2016-09-09 17:46:50', 'Malagasy', 'mg', 'mlg', 0),
(102, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Malay', 'ms', 'msa', 0),
(103, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Malayalam', 'ml', 'mal', 0),
(104, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Maltese', 'mt', 'mlt', 0),
(105, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Manx', 'gv', 'glv', 0),
(106, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'MÃƒÆ’Ã', 'mi', 'mri', 0),
(107, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Marathi', 'mr', 'mar', 0),
(108, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Marshallese', 'mh', 'mah', 0),
(109, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Mongolian', 'mn', 'mon', 0),
(110, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Nauru', 'na', 'nau', 0),
(111, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Navajo', 'nv', 'nav', 0),
(112, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Ndonga', 'ng', 'ndo', 0),
(113, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Nepali', 'ne', 'nep', 0),
(114, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'North Ndebele', 'nd', 'nde', 0),
(115, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Northern Sami', 'se', 'sme', 0),
(116, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Norwegian', 'no', 'nor', 0),
(117, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Norwegian BokmÃƒÆ’Ã', 'nb', 'nob', 0),
(118, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Norwegian Nynorsk', 'nn', 'nno', 0),
(119, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Occitan', 'oc', 'oci', 0),
(120, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Ojibwa', 'oj', 'oji', 0),
(121, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Oriya', 'or', 'ori', 0),
(122, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Oromo', 'om', 'orm', 0),
(123, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Ossetian', 'os', 'oss', 0),
(124, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'PÃƒÆ’Ã', 'pi', 'pli', 0),
(125, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Panjabi', 'pa', 'pan', 0),
(126, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Pashto', 'ps', 'pus', 0),
(127, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Persian', 'fa', 'fas', 0),
(128, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Polish', 'pl', 'pol', 0),
(129, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Portuguese', 'pt', 'por', 0),
(130, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Quechua', 'qu', 'que', 0),
(131, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Raeto-Romance', 'rm', 'roh', 0),
(132, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Romanian', 'ro', 'ron', 0),
(133, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Russian', 'ru', 'rus', 0),
(134, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Samoan', 'sm', 'smo', 0),
(135, '2016-09-09 17:46:51', '2016-09-09 17:46:51', 'Sango', 'sg', 'sag', 0),
(136, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sanskrit', 'sa', 'san', 0),
(137, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sardinian', 'sc', 'srd', 0),
(138, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Scottish Gaelic', 'gd', 'gla', 0),
(139, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Serbian', 'sr', 'srp', 0),
(140, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Shona', 'sn', 'sna', 0),
(141, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sichuan Yi', 'ii', 'iii', 0),
(142, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sindhi', 'sd', 'snd', 0),
(143, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sinhala', 'si', 'sin', 0),
(144, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Slovak', 'sk', 'slk', 0),
(145, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Slovenian', 'sl', 'slv', 0),
(146, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Somali', 'so', 'som', 0),
(147, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'South Ndebele', 'nr', 'nbl', 0),
(148, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Southern Sotho', 'st', 'sot', 0),
(149, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Spanish', 'es', 'spa', 1),
(150, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Sundanese', 'su', 'sun', 0),
(151, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Swahili', 'sw', 'swa', 0),
(152, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Swati', 'ss', 'ssw', 0),
(153, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Swedish', 'sv', 'swe', 0),
(154, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tagalog', 'tl', 'tgl', 0),
(155, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tahitian', 'ty', 'tah', 0),
(156, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tajik', 'tg', 'tgk', 0),
(157, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tamil', 'ta', 'tam', 0),
(158, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tatar', 'tt', 'tat', 0),
(159, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Telugu', 'te', 'tel', 0),
(160, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Thai', 'th', 'tha', 0),
(161, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tibetan', 'bo', 'bod', 0),
(162, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tigrinya', 'ti', 'tir', 0),
(163, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tonga', 'to', 'ton', 0),
(164, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Traditional Chinese', 'zh', 'zh-', 0),
(165, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tsonga', 'ts', 'tso', 0),
(166, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Tswana', 'tn', 'tsn', 0),
(167, '2016-09-09 17:46:52', '2016-09-09 17:46:52', 'Turkish', 'tr', 'tur', 0),
(168, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Turkmen', 'tk', 'tuk', 0),
(169, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Twi', 'tw', 'twi', 0),
(170, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Uighur', 'ug', 'uig', 0),
(171, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Ukrainian', 'uk', 'ukr', 0),
(172, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Urdu', 'ur', 'urd', 0),
(173, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Uzbek', 'uz', 'uzb', 0),
(174, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Venda', 've', 'ven', 0),
(175, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Vietnamese', 'vi', 'vie', 0),
(176, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'VolapÃƒÆ’Ã', 'vo', 'vol', 0),
(177, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Walloon', 'wa', 'wln', 0),
(178, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Welsh', 'cy', 'cym', 0),
(179, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Western Frisian', 'fy', 'fry', 0),
(180, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Wolof', 'wo', 'wol', 0),
(181, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Xhosa', 'xh', 'xho', 0),
(182, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Yiddish', 'yi', 'yid', 0),
(183, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Yoruba', 'yo', 'yor', 0),
(184, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Zhuang', 'za', 'zha', 0),
(185, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Zulu', 'zu', 'zul', 0),
(186, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 'Chinese', 'ch', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `late_payment_details`
--

CREATE TABLE IF NOT EXISTS `late_payment_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `item_user_id` bigint(20) unsigned DEFAULT NULL,
  `booking_start_date` datetime NOT NULL,
  `booking_end_date` datetime NOT NULL,
  `checkin_date` datetime NOT NULL,
  `checkout_date` datetime NOT NULL,
  `booking_amount` double(10,2) DEFAULT '0.00',
  `late_checkout_fee` double(10,2) DEFAULT '0.00',
  `extra_time_taken` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `late_payment_details_id_index` (`id`),
  KEY `late_payment_details_item_user_id_index` (`item_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `to_user_id` bigint(20) unsigned DEFAULT NULL,
  `message_id` bigint(20) unsigned DEFAULT NULL,
  `message_content_id` bigint(20) unsigned DEFAULT NULL,
  `message_folder_id` bigint(20) NOT NULL,
  `messageable_id` bigint(20) NOT NULL,
  `item_user_status_id` bigint(20) unsigned DEFAULT NULL,
  `dispute_status_id` bigint(20) unsigned DEFAULT NULL,
  `messageable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_sender` tinyint(1) NOT NULL,
  `is_starred` tinyint(1) NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_archived` tinyint(1) NOT NULL,
  `is_review` tinyint(1) NOT NULL,
  `is_communication` tinyint(1) NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_id_index` (`id`),
  KEY `messages_user_id_index` (`user_id`),
  KEY `messages_to_user_id_index` (`to_user_id`),
  KEY `messages_message_id_index` (`message_id`),
  KEY `messages_message_content_id_index` (`message_content_id`),
  KEY `messages_item_user_status_id_index` (`item_user_status_id`),
  KEY `messages_dispute_status_id_index` (`dispute_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_contents`
--

CREATE TABLE IF NOT EXISTS `message_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `admin_suspend` tinyint(1) NOT NULL,
  `is_system_flagged` tinyint(1) NOT NULL,
  `detected_suspicious_words` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_contents_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2016_03_23_000001_create_discount_types_table', 1),
('2016_03_24_000001_create_countries_table', 1),
('2016_03_24_000002_create_states_table', 1),
('2016_03_24_000003_create_cities_table', 1),
('2016_03_24_000004_create_ips_table', 1),
('2016_03_24_000005_create_roles_table', 1),
('2016_03_24_000006_create_users_table', 1),
('2016_03_24_000007_create_user_profiles_table', 1),
('2016_03_24_000008_create_contacts_table', 1),
('2016_03_24_000009_create_languages_table', 1),
('2016_03_24_000010_create_pages_table', 1),
('2016_03_24_000011_create_user_logins_table', 1),
('2016_03_24_000012_create_providers_table', 1),
('2016_03_24_000013_create_provider_users_table', 1),
('2016_03_24_000014_create_money_transfer_accounts_table', 1),
('2016_03_24_000015_create_withdrawal_statuses_table', 1),
('2016_03_24_000016_create_user_cash_withdrawals_table', 1),
('2016_03_24_000017_create_setting_categories_table', 1),
('2016_03_24_000018_create_settings_table', 1),
('2016_03_24_000019_create_currencies_table', 1),
('2016_03_24_000020_create_currency_conversions_table', 1),
('2016_03_24_000021_create_currency_conversion_histories_table', 1),
('2016_03_24_000022_create_items_table', 1),
('2016_03_24_000023_create_item_user_statuses_table', 1),
('2016_03_24_000024_create_coupons_table', 1),
('2016_03_24_000025_create_cancellation_types_table', 1),
('2016_03_24_000026_create_transaction_types_table', 1),
('2016_03_24_000027_create_counter_locations_table', 1),
('2016_03_24_000028_create_dispute_statuses_table', 1),
('2016_03_24_000029_create_item_users_table', 1),
('2016_03_24_000030_create_email_templates_table', 1),
('2016_03_24_000031_create_attachments_table', 1),
('2016_03_24_000032_create_transactions_table', 1),
('2016_03_24_000035_create_message_contents_table', 1),
('2016_03_24_000036_create_messages_table', 1),
('2016_04_28_000037_create_sudopay_transaction_logs_table', 1),
('2016_04_28_000038_create_sudopay_payment_groups_table', 1),
('2016_04_28_000039_create_sudopay_payment_gateways_table', 1),
('2016_04_28_000040_create_sudopay_payment_gateways_users_table', 1),
('2016_05_10_000041_create_sudopay_ipn_logs_table', 1),
('2016_05_10_000043_create_paypal_transaction_logs_table', 1),
('2016_05_10_000044_create_feedbacks_table', 1),
('2016_05_10_000045_apirequests', 1),
('2016_05_11_000046_create_user_add_wallet_amounts_table', 1),
('2016_05_11_000047_create_duration_types_table', 1),
('2016_05_17_000048_create_dispute_types_table', 1),
('2016_05_17_000049_create_dispute_closed_types_table', 1),
('2016_05_17_000050_create_item_user_disputes_table', 1),
('2016_05_18_000051_create_wallet_transaction_logs_table', 1),
('2016_06_02_000052_create_vehicle_companies_table', 1),
('2016_06_02_000053_create_vehicle_types_table', 1),
('2016_06_02_000054_create_vehicle_makes_table', 1),
('2016_06_02_000055_create_vehicle_models_table', 1),
('2016_06_02_000056_create_vehicle_type_prices_table', 1),
('2016_06_02_000057_create_fuel_types_table', 1),
('2016_06_02_000058_create_vehicles_table', 1),
('2016_06_02_000060_create_vehicle_special_prices_table', 1),
('2016_06_02_000061_create_item_user_additional_charges_table', 1),
('2016_06_02_000062_create_surcharges_table', 1),
('2016_06_02_000063_create_vehicle_type_surcharges_table', 1),
('2016_06_02_000064_create_taxes_table', 1),
('2016_06_02_000065_create_vehicle_type_taxes_table', 1),
('2016_06_02_000066_create_extra_accessories_table', 1),
('2016_06_02_000067_create_vehicle_type_extra_accessories_table', 1),
('2016_06_02_000068_create_insurances_table', 1),
('2016_06_02_000069_create_vehicle_type_insurances_table', 1),
('2016_06_02_000070_create_fuel_options_table', 1),
('2016_06_02_000071_create_vehicle_type_fuel_options_table', 1),
('2016_06_02_000072_create_counter_location_vehicle_table', 1),
('2016_06_10_000073_create_unavailable_vehicles_table', 1),
('2016_06_10_000074_create_late_payment_details_table', 1),
('2016_06_16_000075_create_booker_details_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `money_transfer_accounts`
--

CREATE TABLE IF NOT EXISTS `money_transfer_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `account` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `money_transfer_accounts_id_index` (`id`),
  KEY `money_transfer_accounts_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_content` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pages_id_index` (`id`),
  KEY `pages_language_id_index` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `created_at`, `updated_at`, `language_id`, `title`, `slug`, `page_content`, `is_active`) VALUES
(1, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 42, 'Terms and conditions', 'terms-and-conditions', 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.\r\n\r\nLorem Ipsum has been the industry''s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\r\n\r\nLorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.\r\n\r\nWhen computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.\r\n\r\nThey use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.', 0),
(2, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 42, 'About Us', 'about-us', '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>', 0),
(3, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 42, 'Privacy Policy', 'privacy_policy', '<p>Coming soon</p>', 0),
(4, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 49, 'Terms and conditions', 'terms-and-conditions', 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.\r\n\r\nLorem Ipsum has been the industry''s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\r\n\r\nLorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.\r\n\r\nWhen computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.\r\n\r\nThey use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.', 0),
(5, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 49, 'About Us', 'about-us', '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>', 0),
(6, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 49, 'Privacy Policy', 'privacy_policy', '<p>Coming soon</p>', 0),
(7, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 149, 'Terms and conditions', 'terms-and-conditions', 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.\r\n\r\nLorem Ipsum has been the industry''s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.\r\n\r\nLorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.\r\n\r\nWhen computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.\r\n\r\nThey use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.', 0),
(8, '2016-09-09 17:46:53', '2016-09-09 17:46:53', 149, 'About Us', 'about-us', '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>', 0),
(9, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 149, 'Privacy Policy', 'privacy_policy', '<p>Coming soon</p>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paypal_transaction_logs`
--

CREATE TABLE IF NOT EXISTS `paypal_transaction_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `payment_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_transaction_logable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_transaction_logable_id` bigint(20) unsigned NOT NULL,
  `paypal_pay_key` text COLLATE utf8_unicode_ci NOT NULL,
  `payer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorization_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capture_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `void_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refund_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buyer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buyer_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_transaction_fee` double(10,2) NOT NULL DEFAULT '0.00',
  `fee_payer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paypal_transaction_logs_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `button_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `providers_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `created_at`, `updated_at`, `name`, `secret_key`, `api_key`, `icon_class`, `button_class`, `is_active`, `display_order`) VALUES
(1, '2016-09-09 17:46:44', '2016-09-09 17:46:44', 'Facebook', '19dff7bada02624c89af8d6d94977cb8', '2003061299919443', 'fa-facebook', 'btn-facebook', 1, 1),
(2, '2016-09-09 17:46:44', '2016-09-09 17:46:44', 'Twitter', '3WY4tkA6eEtTNPdU6lvZuIBc4Rqp2kOis9TMJd8lvelAL3g1gu', 'G9vRaWhm11QcDMJ6TrovMcFdP', 'fa-twitter', 'btn-twitter', 1, 2),
(3, '2016-09-09 17:46:44', '2016-09-09 17:46:44', 'Google', 'Y4Rtr7apXX5N0rXE6Ifa5FPJ', '95821807561-2jm168ubd9rccv3en94lu7fn6b1a0hc6.apps.googleusercontent.com', 'fa-google', 'btn-google', 1, 3),
(4, '2016-09-09 17:46:44', '2016-09-09 17:46:44', 'Github', 'ef3761332d0b2c970513712c1fe6b3da371b76b6', '8beb15c45bc7d1d71510', 'fa-github', 'btn-github', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `provider_users`
--

CREATE TABLE IF NOT EXISTS `provider_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `provider_id` bigint(20) unsigned DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_id` bigint(20) NOT NULL,
  `is_connected` tinyint(1) NOT NULL,
  `profile_picture_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_users_id_index` (`id`),
  KEY `provider_users_user_id_index` (`user_id`),
  KEY `provider_users_provider_id_index` (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'admin'),
(2, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `setting_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_id_index` (`id`),
  KEY `settings_setting_category_id_index` (`setting_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `created_at`, `updated_at`, `setting_category_id`, `name`, `value`, `label`, `description`, `display_order`) VALUES
(1, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.name', 'RentnRide', 'Site Name', 'This name will be used in all pages, emails.', 1),
(2, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.version', 'v1.0b.01', 'Site Version', 'This is current site version.', 2),
(3, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.dafault_language', 'en', 'Site Dafault Language', 'This is default language to be used in all pages throughout site.', 3),
(4, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.currency_code', 'USD', 'Currency Code', 'The selected currency will be used in site to display as default currency in all pages. (Replaced with user selected currency)', 4),
(5, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.contact_email', 'productdemo.admin+contact@gmail.com', 'Contact Email Address', 'This is the email address to which you will receive the mail from contact form.', 5),
(6, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.reply_to_email', 'productdemo.admin+reply@gmail.com', 'Reply Email Address', '"Reply-To" email header for all emails. Leave it empty to receive replies as usual (to "From" email address).', 6),
(7, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 1, 'site.from_email', 'productdemo.admin+from@gmail.com', 'From Email Address', 'This is the email address that will appear in the "From" field of all emails sent from the site.', 7),
(8, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'meta.keywords', 'Agriya, RentnRide, Ahsan', 'Meta keywords', 'These are the keywords used for improving search engine results of our site. (Comma separated for multiple keywords.)', 1),
(9, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'meta.description', 'RentnRide', 'Mets description', 'This is the short description of your site, used by search engines on search result pages to display preview snippets for a given page.', 2),
(10, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'site.robots', '', 'Robots', 'Content for robots.txt; (search engine) robots specific instructions. Refer,http://www.robotstxt.org/ for syntax and usage.', 3),
(11, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'twitter.creator', '', 'Twitter Creator', 'Twitter creator id', 4),
(12, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'twitter.site', '', 'Twitter Site', 'Twitter site name', 5),
(13, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 2, 'twitter.card', '', 'Twitter Card', 'Twitter card id', 6),
(14, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 3, 'analytics.is_enabled_google_analytics', '1', 'Enabled Google Analytics?', 'It is for enable/disable the google analytics by giving 0 or 1.', 1),
(15, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 3, 'analytics.google_analytics.profile_id', 'UA-76504232-1', 'Google Analytics Profile ID', 'It is the site''s google analytics profile ID.', 2),
(16, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 3, 'analytics.is_enabled_facebook_pixel', '1', 'Enabled Facebook Pixel?', 'It is for enable/disable the facebook pixel by giving 0 or 1.', 3),
(17, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 3, 'analytics.facebook_analytics.pixel', '6058027948413', 'Facebook Pixel ID', 'It is the site''s facebook analytics pixel ID', 4),
(18, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 4, 'follow.facebook_url', 'https://www.facebook.com/agriya', 'Facebook URL', 'Facebook url of site.', 1),
(19, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 4, 'follow.google_plus_url', 'https://plus.google.com/+AgriyaNews', 'Google Plus URL', 'Google plus url of site.', 2),
(20, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 4, 'follow.linkedin_url', 'https://www.linkedin.com/company/agriya', 'LinkedIn URL', 'LinkedIn url of site.', 3),
(21, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 4, 'follow.foursquare_url', '', 'Foursquare URL', 'Foursquare url of site.', 4),
(22, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 4, 'follow.pinterest_url', '', 'Pinterest URL', 'Pinterest url of site.', 5),
(23, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.flickr_url', '', 'Flickr URL', 'Flickr url of site.', 6),
(24, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.instagram_url', '', 'Instagram URL', 'Instagram url of site.', 7),
(25, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.tumblr_url', '', 'Tumblr URL', 'Tumblr url of site.', 8),
(26, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.youtube_url', '', 'YouTube URL', 'YouTube url of site.', 9),
(27, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.vimeo_url', '', 'Vimeo URL', 'Vimeo url of site.', 10),
(28, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 4, 'follow.twitter_url', '', 'Twitter URL', 'Twitter url of site.', 11),
(29, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_admin_activate_after_register', '0', 'Enable Administrator Approval After Registration', 'On enabling this feature, admin need to approve each user after registration (User cannot login until admin approves)', 1),
(30, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_email_verification_for_register', '0', 'Enable Email Verification After Registration', 'On enabling this feature, user need to verify their email address provided during registration. (User cannot login until email address is verified)', 2),
(31, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_auto_login_after_register', '1', 'Enable Auto Login After Registration', 'On enabling this feature, users will be automatically logged-in after registration. (Only when "Email Verification" & "Admin Approval" is disabled)', 3),
(32, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_admin_mail_after_register', '0', 'Enable Notify Administrator on Each Registration', 'On enabling this feature, notification mail will be sent to administrator on each registration.', 4),
(33, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_welcome_mail_after_register', '1', 'Enable Sending Welcome Mail After Registration	', 'On enabling this feature, users will receive a welcome mail after registration.', 5),
(34, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 5, 'user.is_allow_user_to_switch_language', '1', 'Enable User to Switch Language', 'On enabling this feature, users can change site language to their choice.', 6),
(35, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 6, 'wallet.min_wallet_amount', '10', 'Minimum Wallet Funding Limit', 'This is the minimum amount a user can add to his wallet.', 1),
(36, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 6, 'wallet.max_wallet_amount', '1000', 'Maximum Wallet Funding Limit', 'This is the maximum amount a user can add to his wallet. (If left empty, then, no maximum amount restrictions)', 2),
(37, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 6, 'wallet.wallet_fee_payer', 'Site', 'Who will pay the gateway fee for wallet', 'Fill it [User OR Site]', 3),
(38, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 7, 'user.minimum_withdraw_amount', '2', 'Minimum Wallet Withdrawal Amount', 'This is the minimum amount a user can withdraw from their wallet.', 1),
(39, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 7, 'user.maximum_withdraw_amount', '1000', 'Maximum Wallet Withdrawal Amount', 'This is the maximum amount a user can withdraw from their wallet.', 2),
(40, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.driver', 'log', 'Mail Driver', '(log, smtp, mailgun, mandrill, ses, sparkpost) - Copy and paste the any one driver in value box to make as default mail driver, 1. Log Driver - This driver will write all e-mail messages to your log files for inspection. 2. SMTP Driver - Simple Mail Transfer Protocol is a TCP/IP protocol used to sending and receiving e-mail. 3. Mailgun Driver - Also add domain and secret key. 4. Mandrill Driver - Also add secret key. 5. SES Driver - Also add Amazon SES key, secret and region. 6. SparkPost Driver - Also add secret key.', 1),
(41, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.host', '', 'SMTP Host', 'An email hosting service is an Internet hosting service that operates email servers. add host name of your server.', 2),
(42, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.port', '', 'SMTP Port', 'A port number is a way to identify a specific process to which an Internet or other network message is to be forwarded when it arrives at a server.', 3),
(43, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.encryption', '', 'SMTP Encryption', 'Configure the SMTP encryption key.', 4),
(44, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.username', '', 'SMTP Username', 'Configure the SMTP username.', 5),
(45, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.password', '', 'SMTP Password', 'Configure the SMTP password.', 6),
(46, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.from.address', '', 'SMTP From Mail', 'Configure the SMTP from mail.', 7),
(47, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'mail.from.name', '', 'SMTP From Name', 'Configure the SMTP from name.', 8),
(48, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'services.mailgun.domain', '', 'Mailgun Domain', 'Mailgun domain name to send mail.', 9),
(49, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'services.mailgun.secret', '', 'Mailgun Secret', 'Mailgun secret key to send mail.', 10),
(50, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'services.mandrill.secret', '', 'Mandrill Secret', 'Mandrill secret key to send mail.', 11),
(51, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'services.ses.key', '', 'Amazon SES Key', 'Amazon SES Key to send mail.', 12),
(52, '2016-09-09 17:46:32', '2016-09-09 17:46:32', 8, 'services.ses.secret', '', 'Amazon SES Secret', 'Amazon SES secret to send mail.', 13),
(53, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 8, 'services.ses.region', '', 'Amazon SES Region', 'Amazon SES region to send mail.', 14),
(54, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 8, 'services.sparkpost.secret', '', 'Sparkpost Secret', 'Sparkpost secret key to send mail.', 15),
(55, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 9, 'android_app_store_id', '', 'Android App Store ID', 'This is the App store ID used for Android App', 1),
(56, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 9, 'ios_app_store_id', '', 'iOS App Store ID', 'This is the App store ID used for iOS App', 2),
(57, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 9, 'ipad_app_store_id', '', 'iPad App Store ID', 'This is the App store ID used for iPad App', 3),
(58, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 9, 'windows_phone_app_id', '', 'Windows Phone App ID', 'This is the App ID used for Windows Phone App', 4),
(59, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 9, 'scheme_name', '', 'Scheme Name', 'This is the Scheme Name used in deep link tags', 5),
(60, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 10, 'captcha.site_key', '6Lcsxx0TAAAAAKSHVpbYPh_KJ4zJT8S2Q6bvX3Vx', 'Captcha Site Key', 'Captcha Site Key.', 1),
(61, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 10, 'captcha.secret_key', '6Lcsxx0TAAAAAM3SZqeH8RcLzhLkQvzHjlE4O9MF', 'Captcha Secret Key', 'Captcha Secret Key.', 2),
(62, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 11, 'banner.all_page_top', '<img src="http://placehold.it/728X90" alt ="728X90" width="728" height="90"/>', 'Banner All Page Top', 'Banner for all page top in the site.', 1),
(63, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 11, 'banner.all_page_bottom', '<img src="http://placehold.it/728X90" alt ="728X90" width="728" height="90"/>', 'Banner All Page Bottom', 'Banner for all page bottom in the site.', 2),
(64, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 14, 'site.enabled_plugins', 'SocialLogins, Translations, Vehicles, VehicleRentals, Contacts, Pages, Paypal, VehicleExtraAccessories, VehicleFeedbacks, VehicleFuelOptions, VehicleInsurances, VehicleSurcharges, VehicleTaxes, Withdrawals', 'Enabled Plugins', 'Enabled Plugins list in comma separater', 1),
(65, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 12, 'sudopay.is_live_mode', '', 'Live Mode?', 'This is the site "ZazPay" gateway live mode setting.', 1),
(66, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 12, 'sudopay.sudopay_merchant_id', '', 'Merchant ID', 'This is the site "ZazPay" gateway merchant ID.', 2),
(67, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 12, 'sudopay.sudopay_website_id', '', 'Website ID', 'This is the site "ZazPay" gateway website ID.', 3),
(68, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 12, 'sudopay.sudopay_api_key', '', 'API Key', 'This is the site "ZazPay" gateway api key.', 4),
(69, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 12, 'sudopay.sudopay_secret_string', '', 'Secret', 'This is the site "ZazPay" gateway secret string.', 5),
(70, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.is_live_mode', '0', 'Live Mode?', 'This is the site "PayPal" live mode setting.', 1),
(71, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.api_username', 'poorna.dhivya-developer_api1.agriya.in', 'Username', 'This is the site "PayPal" api username.', 2),
(72, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.api_password', '1401182755', 'Password', 'This is the site "PayPal" api password.', 3),
(73, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.api_signature', 'A8iSWel3wQWHqSE.z.R8W1A8RWdgAz516LDYhCR9FFVT9RPaZfN1yQ3w', 'Signature', 'This is the site "PayPal" api signature.', 4),
(74, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.api_id', 'AVo4aLO9nExsN2n2hxZzPVXdzwyOu23S5HYpNN-tvol8vJaWlgQMpRk3zQjt2KBTaCkSbHlTnQ5GSRXA', 'Api ID', 'This is the site "PayPal" api ID.', 5),
(75, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.api_account_email', 'poorna.dhivya-developer@agriya.in', 'Account Email', 'This is the site "PayPal" api account email.', 6),
(76, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 13, 'paypal.secret', 'EDO5H6v9CLcmFhqejbAGAJUSp2cDvqnOZhfoTW7U7Mkegioeiwc2d190uexR02fiSqR9FwLOxrzzNXGE', 'Secret Key', 'This is the site "PayPal" api secret key.', 7),
(77, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.unit', 'km', 'Vehicle unit type', 'This is for set the distance unit.', 1),
(78, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.company_auto_approve', '1', 'Company Auto Approve?', 'User add company, Auto approve or not ?', 2),
(79, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.auto_approve', '1', 'Vehicle Auto Approve?', 'User add vehicle, Auto approve or not ?', 3),
(80, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.listing_fee', '5', 'Vehicle Listing Fee', 'For free listing post, set it as "0".', 4),
(81, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.no_of_seats', '10', 'Number of seats', 'This is for set the number of seats.', 5),
(82, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.no_of_doors', '10', 'Number of doors', 'This is for set the number of doors.', 6),
(83, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.no_of_gears', '7', 'Number of gears', 'This is for set the number of gears.', 7),
(84, '2016-09-09 17:46:33', '2016-09-09 17:46:33', 15, 'vehicle.no_small_bags', '10', 'Number of small bags', 'This is for set number of small bags.', 8),
(85, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 15, 'vehicle.no_large_bags', '10', 'Number of large bags', 'This is for set the number of small bags.', 9),
(86, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 15, 'vehicle.no_of_airbags', '10', 'Number of air bags', 'This is for set the number of airbags.', 10),
(87, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 15, 'vehicle.driver_min_age', '18', 'Driver Minimum Age', 'This is for set the minimum age of driver', 11),
(88, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 15, 'vehicle.driver_max_age', '50', 'Driver Maximum Age', 'This is for set the maximum age of driver', 12),
(89, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.auto_expire', '1', 'Days after unaccepted booking auto expire', 'This is the maximum number of days after in which unaccepted bookings are automatically expired and amount will be refunded to booker.', 1),
(90, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.is_auto_approve', '1', 'Enable Auto Approval After Vehicle Rental Add', 'On Enabling this feature, booking directly moves to Confirmed status without waiting for acceptance from host.', 2),
(91, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.auto_update_waiting_for_payment_pending_cleared_status', '1', 'Days after booking moves to Waiting For Payment Cleared status', 'This is the maximum number of days for booking moves to Waiting For Payment Cleared status.', 3),
(92, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.days_after_amount_cleared_to_host', '1', 'Days after amount will be cleared to host', 'This is the maximum threshold days after pending payment amount cleared to host and the booking gets completed.', 4),
(93, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.admin_commission_amount', '1', 'Admin commission percentage', 'This is the admin commission percentage which will be calculated and deducted from booking amount.', 5),
(94, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.late_checkout_grace_time', '1', 'Late Checkout Grace Time', 'This is the grace hours of time within the booker has to checkout, to avoid late payment charges', 6),
(95, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 16, 'vehicle_rental.is_host_checkin_and_checkout', '1', 'Enable checkin/checkout for host', 'It is for enable/disable the host to checkin and checkout by giving 0 or 1.', 7),
(96, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 17, 'dispute.discussion_threshold_for_admin_decision', '8', 'Discussion Threshold for Admin Decision', 'Admin will take decision, after given number of conversation between booker and host.', 1),
(97, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 17, 'dispute.days_left_for_disputed_user_to_reply', '5', 'Number of days to reply a dispute', 'Maximum number of days to reply for a dispute raised in booking', 2),
(98, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 17, 'dispute.refund_amount_during_dispute_cancellation', '5', 'Refund Percentage to Booker for Specification Dispute', 'Given percentage will be deduct from booking amount and refund to booker when booker raised dispute if "Doesn''t match the specification as mentioned by the host" and admin decision favored to booker.', 3),
(99, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 17, 'dispute.rating_limit_to_raise_dispute', '3', 'Rating limit to raise Feedback issue', 'Host can only raise the feedback dispute, if the booker rating is below ths limit.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `setting_categories`
--

CREATE TABLE IF NOT EXISTS `setting_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `setting_categories_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `setting_categories`
--

INSERT INTO `setting_categories` (`id`, `created_at`, `updated_at`, `name`, `description`, `display_order`) VALUES
(1, '2016-09-09 17:46:30', '2016-09-09 17:46:30', 'System', 'Manage site name, currency details, language details, email controls.', 1),
(2, '2016-09-09 17:46:30', '2016-09-09 17:46:30', 'SEO', 'Manage content, meta data and other information relevant to browsers or search engines', 2),
(3, '2016-09-09 17:46:30', '2016-09-09 17:46:30', 'Analytics', 'Manage Google and Facebook analytics code here', 3),
(4, '2016-09-09 17:46:30', '2016-09-09 17:46:30', 'Follow Us', 'manage site''s social network links. Enter full URL, Leave it blank if not available.', 4),
(5, '2016-09-09 17:46:30', '2016-09-09 17:46:30', 'Account', 'Manage account settings such as admin approval, email verification, and other site account settings.', 5),
(6, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Wallet', 'Manage wallet related setting such as enabling groupon-like wallet, maximum and minimum funding limit settings.', 6),
(7, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Withdrawal', 'Manage cash withdraw settings for a user such as enabling withdrawal and setting withdraw limit.', 7),
(8, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'E-mail', 'Manage E-mail settings, email will be sent through the email services like Mail, SMTP, Mailgun, Sparkpost, Mandrill, Amazon SES and log. Use any one driver as default mail driver to send mail and add their related settings.', 8),
(9, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Mobile', 'Manage all App Store ID.', 9),
(10, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Captcha', 'Captchas are meant to protect your website from spam and abuse.', 10),
(11, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Banner', 'Banner for all page bottom, all page top.', 11),
(12, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'ZazPay', 'Manage Site''s ZazPay Gateway settings', 12),
(13, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'PayPal', 'Manage Site''s PayPal Gateway settings', 13),
(14, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Plugins', 'Here you can modify site related plugins.', 14),
(15, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Vehicles', 'Here you can set Vehicle based settings.', 15),
(16, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Vehicle Rentals', 'Here you can set Vehicle Rental based settings.', 16),
(17, '2016-09-09 17:46:31', '2016-09-09 17:46:31', 'Disputes', 'Here you can set dispute based settings.', 17);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `states_id_index` (`id`),
  KEY `states_country_id_index` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `created_at`, `updated_at`, `name`, `country_id`, `is_active`) VALUES
(1, '2016-09-09 17:46:40', '2016-09-09 17:46:40', 'Buenos Aires', 11, 1),
(2, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Córdoba', 11, 1),
(3, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Entre Ríos', 11, 1),
(4, '2016-09-09 17:46:41', '2016-09-09 17:46:41', 'Santa Fe', 11, 1),
(5, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Salta', 11, 1),
(6, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Brescia BS', 109, 1),
(7, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Auckland', 159, 1),
(8, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'TamilNadu', 102, 1),
(9, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Karnataka', 102, 1),
(10, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Kerala', 102, 1),
(11, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Maharastra', 102, 1),
(12, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Madhya Pradesh', 102, 1),
(13, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Andhra Pradesh', 102, 1),
(14, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Orissa', 102, 1),
(15, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Punjab', 102, 1),
(16, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Kolkata', 102, 1),
(17, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Assam', 102, 1),
(18, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Bihar', 102, 1),
(19, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Gujarat', 102, 1),
(20, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Jharkhand', 102, 1),
(21, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'Mizoram', 102, 1),
(22, '2016-09-09 17:46:42', '2016-09-09 17:46:42', 'New York', 240, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sudopay_ipn_logs`
--

CREATE TABLE IF NOT EXISTS `sudopay_ipn_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ip` bigint(20) NOT NULL,
  `post_variable` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sudopay_ipn_logs_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sudopay_payment_gateways`
--

CREATE TABLE IF NOT EXISTS `sudopay_payment_gateways` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sudopay_gateway_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sudopay_gateway_details` text COLLATE utf8_unicode_ci NOT NULL,
  `is_marketplace_supported` tinyint(1) NOT NULL DEFAULT '0',
  `sudopay_gateway_id` bigint(20) NOT NULL,
  `sudopay_payment_group_id` bigint(20) unsigned DEFAULT NULL,
  `form_fields_credit_card` text COLLATE utf8_unicode_ci NOT NULL,
  `form_fields_manual` text COLLATE utf8_unicode_ci NOT NULL,
  `form_fields_buyer` text COLLATE utf8_unicode_ci NOT NULL,
  `thumb_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_actions` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_card_types` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_countries` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_credit_card_types` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_currencies` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_languages` text COLLATE utf8_unicode_ci NOT NULL,
  `supported_features_services` text COLLATE utf8_unicode_ci NOT NULL,
  `connect_instruction` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sudopay_payment_gateways_id_index` (`id`),
  KEY `sudopay_payment_gateways_sudopay_payment_group_id_index` (`sudopay_payment_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sudopay_payment_gateway_users`
--

CREATE TABLE IF NOT EXISTS `sudopay_payment_gateway_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `sudopay_payment_gateway_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sudopay_payment_gateway_users_id_index` (`id`),
  KEY `sudopay_payment_gateway_users_user_id_index` (`user_id`),
  KEY `sudopay_payment_gateway_users_sudopay_payment_gateway_id_index` (`sudopay_payment_gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sudopay_payment_groups`
--

CREATE TABLE IF NOT EXISTS `sudopay_payment_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sudopay_group_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sudopay_payment_groups_id_index` (`id`),
  KEY `sudopay_payment_groups_sudopay_group_id_index` (`sudopay_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sudopay_transaction_logs`
--

CREATE TABLE IF NOT EXISTS `sudopay_transaction_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `payment_id` bigint(20) NOT NULL,
  `sudopay_transaction_logable_id` bigint(20) NOT NULL,
  `sudopay_transaction_logable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sudopay_pay_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `merchant_id` bigint(20) NOT NULL,
  `gateway_id` bigint(20) NOT NULL,
  `gateway_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buyer_id` bigint(20) NOT NULL,
  `buyer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `buyer_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sudopay_transaction_fee` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `sudopay_transaction_logs_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `surcharges`
--

CREATE TABLE IF NOT EXISTS `surcharges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `surcharges_id_index` (`id`),
  KEY `surcharges_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `surcharges`
--

INSERT INTO `surcharges` (`id`, `created_at`, `updated_at`, `name`, `slug`, `short_description`, `description`, `is_active`) VALUES
(1, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Excluded Locations Additional Fees', '', 'Excluded Locations Additional Fees', 'Excluded locations additional fees will be collected as $xx for each mile / km.', 1),
(2, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Delivery & Collection', '', 'Delivery & Collection', 'If this Service is purchased host will delicer and collect the vehicle form booker', 1),
(3, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'ACFR', '', 'Airport Concession Fee Recovery / Hotel and Airport Concession Fee Recovery / Fixed Based Operator & Airport Concession Fee Recovery (ACFR)', 'If this service is purchased, some concession will be given for Airport locations', 1),
(4, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Concession / Commission Fee Recovery (CFR) / Concession Recovery Surcharge Fee', '', 'Concession / Commission Fee Recovery (CFR) / Concession Recovery Surcharge Fee', 'If this service is purchased, some concession will be given for Commission', 1),
(5, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Hospitality Fee', '', 'Hospitality Fee', 'For maitain a good hospitality this surachrge will be applied', 1),
(6, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Tourism Surcharge', '', 'Tourism Surcharge', 'For Tourism packages this surchargre is applied', 1),
(7, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Domestic Security Fee', '', 'Domestic Security Fee', 'A domestic security fee of $xx per day applies to rentals.', 1),
(8, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Customer Transportation Fee / Transportation Fee / Transportation Facility Fee', '', 'Customer Transportation Fee / Transportation Fee / Transportation Facility Fee', 'The city requires that all car rental companies collect this fee. The money collected is used to pay for airport services. This fee is mandated by the airport', 1),
(9, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Energy Surcharge', '', 'Energy Surcharge', 'The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,', 1),
(10, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Garage Recoupment Surcharge', '', 'Garage Recoupment Surcharge', 'This fee is to reimburse for certain service facilities charges at the rental location.', 1),
(11, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Rental Contract Fee', '', 'Rental Contract Fee', 'The Airport requires that all vehicle rental companies collect this fee. The money collected is used to pay for vehicle rental facilities.', 1),
(12, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Property Tax, Title/License Reimbursement / Vehicle Licensing Cost Recovery / Vehicle Licensing Fee / Recovery Surcharge / Vehicle Licensing and Business Licensing Fee', '', 'This fee is recovery of the proportionate amount of vehicle registration', 'This fee is recovery of the proportionate amount of vehicle registration, licensing and related fees applicable to a rental.', 1),
(13, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Operation and Maintenance Recovery Fee (O & M fee)', '', 'This fee is imposed to recover amounts it pays toward the operation and maintenance of a consolidated car rental facility at the airport, above the costs paid through the transportation charge. This is not government mandated.', '', 1),
(14, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'CA Tourism Fee', '', 'CA Tourism Fee', 'Car rental companies are required by law to pay monthly assessments to the California Travel and Tourism Commission on revenue generated at either airport or hotel rental locations. This fee has been calculated to recover such assessment on an applicable rental basis.', 1),
(15, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'An Alteration Fee', '', 'An Alteration Fee', 'A prepaid booking can be changed up to 48 hours before the start of the rental (depending on availability). An alteration fee of $28.00 (exclusive of sales tax) applies.', 1),
(16, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'After Hours Service', '', ' After Hours Service', 'Extra payment may be applied if rental end date exceeded.', 1),
(17, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'The Age Differential Charge (car based)', '', 'The Age Differential Charge (car based)', 'The minimum age is 18 without an additional Age Differential Charge, and below 18 with an additional Age Differential Charge.', 1),
(18, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Authorization Amount', '', 'Authorization Amount', 'Authorization amount up to $xxx plus the estimated charges on a customers card, given certain conditions that will be outlined at time of rental.', 1),
(19, '2016-09-09 17:47:00', '2016-09-09 17:47:00', 'Credit Check Fee', '', 'Credit Check Fee', 'There is a US$15 nonrefundable processing fee which offsets the cost to have a modified credit check performed on the applicant.', 1),
(20, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Frequent Flyer surcharge', '', 'Frequent Flyer surcharge', 'Frequent Flyer surcharge equivalent to up to $ 1.00 per day.', 1),
(21, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Smoking Fee', '', 'Smoking Fee', 'Smoking is prohibited in all vehicles. A maximum charge of $ 175.00 can apply when smoking has occurred during the rental.', 1),
(22, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Premium Emergency Roadside Assistance', '', 'Premium Emergency Roadside Assistance', ' It is an optional service which, if accepted, reduces your financial liability for services required to remedy non-mechanical problems of the vehicle and/or problems resulting from an accident or collision.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE IF NOT EXISTS `taxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `taxes_id_index` (`id`),
  KEY `taxes_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `created_at`, `updated_at`, `name`, `slug`, `short_description`, `description`, `is_active`) VALUES
(1, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Motor Vehicle Rental Tax', '', 'Motor Vehicle Rental Tax', 'This is for using the motor vehicle for rental', 1),
(2, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Sales Tax', '', 'Sales Tax (6.0%)', 'Sales Tax', 1),
(3, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Tax for Accessories', '', ' A tax of 6%-18% applies to all accessory charges', 'A tax of 6%-18% applies to all accessory charges.', 1),
(4, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Business Tax', '', 'Business Tax', 'When renting a business tax of USD 0.3% per rental agreement applies (Business Tax =G5)', 1),
(5, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Motor Vehicle Licensing Tax', '', 'Motor Vehicle Licensing Tax', 'The all rental car companies collect a USD 2.75 Motor Vehicle Licensing Tax. This charge is not included in your approximate rental costs.', 1),
(6, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'Motor Vehicle Lessor Tax', '', 'Motor Vehicle Lessor Tax', 'The all car rental company collect a USD 6.00 Motor Vehicle Lessor Tax to finance various city projects. This charge is not included in your approximate rental costs.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `receiver_user_id` bigint(20) unsigned DEFAULT NULL,
  `transactionable_id` bigint(20) NOT NULL,
  `transactionable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_type_id` bigint(20) unsigned DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8_unicode_ci,
  `payment_gateway_id` bigint(20) unsigned DEFAULT NULL,
  `gateway_fees` double(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `transactions_id_index` (`id`),
  KEY `transactions_user_id_index` (`user_id`),
  KEY `transactions_receiver_user_id_index` (`receiver_user_id`),
  KEY `transactions_transaction_type_id_index` (`transaction_type_id`),
  KEY `transactions_payment_gateway_id_index` (`payment_gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

CREATE TABLE IF NOT EXISTS `transaction_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_type_group_id` bigint(20) NOT NULL,
  `is_credit` tinyint(1) NOT NULL,
  `is_credit_to_receiver` tinyint(1) NOT NULL,
  `is_credit_to_admin` tinyint(1) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `message_for_receiver` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message_for_admin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_variables` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_types_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `transaction_types`
--

INSERT INTO `transaction_types` (`id`, `created_at`, `updated_at`, `name`, `transaction_type_group_id`, `is_credit`, `is_credit_to_receiver`, `is_credit_to_admin`, `message`, `message_for_receiver`, `message_for_admin`, `transaction_variables`) VALUES
(1, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Amount added to wallet', 1, 1, 0, 1, 'Amount added to wallet', '', '##USER## added amount to own wallet', 'USER'),
(8, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Rent a item', 4, 0, 1, 0, 'Rent ###ORDER_NO## a item ##ITEM##', '##BOOKER## booked ###ORDER_NO## item ##ITEM##', '##BOOKER## booked ###ORDER_NO## a item ##ITEM##', 'BOOKER, ITEM, ORDER_NO'),
(9, '2016-09-09 17:46:54', '2016-09-09 17:46:54', 'Refund for expired renting', 4, 0, 1, 0, 'renting ###ORDER_NO## expired for item ##ITEM##', 'renting ###ORDER_NO## expired for item ##ITEM##', 'renting ###ORDER_NO## expired for item ##ITEM##', 'ITEM, ORDER_NO'),
(10, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Refund for rejected renting', 4, 0, 1, 0, 'You have rejected renting ###ORDER_NO## for item ##ITEM##', '##HOST## rejected renting ###ORDER_NO## for item ##ITEM##', '##HOST## rejected renting ###ORDER_NO## for item ##ITEM##', 'ITEM, ORDER_NO, HOST'),
(11, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Refund for cancelled renting', 4, 0, 1, 0, 'Cancelled renting ###ORDER_NO## for item ##ITEM##', 'Cancelled renting ###ORDER_NO## for item ##ITEM##', 'Cancelled booking ###ORDER_NO## for item ##ITEM##', 'ITEM, ORDER_NO'),
(12, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Refund for admin cancelled renting', 4, 0, 1, 0, 'Administrator cancelled renting ###ORDER_NO## for item ##ITEM##', 'Administrator cancelled renting ###ORDER_NO## for item ##ITEM##', 'Cancelled renting ###ORDER_NO## for item ##ITEM##', 'ITEM, ORDER_NO'),
(13, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Renting host amount cleared', 3, 0, 1, 0, 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##', 'Vehicle Rental ###ORDER_NO## amount cleared for item ##ITEM##', 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##', 'ITEM, ORDER_NO, HOST'),
(14, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Cash withdrawal request', 2, 0, 0, 0, 'Cash withdrawal request made by you', '', 'Cash withdrawal request made by ##USER##', ''),
(15, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Cash withdrawal request approved', 2, 0, 0, 0, 'Your cash withdrawal request approved by Administrator', '', 'You (Administrator) have approved ##USER## cash withdrawal request', 'USER'),
(16, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Cash withdrawal request rejected', 2, 1, 0, 1, 'Amount refunded for rejected cash withdrawal request', '', 'Amount refunded to ##USER## for rejected cash withdrawal request', 'USER'),
(17, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Cash withdrawal request paid', 2, 1, 1, 1, 'Cash withdraw request amount paid to you', '', 'Cash withdraw request amount paid to ##USER##', 'USER'),
(18, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Cash withdrawal request failed', 2, 0, 1, 0, 'Amount refunded for failed cash withdrawal request', '', 'Amount refunded to ##USER## for failed cash withdrawal request', 'USER'),
(19, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Admin add fund to wallet', 1, 1, 0, 1, 'Administrator added fund to your wallet', 'Administrator added fund to your wallet', 'Added fund to ##USER## wallet', 'USER'),
(20, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Admin deduct fund from wallet', 1, 0, 0, 1, 'Administrator deducted fund from your wallet', 'Administrator deducted fund from your wallet', 'Deducted fund from ##USER## wallet', 'USER'),
(21, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Refund For Specification Dispute', 5, 0, 1, 0, 'Specifications dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking ###ORDER_NO##.', 'Specifications dispute resolved favor to you for Item ##ITEM##, booking ###ORDER_NO##.', 'Specifications dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking ###ORDER_NO##.', 'BOOKER, ITEM, ORDER_NO'),
(22, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Refund for wallet', 1, 0, 0, 0, 'Amount refunded to your account', 'Amount refunded to your account', 'Amount refunded to ##USER## account', 'USER'),
(23, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Vehicle Listing Fee Paid', 4, 0, 0, 1, '##ITEM## Vehicle Listing Fee Paid', '##ITEM## vehicle listing fee paid', '##ITEM## vehicle listing fee paid', 'ITEM'),
(24, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Security Deposit Amount Sent To Host', 5, 1, 0, 1, 'Security deposit dispute resolved favor to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'Security deposit dispute resolved favor to you for Item ##ITEM##, booking# ##ORDER_NO##.', 'Security deposit dispute resolved favor to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'ITEM, ORDER_NO, HOST'),
(25, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Secuirty Deposit Amount Refunded To Booker', 5, 0, 1, 0, 'Security deposit dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking# ##ORDER_NO##.', 'Security deposit dispute resolved favor to you for Item ##ITEM##, booking# ##ORDER_NO##.', 'Security deposit amount refunded to ##BOOKER## for Item ##ITEM##, booking# ##ORDER_NO##.', 'ITEM, ORDER_NO, BOOKER'),
(26, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Manual Transfer For Claim Request Amount', 5, 1, 0, 1, 'Manually transferred claiming amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'Manually transferred for claiming amount to you for Item ##ITEM##, booking# ##ORDER_NO##.', 'Manually transferred for claiming amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'HOST, ITEM, ORDER_NO'),
(27, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Manual Transfer For Late Fee Amount', 4, 0, 1, 0, 'Manually transferred late fee amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'Manually transferred late fee amount to you for Item ##ITEM##, booking# ##ORDER_NO##.', 'Manually transferred late fee amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.', 'HOST, ITEM, ORDER_NO'),
(28, '2016-09-09 17:46:55', '2016-09-09 17:46:55', 'Admin Commision Payment', 4, 0, 0, 1, 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.', 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.', 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.', 'ITEM, ORDER_NO');

-- --------------------------------------------------------

--
-- Table structure for table `unavailable_vehicles`
--

CREATE TABLE IF NOT EXISTS `unavailable_vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `item_user_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_dummy` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `unavailable_vehicles_id_index` (`id`),
  KEY `unavailable_vehicles_item_user_id_index` (`item_user_id`),
  KEY `unavailable_vehicles_vehicle_id_index` (`vehicle_id`),
  KEY `unavailable_vehicles_start_date_index` (`start_date`),
  KEY `unavailable_vehicles_end_date_index` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `available_wallet_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `blocked_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `vehicle_count` bigint(20) DEFAULT '0',
  `vehicle_rental_count` bigint(20) DEFAULT '0',
  `vehicle_rental_order_count` bigint(20) DEFAULT '0',
  `user_login_count` bigint(20) NOT NULL DEFAULT '0',
  `is_agree_terms_conditions` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_email_confirmed` tinyint(1) NOT NULL,
  `register_ip_id` bigint(20) unsigned DEFAULT NULL,
  `last_login_ip_id` bigint(20) unsigned DEFAULT NULL,
  `user_avatar_source_id` bigint(20) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pwd_reset_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feedback_count` bigint(20) DEFAULT '0',
  `feedback_rating` double(10,2) DEFAULT '0.00',
  `activate_hash` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_id_index` (`id`),
  KEY `users_role_id_index` (`role_id`),
  KEY `users_register_ip_id_index` (`register_ip_id`),
  KEY `users_last_login_ip_id_index` (`last_login_ip_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created_at`, `updated_at`, `role_id`, `username`, `email`, `password`, `available_wallet_amount`, `blocked_amount`, `vehicle_count`, `vehicle_rental_count`, `vehicle_rental_order_count`, `user_login_count`, `is_agree_terms_conditions`, `is_active`, `is_email_confirmed`, `register_ip_id`, `last_login_ip_id`, `user_avatar_source_id`, `remember_token`, `pwd_reset_token`, `feedback_count`, `feedback_rating`, `activate_hash`) VALUES
(1, '2016-09-09 17:46:45', '2016-09-09 17:46:45', 1, 'admin', 'productdemo.admin@gmail.com', '$2y$10$KG1HCtDofIoOQcTp9vn.4O7sINhhhuj2O4zp/2ZhejN62RsbmqruC', 0.00, 0.00, 0, 0, 0, 0, 1, 1, 1, 1, 1, NULL, NULL, '', 0, 0.00, 0),
(2, '2016-09-09 17:46:45', '2016-09-09 17:46:45', 2, 'host', 'host@gmail.com', '$2y$10$pIkPJgCYzqBw8hoPYcF5le7XsVHUhHyde2YCcGnHQkD5F9q5WB5ku', 0.00, 0.00, 0, 0, 0, 0, 1, 1, 1, 1, 1, NULL, NULL, '', 0, 0.00, 0),
(3, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 2, 'booker', 'booker@gmail.com', '$2y$10$3e5.hFxCisDPJflwc.siBOAZif9/MmiKx0FbOWUfB.OVfgky5xrQu', 0.00, 0.00, 0, 0, 0, 0, 1, 1, 1, 1, 1, NULL, NULL, '', 0, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_add_wallet_amounts`
--

CREATE TABLE IF NOT EXISTS `user_add_wallet_amounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `payment_gateway_id` bigint(20) NOT NULL,
  `user_paypal_connection_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pay_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sudopay_gateway_id` bigint(20) NOT NULL,
  `sudopay_revised_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `sudopay_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_add_wallet_amounts_id_index` (`id`),
  KEY `user_add_wallet_amounts_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_cash_withdrawals`
--

CREATE TABLE IF NOT EXISTS `user_cash_withdrawals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `withdrawal_status_id` bigint(20) unsigned DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `money_transfer_account_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_cash_withdrawals_id_index` (`id`),
  KEY `user_cash_withdrawals_user_id_index` (`user_id`),
  KEY `user_cash_withdrawals_withdrawal_status_id_index` (`withdrawal_status_id`),
  KEY `user_cash_withdrawals_money_transfer_account_id_index` (`money_transfer_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE IF NOT EXISTS `user_logins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_login_ip_id` bigint(20) unsigned DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_logins_id_index` (`id`),
  KEY `user_logins_user_id_index` (`user_id`),
  KEY `user_logins_user_login_ip_id_index` (`user_login_ip_id`),
  KEY `user_logins_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `about_me` text COLLATE utf8_unicode_ci,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_profile_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_profile_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_plus_profile_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin_profile_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube_profile_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profiles_id_index` (`id`),
  KEY `user_profiles_user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `created_at`, `updated_at`, `user_id`, `first_name`, `last_name`, `about_me`, `website`, `facebook_profile_link`, `twitter_profile_link`, `google_plus_profile_link`, `linkedin_profile_link`, `youtube_profile_link`) VALUES
(1, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 1, 'admin', 'admin', 'I am the site admin and will manange all the pages in the site', NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 2, 'alex', 'paul', 'I am the host and will host the vehicles', NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2016-09-09 17:46:46', '2016-09-09 17:46:46', 3, 'john', 'hastings', 'I am the booker and will book the vehicles', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_company_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_make_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_model_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned DEFAULT NULL,
  `driven_kilometer` double(10,2) DEFAULT '0.00',
  `vehicle_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_seats` bigint(20) DEFAULT '0',
  `no_of_doors` bigint(20) DEFAULT '0',
  `no_of_gears` bigint(20) DEFAULT '0',
  `is_manual_transmission` tinyint(1) DEFAULT '0',
  `no_small_bags` bigint(20) DEFAULT '0',
  `no_large_bags` bigint(20) DEFAULT '0',
  `is_ac` tinyint(1) DEFAULT '0',
  `minimum_age_of_driver` bigint(20) DEFAULT '0',
  `mileage` double(10,2) DEFAULT '0.00',
  `is_km` tinyint(1) DEFAULT '0',
  `is_airbag` tinyint(1) DEFAULT '0',
  `no_of_airbags` bigint(20) DEFAULT '0',
  `is_abs` tinyint(1) DEFAULT '0',
  `per_hour_amount` double(10,2) DEFAULT '0.00',
  `per_day_amount` double(10,2) DEFAULT '0.00',
  `fuel_type_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_rental_count` bigint(20) DEFAULT '0',
  `feedback_count` bigint(20) DEFAULT '0',
  `feedback_rating` double(10,2) DEFAULT '0.00',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicles_id_index` (`id`),
  KEY `vehicles_name_index` (`name`),
  KEY `vehicles_user_id_index` (`user_id`),
  KEY `vehicles_vehicle_company_id_index` (`vehicle_company_id`),
  KEY `vehicles_vehicle_make_id_index` (`vehicle_make_id`),
  KEY `vehicles_vehicle_model_id_index` (`vehicle_model_id`),
  KEY `vehicles_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicles_fuel_type_id_index` (`fuel_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_companies`
--

CREATE TABLE IF NOT EXISTS `vehicle_companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double(10,6) DEFAULT '0.000000',
  `longitude` double(10,6) DEFAULT '0.000000',
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vehicle_count` bigint(20) DEFAULT '0',
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_companies_id_index` (`id`),
  KEY `vehicle_companies_user_id_index` (`user_id`),
  KEY `vehicle_companies_name_index` (`name`),
  KEY `vehicle_companies_address_index` (`address`),
  KEY `vehicle_companies_mobile_index` (`mobile`),
  KEY `vehicle_companies_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_makes`
--

CREATE TABLE IF NOT EXISTS `vehicle_makes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vehicle_count` bigint(20) DEFAULT '0',
  `vehicle_model_count` bigint(20) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_makes_id_index` (`id`),
  KEY `vehicle_makes_name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `vehicle_makes`
--

INSERT INTO `vehicle_makes` (`id`, `created_at`, `updated_at`, `name`, `slug`, `vehicle_count`, `vehicle_model_count`, `is_active`) VALUES
(1, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'BMW', 'bmw', 0, 0, 1),
(2, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'TOYOTA', 'toyota', 0, 0, 1),
(3, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'HYUNDAI', 'hyundai', 0, 0, 1),
(4, '2016-09-09 17:47:01', '2016-09-09 17:47:01', 'HONDA', 'honda', 0, 0, 1),
(5, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'MARUTI', 'maruti', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_models`
--

CREATE TABLE IF NOT EXISTS `vehicle_models` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vehicle_make_id` bigint(20) unsigned DEFAULT NULL,
  `vehicle_count` bigint(20) DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_models_id_index` (`id`),
  KEY `vehicle_models_name_index` (`name`),
  KEY `vehicle_models_vehicle_make_id_index` (`vehicle_make_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `vehicle_models`
--

INSERT INTO `vehicle_models` (`id`, `created_at`, `updated_at`, `name`, `slug`, `vehicle_make_id`, `vehicle_count`, `is_active`) VALUES
(1, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'X1', 'x1', 1, 0, 1),
(2, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'X3', 'x3', 1, 0, 1),
(3, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'X5', 'x5', 1, 0, 1),
(4, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Camry', 'camry', 2, 0, 1),
(5, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Corolla Altis', 'corolla-altis', 2, 0, 1),
(6, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Etios', 'etios', 2, 0, 1),
(7, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Innova', 'innova', 2, 0, 1),
(8, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Elantra', 'elantra', 3, 0, 1),
(9, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Eon', 'eon', 3, 0, 1),
(10, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'i20', 'i20', 3, 0, 1),
(11, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Verna', 'verna', 3, 0, 1),
(12, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Amaze', 'amaze', 4, 0, 1),
(13, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Brio', 'brio', 4, 0, 1),
(14, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'City', 'city', 4, 0, 1),
(15, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Jazz', 'jazz', 4, 0, 1),
(16, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'CR-V', 'cr-v', 4, 0, 1),
(17, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Baleno', 'baleno', 5, 0, 1),
(18, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Celerio', 'celerio', 5, 0, 1),
(19, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Swift', 'swift', 5, 0, 1),
(20, '2016-09-09 17:47:02', '2016-09-09 17:47:02', 'Swift Dezire', 'swift-dezire', 5, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_special_prices`
--

CREATE TABLE IF NOT EXISTS `vehicle_special_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `vehicle_type_id` bigint(20) unsigned DEFAULT NULL,
  `discount_percentage` double(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_special_prices_id_index` (`id`),
  KEY `vehicle_special_prices_vehicle_type_id_index` (`vehicle_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE IF NOT EXISTS `vehicle_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `minimum_hour_price` double(10,2) DEFAULT '0.00',
  `maximum_hour_price` double(10,2) DEFAULT '0.00',
  `minimum_day_price` double(10,2) DEFAULT '0.00',
  `maximum_day_price` double(10,2) DEFAULT '0.00',
  `drop_location_differ_unit_price` double(10,2) DEFAULT '0.00',
  `drop_location_differ_additional_fee` double(10,2) DEFAULT '0.00',
  `deposit_amount` double(10,2) DEFAULT '0.00',
  `vehicle_count` bigint(20) DEFAULT '0',
  `late_checkout_addtional_fee` double(10,2) DEFAULT '0.00',
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_types_id_index` (`id`),
  KEY `vehicle_types_name_index` (`name`),
  KEY `vehicle_types_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `created_at`, `updated_at`, `name`, `slug`, `minimum_hour_price`, `maximum_hour_price`, `minimum_day_price`, `maximum_day_price`, `drop_location_differ_unit_price`, `drop_location_differ_additional_fee`, `deposit_amount`, `vehicle_count`, `late_checkout_addtional_fee`, `duration_type_id`, `is_active`) VALUES
(1, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 'Luxury', 'luxury', 50.00, 200.00, 1000.00, 5000.00, 100.00, 100.00, 1000.00, 0, 0.00, NULL, 1),
(2, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 'SUV', 'suv', 40.00, 150.00, 500.00, 1500.00, 50.00, 100.00, 500.00, 0, 0.00, NULL, 1),
(3, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 'Sedan', 'sedan', 50.00, 150.00, 750.00, 2000.00, 75.00, 80.00, 700.00, 0, 0.00, NULL, 1),
(4, '2016-09-09 17:47:03', '2016-09-09 17:47:03', 'Mini', 'mini', 30.00, 100.00, 300.00, 1200.00, 50.00, 50.00, 100.00, 0, 0.00, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_extra_accessories`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_extra_accessories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `extra_accessory_id` bigint(20) unsigned NOT NULL,
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_allowed_amount` double(10,2) DEFAULT '0.00',
  `deposit_amount` double(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_extra_accessories_id_index` (`id`),
  KEY `vehicle_type_extra_accessories_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicle_type_extra_accessories_extra_accessory_id_index` (`extra_accessory_id`),
  KEY `vehicle_type_extra_accessories_discount_type_id_index` (`discount_type_id`),
  KEY `vehicle_type_extra_accessories_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_fuel_options`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_fuel_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `fuel_option_id` bigint(20) unsigned NOT NULL,
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_allowed_amount` double(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_fuel_options_id_index` (`id`),
  KEY `vehicle_type_fuel_options_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicle_type_fuel_options_fuel_option_id_index` (`fuel_option_id`),
  KEY `vehicle_type_fuel_options_discount_type_id_index` (`discount_type_id`),
  KEY `vehicle_type_fuel_options_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_insurances`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_insurances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `insurance_id` bigint(20) unsigned NOT NULL,
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_allowed_amount` double(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_insurances_id_index` (`id`),
  KEY `vehicle_type_insurances_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicle_type_insurances_insurance_id_index` (`insurance_id`),
  KEY `vehicle_type_insurances_discount_type_id_index` (`discount_type_id`),
  KEY `vehicle_type_insurances_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_prices`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `minimum_no_of_day` bigint(20) DEFAULT NULL,
  `maximum_no_of_day` bigint(20) DEFAULT NULL,
  `discount_percentage` double(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_prices_id_index` (`id`),
  KEY `vehicle_type_prices_vehicle_type_id_index` (`vehicle_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_surcharges`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_surcharges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `surcharge_id` bigint(20) unsigned NOT NULL,
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_allowed_amount` double(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_surcharges_id_index` (`id`),
  KEY `vehicle_type_surcharges_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicle_type_surcharges_surcharge_id_index` (`surcharge_id`),
  KEY `vehicle_type_surcharges_discount_type_id_index` (`discount_type_id`),
  KEY `vehicle_type_surcharges_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type_taxes`
--

CREATE TABLE IF NOT EXISTS `vehicle_type_taxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  `rate` double(10,2) NOT NULL DEFAULT '0.00',
  `discount_type_id` bigint(20) unsigned DEFAULT NULL,
  `duration_type_id` bigint(20) unsigned DEFAULT NULL,
  `max_allowed_amount` double(10,2) DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `vehicle_type_taxes_id_index` (`id`),
  KEY `vehicle_type_taxes_vehicle_type_id_index` (`vehicle_type_id`),
  KEY `vehicle_type_taxes_tax_id_index` (`tax_id`),
  KEY `vehicle_type_taxes_discount_type_id_index` (`discount_type_id`),
  KEY `vehicle_type_taxes_duration_type_id_index` (`duration_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transaction_logs`
--

CREATE TABLE IF NOT EXISTS `wallet_transaction_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `wallet_transaction_logable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_transaction_logable_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_statuses`
--

CREATE TABLE IF NOT EXISTS `withdrawal_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `withdrawal_statuses_id_index` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `withdrawal_statuses`
--

INSERT INTO `withdrawal_statuses` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Pending'),
(2, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Rejected'),
(3, '2016-09-09 17:46:34', '2016-09-09 17:46:34', 'Success');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_requests`
--
ALTER TABLE `api_requests`
  ADD CONSTRAINT `api_requests_ip_id_foreign` FOREIGN KEY (`ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `api_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booker_details`
--
ALTER TABLE `booker_details`
  ADD CONSTRAINT `booker_details_item_user_id_foreign` FOREIGN KEY (`item_user_id`) REFERENCES `item_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ip_id_foreign` FOREIGN KEY (`ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `counter_location_vehicle`
--
ALTER TABLE `counter_location_vehicle`
  ADD CONSTRAINT `counter_location_vehicle_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `counter_location_vehicle_counter_location_id_foreign` FOREIGN KEY (`counter_location_id`) REFERENCES `counter_locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  ADD CONSTRAINT `currency_conversions_converted_currency_id_foreign` FOREIGN KEY (`converted_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `currency_conversions_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `currency_conversion_histories`
--
ALTER TABLE `currency_conversion_histories`
  ADD CONSTRAINT `currency_conversion_histories_currency_conversion_id_foreign` FOREIGN KEY (`currency_conversion_id`) REFERENCES `currency_conversions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dispute_closed_types`
--
ALTER TABLE `dispute_closed_types`
  ADD CONSTRAINT `dispute_closed_types_dispute_type_id_foreign` FOREIGN KEY (`dispute_type_id`) REFERENCES `dispute_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ip_id_foreign` FOREIGN KEY (`ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_item_user_id_foreign` FOREIGN KEY (`item_user_id`) REFERENCES `item_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ips`
--
ALTER TABLE `ips`
  ADD CONSTRAINT `ips_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ips_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ips_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_users`
--
ALTER TABLE `item_users`
  ADD CONSTRAINT `item_users_drop_counter_location_id_foreign` FOREIGN KEY (`drop_counter_location_id`) REFERENCES `counter_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_users_cancellation_type_id_foreign` FOREIGN KEY (`cancellation_type_id`) REFERENCES `cancellation_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_users_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_users_item_user_status_id_foreign` FOREIGN KEY (`item_user_status_id`) REFERENCES `item_user_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_users_pickup_counter_location_id_foreign` FOREIGN KEY (`pickup_counter_location_id`) REFERENCES `counter_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_user_additional_charges`
--
ALTER TABLE `item_user_additional_charges`
  ADD CONSTRAINT `item_user_additional_charges_item_user_id_foreign` FOREIGN KEY (`item_user_id`) REFERENCES `item_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_user_disputes`
--
ALTER TABLE `item_user_disputes`
  ADD CONSTRAINT `item_user_disputes_dispute_closed_type_id_foreign` FOREIGN KEY (`dispute_closed_type_id`) REFERENCES `dispute_closed_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_user_disputes_dispute_status_id_foreign` FOREIGN KEY (`dispute_status_id`) REFERENCES `dispute_statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_user_disputes_dispute_type_id_foreign` FOREIGN KEY (`dispute_type_id`) REFERENCES `dispute_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_user_disputes_last_replied_user_id_foreign` FOREIGN KEY (`last_replied_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_user_disputes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `late_payment_details`
--
ALTER TABLE `late_payment_details`
  ADD CONSTRAINT `late_payment_details_item_user_id_foreign` FOREIGN KEY (`item_user_id`) REFERENCES `item_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_dispute_status_id_foreign` FOREIGN KEY (`dispute_status_id`) REFERENCES `dispute_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_item_user_status_id_foreign` FOREIGN KEY (`item_user_status_id`) REFERENCES `item_user_statuses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_message_content_id_foreign` FOREIGN KEY (`message_content_id`) REFERENCES `message_contents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `money_transfer_accounts`
--
ALTER TABLE `money_transfer_accounts`
  ADD CONSTRAINT `money_transfer_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `provider_users`
--
ALTER TABLE `provider_users`
  ADD CONSTRAINT `provider_users_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_setting_category_id_foreign` FOREIGN KEY (`setting_category_id`) REFERENCES `setting_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sudopay_payment_gateways`
--
ALTER TABLE `sudopay_payment_gateways`
  ADD CONSTRAINT `sudopay_payment_gateways_sudopay_payment_group_id_foreign` FOREIGN KEY (`sudopay_payment_group_id`) REFERENCES `sudopay_payment_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sudopay_payment_gateway_users`
--
ALTER TABLE `sudopay_payment_gateway_users`
  ADD CONSTRAINT `sudopay_payment_gateway_users_sudopay_payment_gateway_id_foreign` FOREIGN KEY (`sudopay_payment_gateway_id`) REFERENCES `sudopay_payment_gateways` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sudopay_payment_gateway_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_receiver_user_id_foreign` FOREIGN KEY (`receiver_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `unavailable_vehicles`
--
ALTER TABLE `unavailable_vehicles`
  ADD CONSTRAINT `unavailable_vehicles_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `unavailable_vehicles_item_user_id_foreign` FOREIGN KEY (`item_user_id`) REFERENCES `item_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_last_login_ip_id_foreign` FOREIGN KEY (`last_login_ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_register_ip_id_foreign` FOREIGN KEY (`register_ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_add_wallet_amounts`
--
ALTER TABLE `user_add_wallet_amounts`
  ADD CONSTRAINT `user_add_wallet_amounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_cash_withdrawals`
--
ALTER TABLE `user_cash_withdrawals`
  ADD CONSTRAINT `user_cash_withdrawals_money_transfer_account_id_foreign` FOREIGN KEY (`money_transfer_account_id`) REFERENCES `money_transfer_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_cash_withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cash_withdrawals_withdrawal_status_id_foreign` FOREIGN KEY (`withdrawal_status_id`) REFERENCES `withdrawal_statuses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD CONSTRAINT `user_logins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_logins_user_login_ip_id_foreign` FOREIGN KEY (`user_login_ip_id`) REFERENCES `ips` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_fuel_type_id_foreign` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_company_id_foreign` FOREIGN KEY (`vehicle_company_id`) REFERENCES `vehicle_companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_make_id_foreign` FOREIGN KEY (`vehicle_make_id`) REFERENCES `vehicle_makes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_model_id_foreign` FOREIGN KEY (`vehicle_model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_companies`
--
ALTER TABLE `vehicle_companies`
  ADD CONSTRAINT `vehicle_companies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  ADD CONSTRAINT `vehicle_models_vehicle_make_id_foreign` FOREIGN KEY (`vehicle_make_id`) REFERENCES `vehicle_makes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_special_prices`
--
ALTER TABLE `vehicle_special_prices`
  ADD CONSTRAINT `vehicle_special_prices_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD CONSTRAINT `vehicle_types_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_type_extra_accessories`
--
ALTER TABLE `vehicle_type_extra_accessories`
  ADD CONSTRAINT `vehicle_type_extra_accessories_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_extra_accessories_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_extra_accessories_extra_accessory_id_foreign` FOREIGN KEY (`extra_accessory_id`) REFERENCES `extra_accessories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_type_extra_accessories_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_type_fuel_options`
--
ALTER TABLE `vehicle_type_fuel_options`
  ADD CONSTRAINT `vehicle_type_fuel_options_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_fuel_options_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_fuel_options_fuel_option_id_foreign` FOREIGN KEY (`fuel_option_id`) REFERENCES `fuel_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_type_fuel_options_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_type_insurances`
--
ALTER TABLE `vehicle_type_insurances`
  ADD CONSTRAINT `vehicle_type_insurances_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_insurances_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_insurances_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `insurances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_type_insurances_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_type_prices`
--
ALTER TABLE `vehicle_type_prices`
  ADD CONSTRAINT `vehicle_type_prices_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_type_surcharges`
--
ALTER TABLE `vehicle_type_surcharges`
  ADD CONSTRAINT `vehicle_type_surcharges_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_surcharges_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_surcharges_surcharge_id_foreign` FOREIGN KEY (`surcharge_id`) REFERENCES `surcharges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_type_surcharges_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_type_taxes`
--
ALTER TABLE `vehicle_type_taxes`
  ADD CONSTRAINT `vehicle_type_taxes_duration_type_id_foreign` FOREIGN KEY (`duration_type_id`) REFERENCES `duration_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_taxes_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_type_taxes_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_type_taxes_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
