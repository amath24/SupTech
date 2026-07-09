# SupTech - Gestion Académique

SupTech est une application web PHP/MySQL dédiée à la gestion académique d’une école ou d’une business school. Elle permet de gérer les étudiants, enseignants, classes, modules, inscriptions, paiements, notes, emploi du temps et statistiques.

## Fonctionnalités principales

- Gestion des étudiants
- Gestion des enseignants
- Gestion des classes et salles
- Gestion des modules
- Gestion des inscriptions
- Gestion des paiements
- Saisie et consultation des notes
- Gestion de l’emploi du temps
- Statistiques et rapports

## Prérequis

Avant d’installer le projet, assurez-vous d’avoir :

- WAMPServer, XAMPP ou un environnement Apache/MySQL/PHP équivalent
- PHP 7.4+ ou 8.x recommandé
- MySQL/MariaDB
- Un navigateur web moderne

## Installation

1. Placez le dossier du projet dans le répertoire web de votre serveur :
   - WAMP : `c:\wamp64\www\SupTech`

2. Démarrez Apache et MySQL depuis WAMP.

3. Créez une base de données nommée :
   - `gestion_academique`

4. Importez le fichier SQL fourni dans le dossier :
   - `BASEDEDONNER/gestion_academique (2).sql`

5. Ouvrez l’application dans votre navigateur :
   - `http://localhost/SupTech`

## Configuration de la base de données

La connexion à la base est définie dans :

- `include/config/database.php`

Par défaut, elle utilise :

- Hôte : `localhost`
- Base de données : `gestion_academique`
- Utilisateur : `root`
- Mot de passe : vide

Si votre configuration MySQL diffère, modifiez ces paramètres dans le fichier correspondant.

## Connexion

Le projet contient des comptes de démonstration dans le dump SQL. Après l’importation du script, vous pouvez vous connecter avec les identifiants présents dans la base.

## Structure du projet

- `dashboard/` : tableau de bord principal
- `gestion_des_etudiants/` : gestion des étudiants
- `gestion_des_enseignants/` : gestion des enseignants
- `gestion_des_classes/` : gestion des classes
- `gestion_des_modules/` : gestion des modules
- `gestion_des_inscriptions/` : inscriptions
- `gestion_des_paiements/` : paiements
- `gestion_des_notes_enseignant/` : saisie des notes
- `emploi_du_temps/` : emploi du temps
- `statistique/` : statistiques
- `include/` : fichiers communs de configuration et d’authentification
- `assets/` : fichiers CSS, JS et images

## Notes

- Le projet est conçu pour un usage local de développement ou de démonstration.
- Pour un usage en production, il est recommandé de renforcer la sécurité :
  - protéger l’accès à la base de données
  - utiliser des mots de passe forts
  - vérifier les permissions des fichiers
  - sécuriser les formulaires et les sessions

## Auteur

Projet développé pour la gestion académique de SupTech Business School.
