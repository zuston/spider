# ************************************************************
# Sequel Pro SQL dump
# Version 4500
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.1.63)
# Database: spider
# Generation Time: 2016-07-13 14:36:53 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table queue
# ------------------------------------------------------------

CREATE TABLE `queue` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `nick_name` char(20) NOT NULL DEFAULT '' COMMENT '唯一识别码',
  `mark` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:为未执行1：执行过',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table relation
# ------------------------------------------------------------

CREATE TABLE `relation` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `followee_nick_name` char(20) NOT NULL DEFAULT '',
  `follower_nick_name` char(20) NOT NULL DEFAULT '',
  `mark` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0:取消关注1：正在关注',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `nick_name` char(20) NOT NULL DEFAULT '',
  `name` char(40) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `location` char(40) DEFAULT NULL,
  `bussiness` char(20) DEFAULT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `employment` varchar(40) DEFAULT NULL,
  `position` char(40) DEFAULT NULL,
  `education` char(40) DEFAULT NULL,
  `education_extra` char(40) DEFAULT NULL,
  `followee_number` int(11) NOT NULL DEFAULT '0',
  `follower_number` int(11) NOT NULL DEFAULT '0',
  `hash_id` char(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;