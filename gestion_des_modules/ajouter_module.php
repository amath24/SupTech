<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$coefficient = isset($_POST['coefficient']) ? (float)$_POST['coefficient'] : 1.0;
$nombre_heures = isset($_POST['nombre_heures']) ? (int)$_POST['nombre_heures'] : 30;

$id_enseignant = isset($_POST['id_enseignant']) ? (int)$_POST['id_enseignant'] : 0;
$id_classe = isset($_POST['id_classe']) ? (int)$_POST['id_classe'] : 0;
$id_annee = isset($_POST['id_annee']) ? (int)$_POST['id_annee'] : 0;

if (empty($nom) || $coefficient <= 0 || $nombre_heures <= 0 || $id_enseignant <= 0 || $id_classe <= 0 || $id_annee <= 0) {
    $_SESSION['error'] = "Veuillez remplir tous les champs avec des valeurs valides.";
    header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
    exit;
}

try {
    $db = Database::getInstance();
    
    // On utilise une transaction pour s'assurer que les deux insertions réussissent
    $db->beginTransaction();
    
    // Insertion du module
    $stmt = $db->prepare("INSERT INTO modules (nom, coefficient, nombre_heures) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $coefficient, $nombre_heures]);
    $id_module = $db->lastInsertId();
    
    // Insertion de l'assignation initiale
    $stmt_assign = $db->prepare("INSERT INTO assignation_modules (id_module, id_enseignant, id_classe, id_annee) VALUES (?, ?, ?, ?)");
    $stmt_assign->execute([$id_module, $id_enseignant, $id_classe, $id_annee]);
    
    $db->commit();
    $_SESSION['success'] = "Le module a été ajouté et assigné avec succès.";
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Erreur lors de l'ajout du module : " . $e->getMessage();
}

header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
exit;
