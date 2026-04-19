-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Version du serveur : 10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gem`
--
CREATE DATABASE IF NOT EXISTS `gem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gem`;

-- --------------------------------------------------------
-- Structure de la table `clients`
--
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `tel` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `telcontact` varchar(20) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `contrats`
--
CREATE TABLE `contrats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `redevence` decimal(10,2) NOT NULL,
  `client_id` int(11) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `contrats_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `employes`
--
CREATE TABLE `employes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `employes_interventions`
--
CREATE TABLE `employes_interventions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employe_id` int(11) NOT NULL,
  `intervention_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employe_id` (`employe_id`),
  KEY `intervention_id` (`intervention_id`),
  CONSTRAINT `employes_interventions_ibfk_1` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`id`),
  CONSTRAINT `employes_interventions_ibfk_2` FOREIGN KEY (`intervention_id`) REFERENCES `interventions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `images`
--
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `img` longblob NOT NULL,
  `dateCapture` date NOT NULL,
  `intervention_id` int(11) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `intervention_id` (`intervention_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`intervention_id`) REFERENCES `interventions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `interventions`
--
CREATE TABLE `interventions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `heuredebutplan` time NOT NULL,
  `heurefinplan` time NOT NULL,
  `commentaires` text NOT NULL,
  `dateplanification` date NOT NULL,
  `heuredebuteffect` time NOT NULL,
  `heurefineffect` time NOT NULL,
  `terminee` tinyint(1) NOT NULL DEFAULT 0,
  `dateterminaison` date NOT NULL,
  `validee` tinyint(1) NOT NULL DEFAULT 0,
  `datevalidation` date NOT NULL,
  `priorite_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `priorite_id` (`priorite_id`),
  KEY `site_id` (`site_id`),
  CONSTRAINT `interventions_ibfk_1` FOREIGN KEY (`priorite_id`) REFERENCES `priorites` (`id`),
  CONSTRAINT `interventions_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `priorites`
--
CREATE TABLE `priorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `sites`
--
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` decimal(10,8) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `adresse` text NOT NULL,
  `rue` varchar(255) NOT NULL,
  `codepostal` int(5) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `telcontact` varchar(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `sites_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Structure de la table `taches`
--
CREATE TABLE `taches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refernce` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `duree` decimal(10,2) NOT NULL,
  `prixheure` decimal(10,2) NOT NULL,
  `dateaction` date NOT NULL,
  `intervention_id` int(11) NOT NULL,
  `valsync` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `intervention_id` (`intervention_id`),
  CONSTRAINT `taches_ibfk_1` FOREIGN KEY (`intervention_id`) REFERENCES `interventions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Données de démo pour la table `priorites`
--
INSERT INTO `priorites` (`id`, `nom`, `valsync`) VALUES
(1, 'Normale', 0),
(2, 'Urgente', 0),
(3, 'Critique', 0);

-- --------------------------------------------------------
-- Données de démo pour la table `employes`
--
INSERT INTO `employes` (`id`, `login`, `pwd`, `prenom`, `nom`, `email`, `actif`, `valsync`) VALUES
(1, 'admin', 'admin123', 'Jean', 'Dupont', 'jean.dupont@expert-maintenance.fr', 1, 0),
(2, 'enzo', 'enzo123', 'Enzo', 'Martin', 'enzo.martin@expert-maintenance.fr', 1, 0);

-- --------------------------------------------------------
-- Données de démo pour la table `clients`
--
INSERT INTO `clients` (`id`, `nom`, `adresse`, `tel`, `fax`, `email`, `contact`, `telcontact`, `valsync`) VALUES
(1, 'La Société Exemple', 'Rue de Paradis', '0123456789', '0123456788', 'contact@societe-exemple.fr', 'Jean Paul', '0612346587', 0);

-- --------------------------------------------------------
-- Données de démo pour la table `sites`
--
INSERT INTO `sites` (`id`, `longitude`, `latitude`, `adresse`, `rue`, `codepostal`, `ville`, `contact`, `telcontact`, `client_id`, `valsync`) VALUES
(1, 2.3522, 48.8566, 'Rue de Paradis', 'Rue de Paradis', 75010, 'Paris', 'Jean Paul', '0612346587', 1, 0);

-- --------------------------------------------------------
-- Données de démo pour la table `interventions`
--
INSERT INTO `interventions` (`id`, `titre`, `datedebut`, `datefin`, `heuredebutplan`, `heurefinplan`, `commentaires`, `dateplanification`, `heuredebuteffect`, `heurefineffect`, `terminee`, `dateterminaison`, `validee`, `datevalidation`, `priorite_id`, `site_id`, `valsync`) VALUES
(1, 'Intervention Mobile', '2018-06-21', '2018-06-21', '07:00:00', '10:00:00', 'Maintenance régulière', '2018-06-15', '07:00:00', '10:00:00', 1, '2018-06-21', 1, '2018-06-21', 1, 1, 1),
(2, 'Intervention Mobile 2', '2018-06-21', '2018-06-21', '15:00:00', '18:00:00', 'Maintenance corrective', '2018-06-15', '00:00:00', '00:00:00', 0, '0000-00-00', 0, '0000-00-00', 1, 1, 0);

-- --------------------------------------------------------
-- Données de démo pour la table `employes_interventions`
--
INSERT INTO `employes_interventions` (`id`, `employe_id`, `intervention_id`) VALUES
(1, 1, 1),
(2, 1, 2);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
