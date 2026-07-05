<?php
/**
 * Traitement de l'ajout d'un nouvel étudiant
 */

$page_required_role = ['Administrateur', 'Scolarité'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
    exit;
}

$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
$date_naissance = isset($_POST['date_naissance']) ? trim($_POST['date_naissance']) : '';
$sexe = isset($_POST['sexe']) ? trim($_POST['sexe']) : '';
$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
$id_classe = isset($_POST['id_classe']) ? (int)$_POST['id_classe'] : 0;

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

    // Vérification de l'unicité de l'email
    $checkEmail = $db->prepare("SELECT COUNT(*) FROM etudiants WHERE email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->fetchColumn() > 0) {
        $_SESSION['error'] = "Un étudiant avec cette adresse email existe déjà.";
        header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
        exit;
    }

    // Vérification que la classe existe et n'est pas pleine (optionnel mais recommandé)
    // Pour cet exercice on se limite à générer le matricule

    // Génération du matricule SUP-YYYY-XXX
    $currentYear = date('Y');
    $prefix = "SUP-$currentYear-";
    
    // Rechercher le dernier matricule de cette année
    $stmtMatricule = $db->prepare("SELECT matricule FROM etudiants WHERE matricule LIKE ? ORDER BY matricule DESC LIMIT 1");
    $stmtMatricule->execute([$prefix . '%']);
    $lastMatricule = $stmtMatricule->fetchColumn();

    if ($lastMatricule) {
        // Extraire la partie numérique (les 3 derniers caractères)
        $lastNumber = (int)substr($lastMatricule, -3);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    $matricule = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    // Gestion de l'upload de la photo
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../upload/etudiants/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = str_replace('-', '_', $matricule) . '_' . time() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $photoPath = 'upload/etudiants/' . $newFileName;
            }
        }
    }

    // Insertion
    $insertQuery = $db->prepare("
        INSERT INTO etudiants (matricule, nom, prenom, date_naissance, sexe, telephone, email, adresse, id_classe, photo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insertQuery->execute([$matricule, $nom, $prenom, $date_naissance, $sexe, $telephone, $email, $adresse, $id_classe, $photoPath]);

    $_SESSION['success'] = "L'étudiant " . htmlspecialchars($prenom . ' ' . $nom) . " a été ajouté avec succès (Matricule: $matricule).";

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : impossible d'enregistrer l'étudiant. " . $e->getMessage();
    error_log("Erreur Ajouter Etudiant: " . $e->getMessage());
}

header('Location: ' . url('gestion_des_etudiants/gestiondesetudiant.php'));
exit;
