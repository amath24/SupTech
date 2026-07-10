<?php
session_start();
$page_required_role = ['Administrateur'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id_role = (int)($_POST['id_role'] ?? 0);
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($id_role)) {
        $_SESSION['error'] = 'Tous les champs sont obligatoires.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    // Le mot de passe par défaut
    $mot_de_passe = password_hash('Pass123!', PASSWORD_DEFAULT);
    
    $db = Database::getInstance();
    
    // Vérifier si l'email existe déjà
    $stmt = $db->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Un utilisateur avec cet email existe déjà.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role, statut) VALUES (?, ?, ?, ?, ?, 'actif')");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $id_role]);
        
        $_SESSION['success'] = "L'utilisateur a été ajouté avec succès.";
        
        // Log de l'action
        if (function_exists('log_action')) {
            log_action('Ajout utilisateur', "Ajout de l'utilisateur $prenom $nom ($email)", $_SESSION['user_id'] ?? null, $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'], $_SESSION['user_role'] ?? 'Admin');
        }
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'ajout : " . $e->getMessage();
    }
    
    header('Location: gestiondesutilisateurs.php');
    exit;
}
