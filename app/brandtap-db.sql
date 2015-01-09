-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access_token`;
CREATE TABLE `access_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(51) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `access_token` (`id`, `access_token`) VALUES
(1,	'1590404687.73574de.4ba73c2bcb37438ebc96d0a94f730d96');

DROP TABLE IF EXISTS `activation`;
CREATE TABLE `activation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inst_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dump` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `post_winners`;
CREATE TABLE `post_winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(30) NOT NULL,
  `inst_id` int(10) unsigned NOT NULL,
  `code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `inst_id` int(10) unsigned NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `brand` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-12-31 17:42:57