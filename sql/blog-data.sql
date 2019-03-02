-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 02 Mars 2019 à 12:24
-- Version du serveur :  10.1.37-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blog`
--

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `id_post`, `id_user`, `reply_to`, `date_publication`, `ip`, `name`, `content`) VALUES
(6, 1, 0, 0, '2019-02-22 10:29:56', '1.2.3.4', 'Anonyme', 'HEllo'),
(11, 1, 1, 0, '2019-02-24 20:21:22', '1.2.3.4', 'Alexandre D', 'Test bleh'),
(13, 1, 5, 0, '2019-02-26 11:08:56', '2.3.4.5', 'David', 'Ceci est un commentaire AAAA'),
(14, 1, 5, 0, '2019-02-26 11:10:09', '2.3.4.5', 'David', 'Hello'),
(17, 1, 5, 0, '2019-02-26 11:15:00', '2.3.4.5', 'David', 'pppp'),
(21, 1, 0, 0, '2019-02-27 21:31:24', '4.5.6.7', 'Sean Paul', 'La chatte hoho'),
(23, 1, 0, 0, '2019-02-28 13:04:46', '1.2.3.4', 'Anonyme', 'Et encore un'),
(24, 1, 0, 0, '2019-02-28 13:04:50', '1.2.3.4', 'Anonyme', 'Et encore un autre'),
(25, 1, 0, 0, '2019-02-28 13:04:55', '1.2.3.4', 'Anonyme', 'Et encore un autre\r\n'),
(27, 1, 0, 0, '2019-02-28 13:05:06', '1.2.3.4', 'Anonyme', 'Et un autre encore'),
(28, 1, 0, 0, '2019-02-28 13:05:14', '1.2.3.4', 'Anonyme', 'Et un petit encore'),
(29, 1, 0, 0, '2019-02-28 13:05:22', '1.2.3.4', 'Anonyme', 'Et encore un'),
(30, 1, 0, 0, '2019-02-28 13:05:31', '1.2.3.4', 'Anonyme', 'Et puis un autre, encore'),
(31, 1, 0, 0, '2019-02-28 13:05:47', '1.2.3.4', 'Anonyme', 'Encore un autre'),
(33, 1, 0, 0, '2019-02-28 13:08:30', '1.2.3.4', 'Anonyme', 'Test test'),
(34, 1, 0, 0, '2019-02-28 13:08:37', '1.2.3.4', 'Anonyme', 'Blabla'),
(35, 1, 0, 0, '2019-02-28 13:08:47', '1.2.3.4', 'Anonyme', 'Blublu'),
(41, 1, 1, 35, '2019-02-28 19:56:45', '1.2.3.4', 'Alexandre D', 'blblblb'),
(43, 1, 1, 0, '2019-02-28 20:03:50', '1.2.3.4', 'Alexandre D', 'Taeryuigfazuig'),
(44, 1, 1, 0, '2019-02-28 20:09:32', '1.2.3.4', 'Alexandre D', 'Blergh'),
(45, 1, 1, 0, '2019-02-28 20:14:21', '1.2.3.4', 'Alexandre D', 'efuzeiufhze'),
(46, 1, 0, 0, '2019-02-28 20:22:41', '1.2.3.4', 'Anony', 'vb'),
(47, 1, 0, 0, '2019-02-28 20:24:03', '1.2.3.4', 'Anonyme', 'blerg'),
(48, 1, 0, 0, '2019-02-28 20:24:14', '1.2.3.4', 'Anon', 'sh'),
(49, 1, 0, 0, '2019-02-28 20:24:23', '1.2.3.4', 'Ano', 'blu'),
(50, 1, 6, 0, '2019-03-01 08:52:37', '6.7.8.9', 'Tyler le BG ', 'OUUUHHH POULOULOU'),
(53, 1, 1, 0, '2019-03-01 15:05:04', '1.2.3.4', 'Alexandre D', 'blergh'),
(54, 1, 1, 0, '2019-03-01 15:05:10', '1.2.3.4', 'Alexandre D', 'blu'),
(55, 1, 0, 0, '2019-03-01 15:20:29', '1.2.3.4', 'Anonyme', 'Bli'),
(56, 1, 0, 0, '2019-03-01 15:21:49', '1.2.3.4', 'Anonyme', 'ble'),
(57, 1, 0, 0, '2019-03-01 15:22:06', '1.2.3.4', 'Anonyme', 'dgiuaz'),
(59, 1, 1, 0, '2019-03-01 16:33:51', '1.2.3.4', 'Alexandre D', 'Blerg'),
(60, 1, 1, 0, '2019-03-01 16:33:58', '1.2.3.4', 'Alexandre D', 'blurgh'),
(61, 1, 1, 45, '2019-03-01 16:34:14', '1.2.3.4', 'Alexandre D', 'Hihi'),
(63, 1, 1, 0, '2019-03-01 16:34:58', '1.2.3.4', 'Alexandre D', 'blblb'),
(64, 1, 1, 0, '2019-03-01 16:36:08', '1.2.3.4', 'Alexandre D', 'blblblb'),
(65, 1, 1, 0, '2019-03-01 16:36:18', '1.2.3.4', 'Alexandre D', 'blblblb'),
(66, 1, 1, 0, '2019-03-01 16:37:17', '1.2.3.4', 'Alexandre D', 'blbl'),
(67, 1, 1, 0, '2019-03-01 16:37:23', '1.2.3.4', 'Alexandre D', 'blbl'),
(68, 1, 0, 0, '2019-03-01 16:45:33', '9.10.11.12', 'Ivy', 'test'),
(69, 1, 0, 0, '2019-03-01 16:45:44', '9.10.11.12', 'Ivy', 'salut toi\r\n'),
(70, 1, 0, 0, '2019-03-01 16:46:04', '9.10.11.12', 'Ivy', 'FILS DE POULPE\r\n'),
(72, 1, 0, 0, '2019-03-01 16:46:32', '9.10.11.12', 'kikidu36', 'jmapel kilyan et g 11 an lol\r\n'),
(73, 1, 1, 0, '2019-03-01 16:47:45', '1.2.3.4', 'Alexandre D', 'BLBLBLBL'),
(74, 1, 0, 0, '2019-03-01 16:48:20', '9.10.11.12', 'kikidu36', 'mdr le ga il c pa parlé tro kon lol'),
(75, 1, 0, 0, '2019-03-01 16:48:39', '9.10.11.12', 'kikidu36', 'vazy parle francé fdp la on kompren pas ce ke tu dis wallah\r\n'),
(76, 1, 0, 73, '2019-03-01 16:49:15', '9.10.11.12', 'kikidu36', 'lol t tro naz\r\n'),
(77, 1, 1, 0, '2019-03-01 16:50:05', '1.2.3.4', 'Alexandre D', 'Jparle comme jle voule'),
(78, 1, 1, 75, '2019-03-01 16:51:09', '1.2.3.4', 'Alexandre D', 'Même que jréponds HA');

--
-- Contenu de la table `images`
--

INSERT INTO `images` (`id`, `id_user`, `date_sent`, `file_name`, `type`, `title`) VALUES
(10, 1, '2019-02-27 20:10:12', '1551294612', 1, 'VODKA'),
(11, 1, '2019-02-27 20:10:30', '1551294630', 1, 'ROGNON');

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `date_publication`, `id_user`, `title`, `content`, `published`) VALUES
(1, '2019-02-27 11:56:00', 1, 'Lorem ipsum dolor sit amet', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a porttitor libero. Integer aliquam elementum molestie. Integer ullamcorper mi sed orci rutrum aliquet. Fusce rutrum, sem et efficitur eleifend, metus lorem luctus lectus, tempor tincidunt nibh mi non turpis. Aliquam ac maximus lorem. Fusce laoreet auctor sem, eget hendrerit orci. Pellentesque non justo sit amet nisi egestas pulvinar volutpat in velit. Curabitur vel condimentum est. Etiam eu sem nulla. Donec commodo efficitur lorem eget rutrum. Nulla vulputate turpis vitae nibh vulputate euismod. Phasellus tempor magna eu magna laoreet malesuada. Curabitur ut mauris at mauris euismod pulvinar non a massa. Aenean eros turpis, porttitor vitae porta sit amet, fringilla et justo. Mauris dapibus magna ipsum, quis aliquam ligula tincidunt vel. Sed a ullamcorper justo, eget pulvinar enim. Nunc accumsan viverra leo non pellentesque. Praesent imperdiet posuere urna vitae tincidunt. Ut hendrerit eget lacus a euismod. Nullam vel turpis sit amet sapien venenatis hendrerit. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer sit amet urna scelerisque, dapibus eros rutrum, auctor libero. Duis nec tempus diam, sed ornare mi. Vivamus blandit dui quis magna volutpat lacinia. Sed eros diam, rutrum nec purus sed, viverra aliquam augue. Pellentesque cursus orci tellus, a blandit sem finibus eget. Quisque interdum vitae mi vitae varius. Cras diam odio, maximus nec tempor ac, suscipit sit amet nulla. Morbi vitae sodales risus. Donec luctus, sem at feugiat ornare, lorem diam varius diam, sit amet ultrices nunc risus vestibulum ex. Proin ligula leo, interdum sed velit ut, finibus tempor nibh. Aliquam turpis tortor, faucibus sed tellus aliquam, mattis venenatis justo. Morbi eu est porta, bibendum sapien semper, malesuada dolor. Maecenas quis libero fringilla, ornare lorem bibendum, lobortis elit. Cras eu consequat turpis. Mauris vel sem porta, molestie dolor ut, ultrices lectus. Mauris lacinia est vel gravida egestas. Sed eget velit nulla. Etiam aliquet sapien neque, eget sagittis nisi egestas ac. Aliquam feugiat ligula non dui porta elementum. Nullam facilisis sagittis sagittis. Cras quis laoreet lectus. Donec vel commodo dui. Sed imperdiet sapien id nulla cursus, at tincidunt est ultricies. Praesent nec velit id dui mattis consequat eget non tellus. Vivamus arcu est, commodo vitae vehicula et, sodales eu felis. Nulla auctor hendrerit turpis ut ultricies. Cras eget rhoncus urna. Nunc sit amet consectetur elit, at ornare purus. Mauris a neque in nulla viverra sodales. Etiam ornare augue eros, in semper tortor sagittis vitae. Sed accumsan urna libero, condimentum fermentum sem varius iaculis. Nunc vulputate blandit nibh a tempor. Pellentesque congue blandit libero, at luctus mi ullamcorper quis. Phasellus rutrum volutpat posuere.&nbsp;&nbsp;</p>', 1),
(4, '2018-02-15 16:13:00', 1, 'Ceci est un article', '<p>Ble balaa un article</p>', 1),
(5, '2018-07-22 16:14:00', 1, 'Encore un article', '<p><iframe src=\"//www.youtube.com/embed/9d8SzG4FPyM\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<p>Bleh article</p>', 1),
(6, '2019-02-27 16:14:00', 1, 'Encore encore un article', '<p><img src=\"../../../../generated/image/1551294612.png\" alt=\"VODKA\" width=\"346\" height=\"275\" /></p>\r\n<p>Bli bla blu</p>', 1);

--
-- Contenu de la table `reports`
--

INSERT INTO `reports` (`id`, `id_comment`, `id_user`, `ip`, `date_report`, `type`, `content`) VALUES
(14, 27, 0, '9.10.11.12', '2019-03-01 16:47:34', 0, 'C UNE HONTE');

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `date_inscription`, `last_seen`, `level`, `ip`, `name_display`) VALUES
(1, 'Darko', '$2y$10$SDpB9HcNPQsP3cpON23gUOG1fZiNwiJnc6riUdvODc18LapNnywLi', 'noreply@oc4.darkvcious.fr', '2019-02-20 18:11:45', '2019-03-01 17:15:55', 10, '1.2.3.4', 'Alexandre D'),
(5, 'David', '$2y$10$SDpB9HcNPQsP3cpON23gUOG1fZiNwiJnc6riUdvODc18LapNnywLi', 'noreply@oc4.darkvcious.fr', '2019-02-26 11:07:02', '2019-02-27 15:17:22', 10, '2.3.4.5', 'David'),
(6, 'Tyler', '$2y$10$SDpB9HcNPQsP3cpON23gUOG1fZiNwiJnc6riUdvODc18LapNnywLi', 'noreply@oc4.darkvcious.fr', '2019-03-01 08:51:31', '2019-03-01 13:33:44', 10, '3.4.5.6', 'Tyler le BG '),
(7, 'admin', '$2y$10$SDpB9HcNPQsP3cpON23gUOG1fZiNwiJnc6riUdvODc18LapNnywLi', 'noreply@oc4.darkvcious.fr', '2019-03-02 12:23:10', '2019-03-02 12:23:10', 3, '1.2.3.4', 'Jean-Michel');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
