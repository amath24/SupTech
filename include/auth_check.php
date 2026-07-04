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
    
    $required_role = strtolower(trim($page_required_role));

    // Si le rôle de l'utilisateur ne correspond pas au rôle exigé par la page
    if ($user_role !== $required_role) {
        $_SESSION['error'] = "Accès refusé : cette zone est réservée.";
        header('Location: ' . url('index.php'));
        exit;
    }
}