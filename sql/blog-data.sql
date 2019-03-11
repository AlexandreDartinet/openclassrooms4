-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 11 Mars 2019 à 11:09
-- Version du serveur :  10.1.37-MariaDB-0+deb9u1
-- Version de PHP :  7.1.27-1+0~20190307202204.14+stretch~1.gbp7163d5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Contenu de la table `bans`
--

INSERT INTO `bans` (`id`, `ip`, `date_ban`, `type`, `content`) VALUES
(24, '0.0.0.0', '2019-03-07 14:18:34', 1, 'nothing');

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `id_post`, `id_user`, `reply_to`, `date_publication`, `ip`, `name`, `content`) VALUES
(6, 1, 0, 0, '2019-02-22 10:29:56', '0.0.0.0', 'Anonyme', 'HEllo'),
(11, 1, 1, 0, '2019-02-24 20:21:22', '0.0.0.0', 'Alexandre D', 'Test bleh'),
(13, 1, 5, 0, '2019-02-26 11:08:56', '0.0.0.0', 'David', 'Ceci est un commentaire AAAA'),
(14, 1, 5, 0, '2019-02-26 11:10:09', '0.0.0.0', 'David', 'Hello'),
(17, 1, 5, 0, '2019-02-26 11:15:00', '0.0.0.0', 'David', 'pppp'),
(21, 1, 0, 0, '2019-02-27 21:31:24', '0.0.0.0', 'Sean Paul', 'La chatte hoho'),
(23, 1, 0, 0, '2019-02-28 13:04:46', '0.0.0.0', 'Anonyme', 'Et encore un'),
(24, 1, 0, 0, '2019-02-28 13:04:50', '0.0.0.0', 'Anonyme', 'Et encore un autre'),
(25, 1, 0, 0, '2019-02-28 13:04:55', '0.0.0.0', 'Anonyme', 'Et encore un autre\r\n'),
(27, 1, 0, 0, '2019-02-28 13:05:06', '0.0.0.0', 'Anonyme', 'Et un autre encore'),
(28, 1, 0, 0, '2019-02-28 13:05:14', '0.0.0.0', 'Anonyme', 'Et un petit encore'),
(29, 1, 0, 0, '2019-02-28 13:05:22', '0.0.0.0', 'Anonyme', 'Et encore un'),
(30, 1, 0, 0, '2019-02-28 13:05:31', '0.0.0.0', 'Anonyme', 'Et puis un autre, encore'),
(31, 1, 0, 0, '2019-02-28 13:05:47', '0.0.0.0', 'Anonyme', 'Encore un autre'),
(33, 1, 0, 0, '2019-02-28 13:08:30', '0.0.0.0', 'Anonyme', 'Test test'),
(34, 1, 0, 0, '2019-02-28 13:08:37', '0.0.0.0', 'Anonyme', 'Blabla'),
(35, 1, 0, 0, '2019-02-28 13:08:47', '0.0.0.0', 'Anonyme', 'Blublu'),
(41, 1, 1, 35, '2019-02-28 19:56:45', '0.0.0.0', 'Alexandre D', 'blblblb'),
(43, 1, 1, 0, '2019-02-28 20:03:50', '0.0.0.0', 'Alexandre D', 'Taeryuigfazuig'),
(44, 1, 1, 0, '2019-02-28 20:09:32', '0.0.0.0', 'Alexandre D', 'Blergh'),
(45, 1, 1, 0, '2019-02-28 20:14:21', '0.0.0.0', 'Alexandre D', 'efuzeiufhze'),
(46, 1, 0, 0, '2019-02-28 20:22:41', '0.0.0.0', 'Anony', 'vb'),
(47, 1, 0, 0, '2019-02-28 20:24:03', '0.0.0.0', 'Anonyme', 'blerg'),
(48, 1, 0, 0, '2019-02-28 20:24:14', '0.0.0.0', 'Anon', 'sh'),
(49, 1, 0, 0, '2019-02-28 20:24:23', '0.0.0.0', 'Ano', 'blu'),
(50, 1, 6, 0, '2019-03-01 08:52:37', '0.0.0.0', 'Tyler le BG ', 'OUUUHHH POULOULOU'),
(53, 1, 1, 0, '2019-03-01 15:05:04', '0.0.0.0', 'Alexandre D', 'blergh'),
(54, 1, 1, 0, '2019-03-01 15:05:10', '0.0.0.0', 'Alexandre D', 'blu'),
(55, 1, 0, 0, '2019-03-01 15:20:29', '0.0.0.0', 'Anonyme', 'Bli'),
(56, 1, 0, 0, '2019-03-01 15:21:49', '0.0.0.0', 'Anonyme', 'ble'),
(57, 1, 0, 0, '2019-03-01 15:22:06', '0.0.0.0', 'Anonyme', 'dgiuaz'),
(59, 1, 1, 0, '2019-03-01 16:33:51', '0.0.0.0', 'Alexandre D', 'Blerg'),
(60, 1, 1, 0, '2019-03-01 16:33:58', '0.0.0.0', 'Alexandre D', 'blurgh'),
(61, 1, 1, 45, '2019-03-01 16:34:14', '0.0.0.0', 'Alexandre D', 'Hihi'),
(63, 1, 1, 0, '2019-03-01 16:34:58', '0.0.0.0', 'Alexandre D', 'blblb'),
(64, 1, 1, 0, '2019-03-01 16:36:08', '0.0.0.0', 'Alexandre D', 'blblblb'),
(65, 1, 1, 0, '2019-03-01 16:36:18', '0.0.0.0', 'Alexandre D', 'blblblb'),
(66, 1, 1, 0, '2019-03-01 16:37:17', '0.0.0.0', 'Alexandre D', 'blbl'),
(67, 1, 1, 0, '2019-03-01 16:37:23', '0.0.0.0', 'Alexandre D', 'blbl'),
(68, 1, 0, 0, '2019-03-01 16:45:33', '0.0.0.0', 'Ivy', 'test'),
(69, 1, 0, 0, '2019-03-01 16:45:44', '0.0.0.0', 'Ivy', 'salut toi\r\n'),
(70, 1, 0, 0, '2019-03-01 16:46:04', '0.0.0.0', 'Ivy', 'FILS DE POULPE\r\n'),
(72, 1, 0, 0, '2019-03-01 16:46:32', '0.0.0.0', 'kikidu36', 'jmapel kilyan et g 11 an lol\r\n'),
(73, 1, 1, 0, '2019-03-01 16:47:45', '0.0.0.0', 'Alexandre D', 'BLBLBLBL'),
(74, 1, 0, 0, '2019-03-01 16:48:20', '0.0.0.0', 'kikidu36', 'mdr le ga il c pa parlé tro kon lol'),
(75, 1, 0, 0, '2019-03-01 16:48:39', '0.0.0.0', 'kikidu36', 'vazy parle francé fdp la on kompren pas ce ke tu dis wallah\r\n'),
(76, 1, 0, 73, '2019-03-01 16:49:15', '0.0.0.0', 'kikidu36', 'lol t tro naz\r\n'),
(77, 1, 1, 0, '2019-03-01 16:50:05', '0.0.0.0', 'Alexandre D', 'Jparle comme jle voule'),
(78, 1, 1, 75, '2019-03-01 16:51:09', '0.0.0.0', 'Alexandre D', 'Même que jréponds HA'),
(79, 6, 0, 0, '2019-03-02 13:29:05', '0.0.0.0', 'Anonyme', '1324'),
(80, 6, 0, 0, '2019-03-02 13:30:25', '0.0.0.0', 'David', 'Super ces commentaires !!!'),
(81, 1, 0, 0, '2019-03-02 16:03:08', '0.0.0.0', 'Anonyme', 'blblblblbl'),
(82, 1, 0, 0, '2019-03-02 16:12:48', '0.0.0.0', 'Anonyme', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
(83, 1, 0, 11, '2019-03-04 11:55:27', '0.0.0.0', 'Cath D', 'Coucou'),
(84, 5, 0, 0, '2019-03-04 11:57:48', '0.0.0.0', 'Cath D', 'Sympa ;)\nModifié le 04/03/2019 à 11h58.'),
(85, 1, 0, 0, '2019-03-05 09:25:40', '0.0.0.0', 'Anonyme', 'ma bite'),
(86, 1, 0, 0, '2019-03-05 09:26:19', '0.0.0.0', 'Badik', 'Ma bite ma bite ma biiiiiiite :D'),
(87, 6, 0, 0, '2019-03-05 09:27:05', '0.0.0.0', 'CACA', 'C\'est du boudiiiiiin.'),
(88, 5, 8, 0, '2019-03-05 09:28:40', '0.0.0.0', 'Badik', 'Le seul Article valable :D'),
(89, 6, 0, 0, '2019-03-05 12:25:26', '0.0.0.0', 'Anonyme', 'bleh'),
(90, 6, 0, 0, '2019-03-05 12:26:08', '0.0.0.0', 'Anonyme', 'bleh'),
(91, 6, 0, 0, '2019-03-05 12:26:19', '0.0.0.0', 'Anonyme', 'Haha'),
(92, 6, 1, 0, '2019-03-05 15:26:19', '0.0.0.0', 'Alexandre D', 'Test'),
(93, 6, 1, 0, '2019-03-05 15:27:22', '0.0.0.0', 'Alexandre D', 'Test'),
(94, 1, 1, 6, '2019-03-05 16:43:27', '0.0.0.0', 'Alexandre D', 'blu'),
(95, 1, 1, 6, '2019-03-05 16:45:38', '0.0.0.0', 'Alexandre D', 'br'),
(96, 1, 1, 0, '2019-03-05 16:46:16', '0.0.0.0', 'Alexandre D', 'br'),
(97, 6, 0, 0, '2019-03-07 15:39:56', '0.0.0.0', 'Florent', 'Blu bli bla'),
(98, 4, 0, 0, '2019-03-07 15:40:46', '0.0.0.0', 'Un connard', 'Édifiant ! '),
(99, 5, 0, 88, '2019-03-07 15:44:42', '0.0.0.0', 'Cath D', 'Tout à fait ! ;)'),
(100, 11, 0, 0, '2019-03-08 22:54:35', '0.0.0.0', 'Cath D', 'C’est une superbe photo !\r\nMême que je connais le photographe ;)\nModifié le 8 Mars 2019 à 22h54.'),
(101, 12, 0, 0, '2019-03-08 22:58:20', '0.0.0.0', 'Cath D', 'J’adore !'),
(102, 16, 0, 0, '2019-03-08 23:01:43', '0.0.0.0', 'CathD', 'Superbe <3'),
(105, 13, 0, 0, '2019-03-11 08:17:50', '0.0.0.0', 'Cath D', 'Très belle photo !');

--
-- Contenu de la table `images`
--

INSERT INTO `images` (`id`, `id_user`, `date_sent`, `file_name`, `type`, `title`) VALUES
(15, 1, '2019-03-10 16:38:27', '1552232307', 1, 'VODKA'),
(16, 1, '2019-03-10 16:38:58', '1552232337', 1, 'loremipsum');

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `date_publication`, `id_user`, `title`, `content`, `published`) VALUES
(1, '2019-03-08 17:30:00', 1, 'Lorem ipsum dolor sit amet', '<p><img src=\"https://cdn-images-1.medium.com/max/1600/1*ddyz8qnOhFeFKY-_c3tleQ.jpeg\" alt=\"Lorem ipsum\" width=\"1583\" height=\"1609\" /></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a porttitor libero. Integer aliquam elementum molestie. Integer ullamcorper mi sed orci rutrum aliquet. Fusce rutrum, sem et efficitur eleifend, metus lorem luctus lectus, tempor tincidunt nibh mi non turpis. Aliquam ac maximus lorem. Fusce laoreet auctor sem, eget hendrerit orci. Pellentesque non justo sit amet nisi egestas pulvinar volutpat in velit.</p>\r\n<p>&nbsp;</p>\r\n<p>Curabitur vel condimentum est. Etiam eu sem nulla. Donec commodo efficitur lorem eget rutrum. Nulla vulputate turpis vitae nibh vulputate euismod. Phasellus tempor magna eu magna laoreet malesuada. Curabitur ut mauris at mauris euismod pulvinar non a massa. Aenean eros turpis, porttitor vitae porta sit amet, fringilla et justo. Mauris dapibus magna ipsum, quis aliquam ligula tincidunt vel. Sed a ullamcorper justo, eget pulvinar enim. Nunc accumsan viverra leo non pellentesque. Praesent imperdiet posuere urna vitae tincidunt. Ut hendrerit eget lacus a euismod. Nullam vel turpis sit amet sapien venenatis hendrerit. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer sit amet urna scelerisque, dapibus eros rutrum, auctor libero. Duis nec tempus diam, sed ornare mi. Vivamus blandit dui quis magna volutpat lacinia. Sed eros diam, rutrum nec purus sed, viverra aliquam augue. Pellentesque cursus orci tellus, a blandit sem finibus eget. Quisque interdum vitae mi vitae varius. Cras diam odio, maximus nec tempor ac, suscipit sit amet nulla. Morbi vitae sodales risus. Donec luctus, sem at feugiat ornare, lorem diam varius diam, sit amet ultrices nunc risus vestibulum ex. Proin ligula leo, interdum sed velit ut, finibus tempor nibh. Aliquam turpis tortor, faucibus sed tellus aliquam, mattis venenatis justo. Morbi eu est porta, bibendum sapien semper, malesuada dolor. Maecenas quis libero fringilla, ornare lorem bibendum, lobortis elit. Cras eu consequat turpis. Mauris vel sem porta, molestie dolor ut, ultrices lectus. Mauris lacinia est vel gravida egestas. Sed eget velit nulla. Etiam aliquet sapien neque, eget sagittis nisi egestas ac. Aliquam feugiat ligula non dui porta elementum. Nullam facilisis sagittis sagittis. Cras quis laoreet lectus. Donec vel commodo dui. Sed imperdiet sapien id nulla cursus, at tincidunt est ultricies. Praesent nec velit id dui mattis consequat eget non tellus. Vivamus arcu est, commodo vitae vehicula et, sodales eu felis. Nulla auctor hendrerit turpis ut ultricies. Cras eget rhoncus urna. Nunc sit amet consectetur elit, at ornare purus. Mauris a neque in nulla viverra sodales. Etiam ornare augue eros, in semper tortor sagittis vitae. Sed accumsan urna libero, condimentum fermentum sem varius iaculis. Nunc vulputate blandit nibh a tempor. Pellentesque congue blandit libero, at luctus mi ullamcorper quis. Phasellus rutrum volutpat posuere.&nbsp;&nbsp;</p>', 1),
(4, '2018-02-15 16:13:00', 1, 'Ceci est un article', '<p>Ble balaa un article bla bla bla</p>', 0),
(5, '2018-07-22 16:14:00', 1, 'Encore un article', '<p><iframe src=\"//www.youtube.com/embed/9d8SzG4FPyM\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<p>Bleh article bliiiiiiiiiiiiieiaieaiea</p>', 0),
(6, '2019-02-27 16:14:00', 1, 'Encore encore un article', '<p><img src=\"../../../../generated/image/1551964418.png\" alt=\"VODKA\" width=\"346\" height=\"275\" /></p>\r\n<p>Bli bla blu bleeeeeeeeeeeeeee</p>', 0),
(11, '2019-03-05 14:44:00', 1, 'Ceci est une photo.', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t31.0-8/12244278_189413528064608_6286052583059263325_o.jpg?_nc_cat=106&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=e2a7b95771a54f7238c02e7e974932a6&amp;oe=5D1E282D\" alt=\"Photo\" width=\"1172\" height=\"781\" /></p>\r\n<p>&nbsp;</p>\r\n<p>Ceci est une photo, elle est jolie non ?</p>', 1),
(12, '2019-03-08 14:46:00', 1, 'Un petit paysage en mode portrait', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/51973011_834820230190598_4214839844959944704_n.jpg?_nc_cat=103&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=25963a9205fd7a5d41a9ddc457c609b6&amp;oe=5D0AC3A3\" alt=\"Paysage\" width=\"640\" height=\"960\" /></p>\r\n<p>Un paysage en mode portrait</p>', 1),
(13, '2018-12-24 14:52:00', 1, 'Photo d\'arc en ciel', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/51874792_834198713586083_6123422383865856000_n.jpg?_nc_cat=104&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=672026452bd732675aa4e8dec7830bf9&amp;oe=5D25B1A5\" alt=\"Ombre d\'un avion\" width=\"960\" height=\"640\" /></p>\r\n<p>Ombre d\'un avion sur son lit d\'arc en ciel</p>', 1),
(14, '2018-12-12 14:54:00', 1, 'Nébuleuse', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/49204588_801408000198488_4024795612355493888_n.jpg?_nc_cat=107&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=8bc7734a7ee4653812750fb689fa08f8&amp;oe=5D0B4515\" alt=\"N&eacute;buleuse\" width=\"960\" height=\"640\" /></p>\r\n<p>N&eacute;buleuses par temps de brouillard... Une petite flamme et une petite t&ecirc;te de cheval en plus de 400 poses de 15s &agrave; travers le brouillard ! Le tout pris ce mois de novembre, avec le 150-600 et le petit eos M !&nbsp;<span title=\"&eacute;motic&ocirc;ne wink\"><img style=\"border-width: 0px; border-image-width: initial;\" role=\"presentation\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t57/1/16/1f609.png\" alt=\"\" width=\"16\" height=\"16\" /></span></p>', 1),
(15, '2019-01-14 14:56:00', 1, 'Foudre dans le ciel de paris', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/37863132_699806673691955_6928201965537067008_n.jpg?_nc_cat=102&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=a0508eae4a4e5aa7e7824b6c4d95b74e&amp;oe=5D0DF434\" alt=\"Foudre\" width=\"960\" height=\"540\" /></p>\r\n<p>Eclipse de Lune, encore un fail, mais par contre, j\'reviens avec une image plut&ocirc;t correcte...&nbsp;<span title=\"&eacute;motic&ocirc;ne wink\"><img style=\"border-width: 0px; border-image-width: initial;\" role=\"presentation\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t57/1/16/1f609.png\" alt=\"\" width=\"16\" height=\"16\" /></span></p>', 1),
(16, '2019-01-16 14:57:00', 1, 'Couple de gobelets', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/35082782_655200354819254_2176487544970018816_n.jpg?_nc_cat=107&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=93d1cb60661bf1b44e0271688a7f7e3e&amp;oe=5D0B92D1\" alt=\"Gobelets\" width=\"960\" height=\"640\" /></p>\r\n<p><span id=\"fbPhotoSnowliftCaption\" tabindex=\"0\" aria-live=\"polite\" data-ft=\"{\">Un couple de goblets, en amoureux...</span><span id=\"fbPhotoSnowliftTagList\"> Pas tr&egrave;s loin d\'une poubelle...</span></p>', 1),
(17, '2019-02-13 15:00:00', 1, 'Des nuages', '<p><img src=\"https://scontent-cdg2-1.xx.fbcdn.net/v/t1.0-9/35026495_654714198201203_241817847154606080_n.jpg?_nc_cat=107&amp;_nc_ht=scontent-cdg2-1.xx&amp;oh=8dcda3127767f7f827d5e08b2adca211&amp;oe=5CDA2161\" alt=\"Nuages\" width=\"960\" height=\"640\" /></p>\r\n<p>Y a des fois, les nuages, bah c\'est beau... Une petite prise de vue qui tra&icirc;ne depuis des mois dans les cartons en attendant d\'&ecirc;tre d&eacute;velopp&eacute;e...&nbsp;<span title=\"&eacute;motic&ocirc;ne smile\"><img style=\"border-width: 0px; border-image-width: initial;\" role=\"presentation\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4c/1/16/1f642.png\" alt=\"\" width=\"16\" height=\"16\" /></span></p>', 1);

--
-- Contenu de la table `reports`
--

INSERT INTO `reports` (`id`, `id_comment`, `id_user`, `ip`, `date_report`, `type`, `content`) VALUES
(14, 27, 0, '0.0.0.0', '2019-03-01 16:47:34', 0, 'C UNE HONTE'),
(15, 44, 0, '0.0.0.0', '2019-03-04 11:54:20', 0, 'Coucou');

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `date_inscription`, `last_seen`, `level`, `ip`, `name_display`) VALUES
(1, 'Darko', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-02-20 18:11:45', '2019-03-11 11:03:44', 10, '0.0.0.0', 'Alexandre'),
(5, 'David', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-02-26 11:07:02', '2019-03-05 11:17:31', 10, '0.0.0.0', 'David'),
(6, 'Tyler', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-03-01 08:51:31', '2019-03-11 09:59:30', 5, '0.0.0.0', 'Tyler le BG '),
(8, 'Badik', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-03-05 09:28:00', '2019-03-11 09:59:33', 4, '0.0.0.0', 'Badik'),
(9, 'Esska', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-03-05 12:51:33', '2019-03-11 09:59:37', 5, '0.0.0.0', 'Esska'),
(10, 'DarkVcious', '$2y$10$RUXDc97x9spbOx3c.wzfH.RjdkWmYCrQFWb6LcVwdlvb2YBAPNlt2', 'anon@nymo.us', '2019-03-05 12:55:37', '2019-03-05 19:26:00', 4, '0.0.0.0', 'DarkVcious');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
