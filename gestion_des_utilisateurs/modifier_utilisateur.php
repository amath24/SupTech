<?php
session_start();
$page_required_role = ['Administrateur'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = (int)($_POST['id_utilisateur'] ?? 0);
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id_role = (int)($_POST['id_role'] ?? 0);
    $statut = trim($_POST['statut'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    
    if ($id_utilisateur <= 0 || empty($nom) || empty($prenom) || empty($email) || empty($id_role) || empty($statut)) {
        $_SESSION['error'] = 'Tous les champs marqués d\'un astérisque sont obligatoires.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    $db = Database::getInstance();
    
    // Vérification de sécurité: L'admin ne peut pas modifier un autre admin
    $stmt = $db->prepare("SELECT id_role FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    $targetUser = $stmt->fetch();
    
    if ($targetUser) {
        if ($targetUser['id_role'] == 1 && $id_utilisateur != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas modifier les informations d\'un autre administrateur.';
            header('Location: gestiondesutilisateurs.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Utilisateur introuvable.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    // Vérifier si le nouvel email est déjà utilisé par quelqu'un d'autre
    $stmt = $db->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ? AND id_utilisateur != ?");
    $stmt->execute([$email, $id_utilisateur]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Un autre utilisateur utilise déjà cet email.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    try {
        if (!empty($mot_de_passe)) {
            // Mettre à jour avec mot de passe
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, id_role = ?, statut = ?, mot_de_passe = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nom, $prenom, $email, $id_role, $statut, $hash, $id_utilisateur]);
        } else {
            // Mettre à jour sans mot de passe
            $stmt = $db->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, id_role = ?, statut = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nom, $prenom, $email, $id_role, $statut, $id_utilisateur]);
        }
        
        $_SESSION['success'] = "Les informations de l'utilisateur ont été mises à jour avec succès.";
        
        if (function_exists('log_action')) {
            log_action('Modification utilisateur', "Modification de l'utilisateur $prenom $nom ($email)", $_SESSION['user_id'] ?? null, $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'], $_SESSION['user_role'] ?? 'Admin');
        }
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la modification : " . $e->getMessage();
    }
    
    header('Location: gestiondesutilisateurs.php');
    exit;
}
