<?php
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Gestion des Classes';
$activePage = 'classes';

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête de page -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="page-title">Gestion des Classes</h2>
                <p class="page-subtitle mb-0">Supervisez et gérez les effectifs académiques.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                Ajouter une classe
            </button>
        </div>

        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon primary">
                        <span class="material-symbols-outlined">domain</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Total Classes</p>
                        <p class="stat-value mb-0">24</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon secondary">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Filières Actives</p>
                        <p class="stat-value mb-0">8</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-card-icon tertiary">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div>
                        <p class="stat-label mb-0">Capacité Globale</p>
                        <p class="stat-value mb-0">85%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-bar d-flex flex-wrap gap-3 align-items-center mb-4">
            <div class="flex-grow-1" style="min-width: 200px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span></span>
                    <select class="form-select" id="filtreFiliere">
                        <option value="">Toutes les filières</option>
                        <option value="info">Informatique</option>
                        <option value="gestion">Gestion &amp; Commerce</option>
                        <option value="rh">Ressources Humaines</option>
                    </select>
                </div>
            </div>
            <div class="flex-grow-1" style="min-width: 200px;">
                <div class="input-group">
                    <span class="input-group-text"><span class="material-symbols-outlined" style="font-size: 18px;">layers</span></span>
                    <select class="form-select" id="filtreNiveau">
                        <option value="">Tous les niveaux</option>
                        <option value="l1">Licence 1</option>
                        <option value="l2">Licence 2</option>
                        <option value="l3">Licence 3</option>
                        <option value="m1">Master 1</option>
                        <option value="m2">Master 2</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-outline-suptech" id="btnResetFiltres">Réinitialiser</button>
        </div>

        <!-- Grille des classes -->
        <div class="row g-4">
            <!-- Classe 1 -->
            <div class="col-lg-6 col-xl-4">
                <div class="class-card">
                    <div class="class-card-header">
                        <span class="material-symbols-outlined bg-icon">terminal</span>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                            <div>
                                <span class="badge-niveau l3">Niveau: L3</span>
                                <h3 class="h5 fw-semibold mb-1">Licence 3</h3>
                                <h4 class="h6 text-primary mb-0">Développement Web</h4>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-muted p-1">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 flex-grow-1 d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light">
                            <div class="avatar-sm bg-light text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">account_tree</span>
                            </div>
                            <div>
                                <p class="stat-label mb-0">Filière</p>
                                <p class="fw-medium mb-0">Informatique</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <p class="stat-label mb-0">Effectif</p>
                                    <p class="mb-0"><span class="fs-4 fw-bold">35</span> <span class="text-muted">/ 40</span></p>
                                </div>
                                <span class="badge bg-success-subtle text-success">87.5%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-secondary" style="width: 87.5%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-top bg-light d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary flex-fill btn-sm">Voir Détails</button>
                        <button type="button" class="btn btn-outline-secondary flex-fill btn-sm">Emploi du temps</button>
                    </div>
                </div>
            </div>

            <!-- Classe 2 -->
            <div class="col-lg-6 col-xl-4">
                <div class="class-card">
                    <div class="class-card-header">
                        <span class="material-symbols-outlined bg-icon">architecture</span>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                            <div>
                                <span class="badge-niveau m1">Niveau: M1</span>
                                <h3 class="h5 fw-semibold mb-1">Master 1</h3>
                                <h4 class="h6 mb-0" style="color: var(--suptech-tertiary);">Génie Logiciel</h4>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-muted p-1">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 flex-grow-1 d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light">
                            <div class="avatar-sm bg-light text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">account_tree</span>
                            </div>
                            <div>
                                <p class="stat-label mb-0">Filière</p>
                                <p class="fw-medium mb-0">Informatique Avancée</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <p class="stat-label mb-0">Effectif</p>
                                    <p class="mb-0"><span class="fs-4 fw-bold">22</span> <span class="text-muted">/ 25</span></p>
                                </div>
                                <span class="badge bg-success-subtle text-success">88%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-tertiary" style="width: 88%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-top bg-light d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary flex-fill btn-sm">Voir Détails</button>
                        <button type="button" class="btn btn-outline-secondary flex-fill btn-sm">Emploi du temps</button>
                    </div>
                </div>
            </div>

            <!-- Classe 3 -->
            <div class="col-lg-6 col-xl-4">
                <div class="class-card">
                    <div class="class-card-header">
                        <span class="material-symbols-outlined bg-icon">public</span>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                            <div>
                                <span class="badge-niveau bts">Niveau: BTS</span>
                                <h3 class="h5 fw-semibold mb-1">BTS 2</h3>
                                <h4 class="h6 mb-0 text-success">Commerce International</h4>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-muted p-1">
                                <span class="material-symbols-outlined">more_vert</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 flex-grow-1 d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light">
                            <div class="avatar-sm bg-light text-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">account_tree</span>
                            </div>
                            <div>
                                <p class="stat-label mb-0">Filière</p>
                                <p class="fw-medium mb-0">Gestion &amp; Commerce</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <p class="stat-label mb-0">Effectif</p>
                                    <p class="mb-0"><span class="fs-4 fw-bold">45</span> <span class="text-muted">/ 50</span></p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary">90%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-primary" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-top bg-light d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary flex-fill btn-sm">Voir Détails</button>
                        <button type="button" class="btn btn-outline-secondary flex-fill btn-sm">Emploi du temps</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
