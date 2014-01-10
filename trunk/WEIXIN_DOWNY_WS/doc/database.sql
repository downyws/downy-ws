/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50615
Source Host           : localhost:3306
Source Database       : downy_ws

Target Server Type    : MYSQL
Target Server Version : 50615
File Encoding         : 65001

Date: 2014-01-11 01:42:33
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `weixin_answer`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_answer`;
CREATE TABLE `weixin_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `val` text NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `key` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `k` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `weixin_aq`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_aq`;
CREATE TABLE `weixin_aq` (
  `q_id` int(10) unsigned NOT NULL,
  `a_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`q_id`,`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `weixin_follower`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_follower`;
CREATE TABLE `weixin_follower` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `state` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `weixin_log`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_log`;
CREATE TABLE `weixin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `follower_id` int(10) unsigned NOT NULL,
  `request` text NOT NULL,
  `response` text NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `weixin_question`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_question`;
CREATE TABLE `weixin_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `val` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `v` (`val`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
