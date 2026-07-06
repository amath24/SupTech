<?php
/**
 * Traitement du formulaire de connexion
 */

session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

// 1. Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('../index.php'));
    exit;
}

// 2. Récupération et validation
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Veuillez remplir tous les champs.';
    header('Location: ' . url('../index.php'));
    exit;
}

try {
    $pdo = Database::getInstance();

    // Auto-migration : s'assurer que la colonne photo existe
    try {
        $pdo->query("SELECT photo FROM utilisateurs LIMIT 1");
    } catch (PDOException $e) {
        $pdo->exec("ALTER TABLE utilisateurs ADD COLUMN photo VARCHAR(255) DEFAULT NULL");
    }

    // 3. Requête SQL alignée avec votre structure
    $query = $pdo->prepare('
        SELECT 
            u.id_utilisateur, 
            u.nom, 
            u.prenom, 
            u.mot_de_passe,
            u.photo,
            r.libelle as role
        FROM utilisateurs u
        INNER JOIN roles r ON u.id_role = r.id_role
        WHERE u.email = ? AND u.statut = "actif"
        LIMIT 1
    ');

    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // 4. Vérification utilisateur et mot de passe
    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['error'] = 'Email ou mot de passe incorrect.';
        header('Location: ' . url('../index.php'));
        exit;
    }

    // 5. Création de la session
    $_SESSION['user_id'] = $user['id_utilisateur'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_photo'] = $user['photo'] ?? null;
    unset($_SESSION['error']);

    // 6. Redirection selon le rôle
    $role = strtolower(trim($user['role']));

 switch ($role) {
    case 'administrateur':
        header('Location: ' . url('dashboard/dashboard.php'));
        break;
    case 'enseignant':
        // Redirection spécifique, pas vers le dashboard admin
        header('Location: ' . url('gestion_des_modules/gestiondesmodule.php'));
        break;
    // ...
case 'scolarité':
    header('Location: ' . url('gestion_des_inscriptions/gestiondesinscription.php'));
    break;
// ...
    default:
        // Si aucun rôle trouvé, on déconnecte
        header('Location: ' . url('logout.php'));
        break;
}
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erreur serveur. Veuillez réessayer plus tard.';
    error_log('Erreur Connexion: ' . $e->getMessage());
    header('Location: ' . url('../index.php'));
    exit;
}