<?php
/**
 * Garde-barrière de sécurité
 * Vérifie l'authentification et applique le passe-droit Administrateur
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assurer l'existence de la fonction url()
require_once __DIR__ . '/config.php';

// 1. Si l'utilisateur n'est pas connecté du tout
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    $_SESSION['error'] = "Veuillez vous connecter pour accéder à cette page.";
    header('Location: ' . url('index.php'));
    exit;
}

$user_role = strtolower(trim($_SESSION['user_role']));

// 2. Si la page impose un rôle ET que l'utilisateur n'est PAS un Administrateur
// (Si c'est un administrateur, cette condition est ignorée et il passe librement)
if (isset($page_required_role) && $user_role !== 'administrateur') {
    
    $is_authorized = false;

    // A. Si la page autorise PLUSIEURS rôles (Tableau)
    if (is_array($page_required_role)) {
        // On nettoie et met en minuscules chaque rôle du tableau
        $required_roles = array_map(function($role) {
            return strtolower(trim($role));
        }, $page_required_role);
        
        // On vérifie si le rôle de l'utilisateur est dans le tableau
        if (in_array($user_role, $required_roles)) {
            $is_authorized = true;
        }
    } 
    // B. Si la page autorise un SEUL rôle (Chaîne de caractères)
    else {
        $required_role = strtolower(trim($page_required_role));
        if ($user_role === $required_role) {
            $is_authorized = true;
        }
    }

    // 3. Si l'utilisateur n'a pas reçu l'autorisation après les vérifications
    if (!$is_authorized) {
        $_SESSION['error'] = "Accès refusé : cette zone est réservée.";
        header('Location: ' . url('index.php'));
        exit;
    }
}
?>