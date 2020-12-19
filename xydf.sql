-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2020-04-29 11:14:07
-- 服务器版本： 5.6.47-log
-- PHP Version: 7.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baodanbao360_com`
--

-- --------------------------------------------------------

--
-- 表的结构 `df_admin`
--

CREATE TABLE `df_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `app_secret` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_admin`
--

INSERT INTO `df_admin` (`id`, `username`, `nickname`, `password`, `salt`, `avatar`, `email`, `loginfailure`, `logintime`, `loginip`, `createtime`, `updatetime`, `token`, `status`, `app_secret`) VALUES
(1, 'admin', 'Admin', 'b10ba36999f387e0e493ade2f490729a', '9OxEVC', '/assets/img/avatar.png', 'admin@admin.com', 0, 1588130037, '125.110.251.22', 1492186163, 1588130037, '6045ea6b-b0e1-4f85-a08c-0276c534d6ab', 'normal', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `df_admin_log`
--

CREATE TABLE `df_admin_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text NOT NULL COMMENT '内容',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) DEFAULT NULL COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员日志表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_admin_log`
--

INSERT INTO `df_admin_log` (`id`, `admin_id`, `username`, `url`, `title`, `content`, `ip`, `useragent`, `createtime`) VALUES
(1, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"9603e0d7a89e54b596c8a03f28cb27da\",\"username\":\"admin\",\"captcha\":\"jxnb\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588082708),
(2, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"9619b8b8c46c4fe8f59451b7e5e18bee\",\"username\":\"admin\",\"captcha\":\"3yx3\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588082741),
(3, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"d5059d8a942c0200bc9ea8c8afa6b810\",\"username\":\"admin\",\"captcha\":\"wqbv\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588082783),
(4, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"6fa95b99b5415a58cb03bcada291b3de\",\"username\":\"admin\",\"captcha\":\"vemv\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588083064),
(5, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"30361ccd80ef053bd6c185b2c555f2c2\",\"username\":\"admin\",\"captcha\":\"epyd\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588083075),
(6, 1, 'admin', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"2c4243d70e37d7708983706c27199465\",\"username\":\"admin\",\"captcha\":\"kfvf\"}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588083203),
(7, 1, 'admin', '/ZLTchzxFfK.php/general.profile/update', '常规管理 个人资料 更新个人信息', '{\"__token__\":\"c275e25a9d8d2d9ade700ec19f37792a\",\"row\":{\"avatar\":\"\\/assets\\/img\\/avatar.png\",\"email\":\"admin@admin.com\",\"nickname\":\"Admin\",\"password\":\"123456\"}}', '112.12.146.80', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588083286),
(8, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"e9f413714db897f593c012e70bc89bfe\",\"username\":\"admin\",\"captcha\":\"yfqa\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588129787),
(9, 1, 'admin', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"d1cbff199e6e880fc2512a6cd25b7ee7\",\"username\":\"admin\",\"captcha\":\"yrt6\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588129860),
(10, 1, 'admin', '/ZLTchzxFfK.php/general.profile/update', '常规管理 个人资料 更新个人信息', '{\"__token__\":\"4e6024003e9330de85260012d73e297d\",\"row\":{\"avatar\":\"\\/assets\\/img\\/avatar.png\",\"email\":\"admin@admin.com\",\"nickname\":\"Admin\",\"password\":\"123456\"}}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588129933),
(11, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"4b4f32c655362a033553d376aa6d8f6c\",\"username\":\"admin\",\"captcha\":\"v52v\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588129952),
(12, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"c3067433da30e51bd9a2b18e354b38bf\",\"username\":\"admin\",\"captcha\":\"kxr3\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588129967),
(13, 0, 'Unknown', '/ZLTchzxFfK.php/index/login', '', '{\"__token__\":\"328e27c1f51024fed14226e2191edae7\",\"username\":\"admin\",\"captcha\":\"pxlo\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588130005),
(14, 1, 'admin', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"464b6fd09fae0117fb0c2c6f9f32b5b0\",\"username\":\"admin\",\"captcha\":\"a2gg\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588130011),
(15, 1, 'admin', '/ZLTchzxFfK.php/general.profile/update', '常规管理 个人资料 更新个人信息', '{\"__token__\":\"edca9aeef1bfb8981129f8dbbc07aaaf\",\"row\":{\"avatar\":\"\\/assets\\/img\\/avatar.png\",\"email\":\"admin@admin.com\",\"nickname\":\"Admin\",\"password\":\"admin123321\"}}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588130020),
(16, 1, 'admin', '/ZLTchzxFfK.php/index/login', '登录', '{\"__token__\":\"5a46ed1003916d09fdd8f56ef376710d\",\"username\":\"admin\",\"captcha\":\"dunx\"}', '125.110.251.22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0', 1588130037);

-- --------------------------------------------------------

--
-- 表的结构 `df_attachment`
--

CREATE TABLE `df_attachment` (
  `id` int(20) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filesize` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_auth_group`
--

CREATE TABLE `df_auth_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text NOT NULL COMMENT '规则ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分组表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_auth_group`
--

