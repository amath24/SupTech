-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 04 juil. 2026 à 16:48
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_utilisateur_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `statut`, `date_creation`, `id_role`) VALUES
(2, 'Admin', 'SupTech', 'admin@suptech.sn', '$2y$10$SPIyRbz19NklkjY8djoB0O1Qy4uqVWExhv/nxWhpjF1n6CmZY1bCK', 'actif', '2026-07-03 23:12:38', 1),
(3, 'Prof', 'Test', 'prof@suptech.sn', '$2y$10$GT5pDvXkwfTOfCvn/27xR.HDuZT2N0gKd84RtKs3BaSVBNZ2c7qIe', 'actif', '2026-07-03 23:12:38', 2),
(4, 'Agent', 'Scolarite', 'scola@suptech.sn', '$2y$10$HkPgaVz4NEdIkYcuPPF6W.YISta4H1YcO79f4ym01DXv5Vd9yfAVK', 'actif', '2026-07-03 23:12:38', 3);

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
