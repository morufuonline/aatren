-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2018 at 10:48 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aatren`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_data`
--

CREATE TABLE `admin_data` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_data`
--

INSERT INTO `admin_data` (`id`, `name`, `email`, `password`, `blocked`, `date_time`) VALUES
(1, 'Administrator', 'admin@aatren.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, '2018-07-29 06:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `admin_messages`
--

CREATE TABLE `admin_messages` (
  `id` int(100) NOT NULL,
  `ticket_id` varchar(20) NOT NULL DEFAULT '',
  `sender_name` varchar(50) NOT NULL DEFAULT '',
  `sender_email` varchar(50) NOT NULL DEFAULT '',
  `sender_phone` varchar(50) NOT NULL DEFAULT '',
  `recipient_name` varchar(50) NOT NULL DEFAULT '',
  `recipient_email` varchar(50) NOT NULL DEFAULT '',
  `subject` varchar(200) NOT NULL DEFAULT '',
  `message` longtext NOT NULL,
  `viewed` tinyint(1) NOT NULL DEFAULT '0',
  `inbox` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(100) NOT NULL,
  `user_id` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `activity` longtext NOT NULL,
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) UNSIGNED NOT NULL,
  `description` varchar(50) NOT NULL DEFAULT '',
  `order_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `description`, `order_id`, `date_time`) VALUES
(1, 'Wedding Ceremony at Ibeju Lekki', 1, '2018-08-04 17:38:33'),
(2, 'Annual Seminal at Lokoja', 2, '2018-08-04 17:40:10');

-- --------------------------------------------------------

--
-- Table structure for table `category_types`
--

CREATE TABLE `category_types` (
  `id` int(10) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_types`
--

INSERT INTO `category_types` (`id`, `type`) VALUES
(1, 'Event');

-- --------------------------------------------------------

--
-- Table structure for table `events_videos`
--

CREATE TABLE `events_videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_date` date NOT NULL DEFAULT '0000-00-00',
  `event_location` varchar(250) NOT NULL DEFAULT '',
  `event_title` varchar(100) NOT NULL DEFAULT '',
  `event_link` varchar(1000) NOT NULL DEFAULT '',
  `event_details` varchar(2000) NOT NULL DEFAULT '',
  `added_by` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events_videos`
--

INSERT INTO `events_videos` (`id`, `event_date`, `event_location`, `event_title`, `event_link`, `event_details`, `added_by`, `date_added`) VALUES
(1, '2018-04-18', '7, Lokoja Street, Ijeshatade, Surulere, Lagos', 'WEDDING CEREMONY AT IJESHA', 'https://www.youtube.com/embed/Nh0agMkVW5U?ecver=2', '&lt;p&gt;This is a &lt;strong&gt;sample&lt;/strong&gt; description&lt;/p&gt;', 1, '2018-08-04 17:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `item_date` date NOT NULL DEFAULT '0000-00-00',
  `item_location` varchar(250) NOT NULL DEFAULT '',
  `item_name` varchar(200) NOT NULL DEFAULT '',
  `item_slug` varchar(200) NOT NULL DEFAULT '',
  `item_details` varchar(2000) NOT NULL DEFAULT '',
  `added_by` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `cat_id`, `item_date`, `item_location`, `item_name`, `item_slug`, `item_details`, `added_by`, `date_added`) VALUES
(1, 2, '2018-08-20', '7, Sample Address, Surulere, Lagos', 'Wedding Ceremony at Surulere', 'wedding-ceremony-at-surulere-2018-08-20', '&lt;p&gt;Sample &lt;strong&gt;Details...&lt;/strong&gt;&lt;/p&gt;', 1, '2018-08-02 18:22:59'),
(2, 2, '2018-08-21', 'Plot 72, Sample Street, Idiaraba, Lagos', 'Naming Ceremony at Idiaraba, Lagos', 'naming-ceremony-at-idiaraba-lagos-2018-08-21', '&lt;p&gt;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;Sample and Testing.&amp;nbsp;&lt;/p&gt;', 1, '2018-08-02 18:28:51'),
(3, 2, '2018-08-22', 'Sample Location', 'General Meeting at Lokoja', 'general-meeting-at-lokoja-2018-08-22', '&lt;p&gt;Sample description&lt;/p&gt;', 1, '2018-08-02 18:30:55'),
(4, 2, '2018-08-23', 'Sample', 'Graduation at Lagos Island', 'graduation-at-lagos-island-2018-08-23', '&lt;p&gt;Sample&lt;/p&gt;', 1, '2018-08-02 18:32:36'),
(5, 1, '2018-01-15', 'Sample', 'Campaign at Ibadan', 'campaign-at-ibadan-2018-01-15', '&lt;p&gt;Sample&lt;/p&gt;', 1, '2018-08-02 18:34:21'),
(6, 1, '2018-02-02', 'Sample', 'Filth-Free Programme at Oyingbo', 'filth-free-programme-at-oyingbo-2018-02-10', '&lt;p&gt;Sample&lt;/p&gt;', 1, '2018-08-03 01:48:57'),
(7, 1, '2018-03-20', 'Teslim Balogun Stadium, Lagos State', 'Football Match Competition at Teslim Balogun Stadium', 'football-match-competition-at-teslim-balogun-stadium-2018-03-20', '&lt;p&gt;&lt;strong&gt;Sample details&lt;/strong&gt;&lt;/p&gt;', 1, '2018-08-03 01:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `items_categories`
--

CREATE TABLE `items_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_type` int(11) NOT NULL DEFAULT '0',
  `cat_name` varchar(50) NOT NULL DEFAULT '',
  `cat_slug` varchar(50) NOT NULL DEFAULT '',
  `cat_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `home_display` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items_categories`
--

INSERT INTO `items_categories` (`id`, `cat_type`, `cat_name`, `cat_slug`, `cat_order`, `home_display`) VALUES
(1, 1, 'Past Events', 'past-events', 2, 1),
(2, 1, 'News Update', 'news-update', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(50) NOT NULL DEFAULT '',
  `order_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `position`, `order_id`, `date_time`) VALUES
(1, 'Chief Agunlejika Agbabiaka', 'President', 1, '2018-08-04 03:01:53'),
(2, 'Mrs Bolakale Ayinde', 'Treasurer', 4, '2018-08-04 03:10:33'),
(3, 'Mr. Ayinde Olayiwola', 'Chief Whip', 3, '2018-08-04 03:11:05'),
(4, 'Mr. Oluwatoyin Damilare', 'Vice President', 2, '2018-08-04 03:11:56'),
(5, 'Mr. Timileyin Dare', 'PRO 1', 5, '2018-08-04 03:13:48'),
(6, 'Mr. Daniel Babatunde', 'PRO 2', 6, '2018-08-04 03:14:24');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(300) NOT NULL DEFAULT '',
  `message` longtext NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `name`, `email`, `date_time`) VALUES
(1, 'Wasiu', 'wasiuonline@gmail.com', '2018-08-02 14:09:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_types`
--
ALTER TABLE `category_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_videos`
--
ALTER TABLE `events_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_categories`
--
ALTER TABLE `items_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_data`
--
ALTER TABLE `admin_data`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `admin_messages`
--
ALTER TABLE `admin_messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `category_types`
--
ALTER TABLE `category_types`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `events_videos`
--
ALTER TABLE `events_videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `items_categories`
--
ALTER TABLE `items_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
