<?php
/**
 * Script de déconnexion
 */

session_start();
require_once __DIR__ . '/include/config.php';

if (isset($_SESSION['user_id'])) {
    $nom_complet = trim(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? ''));
    log_action('Déconnexion', 'L\'utilisateur s\'est déconnecté.', $_SESSION['user_id'], $nom_complet, $_SESSION['user_role'] ?? 'Inconnu');
}

// On vide toutes les variables de session
$_SESSION = array();
// On détruit le cookie de session si présent
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// On détruit la session côté serveur
session_destroy();

// Redirection vers la page de connexion
header("Location: index.php");
exit;