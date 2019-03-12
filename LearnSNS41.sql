-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018 年 5 月 16 日 01:59
-- サーバのバージョン： 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LearnSNS41`
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
(1, 'aaa', 13, 1, '2018-05-02 17:23:16', '2018-05-02 09:23:16');

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
(4, 'ほげほげ！', 13, NULL, 0, 0, '2018-05-15 11:12:47', '2018-05-15 03:12:47');

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(15, '野比のび太', 'nobita@nobi', '$2y$10$AmwNU8FklycDThnLgrXMBuOS.SFryuAeEJvWDTBAvVabl28ZE27vW', '20180428054902nobita.jpg', '2018-04-28 11:49:04', '2018-04-28 03:49:04');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
