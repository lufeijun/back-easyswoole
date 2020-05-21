-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2020-05-21 18:21:02
-- 服务器版本： 8.0.18
-- PHP 版本： 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `easyswoole`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_ability`
--

CREATE TABLE `admin_ability` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `ability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `half_checked` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色权限对应表、';

--
-- 转存表中的数据 `admin_ability`
--

INSERT INTO `admin_ability` (`id`, `role_id`, `ability`, `type`, `half_checked`) VALUES
(1, 2, 'dsdasd', 1, 0),
(2, 2, 'dasdsd', 2, 0),
(45, 3, 'one级,two级,功能二,', 2, 0),
(46, 3, '权限管理,', 1, 0),
(47, 3, '权限管理,角色列表,', 1, 0),
(48, 3, '权限管理,成员列表,', 1, 0),
(49, 3, '权限,成员管理,', 1, 0),
(50, 3, '权限,成员管理,成员列表,', 1, 0),
(51, 3, '权限,成员管理,成员信息,', 1, 0),
(52, 3, '权限,', 1, 1),
(53, 1, '权限管理,', 1, 0),
(54, 1, '权限管理,角色列表,', 1, 0),
(55, 1, '权限管理,成员列表,', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色表';

--
-- 转存表中的数据 `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `create_time`, `update_time`) VALUES
(1, '管理员', '2020-05-15 00:00:00', '2020-05-15 00:00:00'),
(2, '编辑员', '2020-05-18 00:00:00', '2020-05-18 15:29:46'),
(3, '测试员', '2020-05-18 15:39:14', '2020-05-18 15:39:14');

-- --------------------------------------------------------

--
-- 表的结构 `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `pwd` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `enable` smallint(4) NOT NULL DEFAULT '1',
  `create_at` timestamp NOT NULL,
  `update_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `name`, `pwd`, `avatar`, `role_ids`, `enable`, `create_at`, `update_at`) VALUES
(1, 'admin@ooxx.com', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1589968481-28685.jpg', ',1,', 1, '2020-05-13 16:00:00', '2020-05-21 09:14:13'),
(2, 'test@ooxx.com', '测试', 'e10adc3949ba59abbe56e057f20f883e', '0.jpg', ',3,', 1, '2020-05-19 10:00:06', '2020-05-19 10:14:02'),
(3, 'editor@ooxx.com', 'editor', 'e10adc3949ba59abbe56e057f20f883e', '0.jpg', ',2,', 1, '2020-05-20 04:21:45', '2020-05-20 04:21:45'),
(4, 'test@qq.com', 'test', 'e40f01afbb1b9ae3dd6747ced5bca532', '0.jpg', ',,', 1, '2020-05-20 04:24:27', '2020-05-20 04:29:58'),
(5, 'lufeijun@qq.com', '路飞君', 'e40f01afbb1b9ae3dd6747ced5bca532', '1589968542-76915.jpeg', ',3,', 1, '2020-05-20 08:27:24', '2020-05-20 09:55:43'),
(6, 'lufeijun@qq1.com', '测试1', 'e40f01afbb1b9ae3dd6747ced5bca532', '0.jpg', ',,', 1, '2020-05-21 09:15:56', '2020-05-21 09:17:52');

--
-- 转储表的索引
--

--
-- 表的索引 `admin_ability`
--
ALTER TABLE `admin_ability`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin_ability`
--
ALTER TABLE `admin_ability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- 使用表AUTO_INCREMENT `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
