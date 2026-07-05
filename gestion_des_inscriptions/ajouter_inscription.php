<?php
/**
 * Traitement de l'ajout d'une nouvelle inscription
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_inscriptions/gestiondesinscription.php'));
    exit;
}

$id_etudiant = isset($_POST['id_etudiant']) ? (int)$_POST['id_etudiant'] : 0;
$id_classe = isset($_POST['id_classe']) ? (int)$_POST['id_classe'] : 0;
$id_annee = isset($_POST['id_annee']) ? (int)$_POST['id_annee'] : 0;

if ($id_etudiant <= 0 || $id_classe <= 0 || $id_annee <= 0) {
    $_SESSION['error'] = "Veuillez sélectionner l'étudiant, la classe et l'année universitaire.";
    header('Location: ' . url('gestion_des_inscriptions/gestiondesinscription.php'));
    exit;
}

try {
    $db = Database::getInstance();

    // 1. Vérification de l'existence de l'inscription pour cette année
    // Un étudiant ne peut s'inscrire qu'une seule fois par année académique.
    $checkQuery = $db->prepare("SELECT COUNT(*) FROM inscriptions WHERE id_etudiant = ? AND id_annee = ?");
    $checkQuery->execute([$id_etudiant, $id_annee]);
    if ($checkQuery->fetchColumn() > 0) {
        $_SESSION['error'] = "Cet étudiant est déjà inscrit pour cette année académique.";
        header('Location: ' . url('gestion_des_inscriptions/gestiondesinscription.php'));
        exit;
    }

    // Début de la transaction
    $db->beginTransaction();

    // 2. Insertion de l'inscription
    $insertQuery = $db->prepare("
        INSERT INTO inscriptions (id_etudiant, id_classe, id_annee, date_inscription)
        VALUES (?, ?, ?, CURDATE())
    ");
    $insertQuery->execute([$id_etudiant, $id_classe, $id_annee]);

    // 3. Mise à jour de la classe de l'étudiant dans sa fiche (pour qu'il reflète sa dernière inscription)
    $updateEtudiantQuery = $db->prepare("UPDATE etudiants SET id_classe = ? WHERE id_etudiant = ?");
    $updateEtudiantQuery->execute([$id_classe, $id_etudiant]);

    // Validation de la transaction
    $db->commit();

    $_SESSION['success'] = "L'inscription a été enregistrée avec succès, et le dossier de l'étudiant a été mis à jour.";

} catch (PDOException $e) {
    // Annulation en cas d'erreur
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    $_SESSION['error'] = "Erreur serveur : impossible d'enregistrer l'inscription. " . $e->getMessage();
    error_log("Erreur Ajouter Inscription: " . $e->getMessage());
}

header('Location: ' . url('gestion_des_inscriptions/gestiondesinscription.php'));
exit;
