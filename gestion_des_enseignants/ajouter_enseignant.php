<?php
$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$grade = trim($_POST['grade'] ?? '');
$specialite = trim($_POST['specialite'] ?? '');

if (empty($prenom) || empty($nom) || empty($email) || empty($telephone) || empty($grade) || empty($specialite)) {
    $_SESSION['error'] = "Veuillez remplir tous les champs.";
    header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
    exit;
}

try {
    $db = Database::getInstance();
    $db->beginTransaction();

    // 1. Check if email already exists in utilisateurs or enseignants
    $stmt_check = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
    $stmt_check->execute([$email]);
    if ($stmt_check->fetchColumn() > 0) {
        throw new Exception("L'adresse email est déjà utilisée par un utilisateur.");
    }

    $stmt_check2 = $db->prepare("SELECT COUNT(*) FROM enseignants WHERE email = ?");
    $stmt_check2->execute([$email]);
    if ($stmt_check2->fetchColumn() > 0) {
        throw new Exception("L'adresse email est déjà utilisée par un enseignant.");
    }

    // 2. Generate Matricule ENS-YYYY-XXX
    $year = date('Y');
    $stmt_last = $db->query("SELECT matricule FROM enseignants WHERE matricule LIKE 'ENS-$year-%' ORDER BY matricule DESC LIMIT 1");
    $last_mat = $stmt_last->fetchColumn();
    $next_num = 1;
    if ($last_mat) {
        $parts = explode('-', $last_mat);
        $next_num = (int)end($parts) + 1;
    }
    $matricule = "ENS-$year-" . str_pad($next_num, 3, '0', STR_PAD_LEFT);

    // 3. Create utilisateur
    $default_password = password_hash('Pass123!', PASSWORD_DEFAULT);
    $role_enseignant = 2; // ID for Enseignant role
    $stmt_user = $db->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, statut, id_role) VALUES (?, ?, ?, ?, 'actif', ?)");
    $stmt_user->execute([$nom, $prenom, $email, $default_password, $role_enseignant]);
    $id_utilisateur = $db->lastInsertId();

    // 4. Create enseignant
    $stmt_ens = $db->prepare("INSERT INTO enseignants (matricule, nom, prenom, grade, telephone, email, specialite, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_ens->execute([$matricule, $nom, $prenom, $grade, $telephone, $email, $specialite, $id_utilisateur]);

    $db->commit();
    $_SESSION['success'] = "L'enseignant a été ajouté avec succès avec le matricule $matricule. Un compte a été créé.";
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    $_SESSION['error'] = "Erreur lors de l'ajout : " . $e->getMessage();
}

header('Location: ' . url('gestion_des_enseignants/gestiondesenseignants.php'));
exit;
