<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/config.php';
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$activePage = $activePage ?? '';
$menuItems = getMenuItems();
$logoUrl = asset('image/LOGO.png');
$userRole = $_SESSION['user_role'] ?? 'Utilisateur';
?>
<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="<?= $logoUrl ?>" alt="Logo SupTech" class="sidebar-logo">
        <div class="sidebar-brand">

            <div class="mt-2">
                <span class="badge" style="background-color: rgba(255,255,255,0.2); color: #6cf8bb; padding: 0.4rem 0.8rem; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <?= htmlspecialchars($userRole) ?>
                </span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav flex-grow-1">
        <ul class="nav flex-column">
            <?php foreach ($menuItems as $item): ?>
            <li class="nav-item">
                <a href="<?= $item['url'] ?>"
                   class="nav-link sidebar-link <?= ($activePage === $item['id']) ? 'active' : '' ?>"
                   <?= ($activePage === $item['id']) ? 'aria-current="page"' : '' ?>>
                    <span class="material-symbols-outlined"><?= $item['icon'] ?></span>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= url('logout.php') ?>" class="nav-link sidebar-link sidebar-logout">
            <span class="material-symbols-outlined">logout</span>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Overlay mobile -->
<div id="sidebarOverlay" class="sidebar-overlay d-md-none"></div>
