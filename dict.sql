-- ----------------------------
-- Records of sy_dict
-- ----------------------------
INSERT INTO `sy_dict` VALUES ('1', '测试_1', 'test_1', '1', '0');
INSERT INTO `sy_dict` VALUES ('2', '测试_2', 'test_2', '2', '0');
INSERT INTO `sy_dict` VALUES ('3', '测试_3', 'test_3', '3', '0');
INSERT INTO `sy_dict` VALUES ('4', '测试_4', 'test_4', '4', '0');
INSERT INTO `sy_dict` VALUES ('5', '测试_5', 'test_5', '5', '0');
INSERT INTO `sy_dict` VALUES ('6', '测试_6', 'test_6', '6', '0');
INSERT INTO `sy_dict` VALUES ('7', '测试_7', 'test_7', '7', '0');
INSERT INTO `sy_dict` VALUES ('8', '测试_8', 'test_8', '8', '0');
INSERT INTO `sy_dict` VALUES ('9', '测试_9', 'test_9', '9', '0');
INSERT INTO `sy_dict` VALUES ('10', '测试_10', 'test_10', '10', '0');
INSERT INTO `sy_dict` VALUES ('11', '测试_11', 'test_11', '11', '0');
INSERT INTO `sy_dict` VALUES ('12', '测试_12', 'test_12', '12', '0');
INSERT INTO `sy_dict` VALUES ('13', '测试_13', 'test_13', '13', '0');
INSERT INTO `sy_dict` VALUES ('14', '测试_14', 'test_14', '14', '0');
INSERT INTO `sy_dict` VALUES ('15', '测试_15', 'test_15', '15', '0');
INSERT INTO `sy_dict` VALUES ('16', '测试_16', 'test_16', '16', '0');
INSERT INTO `sy_dict` VALUES ('17', '测试_17', 'test_17', '17', '0');
INSERT INTO `sy_dict` VALUES ('18', '测试_18', 'test_18', '18', '0');
INSERT INTO `sy_dict` VALUES ('19', '测试_19', 'test_19', '19', '0');
INSERT INTO `sy_dict` VALUES ('20', '测试_20', 'test_20', '20', '0');
INSERT INTO `sy_dict` VALUES ('21', '测试_21', 'test_21', '21', '0');
INSERT INTO `sy_dict` VALUES ('22', '测试_22', 'test_22', '22', '0');
INSERT INTO `sy_dict` VALUES ('23', '测试_23', 'test_23', '23', '0');
INSERT INTO `sy_dict` VALUES ('24', '测试_24', 'test_24', '24', '0');
INSERT INTO `sy_dict` VALUES ('25', '测试_25', 'test_25', '25', '0');
INSERT INTO `sy_dict` VALUES ('26', '测试_26', 'test_26', '26', '0');

-- ----------------------------
-- Table structure for sy_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `sy_dict_data`;
CREATE TABLE `sy_dict_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `dict_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '字典目录id',
  `data` varchar(128) NOT NULL DEFAULT '' COMMENT '数据名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '备注说明',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统数据(为系统数据时, 不可删除); 0: 否, 1: 是',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=313 DEFAULT CHARSET=utf8 COMMENT='数据字典数据';

