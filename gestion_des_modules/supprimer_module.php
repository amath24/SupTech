<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
    exit;
}

$id_module = isset($_POST['id_module']) ? (int)$_POST['id_module'] : 0;

if ($id_module <= 0) {
    $_SESSION['error'] = "Identifiant de module invalide.";
    header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
    exit;
}

try {
    $db = Database::getInstance();
    $stmt = $db->prepare("DELETE FROM modules WHERE id_module = ?");
    $stmt->execute([$id_module]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Le module a été supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Module introuvable ou déjà supprimé.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la suppression du module : " . $e->getMessage();
}

header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
exit;
