/*
Navicat MySQL Data Transfer

Source Server         : 本机3607
Source Server Version : 50726
Source Host           : localhost:3307
Source Database       : dashboard

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-02-21 17:15:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sy_admin
-- ----------------------------
DROP TABLE IF EXISTS `sy_admin`;
CREATE TABLE `sy_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `login_name` varchar(128) NOT NULL DEFAULT '' COMMENT '登录账号',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '名称',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码',
  `avatar` varchar(128) NOT NULL DEFAULT '' COMMENT '头像',
  `logged_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用; 0: 否, 1: 是',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除; 0: 否, 1: 是',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- ----------------------------
-- Records of sy_admin
-- ----------------------------
INSERT INTO `sy_admin` VALUES ('1', 'test', 'syin', '$2y$10$lYM5gt1xCyCzwiGXZlfzGOgkc16AMwQ8BB9.qxBU8OU20LGgk0x56', '/static/common/img/avatar/20.png', '0', '0', '0', '0', '1558075424', '1561367143');
INSERT INTO `sy_admin` VALUES ('2', 'root', '测试用户', '$2y$10$8YLyrp0Kb3HlwE5oK./P/OguFDOv23AXrI58jIJJtAoS6Hnk5avRW', '/static/img/avatar/29.png', '0', '0', '0', '0', '1558323222', '1558495729');
INSERT INTO `sy_admin` VALUES ('3', '管理员', '111', '$2y$10$iSKNZWTGtdyZ0VELqhX6F.y/4zKm5g4F3ao1DW1dodJ1Ym/DuRV2a', '/static/img/avatar/29.png', '0', '0', '0', '0', '1559290020', '1559290020');

CREATE TABLE `sy_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` text COMMENT '角色描述',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用; 0: 否, 1: 是',
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除; 0: 否, 1: 是',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员角色表';

CREATE TABLE `sy_admin_role_ban` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `module` varchar(128) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller` varchar(128) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action` varchar(128) NOT NULL DEFAULT '' COMMENT '操作名称',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '禁止类型; 1: 数据权限, 2: 页面权限',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员权限黑名单表';