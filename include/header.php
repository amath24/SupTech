<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/config.php';
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userName = isset($_SESSION['user_prenom']) ? $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'] : 'Utilisateur';
$userRole = $_SESSION['user_role'] ?? 'Profil';
$userInitials = isset($_SESSION['user_prenom']) ? strtoupper(mb_substr($_SESSION['user_prenom'], 0, 1) . mb_substr($_SESSION['user_nom'], 0, 1)) : 'U';
$userPhoto = $_SESSION['user_photo'] ?? null;
?>
<!-- Top Header -->
<header class="top-header">
    <div class="d-flex align-items-center flex-grow-1">
        <button id="sidebarToggle" class="btn btn-icon d-md-none me-2" type="button" aria-label="Menu">
            <span class="material-symbols-outlined">menu</span>
        </button>

    </div>

    <div class="header-actions">
        <!-- Theme Toggle -->
        <button id="themeToggle" class="btn btn-icon" type="button" aria-label="Changer de thème">
            <span class="material-symbols-outlined theme-icon-light d-none">light_mode</span>
            <span class="material-symbols-outlined theme-icon-dark">dark_mode</span>
        </button>
        
        <?php if (strtolower(trim($_SESSION['user_role'] ?? '')) === 'administrateur'): ?>
        <a href="<?= url('gestion_des_utilisateurs/gestiondesutilisateurs.php') ?>" class="btn btn-icon" aria-label="Paramètres utilisateurs" title="Gestion des utilisateurs">
            <span class="material-symbols-outlined">settings</span>
        </a>
        <?php endif; ?>

        <button class="btn btn-icon position-relative" type="button" aria-label="Notifications">
            <span class="material-symbols-outlined">notifications</span>
            <span class="notification-dot"></span>
        </button>
        <div class="header-divider d-none d-md-block"></div>
        <div class="header-profile">
            <div class="profile-info d-none d-md-flex">
                <span class="profile-name"><?= htmlspecialchars($userName) ?></span>
                <span class="profile-role text-capitalize"><?= htmlspecialchars($userRole) ?></span>
            </div>
            <div class="profile-avatar overflow-hidden">
                <?php if ($userPhoto): ?>
                    <img src="<?= url('upload/' . htmlspecialchars($userPhoto)) ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <?= htmlspecialchars($userInitials) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
