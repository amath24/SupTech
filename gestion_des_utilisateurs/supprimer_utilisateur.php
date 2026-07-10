<?php
session_start();
$page_required_role = ['Administrateur'];
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';
require_once __DIR__ . '/../include/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = (int)($_POST['id_utilisateur'] ?? 0);
    
    if ($id_utilisateur === (int)($_SESSION['user_id'] ?? 0)) {
        $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte.';
        header('Location: gestiondesutilisateurs.php');
        exit;
    }
    
    if ($id_utilisateur > 0) {
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("SELECT prenom, nom, email FROM utilisateurs WHERE id_utilisateur = ?");
            $stmt->execute([$id_utilisateur]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Delete user
                $stmtDel = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
                $stmtDel->execute([$id_utilisateur]);
                
                $_SESSION['success'] = "L'utilisateur a été supprimé avec succès.";
                
                if (function_exists('log_action')) {
                    log_action('Suppression utilisateur', "Suppression de l'utilisateur {$user['prenom']} {$user['nom']} ({$user['email']})", $_SESSION['user_id'] ?? null, $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'], $_SESSION['user_role'] ?? 'Admin');
                }
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Impossible de supprimer cet utilisateur (il est probablement lié à d'autres données).";
        }
    }
    
    header('Location: gestiondesutilisateurs.php');
    exit;
}
