<?php
$page_required_role = ['Administrateur', 'Scolarité', 'Enseignant'];
Require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des Modules';
$activePage = 'modules';

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="page-title">Gestion des Modules</h1>
                <p class="page-subtitle mb-0">Gérez le catalogue des cours, les affectations d'enseignants et les volumes horaires.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                Ajouter un module
            </button>
        </div>

        <!-- Filtres -->
        <div class="filter-bar d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-4">
            <div class="filter-pills d-flex gap-2 overflow-auto pb-1 pb-md-0">
                <button type="button" class="btn btn-filter active btn-sm">Tous les modules</button>
                <button type="button" class="btn btn-outline-secondary btn-filter btn-sm">Actifs</button>
                <button type="button" class="btn btn-outline-secondary btn-filter btn-sm">Archivés</button>
            </div>
            <div style="min-width: 250px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span></span>
                    <select class="form-select form-select-sm">
                        <option>Filtrer par Classe</option>
                        <option>L3 Dev Web</option>
                        <option>M1 Sécurité</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grille des modules -->
        <div class="row g-4">
            <!-- Module 1 -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="module-card">
                    <div class="module-card-bar primary-gradient" style="height: 8px;"></div>
                    <div class="p-4 flex-grow-1 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-2 rounded bg-primary-subtle text-primary">
                                <span class="material-symbols-outlined">code</span>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">L3 DEV WEB</span>
                        </div>
                        <h3 class="h6 fw-semibold mb-1">Développement Web PHP</h3>
                        <p class="text-muted small mb-4">Architecture MVC, requêtes PDO complexes et sécurisation d'applications web PHP.</p>
                        <div class="mt-auto">
                            <div class="d-flex align-items-center gap-2 small mb-3">
                                <span class="material-symbols-outlined text-muted" style="font-size: 18px;">person</span>
                                <span>Dr. Fall</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span> 60h
                                </span>
                                <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">functions</span> Coeff: 4
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="module-card-actions">
                        <button type="button" class="btn btn-sm btn-link text-muted" title="Modifier">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-link text-danger" title="Supprimer">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Module 2 -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="module-card">
                    <div class="module-card-bar tertiary-gradient" style="height: 8px;"></div>
                    <div class="p-4 flex-grow-1 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="p-2 rounded" style="background: rgba(75,28,0,0.1); color: var(--suptech-tertiary);">
                                <span class="material-symbols-outlined">router</span>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary border" style="font-size: 0.7rem;">M1 SÉCU</span>
                        </div>
                        <h3 class="h6 fw-semibold mb-1">Réseaux et Sécurité</h3>
                        <p class="text-muted small mb-4">Topologies réseaux, protocoles de routage, et introduction à la cryptographie appliquée.</p>
                        <div class="mt-auto">
                            <div class="d-flex align-items-center gap-2 small mb-3">
                                <span class="material-symbols-outlined text-muted" style="font-size: 18px;">person</span>
                                <span>Prof. Ndiaye</span>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span> 45h
                                </span>
                                <span class="badge bg-light text-muted d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">functions</span> Coeff: 3
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="module-card-actions">
                        <button type="button" class="btn btn-sm btn-link text-muted" title="Modifier">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-link text-danger" title="Supprimer">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ajouter un module -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="module-add-card">
                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-primary" style="font-size: 28px;">add</span>
                    </div>
                    <h3 class="h6 fw-semibold mb-2">Nouveau Module</h3>
                    <p class="text-muted small mb-0">Cliquez ici pour créer et configurer un nouveau cours.</p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
