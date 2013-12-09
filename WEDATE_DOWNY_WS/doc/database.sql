/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50614
Source Host           : 127.0.0.1:3306
Source Database       : mydowny

Target Server Type    : MYSQL
Target Server Version : 50614
File Encoding         : 65001

Date: 2013-12-09 23:32:53
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `wedate_date`
-- ----------------------------
DROP TABLE IF EXISTS `wedate_date`;
CREATE TABLE `wedate_date` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `meet_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8;
