/**
 * database zhidao
 * By xiayujie
 */
/**
 * 用户表
 * @type {[type]}
 */
CREATE TABLE `zhidao_user` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `nickname` char(32) NOT NULL COMMENT '昵称',
  `email` char(32) NOT NULL COMMENT '邮箱',
  `password` char(32) NOT NULL  COMMENT '密码',
  `salt` char(6) NOT NULL COMMENT '密码加盐',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '用户状态 1启用2禁用',
  `avatar` char(32) NOT NULL DEFAULT '' COMMENT '用户头像',
  `user_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '用户类型1普通用户2管理员',
  `role_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户角色',
  `last_login_ip` char(32) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` DATETIME NOT NULL DEFAULT '2019-04-04 00:00:00' COMMENT '最后登录时间',
  `is_freeze` tinyint(1) NOT NULL DEFAULT 0 COMMENT '冻结状态 0未冻结 1已冻结',
  `is_active` tinyint(1) NOT NULL DEFAULT 2 COMMENT '激活状态 1已激活 2未激活',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `unique_nickname` (`nickname`),
  KEY `_status` (`status`),
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 二手物品表
 * @type {[type]}
 */
CREATE TABLE `zhidao_used_good` (
  `gid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '物品gid',
  `uid` bigint(20) NOT NULL COMMENT '物品发布者uid',
  `name` char(32) NOT NULL COMMENT '物品名称',
  `type` tinyint(1) NOT NULL  COMMENT '物品类型',
  `photo` text NOT NULL DEFAULT '' COMMENT '物品图片',
  `detail` text NOT NULL DEFAULT '' COMMENT '物品介绍',
  `price` char(32) NOT NULL DEFAULT '' COMMENT '物品价格',
  `phone` char(32) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `is_sell` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 卖出 2买入',
  `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1已售/已买 2未售/未买',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`gid`),
  KEY `_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 兼职招聘信息表
 * @type {[type]}
 */
CREATE TABLE `zhidao_recruit_info` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '招聘id',
  `uid` bigint(20) NOT NULL COMMENT '发布人uid',
  `work` varchar(64) NOT NULL COMMENT '招聘职位',
  `detail` text NOT NULL  COMMENT '详情',
  `count` int(4) NOT NULL COMMENT '人数',
  `days` int(4) NOT NULL DEFAULT 1 COMMENT '天数',
  `work_time` varchar(64) NOT NULL DEFAULT '' COMMENT '工作时间',
  `work_place` varchar(64) NOT NULL DEFAULT '' COMMENT '工作地点',
  `salary` varchar(64) NOT NULL DEFAULT '' COMMENT '薪资(元/天)',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '职位状态1在招2结束',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`rid`),
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 登录日志表
 */
