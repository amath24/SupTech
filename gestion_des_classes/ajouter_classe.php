<?php
/**
 * Traitement de l'ajout d'une nouvelle classe
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

// 1. Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_classes/gestiondesclasse.php'));
    exit;
}

// 2. Récupération et nettoyage des données
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$filiere = isset($_POST['filiere']) ? trim($_POST['filiere']) : '';
$niveau = isset($_POST['niveau']) ? trim($_POST['niveau']) : '';
$effectif_max = isset($_POST['effectif_max']) ? (int)$_POST['effectif_max'] : 40;

// 3. Validation des champs
if (empty($nom) || empty($filiere) || empty($niveau)) {
    $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
    header('Location: ' . url('gestion_des_classes/gestiondesclasse.php'));
    exit;
}

if ($effectif_max <= 0 || $effectif_max > 100) {
    $_SESSION['error'] = "L'effectif maximum doit être compris entre 1 et 100.";
    header('Location: ' . url('gestion_des_classes/gestiondesclasse.php'));
    exit;
}

try {
    $db = Database::getInstance();

    // 4. Vérification de l'unicité du nom de la classe
    $checkQuery = $db->prepare('SELECT COUNT(*) FROM classes WHERE nom = ?');
    $checkQuery->execute([$nom]);
    if ($checkQuery->fetchColumn() > 0) {
        $_SESSION['error'] = "Une classe avec le nom '" . htmlspecialchars($nom) . "' existe déjà.";
        header('Location: ' . url('gestion_des_classes/gestiondesclasse.php'));
        exit;
    }

    // 5. Insertion dans la base de données
    $insertQuery = $db->prepare('
        INSERT INTO classes (nom, filiere, niveau, effectif_max)
        VALUES (?, ?, ?, ?)
    ');
    $insertQuery->execute([$nom, $filiere, $niveau, $effectif_max]);

    $_SESSION['success'] = "La classe '" . htmlspecialchars($nom) . "' a été ajoutée avec succès !";
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : impossible d'enregistrer la classe. " . $e->getMessage();
    error_log("Erreur Ajouter Classe: " . $e->getMessage());
}

// 6. Redirection vers la liste des classes
header('Location: ' . url('gestion_des_classes/gestiondesclasse.php'));
exit;
