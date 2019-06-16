/*
Navicat MySQL Data Transfer

Source Server         : Wamp
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : disk

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-06-16 14:01:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for netdisk_files
-- ----------------------------
DROP TABLE IF EXISTS `netdisk_files`;
CREATE TABLE `netdisk_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `real_path` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_share` tinyint(3) DEFAULT '0',
  `is_delete` tinyint(3) unsigned DEFAULT '0' COMMENT '0未删除，1已删除',
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of netdisk_files
-- ----------------------------

-- ----------------------------
-- Table structure for netdisk_folders
-- ----------------------------
DROP TABLE IF EXISTS `netdisk_folders`;
CREATE TABLE `netdisk_folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `father_id` int(10) unsigned DEFAULT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `folder_name` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_share` tinyint(3) DEFAULT '0' COMMENT '0 为私有 1为共用',
  `is_delete` tinyint(3) unsigned DEFAULT '0',
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of netdisk_folders
-- ----------------------------

-- ----------------------------
-- Table structure for netdisk_users
-- ----------------------------
DROP TABLE IF EXISTS `netdisk_users`;
CREATE TABLE `netdisk_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `reg_time` timestamp NULL DEFAULT NULL,
  `reg_ip` varchar(255) DEFAULT NULL,
  `last_login_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `total_size` int(10) unsigned DEFAULT '1073741824',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of netdisk_users
-- ----------------------------
INSERT INTO `netdisk_users` VALUES ('1', 'Alive', 'e10adc3949ba59abbe56e057f20f883e', null, '13260556516', null, null, null, '2019-06-16 13:29:09', '127.0.0.1', '1073741824');
INSERT INTO `netdisk_users` VALUES ('2', '张三', 'e10adc3949ba59abbe56e057f20f883e', 'zs@qq.com', '10086', null, null, null, '2019-06-15 09:10:48', null, '1073741824');
