<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/config.php';
}
$userName = $userName ?? 'Admin Principal';
$userRole = $userRole ?? 'Profil Utilisateur';
$userInitials = $userInitials ?? 'AP';
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
        
        <button class="btn btn-icon position-relative" type="button" aria-label="Notifications">
            <span class="material-symbols-outlined">notifications</span>
            <span class="notification-dot"></span>
        </button>
        <div class="header-divider d-none d-md-block"></div>
        <div class="header-profile">
            <div class="profile-info d-none d-md-flex">
                <span class="profile-name"><?= htmlspecialchars($userName) ?></span>
                <span class="profile-role"><?= htmlspecialchars($userRole) ?></span>
            </div>
            <div class="profile-avatar"><?= htmlspecialchars($userInitials) ?></div>
        </div>
    </div>
</header>
