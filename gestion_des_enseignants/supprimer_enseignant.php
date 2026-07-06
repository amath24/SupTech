<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

$id_enseignant = isset($_POST['id_enseignant']) ? (int)$_POST['id_enseignant'] : 0;

if ($id_enseignant <= 0) {
    $_SESSION['error'] = "Identifiant d'enseignant invalide.";
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

try {
    $db = Database::getInstance();
    $db->beginTransaction();

    // On vérifie d'abord l'utilisateur associé
    $stmt = $db->prepare("SELECT id_utilisateur FROM enseignants WHERE id_enseignant = ?");
    $stmt->execute([$id_enseignant]);
    $id_utilisateur = $stmt->fetchColumn();

    // La suppression de l'enseignant va cascader dans assignation_modules (si la FK est bien configurée)
    // Mais s'il y a des ON DELETE RESTRICT (ce qui est le cas pour assignation_modules -> enseignant dans le script DB ! "ON DELETE RESTRICT")
    // Wait, on the schema: ADD CONSTRAINT `fk_assignation_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE RESTRICT ON UPDATE CASCADE
    // This means we CANNOT delete a teacher if they are assigned to a module. We must either delete the assignation or show an error.
    
    // Let's check for assignations first
    $stmt_check = $db->prepare("SELECT COUNT(*) FROM assignation_modules WHERE id_enseignant = ?");
    $stmt_check->execute([$id_enseignant]);
    if ($stmt_check->fetchColumn() > 0) {
        throw new Exception("Impossible de supprimer cet enseignant car il est assigné à un ou plusieurs modules. Veuillez d'abord réassigner ou supprimer ces modules.");
    }

    $stmt_del = $db->prepare("DELETE FROM enseignants WHERE id_enseignant = ?");
    $stmt_del->execute([$id_enseignant]);
    
    // Si la suppression de l'enseignant a réussi et qu'il avait un compte, on le supprime
    if ($stmt_del->rowCount() > 0 && $id_utilisateur) {
        $stmt_del_user = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
        $stmt_del_user->execute([$id_utilisateur]);
    }

    $db->commit();
    $_SESSION['success'] = "L'enseignant et son compte utilisateur associé ont été supprimés avec succès.";
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    $_SESSION['error'] = "Erreur lors de la suppression de l'enseignant : " . $e->getMessage();
}

header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
exit;