INSERT INTO `df_auth_group` (`id`, `pid`, `name`, `rules`, `createtime`, `updatetime`, `status`) VALUES
(1, 0, 'Admin group', '*', 1490883540, 149088354, 'normal'),
(2, 1, 'Second group', '13,14,16,15,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,1,9,10,11,7,6,8,2,4,5', 1490883540, 1505465692, 'normal'),
(3, 2, 'Third group', '1,4,9,10,11,13,14,15,16,17,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,5', 1490883540, 1502205322, 'normal'),
(4, 1, 'Second group 2', '1,4,13,14,15,16,17,55,56,57,58,59,60,61,62,63,64,65', 1490883540, 1502205350, 'normal'),
(5, 2, 'Third group 2', '1,2,6,7,8,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34', 1490883540, 1502205344, 'normal'),
(6, 1, '码商', '23,24,25,26,27,28,29,30,32,90,91,96,100,101,92,97,102,98,94,7,86,88,89,2,8,85,87', 1583905935, 1587795499, 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `df_auth_group_access`
--

CREATE TABLE `df_auth_group_access` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员ID',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '级别ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限分组表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_auth_group_access`
--

INSERT INTO `df_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(8, 6),
(9, 6),
(10, 6),
(11, 6);

-- --------------------------------------------------------

--
-- 表的结构 `df_auth_rule`
--

CREATE TABLE `df_auth_rule` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='节点表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_auth_rule`
--

INSERT INTO `df_auth_rule` (`id`, `type`, `pid`, `name`, `title`, `icon`, `condition`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(1, 'file', 0, 'dashboard', 'Dashboard', 'fa fa-dashboard', '', 'Dashboard tips', 0, 1497429920, 1586750467, 143, 'normal'),
(2, 'file', 0, 'general', 'General', 'fa fa-cogs', '', '', 1, 1497429920, 1497430169, 137, 'normal'),
(3, 'file', 0, 'category', 'Category', 'fa fa-leaf', '', 'Category tips', 0, 1497429920, 1586750531, 119, 'normal'),
(4, 'file', 0, 'addon', 'Addon', 'fa fa-rocket', '', 'Addon tips', 0, 1502035509, 1586750432, 0, 'normal'),
(5, 'file', 0, 'auth', '商户管理', 'fa fa-group', '', '', 1, 1497429920, 1584684726, 99, 'normal'),
(6, 'file', 2, 'general/config', 'Config', 'fa fa-cog', '', 'Config tips', 1, 1497429920, 1497430683, 60, 'normal'),
(7, 'file', 2, 'general/attachment', 'Attachment', 'fa fa-file-image-o', '', 'Attachment tips', 1, 1497429920, 1497430699, 53, 'normal'),
(8, 'file', 2, 'general/profile', 'Profile', 'fa fa-user', '', '', 1, 1497429920, 1497429920, 34, 'normal'),
(9, 'file', 5, 'auth/admin', '商户列表', 'fa fa-user', '', 'Admin tips', 1, 1497429920, 1584684746, 118, 'normal'),
(10, 'file', 5, 'auth/adminlog', '商户日志', 'fa fa-list-alt', '', 'Admin log tips', 1, 1497429920, 1584684761, 113, 'normal'),
(11, 'file', 5, 'auth/group', '商户权限', 'fa fa-address-card', '', 'Group tips', 1, 1497429920, 1584696857, 109, 'normal'),
(12, 'file', 5, 'auth/rule', 'Rule', 'fa fa-bars', '', 'Rule tips', 1, 1497429920, 1497430581, 104, 'normal'),
(13, 'file', 1, 'dashboard/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 136, 'normal'),
(14, 'file', 1, 'dashboard/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 135, 'normal'),
(15, 'file', 1, 'dashboard/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 133, 'normal'),
(16, 'file', 1, 'dashboard/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 134, 'normal'),
(17, 'file', 1, 'dashboard/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 132, 'normal'),
(18, 'file', 6, 'general/config/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 52, 'normal'),
(19, 'file', 6, 'general/config/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 51, 'normal'),
(20, 'file', 6, 'general/config/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 50, 'normal'),
(21, 'file', 6, 'general/config/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 49, 'normal'),
(22, 'file', 6, 'general/config/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 48, 'normal'),
(23, 'file', 7, 'general/attachment/index', 'View', 'fa fa-circle-o', '', 'Attachment tips', 0, 1497429920, 1497429920, 59, 'normal'),
(24, 'file', 7, 'general/attachment/select', 'Select attachment', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 58, 'normal'),
(25, 'file', 7, 'general/attachment/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 57, 'normal'),
(26, 'file', 7, 'general/attachment/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 56, 'normal'),
(27, 'file', 7, 'general/attachment/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 55, 'normal'),
(28, 'file', 7, 'general/attachment/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 54, 'normal'),
(29, 'file', 8, 'general/profile/index', 'View', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 33, 'normal'),
(30, 'file', 8, 'general/profile/update', 'Update profile', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 32, 'normal'),
(31, 'file', 8, 'general/profile/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 31, 'normal'),
(32, 'file', 8, 'general/profile/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 30, 'normal'),
(33, 'file', 8, 'general/profile/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 29, 'normal'),
(34, 'file', 8, 'general/profile/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 28, 'normal'),
(35, 'file', 3, 'category/index', 'View', 'fa fa-circle-o', '', 'Category tips', 0, 1497429920, 1497429920, 142, 'normal'),
(36, 'file', 3, 'category/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 141, 'normal'),
(37, 'file', 3, 'category/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 140, 'normal'),
(38, 'file', 3, 'category/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 139, 'normal'),
(39, 'file', 3, 'category/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 138, 'normal'),
(40, 'file', 9, 'auth/admin/index', 'View', 'fa fa-circle-o', '', 'Admin tips', 0, 1497429920, 1497429920, 117, 'normal'),
(41, 'file', 9, 'auth/admin/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 116, 'normal'),
(42, 'file', 9, 'auth/admin/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 115, 'normal'),
(43, 'file', 9, 'auth/admin/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 114, 'normal'),
(44, 'file', 10, 'auth/adminlog/index', 'View', 'fa fa-circle-o', '', 'Admin log tips', 0, 1497429920, 1497429920, 112, 'normal'),
(45, 'file', 10, 'auth/adminlog/detail', 'Detail', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 111, 'normal'),
(46, 'file', 10, 'auth/adminlog/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 110, 'normal'),
(47, 'file', 11, 'auth/group/index', 'View', 'fa fa-circle-o', '', 'Group tips', 0, 1497429920, 1497429920, 108, 'normal'),
(48, 'file', 11, 'auth/group/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 107, 'normal'),
(49, 'file', 11, 'auth/group/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 106, 'normal'),
(50, 'file', 11, 'auth/group/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 105, 'normal'),
(51, 'file', 12, 'auth/rule/index', 'View', 'fa fa-circle-o', '', 'Rule tips', 0, 1497429920, 1497429920, 103, 'normal'),
(52, 'file', 12, 'auth/rule/add', 'Add', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 102, 'normal'),
(53, 'file', 12, 'auth/rule/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 101, 'normal'),
(54, 'file', 12, 'auth/rule/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1497429920, 1497429920, 100, 'normal'),
(55, 'file', 4, 'addon/index', 'View', 'fa fa-circle-o', '', 'Addon tips', 0, 1502035509, 1502035509, 0, 'normal'),
(56, 'file', 4, 'addon/add', 'Add', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(57, 'file', 4, 'addon/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(58, 'file', 4, 'addon/del', 'Delete', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(59, 'file', 4, 'addon/downloaded', 'Local addon', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(60, 'file', 4, 'addon/state', 'Update state', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(63, 'file', 4, 'addon/config', 'Setting', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(64, 'file', 4, 'addon/refresh', 'Refresh', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(65, 'file', 4, 'addon/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1502035509, 1502035509, 0, 'normal'),
(66, 'file', 0, 'user', 'User', 'fa fa-list', '', '', 0, 1516374729, 1586750439, 0, 'normal'),
(67, 'file', 66, 'user/user', 'User', 'fa fa-user', '', '', 1, 1516374729, 1516374729, 0, 'normal'),
(68, 'file', 67, 'user/user/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(69, 'file', 67, 'user/user/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(70, 'file', 67, 'user/user/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(71, 'file', 67, 'user/user/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(72, 'file', 67, 'user/user/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(73, 'file', 66, 'user/group', 'User group', 'fa fa-users', '', '', 1, 1516374729, 1516374729, 0, 'normal'),
(74, 'file', 73, 'user/group/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(75, 'file', 73, 'user/group/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(76, 'file', 73, 'user/group/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(77, 'file', 73, 'user/group/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(78, 'file', 73, 'user/group/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(79, 'file', 66, 'user/rule', 'User rule', 'fa fa-circle-o', '', '', 1, 1516374729, 1516374729, 0, 'normal'),
(80, 'file', 79, 'user/rule/index', 'View', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(81, 'file', 79, 'user/rule/del', 'Del', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(82, 'file', 79, 'user/rule/add', 'Add', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(83, 'file', 79, 'user/rule/edit', 'Edit', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(84, 'file', 79, 'user/rule/multi', 'Multi', 'fa fa-circle-o', '', '', 0, 1516374729, 1516374729, 0, 'normal'),
(85, 'file', 0, 'shop', '闲鱼管理', 'fa fa-users', '', '', 1, 1583718407, 1584589120, 0, 'normal'),
(86, 'file', 85, 'shop/shop/index', '闲鱼店铺管理', 'fa fa-institution', '', '', 1, 1583727078, 1584685184, 0, 'normal'),
(87, 'file', 85, 'shop/product/index', '闲鱼产码管理', 'fa fa-cubes', '', '', 1, 1583739221, 1584849409, 0, 'normal'),
(88, 'file', 85, 'shop/order/index', '闲鱼订单管理', 'fa fa-address-card-o', '', '', 1, 1583807845, 1584685207, 0, 'normal'),
(89, 'file', 85, 'demo/demo/index', '闲鱼支付测试', 'fa fa-code', '', '', 1, 1584588778, 1585030276, 0, 'normal'),
(90, 'file', 86, 'shop/shop/add', '添加店铺', 'fa fa-circle-o', '', '', 0, 1584685279, 1584690323, 0, 'normal'),
(91, 'file', 86, 'shop/shop/edit', '编辑店铺', 'fa fa-circle-o', '', '', 0, 1584685333, 1584690338, 0, 'normal'),
(92, 'file', 87, 'shop/product/add', '添加商品', 'fa fa-circle-o', '', '', 0, 1584685375, 1584690351, 0, 'normal'),
(93, 'file', 87, 'shop/product/edit', '编辑商品', 'fa fa-circle-o', '', '', 0, 1584685408, 1584690363, 0, 'normal'),
(94, 'file', 89, 'demo/demo/qr', '测试二维码', 'fa fa-circle-o', '', '', 0, 1584774532, 1584776510, 0, 'normal'),
(95, 'file', 85, 'data/shopdata/index', '闲鱼数据统计', 'fa fa-bar-chart-o', '', '', 1, 1585011803, 1585030349, 0, 'normal'),
(96, 'file', 86, 'shop/shop/multi', '店铺操作', 'fa fa-circle-o', '', '', 0, 1585028409, 1585028409, 0, 'normal'),
(97, 'file', 87, 'shop/product/multi', '产码操作', 'fa fa-circle-o', '', '', 0, 1585028459, 1585028459, 0, 'normal'),
(98, 'file', 88, 'shop/order/callback', '订单回调', 'fa fa-circle-o', '', '', 0, 1585647690, 1585647690, 0, 'normal'),
(99, 'file', 87, 'shop/product/del', '删除', 'fa fa-circle-o', '', '', 0, 1587018320, 1587018320, 0, 'normal'),
(100, 'file', 86, 'shop/shop/clear', '清理店铺数据', 'fa fa-circle-o', '', '', 0, 1587024737, 1587086925, 0, 'normal'),
(101, 'file', 86, 'shop/shop/del', '删除', 'fa fa-circle-o', '', '', 0, 1587132187, 1587132187, 0, 'normal'),
(102, 'file', 87, 'shop/product/import', '导入', 'fa fa-circle-o', '', '', 0, 1587518636, 1587518636, 0, 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `df_category`
--

CREATE TABLE `df_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `flag` set('hot','index','recommend') NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) NOT NULL DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_category`
--

INSERT INTO `df_category` (`id`, `pid`, `type`, `name`, `nickname`, `flag`, `image`, `keywords`, `description`, `diyname`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(1, 0, 'page', '官方新闻', 'news', 'recommend', '/assets/img/qrcode.png', '', '', 'news', 1495262190, 1495262190, 1, 'normal'),
(2, 0, 'page', '移动应用', 'mobileapp', 'hot', '/assets/img/qrcode.png', '', '', 'mobileapp', 1495262244, 1495262244, 2, 'normal'),
(3, 2, 'page', '微信公众号', 'wechatpublic', 'index', '/assets/img/qrcode.png', '', '', 'wechatpublic', 1495262288, 1495262288, 3, 'normal'),
(4, 2, 'page', 'Android开发', 'android', 'recommend', '/assets/img/qrcode.png', '', '', 'android', 1495262317, 1495262317, 4, 'normal'),
(5, 0, 'page', '软件产品', 'software', 'recommend', '/assets/img/qrcode.png', '', '', 'software', 1495262336, 1499681850, 5, 'normal'),
(6, 5, 'page', '网站建站', 'website', 'recommend', '/assets/img/qrcode.png', '', '', 'website', 1495262357, 1495262357, 6, 'normal'),
(7, 5, 'page', '企业管理软件', 'company', 'index', '/assets/img/qrcode.png', '', '', 'company', 1495262391, 1495262391, 7, 'normal'),
(8, 6, 'page', 'PC端', 'website-pc', 'recommend', '/assets/img/qrcode.png', '', '', 'website-pc', 1495262424, 1495262424, 8, 'normal'),
(9, 6, 'page', '移动端', 'website-mobile', 'recommend', '/assets/img/qrcode.png', '', '', 'website-mobile', 1495262456, 1495262456, 9, 'normal'),
(10, 7, 'page', 'CRM系统 ', 'company-crm', 'recommend', '/assets/img/qrcode.png', '', '', 'company-crm', 1495262487, 1495262487, 10, 'normal'),
(11, 7, 'page', 'SASS平台软件', 'company-sass', 'recommend', '/assets/img/qrcode.png', '', '', 'company-sass', 1495262515, 1495262515, 11, 'normal'),
(12, 0, 'test', '测试1', 'test1', 'recommend', '/assets/img/qrcode.png', '', '', 'test1', 1497015727, 1497015727, 12, 'normal'),
(13, 0, 'test', '测试2', 'test2', 'recommend', '/assets/img/qrcode.png', '', '', 'test2', 1497015738, 1497015738, 13, 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `df_config`
--

CREATE TABLE `df_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text NOT NULL COMMENT '变量值',
  `content` text NOT NULL COMMENT '变量字典数据',
  `rule` varchar(100) NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展属性'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_config`
--

INSERT INTO `df_config` (`id`, `name`, `group`, `title`, `tip`, `type`, `value`, `content`, `rule`, `extend`) VALUES
(1, 'name', 'basic', 'Site name', '请填写站点名称', 'string', '代付系統', '', 'required', ''),
(2, 'beian', 'basic', 'Beian', '粤ICP备15000000号-1', 'string', '', '', '', ''),
(3, 'cdnurl', 'basic', 'Cdn url', '如果静态资源使用第三方云储存请配置该值', 'string', '', '', '', ''),
(4, 'version', 'basic', 'Version', '如果静态资源有变动请重新配置该值', 'string', '1.0.1', '', 'required', ''),
(5, 'timezone', 'basic', 'Timezone', '', 'string', 'Asia/Shanghai', '', 'required', ''),
(6, 'forbiddenip', 'basic', 'Forbidden ip', '一行一条记录', 'text', '', '', '', ''),
(7, 'languages', 'basic', 'Languages', '', 'array', '{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}', '', 'required', ''),
(8, 'fixedpage', 'basic', 'Fixed page', '请尽量输入左侧菜单栏存在的链接', 'string', 'dashboard', '', 'required', ''),
(9, 'categorytype', 'dictionary', 'Category type', '', 'array', '{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}', '', '', ''),
(10, 'configgroup', 'dictionary', 'Config group', '', 'array', '{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}', '', '', ''),
(11, 'mail_type', 'email', 'Mail type', '选择邮件发送方式', 'select', '1', '[\"Please select\",\"SMTP\",\"Mail\"]', '', ''),
(12, 'mail_smtp_host', 'email', 'Mail smtp host', '错误的配置发送邮件会导致服务器超时', 'string', 'smtp.qq.com', '', '', ''),
(13, 'mail_smtp_port', 'email', 'Mail smtp port', '(不加密默认25,SSL默认465,TLS默认587)', 'string', '465', '', '', ''),
(14, 'mail_smtp_user', 'email', 'Mail smtp user', '（填写完整用户名）', 'string', '10000', '', '', ''),
(15, 'mail_smtp_pass', 'email', 'Mail smtp password', '（填写您的密码）', 'string', 'password', '', '', ''),
(16, 'mail_verify_type', 'email', 'Mail vertify type', '（SMTP验证方式[推荐SSL]）', 'select', '2', '[\"None\",\"TLS\",\"SSL\"]', '', ''),
(17, 'mail_from', 'email', 'Mail from', '', 'string', '10000@qq.com', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `df_ems`
--

CREATE TABLE `df_ems` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) UNSIGNED DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮箱验证码表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_order`
--

CREATE TABLE `df_order` (
  `id` bigint(15) UNSIGNED NOT NULL,
  `order_sn` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '订单号',
  `shop_id` bigint(15) DEFAULT NULL COMMENT '商鋪id',
  `admin_id` int(11) DEFAULT NULL COMMENT '商家',
  `tb_order_sn` varchar(255) DEFAULT NULL COMMENT '淘寶訂單號',
  `order_status` tinyint(1) DEFAULT '0' COMMENT '订单状态 0：未支付 1：已支付 2：已过期',
  `order_sum` double(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `order_total` double(10,2) DEFAULT '0.00' COMMENT '订单总金额',
  `part_sn` varchar(255) DEFAULT NULL COMMENT '四方订单号',
  `expire_time` int(11) DEFAULT NULL COMMENT '过期时间',
  `ctime` int(11) DEFAULT '0',
  `from` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '下单终端',
  `callback_url` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '回調地址',
  `is_success` tinyint(1) DEFAULT '0' COMMENT '0 未回调 1 已回调 2 回调失败',
  `pay_time` int(11) DEFAULT '0' COMMENT '支付时间',
  `pay_date` varchar(45) DEFAULT NULL COMMENT '支付日期',
  `utime` int(11) NOT NULL DEFAULT '0' COMMENT '订单更新时间',
  `notify_status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '通知状态 1成功 2失败',
  `notify_time` int(11) NOT NULL DEFAULT '0' COMMENT '回调时间',
  `notify_number` tinyint(4) NOT NULL DEFAULT '0' COMMENT '回调次数',
  `hand_notify` tinyint(2) DEFAULT '0' COMMENT '手动回调0:不是1:是'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_product`
--

CREATE TABLE `df_product` (
  `id` int(11) UNSIGNED NOT NULL,
  `tb_order_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '' COMMENT '闲鱼订单号',
  `tb_qr` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '闲鱼代付二维码',
  `shop_id` int(11) DEFAULT '0' COMMENT '商户id',
  `sum` double(10,2) DEFAULT '0.00' COMMENT '金额',
  `is_sale` tinyint(1) DEFAULT '1' COMMENT '1:上架 0：下架 ',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '0:正常 1：已锁定',
  `is_payed` tinyint(1) DEFAULT '0' COMMENT '0:未支付 1：已支付',
  `admin_id` int(11) DEFAULT '0',
  `expire_time` int(11) DEFAULT NULL COMMENT '二維碼過期時間',
  `ctime` int(11) DEFAULT '0',
  `utime` int(11) DEFAULT NULL,
  `tb_name` varchar(45) DEFAULT '' COMMENT '店铺名称',
  `is_expire` tinyint(1) DEFAULT '1' COMMENT '1：未过期 0：已过期',
  `last_use_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后使用时间',
  `tb_qr_url` varchar(255) DEFAULT '' COMMENT '二维码地址',
  `is_del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否被删除 0:正常 1:被删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_shop`
--

CREATE TABLE `df_shop` (
  `id` int(11) UNSIGNED NOT NULL,
  `tb_name` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '闲鱼店铺名',
  `shop_status` tinyint(1) DEFAULT '0' COMMENT '店铺状态 0：正常 1：禁用',
  `ctime` int(11) DEFAULT '0',
  `utime` int(11) DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '商家token',
  `admin_id` int(11) DEFAULT '0' COMMENT '所属商户',
  `day_num` double(11,2) DEFAULT '0.00' COMMENT '今日下单金额',
  `deal_num` double(11,2) DEFAULT '0.00' COMMENT '成功金额',
  `day` varchar(45) DEFAULT NULL COMMENT '统计日期',
  `day_limit` double(11,2) DEFAULT NULL,
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `last_use_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后使用时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='闲鱼店铺名称' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_sms`
--

CREATE TABLE `df_sms` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) UNSIGNED DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信验证码表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_test`
--

CREATE TABLE `df_test` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类ID(单选)',
  `category_ids` varchar(100) NOT NULL COMMENT '分类ID(多选)',
  `week` enum('monday','tuesday','wednesday') NOT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') NOT NULL DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') NOT NULL DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') NOT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `images` varchar(1500) NOT NULL DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) NOT NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `city` varchar(100) NOT NULL DEFAULT '' COMMENT '省市',
  `json` varchar(255) DEFAULT NULL COMMENT '配置:key=名称,value=值',
  `price` float(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '价格',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点击',
  `startdate` date DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year(4) DEFAULT NULL COMMENT '年',
  `times` time DEFAULT NULL COMMENT '时间',
  `refreshtime` int(10) DEFAULT NULL COMMENT '刷新时间(int)',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开关',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_test`
--

INSERT INTO `df_test` (`id`, `admin_id`, `category_id`, `category_ids`, `week`, `flag`, `genderdata`, `hobbydata`, `title`, `content`, `image`, `images`, `attachfile`, `keywords`, `description`, `city`, `json`, `price`, `views`, `startdate`, `activitytime`, `year`, `times`, `refreshtime`, `createtime`, `updatetime`, `deletetime`, `weigh`, `switch`, `status`, `state`) VALUES
(1, 0, 12, '12,13', 'monday', 'hot,index', 'male', 'music,reading', '我是一篇测试文章', '<p>我是测试内容</p>', '/assets/img/avatar.png', '/assets/img/avatar.png,/assets/img/qrcode.png', '/assets/img/avatar.png', '关键字', '描述', '广西壮族自治区/百色市/平果县', '{\"a\":\"1\",\"b\":\"2\"}', 0.00, 0, '2017-07-10', '2017-07-10 18:24:45', 2017, '18:24:45', 1499682285, 1499682526, 1499682526, NULL, 0, 1, 'normal', '1');

-- --------------------------------------------------------

--
-- 表的结构 `df_user`
--

CREATE TABLE `df_user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) NOT NULL DEFAULT '' COMMENT '格言',
  `money` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分',
  `successions` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) NOT NULL DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) NOT NULL DEFAULT '' COMMENT '验证'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_user`
--

INSERT INTO `df_user` (`id`, `group_id`, `username`, `nickname`, `password`, `salt`, `email`, `mobile`, `avatar`, `level`, `gender`, `birthday`, `bio`, `money`, `score`, `successions`, `maxsuccessions`, `prevtime`, `logintime`, `loginip`, `loginfailure`, `joinip`, `jointime`, `createtime`, `updatetime`, `token`, `status`, `verification`) VALUES
(1, 1, 'admin', 'admin', 'c13f62012fd6a8fdf06b3452a94430e5', 'rpR6Bv', 'admin@163.com', '13888888888', '', 0, 0, '2017-04-15', '', '0.00', 0, 1, 1, 1583829110, 1583831590, '127.0.0.1', 0, '127.0.0.1', 1491461418, 0, 1583831590, '', 'normal', '');

-- --------------------------------------------------------

--
-- 表的结构 `df_user_group`
--

CREATE TABLE `df_user_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT '' COMMENT '组名',
  `rules` text COMMENT '权限节点',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员组表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_user_group`
--

INSERT INTO `df_user_group` (`id`, `name`, `rules`, `createtime`, `updatetime`, `status`) VALUES
(1, '默认组', '1,2,3,4,5,6,7,8,9,10,11,12', 1515386468, 1516168298, 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `df_user_money_log`
--

CREATE TABLE `df_user_money_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员余额变动表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_user_rule`
--

CREATE TABLE `df_user_rule` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `title` varchar(50) DEFAULT '' COMMENT '标题',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员规则表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_user_rule`
--

INSERT INTO `df_user_rule` (`id`, `pid`, `name`, `title`, `remark`, `ismenu`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(1, 0, 'index', '前台', '', 1, 1516168079, 1516168079, 1, 'normal'),
(2, 0, 'api', 'API接口', '', 1, 1516168062, 1516168062, 2, 'normal'),
(3, 1, 'user', '会员模块', '', 1, 1515386221, 1516168103, 12, 'normal'),
(4, 2, 'user', '会员模块', '', 1, 1515386221, 1516168092, 11, 'normal'),
(5, 3, 'index/user/login', '登录', '', 0, 1515386247, 1515386247, 5, 'normal'),
(6, 3, 'index/user/register', '注册', '', 0, 1515386262, 1516015236, 7, 'normal'),
(7, 3, 'index/user/index', '会员中心', '', 0, 1516015012, 1516015012, 9, 'normal'),
(8, 3, 'index/user/profile', '个人资料', '', 0, 1516015012, 1516015012, 4, 'normal'),
(9, 4, 'api/user/login', '登录', '', 0, 1515386247, 1515386247, 6, 'normal'),
(10, 4, 'api/user/register', '注册', '', 0, 1515386262, 1516015236, 8, 'normal'),
(11, 4, 'api/user/index', '会员中心', '', 0, 1516015012, 1516015012, 10, 'normal'),
(12, 4, 'api/user/profile', '个人资料', '', 0, 1516015012, 1516015012, 3, 'normal');

-- --------------------------------------------------------

--
-- 表的结构 `df_user_score_log`
--

CREATE TABLE `df_user_score_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员积分变动表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `df_user_token`
--

CREATE TABLE `df_user_token` (
  `token` varchar(50) NOT NULL COMMENT 'Token',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `expiretime` int(10) DEFAULT NULL COMMENT '过期时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员Token表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `df_user_token`
--

INSERT INTO `df_user_token` (`token`, `user_id`, `createtime`, `expiretime`) VALUES
('40b1421663dd32897ace2e78fc83b0fed42c324b', 1, 1583635504, 1586227504),
('b812d906352208d93019889e97feb75f66266497', 1, 1583831590, 1586423590);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `df_admin`
--
ALTER TABLE `df_admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- Indexes for table `df_admin_log`
--
ALTER TABLE `df_admin_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `name` (`username`) USING BTREE;

--
-- Indexes for table `df_attachment`
--
ALTER TABLE `df_attachment`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_auth_group`
--
ALTER TABLE `df_auth_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_auth_group_access`
--
ALTER TABLE `df_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `group_id` (`group_id`) USING BTREE;

--
-- Indexes for table `df_auth_rule`
--
ALTER TABLE `df_auth_rule`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `weigh` (`weigh`) USING BTREE;

--
-- Indexes for table `df_category`
--
ALTER TABLE `df_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `weigh` (`weigh`,`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE;

--
-- Indexes for table `df_config`
--
ALTER TABLE `df_config`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `df_ems`
--
ALTER TABLE `df_ems`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_order`
--
ALTER TABLE `df_order`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_product`
--
ALTER TABLE `df_product`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_shop`
--
ALTER TABLE `df_shop`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_sms`
--
ALTER TABLE `df_sms`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_test`
--
ALTER TABLE `df_test`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_user`
--
ALTER TABLE `df_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `username` (`username`) USING BTREE,
  ADD KEY `email` (`email`) USING BTREE,
  ADD KEY `mobile` (`mobile`) USING BTREE;

--
-- Indexes for table `df_user_group`
--
ALTER TABLE `df_user_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_user_money_log`
--
ALTER TABLE `df_user_money_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_user_rule`
--
ALTER TABLE `df_user_rule`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_user_score_log`
--
ALTER TABLE `df_user_score_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `df_user_token`
--
ALTER TABLE `df_user_token`
  ADD PRIMARY KEY (`token`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `df_admin`
--
ALTER TABLE `df_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `df_admin_log`
--
ALTER TABLE `df_admin_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `df_attachment`
--
ALTER TABLE `df_attachment`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `df_auth_group`
--
ALTER TABLE `df_auth_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `df_auth_rule`
--
ALTER TABLE `df_auth_rule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- 使用表AUTO_INCREMENT `df_category`
--
ALTER TABLE `df_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `df_config`
--
ALTER TABLE `df_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `df_ems`
--
ALTER TABLE `df_ems`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `df_order`
--
ALTER TABLE `df_order`
  MODIFY `id` bigint(15) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `df_product`
--
ALTER TABLE `df_product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `df_shop`
--
ALTER TABLE `df_shop`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `df_sms`
--
ALTER TABLE `df_sms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `df_test`
--
ALTER TABLE `df_test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `df_user`
--
ALTER TABLE `df_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `df_user_group`
--
ALTER TABLE `df_user_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `df_user_money_log`
--
ALTER TABLE `df_user_money_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `df_user_rule`
--
ALTER TABLE `df_user_rule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `df_user_score_log`
--
ALTER TABLE `df_user_score_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
