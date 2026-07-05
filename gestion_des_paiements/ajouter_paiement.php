<?php
/**
 * Traitement de l'ajout d'un paiement
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_paiements/gestiondespaiements.php'));
    exit;
}

$id_inscription = isset($_POST['id_inscription']) ? (int)$_POST['id_inscription'] : 0;
$montant_paye = isset($_POST['montant_paye']) ? (float)$_POST['montant_paye'] : 0;
$mode_paiement = isset($_POST['mode_paiement']) ? trim($_POST['mode_paiement']) : '';
$date_paiement = isset($_POST['date_paiement']) ? trim($_POST['date_paiement']) : '';

if ($id_inscription <= 0 || $montant_paye <= 0 || empty($mode_paiement) || empty($date_paiement)) {
    $_SESSION['error'] = "Veuillez remplir tous les champs correctement.";
    header('Location: ' . url('gestion_des_paiements/gestiondespaiements.php?inscription=' . $id_inscription));
    exit;
}

$modes_valides = ['Especes', 'Orange Money', 'Wave', 'Virement bancaire', 'Cheque'];
if (!in_array($mode_paiement, $modes_valides)) {
    $_SESSION['error'] = "Mode de paiement invalide.";
    header('Location: ' . url('gestion_des_paiements/gestiondespaiements.php?inscription=' . $id_inscription));
    exit;
}

try {
    $db = Database::getInstance();

    // Insertion du paiement
    $insertQuery = $db->prepare("
        INSERT INTO paiements (montant_paye, date_paiement, mode_paiement, id_inscription)
        VALUES (?, ?, ?, ?)
    ");
    $insertQuery->execute([$montant_paye, $date_paiement, $mode_paiement, $id_inscription]);

    $_SESSION['success'] = "Le paiement de " . number_format($montant_paye, 0, ',', ' ') . " FCFA a été enregistré avec succès.";

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : impossible d'enregistrer le paiement. " . $e->getMessage();
    error_log("Erreur Ajouter Paiement: " . $e->getMessage());
}

header('Location: ' . url('gestion_des_paiements/gestiondespaiements.php?inscription=' . $id_inscription));
exit;
