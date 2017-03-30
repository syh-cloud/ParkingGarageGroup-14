-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 28, 2017 at 05:46 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `account`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_acount`
--

CREATE TABLE `user_acount` (
  `id` int(11) NOT NULL,
  `first` varchar(30) NOT NULL,
  `last` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `license` varchar(10) NOT NULL,
  `phone` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_acount`
--

INSERT INTO `user_acount` (`id`, `first`, `last`, `password`, `email`, `license`, `phone`) VALUES
(1, 'yun', 'yun', '1234', 'yun@gmail.com', '1234', 1234);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_acount`
--
ALTER TABLE `user_acount`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_acount`
--
ALTER TABLE `user_acount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;