-- ----------------------------
-- Records of sy_dict_data
-- ----------------------------
INSERT INTO `sy_dict_data` VALUES ('1', '1', '字典数据 (1 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('2', '1', '字典数据 (1 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('3', '1', '字典数据 (1 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('4', '1', '字典数据 (1 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('5', '1', '字典数据 (1 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('6', '1', '字典数据 (1 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('7', '1', '字典数据 (1 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('8', '1', '字典数据 (1 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('9', '1', '字典数据 (1 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('10', '1', '字典数据 (1 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('11', '1', '字典数据 (1 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('12', '1', '字典数据 (1 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('13', '2', '字典数据 (2 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('14', '2', '字典数据 (2 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('15', '2', '字典数据 (2 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('16', '2', '字典数据 (2 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('17', '2', '字典数据 (2 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('18', '2', '字典数据 (2 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('19', '2', '字典数据 (2 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('20', '2', '字典数据 (2 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('21', '2', '字典数据 (2 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('22', '2', '字典数据 (2 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('23', '2', '字典数据 (2 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('24', '2', '字典数据 (2 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('25', '3', '字典数据 (3 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('26', '3', '字典数据 (3 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('27', '3', '字典数据 (3 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('28', '3', '字典数据 (3 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('29', '3', '字典数据 (3 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('30', '3', '字典数据 (3 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('31', '3', '字典数据 (3 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('32', '3', '字典数据 (3 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('33', '3', '字典数据 (3 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('34', '3', '字典数据 (3 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('35', '3', '字典数据 (3 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('36', '3', '字典数据 (3 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('37', '4', '字典数据 (4 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('38', '4', '字典数据 (4 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('39', '4', '字典数据 (4 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('40', '4', '字典数据 (4 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('41', '4', '字典数据 (4 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('42', '4', '字典数据 (4 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('43', '4', '字典数据 (4 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('44', '4', '字典数据 (4 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('45', '4', '字典数据 (4 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('46', '4', '字典数据 (4 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('47', '4', '字典数据 (4 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('48', '4', '字典数据 (4 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('49', '5', '字典数据 (5 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('50', '5', '字典数据 (5 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('51', '5', '字典数据 (5 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('52', '5', '字典数据 (5 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('53', '5', '字典数据 (5 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('54', '5', '字典数据 (5 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('55', '5', '字典数据 (5 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('56', '5', '字典数据 (5 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('57', '5', '字典数据 (5 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('58', '5', '字典数据 (5 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('59', '5', '字典数据 (5 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('60', '5', '字典数据 (5 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('61', '6', '字典数据 (6 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('62', '6', '字典数据 (6 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('63', '6', '字典数据 (6 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('64', '6', '字典数据 (6 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('65', '6', '字典数据 (6 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('66', '6', '字典数据 (6 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('67', '6', '字典数据 (6 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('68', '6', '字典数据 (6 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('69', '6', '字典数据 (6 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('70', '6', '字典数据 (6 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('71', '6', '字典数据 (6 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('72', '6', '字典数据 (6 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('73', '7', '字典数据 (7 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('74', '7', '字典数据 (7 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('75', '7', '字典数据 (7 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('76', '7', '字典数据 (7 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('77', '7', '字典数据 (7 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('78', '7', '字典数据 (7 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('79', '7', '字典数据 (7 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('80', '7', '字典数据 (7 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('81', '7', '字典数据 (7 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('82', '7', '字典数据 (7 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('83', '7', '字典数据 (7 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('84', '7', '字典数据 (7 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('85', '8', '字典数据 (8 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('86', '8', '字典数据 (8 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('87', '8', '字典数据 (8 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('88', '8', '字典数据 (8 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('89', '8', '字典数据 (8 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('90', '8', '字典数据 (8 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('91', '8', '字典数据 (8 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('92', '8', '字典数据 (8 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('93', '8', '字典数据 (8 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('94', '8', '字典数据 (8 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('95', '8', '字典数据 (8 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('96', '8', '字典数据 (8 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('97', '9', '字典数据 (9 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('98', '9', '字典数据 (9 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('99', '9', '字典数据 (9 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('100', '9', '字典数据 (9 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('101', '9', '字典数据 (9 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('102', '9', '字典数据 (9 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('103', '9', '字典数据 (9 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('104', '9', '字典数据 (9 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('105', '9', '字典数据 (9 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('106', '9', '字典数据 (9 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('107', '9', '字典数据 (9 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('108', '9', '字典数据 (9 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('109', '10', '字典数据 (10 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('110', '10', '字典数据 (10 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('111', '10', '字典数据 (10 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('112', '10', '字典数据 (10 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('113', '10', '字典数据 (10 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('114', '10', '字典数据 (10 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('115', '10', '字典数据 (10 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('116', '10', '字典数据 (10 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('117', '10', '字典数据 (10 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('118', '10', '字典数据 (10 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('119', '10', '字典数据 (10 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('120', '10', '字典数据 (10 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('121', '11', '字典数据 (11 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('122', '11', '字典数据 (11 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('123', '11', '字典数据 (11 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('124', '11', '字典数据 (11 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('125', '11', '字典数据 (11 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('126', '11', '字典数据 (11 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('127', '11', '字典数据 (11 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('128', '11', '字典数据 (11 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('129', '11', '字典数据 (11 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('130', '11', '字典数据 (11 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('131', '11', '字典数据 (11 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('132', '11', '字典数据 (11 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('133', '12', '字典数据 (12 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('134', '12', '字典数据 (12 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('135', '12', '字典数据 (12 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('136', '12', '字典数据 (12 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('137', '12', '字典数据 (12 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('138', '12', '字典数据 (12 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('139', '12', '字典数据 (12 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('140', '12', '字典数据 (12 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('141', '12', '字典数据 (12 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('142', '12', '字典数据 (12 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('143', '12', '字典数据 (12 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('144', '12', '字典数据 (12 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('145', '13', '字典数据 (13 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('146', '13', '字典数据 (13 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('147', '13', '字典数据 (13 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('148', '13', '字典数据 (13 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('149', '13', '字典数据 (13 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('150', '13', '字典数据 (13 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('151', '13', '字典数据 (13 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('152', '13', '字典数据 (13 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('153', '13', '字典数据 (13 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('154', '13', '字典数据 (13 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('155', '13', '字典数据 (13 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('156', '13', '字典数据 (13 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('157', '14', '字典数据 (14 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('158', '14', '字典数据 (14 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('159', '14', '字典数据 (14 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('160', '14', '字典数据 (14 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('161', '14', '字典数据 (14 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('162', '14', '字典数据 (14 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('163', '14', '字典数据 (14 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('164', '14', '字典数据 (14 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('165', '14', '字典数据 (14 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('166', '14', '字典数据 (14 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('167', '14', '字典数据 (14 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('168', '14', '字典数据 (14 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('169', '15', '字典数据 (15 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('170', '15', '字典数据 (15 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('171', '15', '字典数据 (15 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('172', '15', '字典数据 (15 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('173', '15', '字典数据 (15 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('174', '15', '字典数据 (15 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('175', '15', '字典数据 (15 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('176', '15', '字典数据 (15 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('177', '15', '字典数据 (15 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('178', '15', '字典数据 (15 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('179', '15', '字典数据 (15 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('180', '15', '字典数据 (15 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('181', '16', '字典数据 (16 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('182', '16', '字典数据 (16 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('183', '16', '字典数据 (16 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('184', '16', '字典数据 (16 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('185', '16', '字典数据 (16 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('186', '16', '字典数据 (16 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('187', '16', '字典数据 (16 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('188', '16', '字典数据 (16 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('189', '16', '字典数据 (16 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('190', '16', '字典数据 (16 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('191', '16', '字典数据 (16 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('192', '16', '字典数据 (16 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('193', '17', '字典数据 (17 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('194', '17', '字典数据 (17 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('195', '17', '字典数据 (17 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('196', '17', '字典数据 (17 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('197', '17', '字典数据 (17 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('198', '17', '字典数据 (17 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('199', '17', '字典数据 (17 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('200', '17', '字典数据 (17 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('201', '17', '字典数据 (17 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('202', '17', '字典数据 (17 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('203', '17', '字典数据 (17 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('204', '17', '字典数据 (17 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('205', '18', '字典数据 (18 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('206', '18', '字典数据 (18 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('207', '18', '字典数据 (18 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('208', '18', '字典数据 (18 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('209', '18', '字典数据 (18 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('210', '18', '字典数据 (18 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('211', '18', '字典数据 (18 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('212', '18', '字典数据 (18 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('213', '18', '字典数据 (18 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('214', '18', '字典数据 (18 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('215', '18', '字典数据 (18 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('216', '18', '字典数据 (18 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('217', '19', '字典数据 (19 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('218', '19', '字典数据 (19 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('219', '19', '字典数据 (19 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('220', '19', '字典数据 (19 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('221', '19', '字典数据 (19 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('222', '19', '字典数据 (19 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('223', '19', '字典数据 (19 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('224', '19', '字典数据 (19 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('225', '19', '字典数据 (19 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('226', '19', '字典数据 (19 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('227', '19', '字典数据 (19 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('228', '19', '字典数据 (19 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('229', '20', '字典数据 (20 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('230', '20', '字典数据 (20 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('231', '20', '字典数据 (20 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('232', '20', '字典数据 (20 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('233', '20', '字典数据 (20 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('234', '20', '字典数据 (20 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('235', '20', '字典数据 (20 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('236', '20', '字典数据 (20 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('237', '20', '字典数据 (20 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('238', '20', '字典数据 (20 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('239', '20', '字典数据 (20 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('240', '20', '字典数据 (20 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('241', '21', '字典数据 (21 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('242', '21', '字典数据 (21 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('243', '21', '字典数据 (21 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('244', '21', '字典数据 (21 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('245', '21', '字典数据 (21 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('246', '21', '字典数据 (21 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('247', '21', '字典数据 (21 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('248', '21', '字典数据 (21 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('249', '21', '字典数据 (21 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('250', '21', '字典数据 (21 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('251', '21', '字典数据 (21 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('252', '21', '字典数据 (21 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('253', '22', '字典数据 (22 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('254', '22', '字典数据 (22 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('255', '22', '字典数据 (22 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('256', '22', '字典数据 (22 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('257', '22', '字典数据 (22 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('258', '22', '字典数据 (22 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('259', '22', '字典数据 (22 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('260', '22', '字典数据 (22 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('261', '22', '字典数据 (22 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('262', '22', '字典数据 (22 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('263', '22', '字典数据 (22 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('264', '22', '字典数据 (22 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('265', '23', '字典数据 (23 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('266', '23', '字典数据 (23 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('267', '23', '字典数据 (23 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('268', '23', '字典数据 (23 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('269', '23', '字典数据 (23 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('270', '23', '字典数据 (23 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('271', '23', '字典数据 (23 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('272', '23', '字典数据 (23 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('273', '23', '字典数据 (23 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('274', '23', '字典数据 (23 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('275', '23', '字典数据 (23 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('276', '23', '字典数据 (23 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('277', '24', '字典数据 (24 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('278', '24', '字典数据 (24 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('279', '24', '字典数据 (24 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('280', '24', '字典数据 (24 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('281', '24', '字典数据 (24 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('282', '24', '字典数据 (24 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('283', '24', '字典数据 (24 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('284', '24', '字典数据 (24 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('285', '24', '字典数据 (24 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('286', '24', '字典数据 (24 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('287', '24', '字典数据 (24 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('288', '24', '字典数据 (24 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('289', '25', '字典数据 (25 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('290', '25', '字典数据 (25 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('291', '25', '字典数据 (25 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('292', '25', '字典数据 (25 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('293', '25', '字典数据 (25 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('294', '25', '字典数据 (25 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('295', '25', '字典数据 (25 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('296', '25', '字典数据 (25 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('297', '25', '字典数据 (25 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('298', '25', '字典数据 (25 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('299', '25', '字典数据 (25 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('300', '25', '字典数据 (25 - 12)', '', '12', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('301', '26', '字典数据 (26 - 1)', '', '1', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('302', '26', '字典数据 (26 - 2)', '', '2', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('303', '26', '字典数据 (26 - 3)', '', '3', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('304', '26', '字典数据 (26 - 4)', '', '4', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('305', '26', '字典数据 (26 - 5)', '', '5', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('306', '26', '字典数据 (26 - 6)', '', '6', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('307', '26', '字典数据 (26 - 7)', '', '7', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('308', '26', '字典数据 (26 - 8)', '', '8', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('309', '26', '字典数据 (26 - 9)', '', '9', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('310', '26', '字典数据 (26 - 10)', '', '10', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('311', '26', '字典数据 (26 - 11)', '', '11', '0', '1587888258', '1587888258');
INSERT INTO `sy_dict_data` VALUES ('312', '26', '字典数据 (26 - 12)', '', '12', '0', '1587888258', '1587888258');