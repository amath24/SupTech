-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 10 juil. 2026 à 15:59
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_academique`
--

-- --------------------------------------------------------

--
-- Structure de la table `annees_universitaires`
--

DROP TABLE IF EXISTS `annees_universitaires`;
CREATE TABLE IF NOT EXISTS `annees_universitaires` (
  `id_annee` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  PRIMARY KEY (`id_annee`),
  UNIQUE KEY `libelle` (`libelle`)
) ;

--
-- Déchargement des données de la table `annees_universitaires`
--

INSERT INTO `annees_universitaires` (`id_annee`, `libelle`, `date_debut`, `date_fin`) VALUES
(1, '2025-2026', '2025-10-01', '2026-07-31');

-- --------------------------------------------------------

--
-- Structure de la table `assignation_modules`
--

DROP TABLE IF EXISTS `assignation_modules`;
CREATE TABLE IF NOT EXISTS `assignation_modules` (
  `id_assignation` int NOT NULL AUTO_INCREMENT,
  `id_module` int NOT NULL,
  `id_enseignant` int NOT NULL,
  `id_classe` int NOT NULL,
  `id_annee` int NOT NULL,
  PRIMARY KEY (`id_assignation`),
  UNIQUE KEY `uq_assignation` (`id_module`,`id_classe`,`id_annee`),
  KEY `idx_assignation_enseignant` (`id_enseignant`),
  KEY `idx_assignation_classe` (`id_classe`),
  KEY `idx_assignation_annee` (`id_annee`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `assignation_modules`
--

INSERT INTO `assignation_modules` (`id_assignation`, `id_module`, `id_enseignant`, `id_classe`, `id_annee`) VALUES
(1, 1, 2, 1, 1),
(2, 2, 6, 1, 1),
(3, 3, 1, 1, 1),
(4, 4, 3, 1, 1),
(5, 5, 4, 2, 1),
(6, 6, 9, 2, 1),
(7, 7, 7, 2, 1),
(8, 8, 10, 2, 1),
(9, 9, 8, 3, 1),
(10, 10, 1, 3, 1),
(11, 11, 3, 3, 1),
(12, 12, 5, 3, 1),
(13, 13, 2, 4, 1),
(14, 14, 8, 4, 1),
(15, 15, 10, 4, 1),
(16, 16, 7, 4, 1),
(17, 17, 1, 5, 1),
(18, 18, 4, 5, 1),
(19, 19, 9, 5, 1),
(20, 20, 5, 5, 1),
(21, 21, 10, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id_classe` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filiere` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `niveau` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `effectif_max` int NOT NULL DEFAULT '40',
  PRIMARY KEY (`id_classe`)
) ;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id_classe`, `nom`, `filiere`, `niveau`, `effectif_max`) VALUES
(1, 'L3 Génie Logiciel', 'Informatique et Développement', 'L3', 40),
(2, 'M1 Cybersécurité', 'Sécurité Informatique', 'M1', 40),
(3, 'L1 Informatique de Gestion', 'Business & IT', 'L1', 50),
(4, 'L2 Web & Mobile', 'Informatique et Développement', 'L2', 45),
(5, 'M2 Data Science', 'Intelligence Artificielle', 'M2', 30);

-- --------------------------------------------------------

--
-- Structure de la table `emploi_temps`
--

