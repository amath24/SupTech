<?php
/**
 * Traitement de la modification d'un étudiant
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

// Récupération et nettoyage des données
$id_etudiant    = isset($_POST['id_etudiant'])    ? (int)$_POST['id_etudiant']         : 0;
$nom            = isset($_POST['nom'])            ? trim($_POST['nom'])                : '';
$prenom         = isset($_POST['prenom'])         ? trim($_POST['prenom'])             : '';
$date_naissance = isset($_POST['date_naissance']) ? trim($_POST['date_naissance'])     : '';
$sexe           = isset($_POST['sexe'])           ? trim($_POST['sexe'])               : '';
$telephone      = isset($_POST['telephone'])      ? trim($_POST['telephone'])          : '';
$email          = isset($_POST['email'])          ? trim($_POST['email'])              : '';
$adresse        = isset($_POST['adresse'])        ? trim($_POST['adresse'])            : '';
$id_classe      = isset($_POST['id_classe'])      ? (int)$_POST['id_classe']           : 0;
$supprimer_photo = isset($_POST['supprimer_photo']) && $_POST['supprimer_photo'] === '1';

// Validations de base
if ($id_etudiant <= 0) {
    $_SESSION['error'] = "Identifiant étudiant invalide.";
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

if (empty($nom) || empty($prenom) || empty($date_naissance) || empty($sexe) || empty($telephone) || empty($email) || empty($adresse) || $id_classe <= 0) {
    $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Le format de l'adresse email est invalide.";
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

try {
    $db = Database::getInstance();

    // Récupérer l'étudiant existant
    $stmtGet = $db->prepare("SELECT * FROM etudiants WHERE id_etudiant = ?");
    $stmtGet->execute([$id_etudiant]);
    $etudiantExistant = $stmtGet->fetch();

    if (!$etudiantExistant) {
        $_SESSION['error'] = "Étudiant introuvable.";
        header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
        exit;
    }

    // Vérification unicité email (ignorer l'email actuel de cet étudiant)
    $checkEmail = $db->prepare("SELECT COUNT(*) FROM etudiants WHERE email = ? AND id_etudiant != ?");
    $checkEmail->execute([$email, $id_etudiant]);
    if ($checkEmail->fetchColumn() > 0) {
        $_SESSION['error'] = "Un autre étudiant utilise déjà cette adresse email.";
        header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
        exit;
    }

    // ── Gestion de la photo ──────────────────────────────────────────────────
    $photoPath = $etudiantExistant['photo']; // conserver l'ancienne par défaut

    // Cas 1 : L'utilisateur veut supprimer la photo actuelle
    if ($supprimer_photo) {
        if (!empty($photoPath) && strpos($photoPath, 'http') !== 0) {
            $ancienFichier = BASE_PATH . '/' . ltrim($photoPath, '/');
            if (file_exists($ancienFichier)) {
                unlink($ancienFichier);
            }
        }
        $photoPath = null;
    }

    // Cas 2 : Upload d'une nouvelle photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../upload/etudiants/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 2 * 1024 * 1024; // 2 Mo

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['error'] = "Format de photo non autorisé. Utilisez JPG, PNG, GIF ou WEBP.";
            header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
            exit;
        }

        if ($_FILES['photo']['size'] > $maxSize) {
            $_SESSION['error'] = "La photo ne doit pas dépasser 2 Mo.";
            header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
            exit;
        }

        // Supprimer l'ancienne photo si elle existe
        if (!empty($etudiantExistant['photo']) && strpos($etudiantExistant['photo'], 'http') !== 0) {
            $ancienFichier = BASE_PATH . '/' . ltrim($etudiantExistant['photo'], '/');
            if (file_exists($ancienFichier)) {
                unlink($ancienFichier);
            }
        }

        $newFileName = str_replace('-', '_', $etudiantExistant['matricule']) . '_' . time() . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $photoPath = 'upload/etudiants/' . $newFileName;
        } else {
            $_SESSION['error'] = "Erreur lors de l'upload de la photo.";
            header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
            exit;
        }
    }

    // ── Mise à jour en base ──────────────────────────────────────────────────
    $updateQuery = $db->prepare("
        UPDATE etudiants
        SET nom = ?, prenom = ?, date_naissance = ?, sexe = ?,
            telephone = ?, email = ?, adresse = ?, id_classe = ?, photo = ?
        WHERE id_etudiant = ?
    ");
    $updateQuery->execute([
        $nom, $prenom, $date_naissance, $sexe,
        $telephone, $email, $adresse, $id_classe, $photoPath,
        $id_etudiant
    ]);

    $_SESSION['success'] = "Les informations de " . htmlspecialchars($prenom . ' ' . $nom) . " ont été mises à jour avec succès.";

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : impossible de modifier l'étudiant. " . $e->getMessage();
    error_log("Erreur Modifier Etudiant: " . $e->getMessage());
}

header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
exit;
