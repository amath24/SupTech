<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

$id_enseignant = (int)($_POST['id_enseignant'] ?? 0);
$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$grade = trim($_POST['grade'] ?? '');
$specialite = trim($_POST['specialite'] ?? '');

if ($id_enseignant <= 0 || empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($grade) || empty($specialite)) {
    $_SESSION['error'] = "Veuillez remplir tous les champs.";
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

try {
    $db = Database::getInstance();
    $db->beginTransaction();

    // Verify existence of teacher
    $stmt_get = $db->prepare("SELECT id_utilisateur FROM enseignants WHERE id_enseignant = ?");
    $stmt_get->execute([$id_enseignant]);
    $id_utilisateur = $stmt_get->fetchColumn();

    if ($id_utilisateur === false) {
        throw new Exception("Enseignant introuvable.");
    }

    // Check if email already used by ANOTHER user
    if ($id_utilisateur) {
        $stmt_check = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id_utilisateur != ?");
        $stmt_check->execute([$email, $id_utilisateur]);
        if ($stmt_check->fetchColumn() > 0) {
            throw new Exception("L'adresse email est déjà utilisée par un autre compte utilisateur.");
        }
    }

    $stmt_check2 = $db->prepare("SELECT COUNT(*) FROM enseignants WHERE email = ? AND id_enseignant != ?");
    $stmt_check2->execute([$email, $id_enseignant]);
    if ($stmt_check2->fetchColumn() > 0) {
        throw new Exception("L'adresse email est déjà utilisée par un autre enseignant.");
    }

    // Update teacher
    $stmt_update_ens = $db->prepare("UPDATE enseignants SET nom = ?, prenom = ?, email = ?, telephone = ?, grade = ?, specialite = ? WHERE id_enseignant = ?");
    $stmt_update_ens->execute([$nom, $prenom, $email, $telephone, $grade, $specialite, $id_enseignant]);

    // Update user if linked
    if ($id_utilisateur) {
        $stmt_update_user = $db->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ? WHERE id_utilisateur = ?");
        $stmt_update_user->execute([$nom, $prenom, $email, $id_utilisateur]);
    }

    $db->commit();
    $_SESSION['success'] = "Les informations de l'enseignant ont été mises à jour.";
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    $_SESSION['error'] = "Erreur lors de la modification : " . $e->getMessage();
}

header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
exit;
