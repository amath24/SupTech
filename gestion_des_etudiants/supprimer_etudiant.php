<?php
/**
 * Traitement de la suppression d'un étudiant
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

$id_etudiant = isset($_POST['id_etudiant']) ? (int)$_POST['id_etudiant'] : 0;

if ($id_etudiant <= 0) {
    $_SESSION['error'] = "Identifiant étudiant invalide.";
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

try {
    $db = Database::getInstance();

    // Récupérer les infos de l'étudiant (notamment la photo)
    $stmtGet = $db->prepare("SELECT nom, prenom, photo FROM etudiants WHERE id_etudiant = ?");
    $stmtGet->execute([$id_etudiant]);
    $etudiant = $stmtGet->fetch();

    if (!$etudiant) {
        $_SESSION['error'] = "Étudiant introuvable.";
        header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
        exit;
    }

    // Supprimer la photo du disque si elle existe
    if (!empty($etudiant['photo']) && strpos($etudiant['photo'], 'http') !== 0) {
        $photoFile = BASE_PATH . '/' . ltrim($etudiant['photo'], '/');
        if (file_exists($photoFile)) {
            unlink($photoFile);
        }
    }

    // Supprimer l'étudiant de la base
    $stmtDel = $db->prepare("DELETE FROM etudiants WHERE id_etudiant = ?");
    $stmtDel->execute([$id_etudiant]);

    $_SESSION['success'] = "L'étudiant " . htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) . " a été supprimé avec succès.";

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : impossible de supprimer l'étudiant. " . $e->getMessage();
    error_log("Erreur Supprimer Etudiant: " . $e->getMessage());
}

header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
exit;
