/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50619
Source Host           : localhost:3306
Source Database       : downy_ws

Target Server Type    : MYSQL
Target Server Version : 50619
File Encoding         : 65001

Date: 2014-08-09 22:35:52
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `accounting_address`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_address`;
CREATE TABLE `accounting_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '简称',
  `detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `lon` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '经度',
  `lat` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '纬度',
  `type` tinyint(4) NOT NULL COMMENT '类型<[1:实体][2:虚拟]>',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='地址';

-- ----------------------------
-- Table structure for `accounting_category`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_category`;
CREATE TABLE `accounting_category` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `parent_id` tinyint(4) NOT NULL COMMENT '父分类',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `sort` tinyint(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='分类';

-- ----------------------------
-- Table structure for `accounting_currency`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_currency`;
CREATE TABLE `accounting_currency` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `abbr` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '缩写',
  `exchange_rate` double NOT NULL COMMENT '当前汇率',
  `exchange_rate_log` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '汇率日志',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置',
  `rate_last_update_time` int(11) NOT NULL COMMENT '汇率最后更新时间',
  `sort` tinyint(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='货币';

-- ----------------------------
-- Table structure for `accounting_detail`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_detail`;
CREATE TABLE `accounting_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `record_id` int(11) NOT NULL COMMENT '记录编号',
  `amount` double NULL DEFAULT NULL COMMENT '金额',
  `amount_currency_id` tinyint(4) NULL DEFAULT NULL COMMENT '所用货币',
  `exchange_rate` double NULL DEFAULT NULL COMMENT '汇率',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '说明',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='记录明细';

-- ----------------------------
-- Table structure for `accounting_file`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_file`;
CREATE TABLE `accounting_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `detail_id` int(11) NOT NULL COMMENT '记录明细编号',
  `title` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件名',
  `data` longblob NOT NULL COMMENT '二进制文件数据',
  `type` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件类型',
  `hash` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件标识',
  `create_time` int(11) NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='凭证';

-- ----------------------------
-- Table structure for `accounting_record`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_record`;
CREATE TABLE `accounting_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `category_id` tinyint(4) NULL DEFAULT NULL COMMENT '分类',
  `address_id` int(11) NULL DEFAULT NULL COMMENT '地址',
  `surplus` double NULL DEFAULT NULL COMMENT '结余',
  `surplus_currency_id` tinyint(4) NULL DEFAULT NULL COMMENT '结余所用货币',
  `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '说明',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `remind_time` int(11) NULL DEFAULT NULL COMMENT '提醒时间',
  `finish_time` int(11) NULL DEFAULT NULL COMMENT '完成时间',
  `state` tinyint(4) NOT NULL COMMENT '状态<[1:已完成][2:进行中]>',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='记录';
