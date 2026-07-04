<?php
$page_required_role = 'Administrateur';
require_once __DIR__ . '/../include/auth_check.php';
require_once __DIR__ . '/../include/config.php';

$pageTitle = 'Tableau de bord';
$activePage = 'dashboard';

// Données de session )
$userName = $_SESSION['user_nom'] ?? '';
$userRole = $_SESSION['user_role'] ?? '';
$userInitials = strtoupper(substr($_SESSION['user_nom'] ?? 'C', 0, 1));

require_once __DIR__ . '/../include/head.php';
?>
<body>
<?php require_once __DIR__ . '/../include/sidebar.php'; ?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/../include/header.php'; ?>

    <main class="main-content">
        <!-- En-tête du tableau de bord -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="page-title">Tableau de bord académique</h1>
                <p class="page-subtitle mb-0">Bienvenue, <?= htmlspecialchars($userName) ?>. Voici l'état actuel de l'institution.</p>
            </div>
            <button type="button" class="btn btn-primary-suptech d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                Nouvelle action
            </button>
        </div>

        <!-- Grille de statistiques -->
        <div class="row g-4 mb-5">
            <!-- Étudiants -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon primary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">groups</span>
                        </div>
                        <span class="badge bg-success">+12%</span>
                    </div>
                    <p class="stat-label mb-2">ÉTUDIANTS</p>
                    <h3 class="stat-value mb-2">342</h3>
                    <small class="text-muted d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 16px;">trending_up</span>
                        Comparé au mois dernier
                    </small>
                </div>
            </div>

            <!-- Enseignants -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon secondary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">school</span>
                        </div>
                    </div>
                    <p class="stat-label mb-2">ENSEIGNANTS</p>
                    <h3 class="stat-value mb-2">28</h3>
                    <small class="text-muted">
                        Tous les rôles confondus
                    </small>
                </div>
            </div>

            <!-- Inscriptions -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon tertiary-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">person_add</span>
                        </div>
                        <span class="badge bg-info">Actif</span>
                    </div>
                    <p class="stat-label mb-2">INSCRIPTIONS</p>
                    <h3 class="stat-value mb-2">310</h3>
                    <small class="text-muted">Année 2025-2026</small>
                </div>
            </div>

            <!-- Taux de réussite -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon success-gradient d-flex align-items-center justify-content-center">
                            <span class="material-symbols-outlined" style="font-size: 28px;">auto_graph</span>
                        </div>
                    </div>
                    <p class="stat-label mb-2">TAUX DE RÉUSSITE</p>
                    <h3 class="stat-value mb-2">78%</h3>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 78%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des graphiques -->
        <div class="row g-4 mb-5">
            <!-- Paiements mensuels -->
            <div class="col-lg-8">
                <div class="surface-card">
                    <div class="card-header-surface d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h6 fw-semibold">Paiements mensuels (FCFA)</h3>
                        <select class="form-select form-select-sm" style="max-width: 150px;">
                            <option>Année 2024</option>
                            <option>Année 2023</option>
                        </select>
                    </div>
                    <div class="p-4">
                        <div class="d-flex align-items-end justify-content-between" style="height: 300px; gap: 1rem;">
                            <!-- Barre JAN -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary-subtle rounded-top" style="height: 45%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="1.2M" onmouseover="this.style.backgroundColor='var(--suptech-primary)'" onmouseout="this.style.backgroundColor='var(--bs-primary-subtle)'"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">JAN</small>
                            </div>
                            <!-- Barre FEV -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary-subtle rounded-top" style="height: 65%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="2.1M" onmouseover="this.style.backgroundColor='var(--suptech-primary)'" onmouseout="this.style.backgroundColor='var(--bs-primary-subtle)'"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">FEV</small>
                            </div>
                            <!-- Barre MAR -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary rounded-top" style="height: 85%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="3.5M"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">MAR</small>
                            </div>
                            <!-- Barre AVR -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary-subtle rounded-top" style="height: 70%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="2.8M" onmouseover="this.style.backgroundColor='var(--suptech-primary)'" onmouseout="this.style.backgroundColor='var(--bs-primary-subtle)'"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">AVR</small>
                            </div>
                            <!-- Barre MAI -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary rounded-top" style="height: 95%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="4.2M"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">MAI</small>
                            </div>
                            <!-- Barre JUIN -->
                            <div class="flex-grow-1 text-center">
                                <div class="bg-primary-subtle rounded-top" style="height: 75%; min-height: 30px; position: relative; cursor: pointer;" 
                                     title="3.1M" onmouseover="this.style.backgroundColor='var(--suptech-primary)'" onmouseout="this.style.backgroundColor='var(--bs-primary-subtle)'"></div>
                                <small class="d-block mt-2 text-muted fw-semibold">JUIN</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par classe -->
            <div class="col-lg-4">
                <div class="surface-card">
                    <div class="card-header-surface">
                        <h3 class="mb-0 h6 fw-semibold">Répartition par classe</h3>
                    </div>
                    <div class="p-4">
                        <div class="position-relative mx-auto mb-4" style="width: 200px; height: 200px;">
                            <svg viewBox="0 0 100 100" class="w-100 h-100" style="transform: rotate(-90deg)">
                                <circle cx="50" cy="50" r="40" fill="none" stroke="#f0f0f0" stroke-width="8"></circle>
                                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--suptech-primary)" stroke-width="8" stroke-dasharray="32 100" stroke-linecap="round"></circle>
                                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--suptech-secondary)" stroke-width="8" stroke-dasharray="22 100" stroke-dashoffset="-32" stroke-linecap="round"></circle>
                                <circle cx="50" cy="50" r="40" fill="none" stroke="var(--suptech-tertiary)" stroke-width="8" stroke-dasharray="12 100" stroke-dashoffset="-54" stroke-linecap="round"></circle>
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h4 class="mb-0 fw-bold">342</h4>
                                <small class="text-muted">TOTAL</small>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="d-flex justify-content-between align-items-center small">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle" style="background-color: var(--suptech-primary); width: 12px; height: 12px;"></span>
                                    <span>L3 Dev Web</span>
                                </div>
                                <span class="fw-semibold">40%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle" style="background-color: var(--suptech-secondary); width: 12px; height: 12px;"></span>
                                    <span>M1 Gestion Logiciel</span>
                                </div>
                                <span class="fw-semibold">28%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle" style="background-color: var(--suptech-tertiary); width: 12px; height: 12px;"></span>
                                    <span>M2 Cybersecurity</span>
                                </div>
                                <span class="fw-semibold">18%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables des données récentes -->
        <div class="row g-4">
            <!-- Derniers étudiants inscrits -->
            <div class="col-lg-6">
                <div class="surface-card">
                    <div class="card-header-surface d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h6 fw-semibold">Derniers étudiants inscrits</h3>
                        <a href="<?= url('gestion_des_inscriptions/gestiondesinscription.php') ?>" class="text-primary text-decoration-none small fw-semibold">
                            Voir tout
                            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: -2px;">arrow_forward</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Filière</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-avatar sm">MD</div>
                                            <span>Mamadou Diallo</span>
                                        </div>
                                    </td>
                                    <td>L3 Dev Web</td>
                                    <td>12 Oct 2023</td>
                                    <td>
                                        <button class="btn btn-sm btn-icon" title="Voir">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-avatar sm">FN</div>
                                            <span>Fatou Ndiaye</span>
                                        </div>
                                    </td>
                                    <td>M1 Gestion Logiciel</td>
                                    <td>11 Oct 2023</td>
                                    <td>
                                        <button class="btn btn-sm btn-icon" title="Voir">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-avatar sm">OS</div>
                                            <span>Ousmane Sarr</span>
                                        </div>
                                    </td>
                                    <td>M2 Data Science</td>
                                    <td>10 Oct 2023</td>
                                    <td>
                                        <button class="btn btn-sm btn-icon" title="Voir">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Dernières notes enregistrées -->
            <div class="col-lg-6">
                <div class="surface-card">
                    <div class="card-header-surface d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 h6 fw-semibold">Dernières notes enregistrées</h3>
                        <a href="<?= url('gestion_des_notes_enseignant/gestiondesnote.php') ?>" class="text-primary text-decoration-none small fw-semibold">
                            Saisir une note
                            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: -2px;">arrow_forward</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Module</th>
                                    <th>Étudiant</th>
                                    <th>Note</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Algorithmique</td>
                                    <td>Mamadou Diallo</td>
                                    <td class="fw-semibold text-primary">16.5</td>
                                    <td><span class="badge bg-success-subtle text-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>Base de Données</td>
                                    <td>Fatou Ndiaye</td>
                                    <td class="fw-semibold text-primary">14.0</td>
                                    <td><span class="badge bg-success-subtle text-success">Validé</span></td>
                                </tr>
                                <tr>
                                    <td>Marketing Digital</td>
                                    <td>Ousmane Sarr</td>
                                    <td class="fw-semibold text-danger">08.0</td>
                                    <td><span class="badge bg-danger-subtle text-danger">Rattrapage</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../include/footer.php'; ?>
