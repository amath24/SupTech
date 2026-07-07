<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/config.php';
}
$pageTitle = $pageTitle ?? APP_NAME;
$layout = $layout ?? 'admin';
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <script>
        // Appliquer le thème avant le rendu pour éviter un flash
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - SupTech Business School</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('image/LOGO.png') ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