DROP TABLE IF EXISTS `emploi_temps`;
CREATE TABLE IF NOT EXISTS `emploi_temps` (
  `id_seance` int NOT NULL AUTO_INCREMENT,
  `jour` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_salle` int NOT NULL,
  `id_assignation` int NOT NULL,
  PRIMARY KEY (`id_seance`),
  UNIQUE KEY `uq_seance_salle` (`id_salle`,`jour`,`heure_debut`),
  KEY `fk_seance_assignation` (`id_assignation`)
) ;

--
-- Déchargement des données de la table `emploi_temps`
--

INSERT INTO `emploi_temps` (`id_seance`, `jour`, `heure_debut`, `heure_fin`, `id_salle`, `id_assignation`) VALUES
(1, 'Lundi', '08:30:00', '11:45:00', 2, 1),
(2, 'Mardi', '14:00:00', '17:15:00', 1, 5),
(3, 'Mercredi', '08:30:00', '11:45:00', 4, 11),
(4, 'Jeudi', '14:00:00', '17:15:00', 2, 13),
(5, 'Vendredi', '08:30:00', '11:45:00', 3, 17);

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

DROP TABLE IF EXISTS `enseignants`;
CREATE TABLE IF NOT EXISTS `enseignants` (
  `id_enseignant` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialite` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_utilisateur` int DEFAULT NULL,
  PRIMARY KEY (`id_enseignant`),
  UNIQUE KEY `matricule` (`matricule`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `id_utilisateur` (`id_utilisateur`),
  KEY `idx_enseignant_nom` (`nom`,`prenom`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id_enseignant`, `matricule`, `nom`, `prenom`, `grade`, `telephone`, `email`, `specialite`, `id_utilisateur`) VALUES
(1, 'ENS-2026-001', 'Kamara', 'Ibrahima', 'Professeur', '775551122', 'ibrahima.kamara@suptech.sn', 'Génie Logiciel & Sécurité', NULL),
(2, 'ENS-2026-002', 'Diallo', 'Aissatou', 'Maître de Conférences', '775553344', 'aissatou.diallo@suptech.sn', 'Management & Digital', NULL),
(3, 'ENS-2026-003', 'Seck', 'Amath', 'DOCTEUR', '7578900', 'amath@gmail.com', 'COMPTA', 5),
(4, 'ENS202601', 'Diop', 'Cheikh Anta', 'Professeur Titulaire', '+221771234567', 'c.diop@suptech.edu', 'Base de données', 6),
(5, 'ENS202602', 'Bâ', 'Mariama', 'Maître de Conférences', '+221772345678', 'm.ba@suptech.edu', 'Développement Web', 7),
(6, 'ENS202603', 'Sembène', 'Ousmane', 'Assistant', '+221773456789', 'o.sembene@suptech.edu', 'Algorithmique & C', 8),
(7, 'ENS202604', 'Sow Fall', 'Aminata', 'Maître de Conférences', '+221774567890', 'a.sowfall@suptech.edu', 'Systèmes & Virtualisation', 9),
(8, 'ENS202605', 'Senghor', 'Léopold', 'Professeur Titulaire', '+221775678901', 'l.senghor@suptech.edu', 'Mathématiques Financières', 10),
(9, 'ENS202606', 'Diome', 'Fatou', 'Assistant', '+221776789012', 'f.diome@suptech.edu', 'Applications Mobiles', 11),
(10, 'ENS202607', 'Diop_B', 'Boubacar Boris', 'Maître de Conférences', '+221777890123', 'b.diop@suptech.edu', 'Réseaux & Sécurité', 12),
(11, 'ENS202608', 'Bugul', 'Ken', 'Assistant', '+221778901234', 'k.bugul@suptech.edu', 'Interface Homme-Machine', 13),
(12, 'ENS202609', 'Sarr', 'Felwine', 'Professeur Titulaire', '+221779012345', 'f.sarr@suptech.edu', 'Économie Numérique', 14),
(13, 'ENS202610', 'Sylla', 'Khady', 'Maître de Conférences', '+221770123456', 'k.sylla@suptech.edu', 'Génie Logiciel', 15);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

