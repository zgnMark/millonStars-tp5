SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `j_monolog`
-- ----------------------------
DROP TABLE IF EXISTS `j_monolog`;
CREATE TABLE `j_monolog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) DEFAULT NULL COMMENT '通道',
  `level` varchar(255) DEFAULT NULL COMMENT '日志级别',
  `message` mediumtext COMMENT '日志消息',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`) USING HASH,
  KEY `level` (`level`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='日志表';


-- ----------------------------
-- Table structure for tp_action_log
-- ----------------------------
DROP TABLE IF EXISTS `j_action_log`;
CREATE TABLE `j_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `log` longtext NOT NULL COMMENT '日志备注',
  `log_url` varchar(255) NOT NULL COMMENT '执行的URL',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COMMENT='行为日志表';

-- ----------------------------
-- Table structure for `cmd_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `cmd_schedule`;
CREATE TABLE `cmd_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expression` varchar(255) DEFAULT NULL COMMENT '时间执行表达式',
  `command` varchar(255) DEFAULT NULL COMMENT '任务执行表达式',
  `remarks` varchar(255) DEFAULT NULL COMMENT '备注',
  `locked` tinyint(1) DEFAULT '0' COMMENT '0正常，1锁定状态',
  `status` tinyint(1) DEFAULT '0' COMMENT '任务状态,0下线，1上线',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='任务调度表';

-- ----------------------------
-- Records of cmd_schedule
-- ----------------------------
INSERT INTO `cmd_schedule` VALUES ('1', '*/5 * * * *', 'D:\\xampp\\php\\php D:\\xampp\\htdocs\\gworld\\lxt querywithdraw', '提现查单（每5分钟执行一次）', '0', '1');

-- ----------------------------
-- Table structure for `cmd_schedule_log`
-- ----------------------------
DROP TABLE IF EXISTS `cmd_schedule_log`;
CREATE TABLE `cmd_schedule_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) DEFAULT NULL COMMENT '任务ID',
  `standard_output` text COMMENT '标准输出',
  `error_output` text COMMENT '错误输出',
  `start_time` datetime DEFAULT NULL COMMENT '任务开始执行时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `run_status` tinyint(1) DEFAULT NULL COMMENT '运行状态,1运行中2运行成功，3失败',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='任务调度日志';


##################################权限########################################
-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `j_admin`;
CREATE TABLE `j_admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL DEFAULT '',
  `mobile` varchar(11) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0正常，1冻结',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '软删除状态，0正常，1删除',
  `last_login_time` datetime DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `j_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员', '351699382@qq.com', '', '最高管理员', '2', 0,null, '2017-11-23 04:18:57');


-- ----------------------------
-- auth_rule，规则表，
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
`title` varchar(255) NOT NULL DEFAULT '' COMMENT '规则中文名称',
`type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '暂时没用',
`status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
`condition` text NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',  # 规则附件条件,满足附加条件的规则,才认为是有效的规则
`pid` mediumint(8) unsigned NOT NULL COMMENT '用户把规则划分成组，方便分配权限',
PRIMARY KEY (`id`),
UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- auth_group 用户组表
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
`status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
`rules` text NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则","隔开',
PRIMARY KEY (`id`)
) ENGINE=InnoDB  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- auth_group_access 用户组明细表
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
`uid` mediumint(8) unsigned NOT NULL COMMENT '用户id',
`group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
KEY `uid` (`uid`),
KEY `group_id` (`group_id`)
) ENGINE=InnoDB  AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户组明细表';

##########################权限################################################

-- ----------------------------
-- Table structure for `j_sysuser_account`
-- ----------------------------
DROP TABLE IF EXISTS `j_sysuser_account`;
CREATE TABLE `j_sysuser_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` int(11) DEFAULT NULL COMMENT '账号',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮件',
  `mobile_area_code` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机国家区号',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机',
  `login_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `account_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '账户类型，0:手机，1：平板',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态，0:正常，1:冻结',
  `country_id` int(11) DEFAULT NULL COMMENT '所属国家,j_country表',
  `create_time` datetime DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '软删除状态，0正常，1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='前台帐户通行表';

-- ----------------------------
-- Table structure for `j_sysuser_user`
-- ----------------------------
DROP TABLE IF EXISTS `j_sysuser_user`;
CREATE TABLE `j_sysuser_user` (
  `user_id` int(11) NOT NULL,
  `nickname` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '昵称',
  `realname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真名',
  `sex` int(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '性别:0未知，1男，2女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `login_num` int(11) DEFAULT NULL COMMENT '登录次数',
  `reg_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '注册IP',
  `reg_time` datetime DEFAULT NULL COMMENT '注册时间',
  `last_login_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登录IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `signature` text COLLATE utf8_unicode_ci COMMENT '签名',
  `avatar_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `device_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '注册类型，0:手机，1:平板',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '软删除状态，0正常，1删除',
  PRIMARY KEY (`user_id`),
  KEY `name` (`nickname`),
  KEY `userId` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息表';

-- ----------------------------
-- Table structure for `j_oauth_login`
-- ----------------------------
DROP TABLE IF EXISTS `j_oauth_login`;
CREATE TABLE `j_oauth_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '本站用户',
  `openid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '第三方用户id',
  `unionid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微信多平台唯一',
  `type_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '类型:qq、wechat、sina',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '访问令牌',
  `param` text COLLATE utf8_unicode_ci COMMENT '返回参数',
  `create_time` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0:正常，1：删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='第三方绑定登录用户表';


-- ----------------------------
-- Table structure for `j_country`
-- ----------------------------
DROP TABLE IF EXISTS `j_country`;
CREATE TABLE `j_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '自己国家所属语言名称',
  `chinese_name` varchar(255) DEFAULT NULL COMMENT '区域即国家名称,统一为中文名称',
  `english_name` varchar(255) DEFAULT NULL COMMENT '英文标识,统一为英文标识',
  `logo` varchar(255) DEFAULT NULL COMMENT '图片即国旗',
  `area_code` varchar(255) DEFAULT NULL COMMENT '区号，手机带的',
  `status` tinyint(1) DEFAULT NULL COMMENT '开放状态,0开放，1为未开放',
  `update_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '软删除状态，0正常，1删除',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序，升序即越大越靠前',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='区域表（国家）';

-- ----------------------------
-- Records of j_country
-- ----------------------------

-- ----------------------------
-- Table structure for `j_live`
-- ----------------------------
DROP TABLE IF EXISTS `j_live`;
CREATE TABLE `j_live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live_channel_id` int(11) DEFAULT NULL COMMENT '直播频道ID，目前对应网易直播表j_netease_live_channel',
  `title` varchar(255) DEFAULT NULL COMMENT '标题(歌曲名称)',
  `cover_picture` varchar(255) DEFAULT NULL COMMENT '封面图',
  `content` text COMMENT '直播内容',
  `update_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='直播表';

-- ----------------------------
-- Records of j_live
-- ----------------------------

-- ----------------------------
-- Table structure for `j_live_vote`
-- ----------------------------
DROP TABLE IF EXISTS `j_live_vote`;
CREATE TABLE `j_live_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live_id` int(11) DEFAULT NULL COMMENT '直播ID',
  `user_id` int(11) DEFAULT NULL COMMENT '投票用户ID',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live_id` (`live_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='直播投票详细记录表';

-- ----------------------------
-- Records of j_live_vote
-- ----------------------------

-- ----------------------------
-- Table structure for `j_netease_live_channel`
-- ----------------------------
DROP TABLE IF EXISTS `j_netease_live_channel`;
CREATE TABLE `j_netease_live_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '所属用户',
  `name` varchar(255) DEFAULT NULL COMMENT '频道名称',
  `cid` varchar(255) DEFAULT NULL COMMENT '频道ID',
  `ctime` varchar(255) DEFAULT NULL COMMENT '网易云频道创建时间',
  `push_url` varchar(255) DEFAULT NULL COMMENT '推流地址',
  `http_pull_url` varchar(255) DEFAULT NULL COMMENT 'http拉流地址',
  `hls_pull_url` varchar(255) DEFAULT NULL COMMENT 'hls拉流地址',
  `rtmp_pull_url` varchar(255) DEFAULT NULL COMMENT 'rtmp拉流地址',
  `update_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网易直播频道表';

-- ----------------------------
-- Table structure for `j_singer`
-- ----------------------------
DROP TABLE IF EXISTS `j_singer`;
CREATE TABLE `j_singer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `update_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='歌手表';

-- ----------------------------
-- Records of j_singer
-- ----------------------------

-- ----------------------------
-- Table structure for `j_user_follow`
-- ----------------------------
DROP TABLE IF EXISTS `j_user_follow`;
CREATE TABLE `j_user_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follow_user_id` int(11) DEFAULT NULL COMMENT '被关注用户',
  `user_id` int(11) DEFAULT NULL COMMENT '关注用户',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follow_user_id` (`follow_user_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户关注表';

-- ----------------------------
-- Records of j_user_follow
-- ----------------------------

-- ----------------------------
-- Table structure for `j_user_follow_log`
-- ----------------------------
DROP TABLE IF EXISTS `j_user_follow_log`;
CREATE TABLE `j_user_follow_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follow_user_id` int(11) DEFAULT NULL COMMENT '被关注用户',
  `user_id` int(11) DEFAULT NULL COMMENT '关注用户',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型：0关注，1取消关注',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follow_user_id` (`follow_user_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户关注历史记录表';