CREATE TABLE `zhidao_login_log` (
  `lid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(20) NOT NULL COMMENT '登录用户id',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '登录状态0失败1成功',
  `login_ip` char(32) NOT NULL COMMENT '登录ip',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`lid`),
  KEY `create_time`（`create_time`），
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 操作日志表
 */
CREATE TABLE `zhidao_action_log` (
  `log_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `action` tinyint(1) NOT NULL COMMENT '1增2删3查4改',
  `detail` varchar(64) NOT NULL DEFAULT '' COMMENT '操作原因',
  `data` text NOT NULL DEFAULT '' COMMENT '操作的数据',
  `uid` bigint(20) NOT NULL COMMENT '操作用户id',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 网站配置信息表
 */
CREATE TABLE `zhidao_config` (
  `cid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增cid',
  `config_name` char(32) NOT NULL COMMENT '配置名',
  `config_value` varchar(32) NOT NULL COMMENT '配置信息',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 角色表
 */
CREATE TABLE `zhidao_role` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(20) NOT NULL COMMENT '添加这uid',
  `role_name` varchar(32) NOT NULL COMMENT '角色名称',
  `role_permission` text NOT NULL DEFAULT '' COMMENT '角色权限,job/index',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY kEY (`rid`),
  UNIQUE KEY `_rolename` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
-- /**
--  * 用户角色表
--  */
-- CREATE TABLE `zhidao_user_role` (
--   `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
--   `uid` bigint(20) NOT NULL COMMENT '用户id',
--   `rid` bigint(20) NOT NULL COMMENT '角色id',
--   PRIMARY kEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 首页轮播图表
 */
CREATE TABLE `zhidao_carousel_img` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `img` varchar(60) NOT NULL DEFAULT '' COMMENT '图片地址',
  `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '添加人uid',
  `sort` int(3) NOT NULL DEFAULT 0 COMMENT '排序字段',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY kEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 友情链接表
 */
CREATE TABLE `zhidao_friend_link` (
  `lid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '链接文字',
  `link` varchar(64) NOT NULL DEFAULT '' COMMENT '链接地址',
  `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '添加人uid',
  `sort` int(3) NOT NULL DEFAULT 0 COMMENT '排序字段',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY kEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
-- /**
--  * 帖子信息表
--  */
-- CREATE TABLE `zhidao_forum_post` (
--   `pid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
--   `title` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子标题',
--   `content` text NOT NULL DEFAULT '' COMMENT '帖子内容',
--   `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '发帖人uid',
--   `type` int(10) NOT NULL DEFAULT 0 COMMENT '帖子类型',
--   `key_word` varchar(64) NOT NULL DEFAULT '' COMMENT '帖子关键字',
--   `is_private` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否私密',
--   `click_up` int(10) NOT NULL DEFAULT 0 COMMENT '点赞数',
--   `click_down` int(10) NOT NULL DEFAULT 0 COMMENT '踩数', 
--   `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发帖时间',
--   PRIMARY kEY (`pid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
-- /**
--  * 帖子留言表
--  */
-- CREATE TABLE `zhidao_forum_reply` (
--   `rid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增rid',
--   `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '评论人id',
--   `content`varchar(256) NOT NULL DEFAULT '' COMMENT '评论内容',
--   `click_up` int(10) NOT NULL DEFAULT 0 COMMENT '点赞数',
--   `click_down` int(10) NOT NULL DEFAULT 0 COMMENT '踩数',
--   `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '评论时间',
--   PRIMARY kEY (`rid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
-- /**
--  * 论坛版块表
--  */
-- CREATE TABLE `zhidao_forum` (
--   `fid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增fid',
--   `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
--   `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建者uid',
--   `sort` int(6) NOT NULL DEFAULT 0 COMMENT '排序',
--   `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
--   `update_time` DATETIME NOT NUll DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
--   PRIMARY KEY (`fid`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 高校资讯表
 */
CREATE TABLE `zhidao_news` (
  `nid` bigint(20) NOT NUll AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(64) NOT NUll DEFAULT '' COMMENT '标题',
  `type` tinyint(1) NOT NULL DEFAULT 6 COMMENT '文章分类',
  `content` text NOT NUll DEFAULT '' COMMENT '内容',
  `uid` bigint(20) NOT NULL DEFAULT 0 COMMENT '发布人uid',
  `r_count` int(10) NOT NULL DEFAULT 0 COMMENT '阅读人数',
  `click_up` int(10) NOT NULL DEFAULT 0 COMMENT '点赞数',
  `click_down` int(10) NOT NULL DEFAULT 0 COMMENT '踩数',
  `create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY kEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 * 用户文章关系表
 */
CREATE TABLE `zhidao_user_news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(20) NOT NULL COMMENT '操作用户uid',
  `nid` bigint(20) NOT NULL COMMENT '文章id',
  `is_up` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否点赞1是0否',
  `is_down` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否踩1是0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
/**
 *变更表结构
 */
ALTER TABLE zhidao_user ADD `user_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '用户类型1普通用户2管理员' AFTER `avatar`;
ALTER TABLE zhidao_login_logs ADD `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '登录状态' AFTER `uid`;
ALTER TABLE zhidao_user ADD `is_freeze` tinyint(1) NOT NULL DEFAULT 0 COMMENT '冻结状态 0未冻结 1已冻结' AFTER `is_active`;
ALTER TABLE zhidao_role ADD `uid` bigint(20) NOT NULL COMMENT '添加这uid' AFTER `rid`;
ALTER TABLE zhidao_friend_link ADD `uid` bigint(20) NOT NULL COMMENT '添加者uid' AFTER `link`;
ALTER TABLE zhidao_carousel_img ADD `uid` bigint(20) NOT NULL COMMENT '添加者uid' AFTER `img`;
ALTER TABLE zhidao_action_log ADD `detail` varchar(64) NOT NULL DEFAULT '' COMMENT '操作原因' AFTER `action`;
ALTER TABLE zhidao_news ADD `type` tinyint(1) NOT NULL DEFAULT 6 COMMENT '文章分类' AFTER `title`;
ALTER TABLE zhidao_used_good ADD `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1已售/已买 2未售/未买' AFTER `is_sell`;