-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019 年 3 月 12 日 15:45
-- サーバのバージョン： 10.1.37-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LearnSNS`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `comment`, `user_id`, `feed_id`, `created`, `updated`) VALUES
(1, 'aaa', 13, 1, '2018-05-02 17:23:16', '2018-05-02 09:23:16'),
(2, 'demodemo', 17, 13, '2019-03-12 23:06:21', '2019-03-12 14:06:21'),
(3, 'moga', 17, 12, '2019-03-12 23:39:29', '2019-03-12 14:39:29'),
(4, 'moga', 16, 14, '2019-03-12 23:40:29', '2019-03-12 14:40:29'),
(5, 'mogamoga', 17, 14, '2019-03-12 23:42:50', '2019-03-12 14:42:50'),
(6, 'moga', 17, 14, '2019-03-12 23:42:56', '2019-03-12 14:42:56'),
(7, 'moga', 17, 14, '2019-03-12 23:43:02', '2019-03-12 14:43:02');

-- --------------------------------------------------------

--
-- テーブルの構造 `feeds`
--

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL,
  `feed` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `img_name` varchar(255) DEFAULT NULL,
  `like_count` int(11) NOT NULL DEFAULT '0',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `feeds`
--

INSERT INTO `feeds` (`id`, `feed`, `user_id`, `img_name`, `like_count`, `comment_count`, `created`, `updated`) VALUES
(1, 'のび太くん、どこへ行くの？', 13, NULL, 0, 0, '2018-04-29 10:30:38', '2018-04-29 02:30:38'),
(2, 'のびただ。', 15, NULL, 0, 0, '2018-05-02 15:31:10', '2018-05-02 07:31:10'),
(3, 'どこでもいいじゃないか。', 15, NULL, 0, 0, '2018-05-02 15:31:38', '2018-05-02 07:31:38'),
(4, 'ほげほげ！', 13, NULL, 0, 0, '2018-05-15 11:12:47', '2018-05-15 03:12:47'),
(5, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:54:52', '2019-03-12 13:54:52'),
(6, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:57:45', '2019-03-12 13:57:45'),
(7, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:57:56', '2019-03-12 13:57:56'),
(8, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:58:00', '2019-03-12 13:58:00'),
(9, 'hoge\r\n', 16, NULL, 0, 0, '2019-03-12 22:58:17', '2019-03-12 13:58:17'),
(10, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:58:30', '2019-03-12 13:58:30'),
(11, 'hoge', 16, NULL, 0, 0, '2019-03-12 22:58:33', '2019-03-12 13:58:33'),
(12, 'moga', 16, NULL, 0, 1, '2019-03-12 23:04:12', '2019-03-12 14:39:29'),
(13, 'moge', 16, NULL, 0, 1, '2019-03-12 23:04:16', '2019-03-12 14:06:21'),
(14, 'moge\r\n', 17, NULL, 0, 4, '2019-03-12 23:09:24', '2019-03-12 14:43:02');

-- --------------------------------------------------------

--
-- テーブルの構造 `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`) VALUES
(1, 15, 17),
(2, 16, 17);

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `feed_id`) VALUES
(3, 17, 13),
(4, 16, 14);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `img_name`, `created`, `updated`) VALUES
(13, '重えもん', 'omo@emon', '$2y$10$x5jmaasyy2rf2Cne8UiSzu099babuxjyxymAsQWIpX5.kwnmpqzMK', '20180426105011doraemon.jpeg', '2018-04-26 10:50:22', '2018-04-26 02:50:22'),
(15, '野比のび太', 'nobita@nobi', '$2y$10$AmwNU8FklycDThnLgrXMBuOS.SFryuAeEJvWDTBAvVabl28ZE27vW', '20180428054902nobita.jpg', '2018-04-28 11:49:04', '2018-04-28 03:49:04'),
(16, 'demo', 'demo@demo', '$2y$10$knmYoMx9bD8TroBLFP3PFeBiYdgSMbGj5K0vWYOy.ZmlrAUQ21zV.', '20190312215410profile.jpg', '2019-03-12 22:54:11', '2019-03-12 13:54:11'),
(17, 'hoge', 'hoge@hoge', '$2y$10$TWmsjE.ppT8j2MfAjhkfSO2bqTyF2Mrq4Cwlh6dWEh7eU4Q70E7mO', '20190312215428doraemon.jpeg', '2019-03-12 22:54:29', '2019-03-12 13:54:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
