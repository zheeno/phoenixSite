-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2020 at 09:38 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `phoenix_courier`
--

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `tracking_id` varchar(200) NOT NULL,
  `item_name` varchar(500) NOT NULL,
  `item_desc` varchar(500) NOT NULL,
  `no_items` varchar(200) NOT NULL,
  `courier_fee` varchar(200) NOT NULL,
  `transit_status` varchar(200) NOT NULL,
  `sender_name` varchar(200) NOT NULL,
  `sender_details` varchar(200) NOT NULL,
  `receiver_name` varchar(200) NOT NULL,
  `receiver_details` varchar(200) NOT NULL,
  `origin` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

