-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 21, 2023 at 01:38 AM
-- Server version: 10.3.37-MariaDB-log-cll-lve
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zaid_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `opening_date` datetime NOT NULL DEFAULT current_timestamp(),
  `closing_date` datetime DEFAULT NULL,
  `opening_user_id` int(11) NOT NULL,
  `closing_user_id` int(11) DEFAULT NULL,
  `reservation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `opening_date`, `closing_date`, `opening_user_id`, `closing_user_id`, `reservation_id`) VALUES
(1, '2023-03-09 19:41:16', '2023-03-13 12:45:45', 1, 1, 1),
(2, '2023-03-04 00:00:00', NULL, 1, NULL, 3),
(4, '2023-03-20 00:53:51', NULL, 1, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(30) NOT NULL,
  `type` varchar(10) NOT NULL,
  `picture` varchar(256) NOT NULL,
  `release_date` varchar(10) NOT NULL,
  `language` varchar(30) NOT NULL,
  `page_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `title`, `author`, `type`, `picture`, `release_date`, `language`, `page_count`) VALUES
(1, 'Et après', 'Guillaume Musso', 'roman', 'guillaume-musso_et-apres.jpg', '2004', 'French', 528),
(2, 'The universe in a nutshell', 'Stephen Hawking', 'livre', 'universeinnutshell.jpg', '2001', 'English', 244),
(3, 'فيه ما فيه', 'جلال الدين الرومي', 'livre', 'WhatsApp-Image-2020-03-28-at-3.01.07-PM.jpeg', '1947', 'Arabic', 334),
(4, 'Quantum Philosophy', 'Roland Omnès', 'livre', '9781400822867.jpg', '1999', 'English', 576),
(8, 'One Piece, Vol. 1: Romance Dawn', 'Eiichiro Oda', 'revues', 'OnePieceVol.1RomanceDawn2323-0303-1313200125.jpeg', '2003', 'English', 226);

-- --------------------------------------------------------

--
-- Table structure for table `item_unit`
--

CREATE TABLE `item_unit` (
  `id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `brought_date` date NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `item_unit`
--

INSERT INTO `item_unit` (`id`, `status`, `brought_date`, `item_id`) VALUES
(1, 'neuf', '2023-03-09', 2),
(2, 'acceptable', '2023-03-05', 2),
(3, 'déchiré', '2023-03-15', 2),
(4, 'bon état', '2022-01-15', 2),
(5, 'assez usé', '2021-01-15', 2),
(6, 'neuf', '2023-03-15', 1),
(7, 'bon état', '2023-03-15', 1),
(8, 'acceptable', '2023-03-15', 1),
(9, 'assez usé', '2023-03-12', 1),
(10, 'déchiré', '2023-03-03', 1),
(11, 'neuf', '2023-03-18', 8),
(12, 'bon état', '2023-03-07', 3),
(13, 'neuf', '2023-03-18', 3),
(14, 'acceptable', '2023-01-15', 3),
(15, 'assez usé', '2022-10-30', 3),
(16, 'déchiré', '2017-01-20', 3),
(18, 'neuf', '2023-03-18', 4),
(19, 'bon état', '2023-02-26', 4),
(20, 'acceptable', '2023-01-10', 4),
(21, 'assez usé', '2022-08-23', 4),
(22, 'déchiré', '2021-09-08', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `opening_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `item_unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `opening_date`, `user_id`, `item_unit_id`) VALUES
(1, '2023-03-09 19:40:53', 1, 1),
(2, '2023-03-10 20:48:20', 1, 2),
(3, '2023-03-12 16:01:12', 1, 2),
(4, '2023-03-19 23:25:42', 5, 12),
(5, '2023-03-19 23:25:52', 5, 21),
(6, '2023-03-19 23:25:56', 5, 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `identity_card_number` varchar(8) NOT NULL,
  `birthday` date NOT NULL,
  `type` varchar(20) NOT NULL,
  `phone_number` varchar(14) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `creation_date` date NOT NULL DEFAULT curdate(),
  `tickets` int(1) DEFAULT 0,
  `role` varchar(5) DEFAULT 'user',
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `last_name`, `identity_card_number`, `birthday`, `type`, `phone_number`, `email`, `password`, `creation_date`, `tickets`, `role`, `creator_id`) VALUES
(1, 'zaidsmd', 'zaid', 'samadi', 'K0101010', '2004-03-23', 'admin', '0681515037', 'zaidsmd111@gmail.com', 'edfd511ccecc13918de1090a6d1cb310', '2023-03-07', 0, 'admin', NULL),
(5, 'salsabeel1251', 'Jalil', 'Salsabeel', 'L123123', '1999-05-22', 'Étudiant', '0654891351', 'Salsabeel1999@email.com', '84011bec98ef5ab831d88867a8ca44f5', '2023-03-18', 0, 'user', 1),
(6, 'daoud0213', 'Haroun', 'Daoud', 'P516891', '2001-02-22', 'Fonctionnaire', '0687965412', 'DaoudHaroun@email.fr', 'cc447a94e850ca058229e85853703534', '2023-03-18', 0, 'user', 1),
(7, 'ssaddamazizi', 'Azizi', 'Saddam', 'k120345', '1989-01-22', 'Employé', '0612365987', 'SSaddamAzizi@email.ma', 'e521592b818f3548b446f2737807baf2', '2023-03-18', 0, 'user', 1),
(8, 'ahbab123mannan', 'Ahbab', 'Mannan', 'KL98775', '1995-05-20', 'Femme au foyer', '0788441122', 'Ahbab123Mannan@email.ru', '54192ba1e99cac511e6f78e93b0dc347', '2023-03-18', 0, 'user', 1),
(9, 'kasiblatifu', 'Kasib', 'Latif', 'PO12513', '2002-01-10', 'Étudiant', '0788441125', 'Kasibl12@email.ru', 'fca501d1f8a2ebee9f9ff8e2f30dba6d', '2023-03-18', 0, 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opening_user_id` (`opening_user_id`),
  ADD KEY `closing_user_id` (`closing_user_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_unit`
--
ALTER TABLE `item_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_unit_id` (`item_unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `item_unit`
--
ALTER TABLE `item_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`opening_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`closing_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowings_ibfk_3` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);

--
-- Constraints for table `item_unit`
--
ALTER TABLE `item_unit`
  ADD CONSTRAINT `item_unit_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`item_unit_id`) REFERENCES `item_unit` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
