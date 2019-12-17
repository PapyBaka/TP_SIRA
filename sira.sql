-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2019 at 03:15 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sira`
--

-- --------------------------------------------------------

--
-- Table structure for table `agences`
--

CREATE TABLE `agences` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `cp` int(11) NOT NULL,
  `description` text NOT NULL,
  `photos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agences`
--

INSERT INTO `agences` (`id`, `titre`, `adresse`, `ville`, `cp`, `description`, `photos`) VALUES
(1, 'Agence de Paris', '30 rue des petits moulins', 'paris', 75015, 'La meilleure agence de Paris', '/TP_SIRA/utilities/img/agences/agence_paris.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `membres`
--

CREATE TABLE `membres` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `civilite` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `date_enregistrement` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membres`
--

INSERT INTO `membres` (`id`, `pseudo`, `nom`, `prenom`, `email`, `mot_de_passe`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'admin', 'admin', 'admin', 'admin@admin.fr', 'admin', 'M', 'Admin', '2019-12-16 09:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `date_debut` int(11) NOT NULL,
  `date_fin` int(11) NOT NULL,
  `prix_total` int(11) NOT NULL,
  `id_vehicule` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_agence` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehicules`
--

CREATE TABLE `vehicules` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `modele` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` int(11) NOT NULL,
  `photos` varchar(255) NOT NULL,
  `agence_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vehicules`
--

INSERT INTO `vehicules` (`id`, `titre`, `marque`, `modele`, `description`, `prix`, `photos`, `agence_id`) VALUES
(2, 'Voiture banale', 'Mercedes', 'BMMM-110', 'Super voiture. Prix en or.', 2500, '/TP_SIRA/utilities/img/vehicules/voiture.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agences`
--
ALTER TABLE `agences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vehicule` (`id_vehicule`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_agence` (`id_agence`);

--
-- Indexes for table `vehicules`
--
ALTER TABLE `vehicules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agence_id` (`agence_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agences`
--
ALTER TABLE `agences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicules`
--
ALTER TABLE `vehicules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicules` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membres` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_agence`) REFERENCES `agences` (`id`);

--
-- Constraints for table `vehicules`
--
ALTER TABLE `vehicules`
  ADD CONSTRAINT `vehicules_ibfk_1` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
