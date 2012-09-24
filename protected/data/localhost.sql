-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 24, 2012 at 02:24 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `migration-diff`
--
CREATE DATABASE `migration-diff` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `migration-diff`;

-- --------------------------------------------------------

--
-- Table structure for table `arg`
--

CREATE TABLE IF NOT EXISTS `arg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT 'test',
  `woo` varchar(32) DEFAULT NULL,
  `garwr` varchar(32) NOT NULL,
  `sdfds` int(11) NOT NULL,
  `fkey` int(11) NOT NULL,
  PRIMARY KEY (`id`,`parent`),
  UNIQUE KEY `name` (`name`),
  KEY `woo` (`woo`),
  KEY `parent` (`parent`),
  KEY `child` (`child`),
  KEY `parent_2` (`parent`,`child`),
  KEY `sdfds` (`sdfds`),
  KEY `fkey` (`fkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_9` FOREIGN KEY (`fkey`) REFERENCES `test` (`child`),
  ADD CONSTRAINT `test_ibfk_6` FOREIGN KEY (`parent`) REFERENCES `arg` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `test_ibfk_8` FOREIGN KEY (`child`) REFERENCES `arg` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--
-- Database: `migration-sync`
--
CREATE DATABASE `migration-sync` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `migration-sync`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
