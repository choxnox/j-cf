SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `jobs_currencyfair_endpoint` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jobs_currencyfair_endpoint`;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `currency_from` char(3) NOT NULL,
  `currency_to` char(3) NOT NULL,
  `amount_sell` decimal(10,2) NOT NULL,
  `amount_buy` decimal(10,2) NOT NULL,
  `rate` decimal(12,6) NOT NULL,
  `datetime_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_code` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`currency_from`,`currency_to`),
  KEY `country_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `reports_by_country`;
CREATE TABLE IF NOT EXISTS `reports_by_country` (
  `date` date NOT NULL,
  `country_code` char(2) NOT NULL,
  `count_messages` bigint(20) NOT NULL DEFAULT '0',
  UNIQUE KEY `date_country_code_key` (`date`,`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
