<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/config.php';
}
$activePage = $activePage ?? '';
$menuItems = getMenuItems();
$logoUrl = 'https://lh3.googleusercontent.com/aida/AP1WRLtzoZ2rcpMxMScovgaHRyc7GYXZ38veD2Icj5ryxozctkkqhQgiVxtyGP3X3oUVUx665NG_e-7F3cQxWJFoxm1DxKC5kz597JAPjIPkOFtdvQDvcXzFYjRi8-_UOKz5efA5xZSUR1zHF0CH5X1b3PY-vwvhMFhMN31PMuQEjmDm4yCbDlIy5zpvq0CLObjS7CwKt7pLVkeKKXzNt4faSKXdsdR-dQRaclSR4QH20G7KZxp76JlG1XnWbi24';
?>
<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="<?= $logoUrl ?>" alt="Logo SupTech" class="sidebar-logo">
        <div class="sidebar-brand">
            <h1 class="sidebar-title"><?= APP_NAME ?></h1>
            <p class="sidebar-subtitle"><?= APP_SUBTITLE ?></p>
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
        <a href="<?= url('index.php') ?>" class="nav-link sidebar-link sidebar-logout">
            <span class="material-symbols-outlined">logout</span>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Overlay mobile -->
<div id="sidebarOverlay" class="sidebar-overlay d-md-none"></div>
