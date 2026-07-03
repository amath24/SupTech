<?php
require_once __DIR__ . '/include/config.php';

$pageTitle = 'Connexion';
$layout = 'login';

$logoUrl = 'https://lh3.googleusercontent.com/aida/AP1WRLtzoZ2rcpMxMScovgaHRyc7GYXZ38veD2Icj5ryxozctkkqhQgiVxtyGP3X3oUVUx665NG_e-7F3cQxWJFoxm1DxKC5kz597JAPjIPkOFtdvQDvcXzFYjRi8-_UOKz5efA5xZSUR1zHF0CH5X1b3PY-vwvhMFhMN31PMuQEjmDm4yCbDlIy5zpvq0CLObjS7CwKt7pLVkeKKXzNt4faSKXdsdR-dQRaclSR4QH20G7KZxp76JlG1XnWbi24';

require_once __DIR__ . '/include/head.php';
?>
<body class="login-page">
    <main class="login-card">
        <header class="text-center mb-4">
            <img src="<?= $logoUrl ?>" alt="Logo SupTech Business School" class="login-logo mb-3">
            <h1 class="login-title">SupTech Business School</h1>
            <p class="login-subtitle">Gestion Académique</p>
        </header>

        <form action="<?= url('dashboard/dashboard.php') ?>" method="POST" class="d-flex flex-column gap-3">
            <div>
                <label for="email" class="form-label fw-semibold small">Adresse Email</label>
                <div class="input-icon-group">
                    <span class="material-symbols-outlined input-icon">mail</span>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="prenom.nom@suptech.sn" autocomplete="email" required>
                </div>
            </div>

            <div>
                <div class="d-flex justify-content-between align-items-baseline mb-1">
                    <label for="password" class="form-label fw-semibold small mb-0">Mot de passe</label>
                    <a href="#" class="small text-decoration-none" style="color: var(--suptech-primary);">Mot de passe oublié ?</a>
                </div>
                <div class="input-icon-group">
                    <span class="material-symbols-outlined input-icon">lock</span>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="••••••••" autocomplete="current-password" required>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-login d-flex align-items-center justify-content-center gap-2">
                    <span>Se connecter</span>
                    <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                </button>
            </div>
        </form>

        <footer class="login-footer">
            <p class="mb-0 d-flex align-items-center justify-content-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 16px;">help</span>
                Problème d'accès ?
                <a href="#" class="text-decoration-none fw-medium" style="color: var(--suptech-primary);">Contacter le support</a>
            </p>
        </footer>
    </main>

<?php require_once __DIR__ . '/include/footer.php'; ?>
