SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `jobs_currencyfair_dashboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jobs_currencyfair_dashboard`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `password_salt` char(32) NOT NULL,
  `api_client_id` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `users` (`id`, `username`, `password`, `password_salt`, `api_client_id`) VALUES
(1, 'admin', 'ad4e271b9ba2d138bbc4cedca1855e2aa528c136bac27ba0267ef5fb1f5971ac', 'fdf41c8f9dbd8a8dde71d8d241ae58d4', 'V4629HX2iF64yl66f875GZe6TqJ90n2M');