DROP TABLE IF EXISTS `etudiants`;
CREATE TABLE IF NOT EXISTS `etudiants` (
  `id_etudiant` int NOT NULL AUTO_INCREMENT,
  `matricule` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_classe` int NOT NULL,
  PRIMARY KEY (`id_etudiant`),
  UNIQUE KEY `matricule` (`matricule`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_etudiant_classe` (`id_classe`),
  KEY `idx_etudiant_nom` (`nom`,`prenom`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `matricule`, `nom`, `prenom`, `date_naissance`, `sexe`, `telephone`, `email`, `adresse`, `photo`, `id_classe`) VALUES
(1, 'ETU202601', 'Ndiaye', 'Moussa', '2004-03-12', 'M', '+221771001122', 'moussa.ndiaye@suptech.edu', 'Dakar', 'HOMME1', 1),
(2, 'ETU202602', 'Diop', 'Ibrahima', '2003-07-22', 'M', '+221772002233', 'ibrahima.diop@suptech.edu', 'Pikine', 'HOMME2', 1),
(3, 'ETU202603', 'Fall', 'Fatou', '2005-01-15', 'F', '+221773003344', 'fatou.fall@suptech.edu', 'Guédiawaye', 'FILLE1', 1),
(4, 'ETU202604', 'Sané', 'Amadou', '2004-11-02', 'M', '+221774004455', 'amadou.sane@suptech.edu', 'Thiaroye', 'upload/etudiants/ETU202604_1783626969.jpg', 1),
(5, 'ETU202605', 'Cissé', 'Awa', '2004-04-30', 'F', '+221775005566', 'awa.cisse@suptech.edu', 'Parcelles', 'upload/etudiants/ETU202605_1783626800.jpg', 1),
(6, 'ETU202606', 'Gaye', 'Oumar', '2003-08-19', 'M', '+221776006677', 'oumar.gaye@suptech.edu', 'Rufisque', 'HOMME4', 1),
(7, 'ETU202607', 'Diallo', 'Mariama', '2005-09-05', 'F', '+221777007788', 'mariama.diallo@suptech.edu', 'Mermoz', 'FILLE3', 1),
(8, 'ETU202608', 'Sarr', 'Abdoulaye', '2002-02-10', 'M', '+221778008899', 'abdoulaye.sarr@suptech.edu', 'Yoff', 'HOMME5', 2),
(9, 'ETU202609', 'Ba', 'Khadija', '2001-12-25', 'F', '+221779009900', 'khadija.ba@suptech.edu', 'Médina', 'upload/etudiants/ETU202609_1783626831.jpg', 2),
(10, 'ETU202610', 'Seck', 'Babacar', '2002-06-14', 'M', '+221771110011', 'babacar.seck@suptech.edu', 'Fann', 'HOMME6', 2),
(11, 'ETU202611', 'Sow', 'Aminata', '2003-04-08', 'F', '+221772220022', 'aminata.sow@suptech.edu', 'Ouakam', 'FILLE5', 2),
(12, 'ETU202612', 'Mbacké', 'Serigne', '2001-10-11', 'M', '+221773330033', 'serigne.mbacke@suptech.edu', 'Touba', 'HOMME7', 2),
(13, 'ETU202613', 'Kane', 'Aissatou', '2002-07-20', 'F', '+221774440044', 'aissatou.kane@suptech.edu', 'Almadies', 'FILLE6', 2),
(14, 'ETU202614', 'Sy', 'Ramata', '2002-01-30', 'F', '+221775550055', 'ramata.sy@suptech.edu', 'Grand Yoff', 'FILLE7', 2),
(15, 'ETU202615', 'Mané', 'Ousmane', '2006-05-14', 'M', '+221776660011', 'ousmane.mane@suptech.edu', 'Mbour', 'upload/etudiants/ETU202615_1783626954.jpg', 3),
(16, 'ETU202616', 'Goudiaby', 'Salimata', '2005-11-20', 'F', '+221776660022', 'salimata.goudiaby@suptech.edu', 'Bignona', 'upload/etudiants/ETU202616_1783626785.jpg', 3),
(17, 'ETU202617', 'Thiam', 'Cheikh', '2006-02-18', 'M', '+221776660033', 'cheikh.thiam@suptech.edu', 'Thies', 'upload/etudiants/ETU202617_1783626895.jpg', 3),
(18, 'ETU202618', 'Niang', 'Penda', '2005-08-03', 'F', '+221776660044', 'penda.niang@suptech.edu', 'Saint-Louis', 'FILLE9', 3),
(19, 'ETU202619', 'Diagne', 'Mustapha', '2006-07-29', 'M', '+221776660055', 'mustapha.diagne@suptech.edu', 'Dakar', 'upload/etudiants/ETU202619_1783626816.jpg', 3),
(20, 'ETU202620', 'Samb', 'Anta', '2005-10-12', 'F', '+221776660066', 'anta.samb@suptech.edu', 'Kaolack', 'upload/etudiants/ETU202620_1783626989.jpg', 3),
(21, 'ETU202621', 'Boye', 'Modou', '2005-12-01', 'M', '+221776660077', 'modou.boye@suptech.edu', 'Fatick', 'HOMME11', 3),
(22, 'ETU202622', 'Badji', 'Lamine', '2004-09-11', 'M', '+221777770011', 'lamine.badji@suptech.edu', 'Ziguinchor', 'upload/etudiants/ETU202622_1783626939.jpg', 4),
(23, 'ETU202623', 'Faty', 'Astou', '2005-04-05', 'F', '+221777770022', 'astou.faty@suptech.edu', 'Kolda', 'upload/etudiants/ETU202623_1783626866.jpg', 4),
(24, 'ETU202624', 'Camara', 'Demba', '2003-12-30', 'M', '+221777770033', 'demba.camara@suptech.edu', 'Tambacounda', 'upload/etudiants/ETU202624_1783626878.jpg', 4),
(25, 'ETU202625', 'Gueye', 'Seynabou', '2004-06-17', 'F', '+221777770044', 'seynabou.gueye@suptech.edu', 'Dakar', 'FILLE12', 4),
(26, 'ETU202626', 'Tine', 'Birame', '2004-01-22', 'M', '+221777770055', 'birame.tine@suptech.edu', 'Diofior', 'upload/etudiants/ETU202626_1783626912.jpg', 4),
(27, 'ETU202627', 'Lo', 'Ndeye', '2005-02-14', 'F', '+221777770066', 'ndeye.lo@suptech.edu', 'Louga', 'FILLE13', 4),
(28, 'ETU202628', 'Deme', 'Mansour', '2003-10-05', 'M', '+221777770077', 'mansour.deme@suptech.edu', 'Richard-Toll', 'HOMME15', 4),
(29, 'ETU202629', 'Traoré', 'Souleymane', '2002-05-19', 'M', '+221778880011', 'souleymane.traore@suptech.edu', 'Dakar', 'HOMME16', 5),
(30, 'ETU202630', 'Badiane', 'Coumba', '2001-08-27', 'F', '+221778880022', 'coumba.badiane@suptech.edu', 'Bignona', 'FILLE14', 5),
(31, 'ETU202631', 'Fall', 'Alioune', '2002-11-04', 'M', '+221778880033', 'alioune.fall2@suptech.edu', 'Thies', 'HOMME17', 5),
(32, 'ETU202632', 'Kandji', 'Yacine', '2002-03-16', 'F', '+221778880044', 'yacine.kandji@suptech.edu', 'Rufisque', 'FILLE15', 5),
(33, 'ETU202633', 'Gomis', 'Jean', '2001-01-25', 'M', '+221778880055', 'jean.gomis@suptech.edu', 'Oussouye', 'upload/etudiants/ETU202633_1783626738.jpg', 5),
(34, 'ETU202634', 'Ciss', 'Khady', '2002-09-09', 'F', '+221778880066', 'khady.ciss@suptech.edu', 'Mbour', 'FILLE16', 5),
(35, 'ETU202635', 'Ndour', 'Sokhna', '2001-07-12', 'F', '+221778880077', 'sokhna.ndour@suptech.edu', 'Fatick', 'upload/etudiants/ETU202635_1783626928.jpg', 5);

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `date_inscription` date NOT NULL DEFAULT (curdate()),
  `id_etudiant` int NOT NULL,
  `id_classe` int NOT NULL,
  `id_annee` int NOT NULL,
  PRIMARY KEY (`id_inscription`),
  UNIQUE KEY `uq_inscription` (`id_etudiant`,`id_classe`,`id_annee`),
  KEY `fk_inscription_classe` (`id_classe`),
  KEY `fk_inscription_annee` (`id_annee`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id_inscription`, `date_inscription`, `id_etudiant`, `id_classe`, `id_annee`) VALUES
(1, '2026-07-09', 1, 1, 1),
(2, '2026-07-09', 2, 1, 1),
(3, '2026-07-09', 3, 1, 1),
(4, '2026-07-09', 4, 1, 1),
(5, '2026-07-09', 5, 1, 1),
(6, '2026-07-09', 6, 1, 1),
(7, '2026-07-09', 7, 1, 1),
(8, '2026-07-09', 8, 2, 1),
(9, '2026-07-09', 9, 2, 1),
(10, '2026-07-09', 10, 2, 1),
(11, '2026-07-09', 11, 2, 1),
(12, '2026-07-09', 12, 2, 1),
(13, '2026-07-09', 13, 2, 1),
(14, '2026-07-09', 14, 2, 1),
(15, '2026-07-09', 15, 3, 1),
(16, '2026-07-09', 16, 3, 1),
(17, '2026-07-09', 17, 3, 1),
(18, '2026-07-09', 18, 3, 1),
(19, '2026-07-09', 19, 3, 1),
(20, '2026-07-09', 20, 3, 1),
(21, '2026-07-09', 21, 3, 1),
(22, '2026-07-09', 22, 4, 1),
(23, '2026-07-09', 23, 4, 1),
(24, '2026-07-09', 24, 4, 1),
(25, '2026-07-09', 25, 4, 1),
(26, '2026-07-09', 26, 4, 1),
(27, '2026-07-09', 27, 4, 1),
(28, '2026-07-09', 28, 4, 1),
(29, '2026-07-09', 29, 5, 1),
(30, '2026-07-09', 30, 5, 1),
(31, '2026-07-09', 31, 5, 1),
(32, '2026-07-09', 32, 5, 1),
(33, '2026-07-09', 33, 5, 1),
(34, '2026-07-09', 34, 5, 1),
(35, '2026-07-09', 35, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id_module` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coefficient` decimal(3,1) NOT NULL,
  `nombre_heures` int NOT NULL,
  PRIMARY KEY (`id_module`)
) ;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id_module`, `nom`, `coefficient`, `nombre_heures`) VALUES
(1, 'Applications Web avec PHP / Laravel', 3.0, 60),
(2, 'Développement Mobile React Native CLI', 4.0, 50),
(3, 'Architecture des Bases de Données & SQL', 3.0, 45),
(4, 'Algorithmique Avancée & Structures de données', 3.0, 40),
(5, 'Sécurité des Systèmes et Virtualisation', 4.0, 50),
(6, 'Cryptographie Appliquée', 3.0, 45),
(7, 'Administration Réseaux et Scripts Shell', 3.0, 40),
(8, 'Management de Projet Informatique', 2.0, 30),
(9, 'Bureautique & Outils Collaboratifs', 2.0, 30),
(10, 'Introduction aux Bases de Données', 3.0, 45),
(11, 'Algorithmique de Base et Langage C', 4.0, 60),
(12, 'Comptabilité Générale', 2.0, 40),
(13, 'Développement Web Front-end (JavaScript)', 3.0, 50),
(14, 'Conception UI/UX pour Mobile', 2.0, 30),
(15, 'Programmation Orientée Objet (Java)', 4.0, 60),
(16, 'Réseaux Informatiques Fondamentaux', 3.0, 45),
(17, 'Machine Learning & Python', 4.0, 60),
(18, 'Big Data & Écosystème Hadoop', 4.0, 50),
(19, 'Visualisation des Données & BI', 3.0, 40),
(20, 'Éthique et Droit des Données', 2.0, 24),
(21, 'ROGRAMATION ORIENTER OBJET (java)', 4.0, 30);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `cc` decimal(4,2) NOT NULL,
  `tp` decimal(4,2) NOT NULL,
  `examen` decimal(4,2) NOT NULL,
  `moyenne` decimal(4,2) GENERATED ALWAYS AS (round((((`cc` * 0.2) + (`tp` * 0.3)) + (`examen` * 0.5)),2)) STORED,
  `mention` varchar(30) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS ((case when (round((((`cc` * 0.2) + (`tp` * 0.3)) + (`examen` * 0.5)),2) >= 16) then _utf8mb4'Tres bien' when (round((((`cc` * 0.2) + (`tp` * 0.3)) + (`examen` * 0.5)),2) >= 14) then _utf8mb4'Bien' when (round((((`cc` * 0.2) + (`tp` * 0.3)) + (`examen` * 0.5)),2) >= 12) then _utf8mb4'Assez bien' when (round((((`cc` * 0.2) + (`tp` * 0.3)) + (`examen` * 0.5)),2) >= 10) then _utf8mb4'Passable' else _utf8mb4'Insuffisant' end)) STORED,
  `id_etudiant` int NOT NULL,
  `id_assignation` int NOT NULL,
  PRIMARY KEY (`id_note`),
  UNIQUE KEY `uq_note` (`id_etudiant`,`id_assignation`),
  KEY `fk_note_assignation` (`id_assignation`),
  KEY `idx_notes_etudiant` (`id_etudiant`)
) ;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id_note`, `cc`, `tp`, `examen`, `id_etudiant`, `id_assignation`) VALUES
(1, 15.00, 14.50, 13.00, 1, 1),
(2, 12.00, 16.00, 11.50, 15, 11),
(3, 14.00, 18.00, 15.00, 29, 17),
(4, 12.00, 4.00, 14.00, 22, 14),
(5, 14.00, 19.00, 10.50, 24, 14),
(6, 10.00, 10.00, 16.00, 28, 14),
(7, 7.00, 16.00, 17.50, 25, 14),
(8, 8.00, 19.00, 17.00, 27, 14),
(9, 20.00, 18.00, 19.00, 26, 14),
(13, 17.00, 19.00, 16.50, 23, 14);

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `montant_paye` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL DEFAULT (curdate()),
  `mode_paiement` enum('Especes','Orange Money','Wave','Virement bancaire','Cheque') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_inscription` int NOT NULL,
  PRIMARY KEY (`id_paiement`),
  KEY `idx_paiements_inscription` (`id_inscription`)
) ;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id_paiement`, `montant_paye`, `date_paiement`, `mode_paiement`, `id_inscription`) VALUES
(1, 250000.00, '2026-07-09', 'Wave', 1),
(2, 300000.00, '2026-07-09', 'Orange Money', 8),
(3, 200000.00, '2026-07-09', 'Especes', 15),
(4, 350000.00, '2026-07-09', 'Virement bancaire', 22),
(5, 500000.00, '2026-07-09', 'Wave', 29);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `libelle`) VALUES
(1, 'Administrateur'),
(2, 'Enseignant'),
(3, 'Scolarité');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

DROP TABLE IF EXISTS `salles`;
CREATE TABLE IF NOT EXISTS `salles` (
  `id_salle` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite` int NOT NULL,
  PRIMARY KEY (`id_salle`),
  UNIQUE KEY `nom` (`nom`)
) ;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id_salle`, `nom`, `capacite`) VALUES
(1, 'Salle A101', 50),
(2, 'Labo Informatique', 30),
(3, 'Amphi SupTech', 150),
(4, 'Salle B202', 40);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_role` int NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_utilisateur_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `statut`, `date_creation`, `id_role`, `photo`) VALUES
(2, 'Admin', 'SupTech', 'admin@suptech.sn', '$2y$10$SPIyRbz19NklkjY8djoB0O1Qy4uqVWExhv/nxWhpjF1n6CmZY1bCK', 'actif', '2026-07-03 23:12:38', 1, NULL),
(3, 'Prof', 'Test', 'prof@suptech.sn', '$2y$10$GT5pDvXkwfTOfCvn/27xR.HDuZT2N0gKd84RtKs3BaSVBNZ2c7qIe', 'actif', '2026-07-03 23:12:38', 2, NULL),
(4, 'Agent', 'Scolarite', 'scola@suptech.sn', '$2y$10$HkPgaVz4NEdIkYcuPPF6W.YISta4H1YcO79f4ym01DXv5Vd9yfAVK', 'actif', '2026-07-03 23:12:38', 3, NULL),
(5, 'Seck', 'Amath', 'amath@gmail.com', '$2y$10$GFI/3HSWpGMVsAvOWGgUjOEadWQc1XAMXGmvjGBGa/PBsLRoQilKy', 'actif', '2026-07-06 14:34:56', 2, NULL),
(6, 'Diop', 'Cheikh Anta', 'c.diop@suptech.edu', '$2y$10$oylgA7bRJBCBeq1WF4bjs.0vyE2S.OFmdvvVdr3UvvMTk3bY2ioPu', 'actif', '2026-07-09 18:56:05', 2, NULL),
(7, 'Bâ', 'Mariama', 'm.ba@suptech.edu', '$2y$10$htrucilqBHqUa75/XWaz/eyzaDrfwqYHEbhJys4Sp4ayo5wcb.gzG', 'actif', '2026-07-09 18:56:05', 2, NULL),
(8, 'Sembène', 'Ousmane', 'o.sembene@suptech.edu', '$2y$10$EODKGrm0U/MZktUMUXsBEuX2WUT7.3tGC5mtiWI00kwXZiKGCecqW', 'actif', '2026-07-09 18:56:05', 2, NULL),
(9, 'Sow Fall', 'Aminata', 'a.sowfall@suptech.edu', '$2y$10$BjrXYT4hewsqbufyL3a9..6.ZztKE5B9u4e2dMIcVoXARoxbHhiI2', 'actif', '2026-07-09 18:56:05', 2, NULL),
(10, 'Senghor', 'Léopold', 'l.senghor@suptech.edu', '$2y$10$7VpeDJ.zQ8Y7ymcInW8zieocd5/3oNxeana9eMxBE9W7jsXHU7zRe', 'actif', '2026-07-09 18:56:05', 2, NULL),
(11, 'Diome', 'Fatou', 'f.diome@suptech.edu', '$2y$10$AR6tYcLQD9fW3j6QOmPOFewc3kqX6IR5XGIFqg4yvqFhwCu6n68FS', 'actif', '2026-07-09 18:56:05', 2, NULL),
(12, 'Diop_B', 'Boubacar Boris', 'b.diop@suptech.edu', '$2y$10$zHpK6tI0uOBEZhoik01mdux5GtrdxOQ89MDSQj8GXRXOGEGHZ9kHW', 'actif', '2026-07-09 18:56:05', 2, NULL),
(13, 'Bugul', 'Ken', 'k.bugul@suptech.edu', '$2y$10$Arr8j7YoKcsArZRR6izRQ.cSWDjxJg4tdMymn713Ex/hXol6oawCS', 'actif', '2026-07-09 18:56:05', 2, NULL),
(14, 'Sarr', 'Felwine', 'f.sarr@suptech.edu', '$2y$10$bI.l6JNDAym5gw.KipWWAexRXAUU1kPGQ5dCwqbj7H8P/61ODXP.e', 'actif', '2026-07-09 18:56:05', 2, NULL),
(15, 'Sylla', 'Khady', 'k.sylla@suptech.edu', '$2y$10$mabot88iPeNuIlx4op3ls.v3YJBlMFDpt7Ff4jIoX59dVs71k9Wg.', 'actif', '2026-07-09 18:56:05', 2, NULL),
(16, 'ly', 'mamad', 'mamad@gmail.com', '$2y$10$cTQD0IVdddteQBWlFJ8m9ONXlIkxfrfYvH3J7rJsQ0XDN/R.vMram', 'actif', '2026-07-10 15:45:01', 3, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `assignation_modules`
--
ALTER TABLE `assignation_modules`
  ADD CONSTRAINT `fk_assignation_annee` FOREIGN KEY (`id_annee`) REFERENCES `annees_universitaires` (`id_annee`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assignation_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assignation_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assignation_module` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `emploi_temps`
--
ALTER TABLE `emploi_temps`
  ADD CONSTRAINT `fk_seance_assignation` FOREIGN KEY (`id_assignation`) REFERENCES `assignation_modules` (`id_assignation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_seance_salle` FOREIGN KEY (`id_salle`) REFERENCES `salles` (`id_salle`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD CONSTRAINT `fk_enseignant_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `fk_etudiant_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `fk_inscription_annee` FOREIGN KEY (`id_annee`) REFERENCES `annees_universitaires` (`id_annee`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscription_classe` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id_classe`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscription_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_note_assignation` FOREIGN KEY (`id_assignation`) REFERENCES `assignation_modules` (`id_assignation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_note_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `fk_paiement_inscription` FOREIGN KEY (`id_inscription`) REFERENCES `inscriptions` (`id_inscription`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `fk_utilisateur_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
